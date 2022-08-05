<?php

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);
require('../fpdf.php');
include_once '../../includes/database.php';
include '../../includes/numberformatter.php'; //format amount in words
include  '../../modules/functions.php';
require_once('../../Swift-5.1.0/lib/swift_required.php');
require_once('../../Swift-5.1.0/lib/swift_init.php');  

class PDF extends FPDF
{
// Load dat
 
function Header()
{
    //$pdf->Image('../../images/cursors/LHEAD.png');
 $this->Image('../../images/cursors/LHEAD.png',0,0);
}
  function Footer()
{
    // Position at 1.5 cm from bottom
    $this->SetY(-20);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Page number../images/cursors/Footer.png
  $this->Image('../../images/cursors/Footer.png', '', null, 150.78);
}
  

    
}

$document=$_REQUEST['document'];
$propid=$_REQUEST['propid'];
$user=$_REQUEST['username'];

if($document=='invoice'){
 printthisinvoice($_REQUEST['invoiceno'], $propid, $user);//$pdf->LoadData('countries.txt');
}

if($document=='receipt'){
 printthisreceipt($_REQUEST['recpno'],$propid, $user);//$pdf->LoadData('countries.txt');
}


function printthisinvoice($invoiceno, $propid, $user) {
 
    $pdf = new PDF();
// Column headings
// Data loading
$pdf->SetFont('Arial','',12);
$pdf->AddPage('L','A5');

      $db = new MySQLDatabase();
    date_default_timezone_set('Africa/Nairobi');
    $date = date("d/m/y");
    $time = date('h:i A');
    $db->open_connection();
    $tablename = "invoices";
    $tablename1 = "chargeitems";
    $tablename2 = "invoiceitems";
    $tableitems = [];
    $chargeablesamount=array();
    $chargeableitems=array();
    $sql =$db->query("SELECT * FROM $tablename WHERE `invoiceno` like '$invoiceno'") or die($db->error());
    if ($db->num_rows($sql) > 0) {
        while ($row = $db->fetch_array($sql)) {
            $invoiceno = $row['invoiceno'];
            $invoicedate = $row['invoicedate'];
            $amount = $row['amount'];
            $idno = $row['idno'];
            $incomeacct = $row['incomeaccount'];
            $billing = $row['invoicecredit'];
            $remarks = $row['remarks'];
        }
        //get apartment No
        $apartmentid=getApartmentFromTenant($idno);
        $aptdetails=  getApartmentDetails($apartmentid);
        $billing == 0 ? $value ="INVOICE" : $value ="CREDIT NOTE"; //type of billing
        $sql1 = $db->query("SELECT accname FROM $tablename1 WHERE `id` like '$incomeacct' ORDER BY `priority` ASC") or die($db->error());
        while ($row1 = $db->fetch_array($sql1)) {
            $accname = $row1['accname'];
        }
        $sql2 = $db->query("SELECT tenants.property_name,tenants.apartmentid,tenants.Id,tenants.tenant_name,floorplan.last_water_reading,floorplan.current_water_reading FROM tenants  JOIN floorplan ON tenants.apartmentid=floorplan.apt_id WHERE ( tenants.Id='$idno' AND tenants.vacated like '0')") or die($db->error());
        while ($row2 = $db->fetch_array($sql2)) {
            $propertyname = $row2['property_name'];
            $tname = $row2['tenant_name'];
            $tenantid=$row['Id'];
            $lastwater = $row2['last_water_reading'];
            $currentwater = $row2['current_water_reading'];
        }
        //get sum of chargeable items
        $sql3 = $db->query("SELECT item_name,amount FROM $tablename2 WHERE invoiceno='$invoiceno' ORDER BY priority ASC") or die($db->error());
        while ($row3 = $db->fetch_array($sql3)) {
            $item_name = $row3['item_name'];
            $item_amount = $row3['amount'];
            //total amount
            array_push($chargeablesamount,$item_amount);
            array_push($chargeableitems,$item_name);
            //if the charge item !=0
            if($item_amount !=0){
            array_push($tableitems, ' '. $item_name . '   KES: ' . number_format($item_amount, 2));
            }
        }
        //get outstanding balance/prepayment
        $balance=  getCorrectBalance($idno,$invoiceno);
        $chargestotal=array_sum($chargeablesamount);
        $totaldue=$balance+$chargestotal;
        //page width
        $pagewidth=140;
        $pdf->SetX(9);
      
      //logo
       $pdf->Image('../../images/cursors/logo1.png',$pagewidth+30,2,25,20,'','http://www.petaagency.com');
       $pdf->Ln();
       
       //invoiceno
        $pdf->SetX(9);
        $pdf->SetY(25);
 $pdf->SetFont('Arial','',11);
    $pdf->SetFillColor(245,245,220);
    $pdf->SetDrawColor(217,74,56);
    $pdf->SetLineWidth(1);
   $pdf->Cell(45,8,$value.' NO '.$invoiceno,1,1,'C',true);
   //invoice date
   $pdf->SetY(30);
   $pdf->SetX($pagewidth-20);
           $pdf->Cell(20,0,'Date: '.date("d-m-Y",strtotime($invoicedate)));
       $pdf->Ln(5);
       //$pdf->SetDrawColor(217,74,56);
    $pdf->SetFont('Arial','B',10);
   $pdf->Cell(45,10,'NAME:'.ucwords(@$tname).'('.$aptdetails[0]['apt_tag'].')');
   $pdf->Ln(5);
   $pdf->Cell(45,10,'PROPERTY:'.ucwords(str_replace('_', " ", @$propertyname)));
   
    $pdf->SetY(40);
   $pdf->SetX(120);
           $pdf->Cell(20,0,'CHARGEABLES AMOUNT: Ksh ' . number_format($chargestotal,2) );
     $pdf->SetY(45);
   $pdf->SetX(120);
           $pdf->Cell(20,0,'TOTAL AMOUNT DUE: Ksh '.number_format($totaldue,2));
           $pdf->SetFont('Arial','I',10);
            $pdf->SetY(50);
   $pdf->SetX(5);
           $pdf->SetDrawColor(217,74,56);
      $pdf->Cell(200,2,  str_repeat('-',$pagewidth));
      //chargeables
      $pdf->SetX(6);
      $pdf->Ln(1);
      $pdf->SetFont('Arial','I',8);
   $pdf->Cell(45,10,'B.B.F: KES '.number_format($balance,2));
   $pdf->Ln(1);
   foreach ($chargeableitems as $value) {
    if (strtoupper($value) == 'WATER') {
                $pdf->Ln(3);
                $pdf->Cell(105,10,'Last Reading: ' . $lastwater . ' Current Reading:  ' . $currentwater . ' Consumption: ' . ($currentwater - $lastwater) . 'units Rate:(ksh)' . get_water_rate($propid));
                          }
                          }
   foreach ($tableitems as $key => $value1) {
           
            $pdf->Ln(5);
             $pdf->Cell(45,10,trim($key[0]).trim($value1,0));
           
        }
          $pdf->Ln(8);
   $pdf->Cell(100,10,'Being '.strtolower($value).' for '.$remarks );
   $pdf->Ln(8);
        $pdf->SetDrawColor(217,74,56);
      $pdf->Cell(200,2,  str_repeat('-',$pagewidth));
     $pdf->Ln(1);
     $pdf->SetFont('Arial','I',9);
   $pdf->Cell(100,10,'All acounts are due on or before the 5th day of the month,failure to settle by the said date attracts a penalty of 10%');
   $pdf->Ln(4);
     $pdf->Cell(100,10,$user . ' ' . $date . ' ' . $time );
     
   $tenantdetails=getTenantDetails($idno) ;
$pdf->SetX($pagewidth-10);
  $pdf->Cell(100,10,$tenantdetails['email']);
   
    } else {
        return false;
    }
       //save and email
$emails=getAdminEmail();
$name = $tenantdetails['name'];
    $to =$tenantdetails['email'];
$from =$emails['from'];
$cc =$emails['cc'];
$subject = "RIVERCOURT PROPERTY Rent Invoice ".date("d-m-Y",strtotime($invoicedate));
$message1 = "Greetings ".$name." , 

Kindly find attached invoice .

Regards
".$user."
";
// a random hash will be necessary to send mixed content
$separator = md5(time());
// carriage return type (we use a PHP end of line constant)
$eol = PHP_EOL;
// attachment name
$filename = $invoiceno.".pdf";
// encode data (puts attachment in proper format)
$pdfdoc = $pdf->Output($filename, "S");
$attachment = chunk_split(base64_encode($pdfdoc));
// main header (multipart mandatory)


   $transport = Swift_SmtpTransport::newInstance('mail.techsavanna.technology', 587)
 ->setUsername('francis@techsavanna.technology')
 ->setPassword('qwerty789.');
          $message = Swift_Message::newInstance();
          $mailer = Swift_Mailer::newInstance($transport);
         $mailer->registerPlugin(new Swift_Plugins_AntiFloodPlugin(100, 30));
         
 $message = Swift_Message::newInstance()
              ->setFrom('francis@techsavanna.technology')
				//->setFrom(array($sentfrom => 'ACCOUNT STATEMENT')) // From: 
                ->setTo('frankmutura@gmail.com')
         ->setBody($message1)
                ->setSubject($subject);
$message->attach(Swift_Attachment::newInstance($pdfdoc, 'document.pdf','application/pdf'));
     
        if($mailer->send($message)){
            echo "Successfully sent email";
        } else{
                 echo "Could not send email";
        }
         
// send message

}

function printthisreceipt($receiptno, $propid, $user) {
    $pdf = new PDF();
// Column headings
// Data loading
$pdf->SetFont('Arial','',12);
$pdf->AddPage('P','A4');

    $db = new MySQLDatabase();
    date_default_timezone_set('Africa/Nairobi');
    $date = date("d/m/y");
    $time = date('h:i A');
    $db->open_connection();
    $tablename = "recptrans";
    $tablename2 = "tenants";
    $tablename3 = "invoiceitems";
    $invoicetable=  getInvoiceTable();
    $tableitems = [];
    $chargeablesamount=array();
    
    $sql = $db->query("SELECT * FROM $tablename WHERE `recpno` like '$receiptno'") or die($db->error());
    if ($db->num_rows($sql) > 0) {
        while ($row = $db->fetch_array($sql)) {
            $recpno = $row['recpno'];
            $recpdate = $row['rdate'];
            $amount = $row['amount'];
            $idno = $row['idno'];
            $remarks = $row['rmks'];
            $invoiceno = $row['invoicenopaid'];
        }
        
    }
        $sql2 = $db->query("SELECT property_name,tenant_name,Apartment_tag FROM $tablename2 WHERE ( `Id`='$idno' AND `vacated` like '0')") or die($db->error());
        while ($row2 =$db->fetch_array($sql2)) {
            $propertyname = $row2['property_name'];
            $tname = $row2['tenant_name'];
            $tag = $row2['Apartment_tag'];
        }
        $sql3 =$db->query("SELECT item_name,amount FROM $tablename3 WHERE invoiceno='$invoiceno' ORDER BY `priority` ASC") or die($db->error());
        while ($row3 = $db->fetch_array($sql3)) {
            $item_name = $row3['item_name'];
            $item_amount = $row3['amount'];
            array_push($chargeablesamount,$item_amount);
            array_push($tableitems, $item_name . ': Ksh:' . number_format($item_amount, 2));
        }

//tenant enail
   $tenantdetails=getTenantDetails($idno) ;

         //get outstanding balance/prepayment
        
        $balance=  getCorrectBalance($idno,$latestinvoice='0');
        
        $penaltysql=$db->query("SELECT amount FROM $invoicetable WHERE recpno='$recpno' AND `is_penalty`=1 ") or die($db->error());
        while ($row= $db->fetch_array($penaltysql)) {
            $penaltyamount = $row['amount'];
              }
        //page width
        $pagewidth=140;
       
    $pdf->Ln(10);
    $table ="<table class='tftable printable ' width='90%' style='font-family:Arial,sans-serif;letter-spacing:2px;' >";
    $table .=" <b>RECEIPT</b><br>";
    $table .=" <u>Date:</u>  ".date('d-m-Y',strtotime($recpdate ))."<br>";
    $table .="<u>Receipt No:</u>    ".$recpno."   <br>";
    $table .="<b>Received From:</b  ". ucwords(@$tname) .'('.@$tag.')'." <b>OF </b><br>".ucwords(str_replace('_', " ", $propertyname)) ."<br>";
    $table .="<b>Amount: ".number_format($amount, 2)." <br> (Ksh  ".convert_number_to_words($amount).' only)'."</b><br>";
    $table .="<b>Remarks</b>&nbsp;".$remarks."<br>";
    $table .="<br>";
    $table .="<u>Date</u><u>Description</u><u>Amount</u><u>Balance</td></tr> 
          ";
    $table .="<tr height='1%' border='1' ><td style='border: 1px solid black;'>".date("d-m-Y",strtotime($recpdate))."</td><td style='border: 1px solid black;'>B/F</td><td style='border: 1px solid black;'>-</td><td style='border: 1px solid black;'>-</td></tr> ";
    $table .="<tr height='1%'  ><td></td><td>Rent</td><td>".number_format($amount, 2)."</td><td>".number_format($balance,2)."</td></tr>
                                </table></td></tr>";
    $table .="<tr ><td colspan='4'><b>Payment Mode&nbsp;&nbsp;</b>".strtoupper($mode[0]['paymode'])."</b></td></tr>"; 
    if(strtoupper($mode[0]['paymode'])=="CHEQUE"){
     $table .="<tr height='2%'><td colspan='4' style='height:1px;'><b>CHEQUE NO ". $chequeno.' OF '.$cheqdet."</b>&nbsp;<b>Chq.Date&nbsp;</b>".date("d-m-Y",strtotime($chequedate))."</td></tr>";   
    }else if(strtoupper($mode[0]['paymode'])=="BANK DEPOSIT"){  
      $table .="<tr height='2%'><td colspan='4' style='height:1px;'><b>". getReceiptBank($recpno,$recpdate)."</b></td></tr>";   
    }
     $table .="<tr height='2%'><td colspan='4' style='height:1px;'><b>REFERENCE:</b>".strtoupper($reference)."</b></td></tr>";
      if($balance){
   $table .="<tr height='5%'><td colspan='4' style='text-align:center' class='blackfont'>BAL C/FORWARD:&nbsp;&nbsp;". 'Ksh: '.number_format($balance,2)."</td></tr>";       
      }
      $table .="<tr height='5%'><td colspan='4' align='right' style='font-size:10px' >". $user.'&nbsp;&nbsp;'.$date.'&nbsp;&nbsp;'.$time."</td></tr>
<tr height='1%'><td colspan='4' ><center><b><br></b></center></td></tr>
<tr height='1%'><td  colspan='4' ><img src='../images/cursors/Footer.png'/></td></tr></table>";
$pdf->WriteHTML($table);
//$pdf->WriteHTML('You can<br><p align="center">center a line</p>and add a horizontal rule:<br><hr>');
$filename = $receiptno.".pdf";
// encode data (puts attachment in proper format)
$pdfdoc = $pdf->Output($filename, "S");
        //save and email
$emails=getAdminEmail();
$name = $tenantdetails['name'];
    $to =$tenantdetails['email'];
    $from =$emails['from'];
     $cc =$emails['cc'];
$subject = "RIVERCOURT PROPERTY RECEIPT ";
$message1 = "Greetings ".$name." , 

Kindly find attached invoice .

Regards
".$user."
";
// a random hash will be necessary to send mixed content
//$separator = md5(time());
// carriage return type (we use a PHP end of line constant)
//$eol = PHP_EOL;
// attachment name

$attachment = chunk_split(base64_encode($pdfdoc));
// main header (multipart mandatory)

 $transport = Swift_SmtpTransport::newInstance('mail.techsavanna.technology', 587)
 ->setUsername('francis@techsavanna.technology')
 ->setPassword('qwerty789.');
          $message = Swift_Message::newInstance();
          $mailer = Swift_Mailer::newInstance($transport);
         $mailer->registerPlugin(new Swift_Plugins_AntiFloodPlugin(100, 30));
         
 $message = Swift_Message::newInstance()
              ->setFrom('francis@techsavanna.technology')
				//->setFrom(array($sentfrom => 'ACCOUNT STATEMENT')) // From: 
                ->setTo('francis@techsavanna.technology')
         ->setBody($message1)
                ->setSubject($subject);
$message->attach(Swift_Attachment::newInstance($pdfdoc, 'document.pdf','application/pdf'));
     
        if($mailer->send($message)){
            echo "Successfully sent email";
        } else{
                 echo "Could not send email";
        }

}





