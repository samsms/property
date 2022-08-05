<?php
ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(E_ALL);
require_once('../modules/functions.php');
include_once '../includes/database.php';
include '../includes/numberformatter.php'; //format amount in words
require_once('../Swift-5.1.0/lib/swift_required.php');
require_once('../Swift-5.1.0/lib/swift_init.php');  
require_once('../tcpdf/config/tcpdf_config.php');
require_once('../tcpdf/tcpdf.php');

$document=$_REQUEST['document'];
$propid=$_REQUEST['propid'];
$user=$_REQUEST['username'];
class MYPDF extends TCPDF {

	//Page header
	public function Header() {
		// Logo
		$image_file = K_PATH_IMAGES.'LHEAD.png';
	$this->Image($image_file, 0, 0, 210, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
		// Set font
		$this->SetFont('helvetica', 'B', 20);
		// Title
	//	$this->Cell(0, 15, '<< TCPDF Example 003 >>', 0, false, 'C', 0, '', 0, false, 'M', 'M');
	}
	// Page footer
	public function Footer() {
		
		$logoX = 40; // 
   $logoFileName = "../images/cursors/Footer.png";
   $logoWidth = 130; // 15mm
   $logoY = 280;
   $logo = $this->PageNo() . ' | '. $this->Image($logoFileName, $logoX, $logoY, $logoWidth);

   $this->SetX($this->w - $this->documentRightMargin - $logoWidth); // documentRightMargin = 18
   $this->Cell(10,10, $logo, 0, 0, 'C');
	}
}
if($document=='invoice'){
 printthisinvoice($_REQUEST['invoiceno'], $propid, $user);//$pdf->LoadData('countries.txt');
}

if($document=='receipt'){
 printthisreceipt($_REQUEST['recpno'],$propid, $user);//$pdf->LoadData('countries.txt');
}
function printthisinvoice($invoiceno, $propid, $user) {
 
    
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
    //if ($db->num_rows($sql) > 0) {
        while ($row = $db->fetch_array($sql)) {
            $invoiceno = $row['invoiceno'];
            $invoicedate = $row['invoicedate'];
            $amount = $row['amount'];
            $idno = $row['idno'];
            $incomeacct = $row['incomeaccount'];
            $billing = $row['invoicecredit'];
            $remarks = $row['remarks'];
            $bbf = $row['bbf'];
        }
        //get apartment No
        $apartmentid=getApartmentFromTenant($idno);
        $aptdetails=  getApartmentDetails($apartmentid);
        $billing == 0 ? $valuey ="INVOICE" : $valuey ="CREDIT NOTE"; //type of billing
        $sql1 = $db->query("SELECT accname FROM $tablename1 WHERE `id` like '$incomeacct' ORDER BY `priority` ASC") or die($db->error());
        while ($row1 = $db->fetch_array($sql1)) {
            $accname = $row1['accname'];
        }
        $sql2 = $db->query("SELECT tenants.property_name,tenants.apartmentid,tenants.Id,tenants.tenant_name,floorplan.last_water_reading,floorplan.current_water_reading,banks.bank_name,banks.acct_no,banks.acct_name FROM tenants  JOIN floorplan ON tenants.apartmentid=floorplan.apt_id LEFT JOIN banks ON banks.id = tenants.bank_id  WHERE ( tenants.Id='$idno' AND tenants.vacated like '0')") or die($db->error());
        while ($row2 = $db->fetch_array($sql2)) {
            $propertyname = $row2['property_name'];
            $tname = $row2['tenant_name'];
            $tenantid=$row['Id'];
            $lastwater = $row2['last_water_reading'];
            $currentwater = $row2['current_water_reading'];
            $bankname = $row2['bank_name'];
             $acct_no = $row2['acct_no'];
             $acct_name = $row2['acct_name'];
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
             array_push($tableitems, '<tr height="2%"><td  style="border: 1px solid black;"></td><td style="color:black;border: 1px solid black;" ><u>' . $item_name . '</u></td><td style="border: 1px solid black;" align="right">Ksh:' . number_format($item_amount, 2) . '&nbsp;&nbsp;</td></tr>');

            }
        }
        //get outstanding balance/prepayment
         if($bbf =='0'){
        $balance = getCorrectBalance($idno, $invoiceno,$invoicedate);
        } else{
          $balance = $bbf;  
        }
        //$balance=  getCorrectBalance($idno,$invoiceno,$invoicedate);
        $chargestotal=array_sum($chargeablesamount);
        $totaldue=$balance+$chargestotal;
        $pdf = getPdfSettings();
        $invdata = '<table class="tftable printable" width="90%"  style="font-family:Arial,sans-serif;letter-spacing:2px;" > 
        <tr><td><b>'. $valuey.' NO: '.$invoiceno.'</b></td><td></td><td align="right"> Date ' . date("d-m-Y", strtotime($invoicedate)) . '</td></tr>
        <tr><td colspan="3"></td></tr>
        <tr><td>TO :</td><td colspan="2">'. ucwords(@$tname) . '(' . $aptdetails[0]['apt_tag'] . ') <b>OF </b>'.ucwords(str_replace('_', " ", $propertyname)).'</td></tr><tr><td colspan="3"></td></tr>';
        
   foreach ($chargeableitems as $value) {
    if (strtoupper($value) == 'WATER') {
        $invdata .='<tr><td>WATER CONSUMPTION :</td><td colspan="2" border="1"> <span style="border:2px solid black;color:black">Last Reading:&nbsp;&nbsp;' . $lastwater . '&nbsp;Current Reading:&nbsp;&nbsp;' . $currentwater . '&nbsp;<br>Consumption:&nbsp;' . ($currentwater - $lastwater) . '&nbsp;&nbsp;units&nbsp;&nbsp;Rate:(ksh)' . get_water_rate($propid) . '&nbsp;&nbsp;&nbsp;</span>
                            </td></tr>';

                          }
                          }
           $invdata .='<tr><td colspan="3"></td></tr>'; 
            $invdata .='<tr><td colspan="3"><table class="tdborder" width="100%" align="center" style="line-height: 1.8px;
      padding: 1.8px">'; 
             $invdata .='<tr><th style="border: 1px solid black;"></th><th style="border: 1px solid black;"><b>Particulars</b></th><th style="border: 1px solid black;"><b>Amount</b></th></tr>'; 
   foreach ($tableitems as $key => $value1) {
            $invdata .= trim($value1, 0); //remove trailing zeros
        }
       
        $invdata .='<tr><td colspan="2" style="border: 1px solid black;"><b>TOTAL CHARGEABLE</b></td><td style="border: 1px solid black;"  align="right"><b>'. number_format($chargestotal, 2).'&nbsp;&nbsp;</b></td></tr>';
       $invdata .='<tr><td colspan="2" class="blackfont" style="border: 1px solid black;">BALANCE BROUGHT FORWARD:</td><td style="border: 1px solid black;" align="right">Ksh: ' . number_format($balance, 2).' &nbsp;&nbsp;</td></tr>';
        $invdata .='<tr><td colspan="2" style="border: 1px solid black;"><b>TOTAL DUE</b></td><td style="border: 1px solid black;" align="right"><b>'. number_format($totaldue, 2).'&nbsp;&nbsp;</b></td></tr>';
          $invdata .='</table></td></tr>';
        $invdata .='<tr><td colspan="3"></td></tr>';   
       $invdata .='<tr><td colspan="2">Being ' . $valuey . ' for:' . @$remarks .'</td></tr>';
           $invdata .='<tr><td colspan="3"></td></tr>';
          // $invdata .='<tr><td colspan="3" align="right" style="font-size:10px">Bank Details:'.$bankname.' <br> Acct No:'.$acct_no.'<br> Acct Name: '.$acct_name.'</td></tr>';
       $invdata .='<tr><td colspan="3" align="right" style="font-size:8px"><span class="linkright">Created by &nbsp;&nbsp;'. $user . '&nbsp;&nbsp;' . $date . '&nbsp;&nbsp;' . $time.'</span></td></tr></table>';
       
   $tenantdetails=getTenantDetails($idno) ;
 
    $pdf->AddPage("P");
        $pdf->SetFont('dejavusans', '', 10);
        $tbl = <<<EOD
        
   $invdata
        
EOD;

$filename = "rerer.pdf";

 $pdf->Ln(60);   
        $pdf->writeHTML($tbl, true, false, false, false, '');

        
        $pdfdoc = $pdf->Output($filename, "S");
       //save and email
$emails=getAdminEmail();
$name = $tenantdetails['name'];
    $to =$tenantdetails['email'];
$from =$emails['from'];
$cc =$emails['cc'];
$subject = "HILLSGATE PROPERTY Invoice ".date("d-m-Y",strtotime($invoicedate));
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
$filename = $invoiceno.".pdf";
// encode data (puts attachment in proper format)
$pdfdoc = $pdf->Output($filename, "S");
$attachment = chunk_split(base64_encode($pdfdoc));
// main header (multipart mandatory)


  $transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465,'ssl')->setUsername('hillsgateproperties1@gmail.com')->setPassword('rupbwawxlcwljgdo');
          $message = Swift_Message::newInstance();
          $mailer = Swift_Mailer::newInstance($transport);
         $mailer->registerPlugin(new Swift_Plugins_AntiFloodPlugin(100, 30));
         
 $message = Swift_Message::newInstance()
              ->setFrom('hillsgateproperties1@gmail.com')
				//->setFrom(array($sentfrom => 'ACCOUNT STATEMENT')) // From: 
               ->setTo($to)
               //->setTo('frankmutura@gmail.com')
         ->setBody($message1)
                ->setSubject($subject);
$message->attach(Swift_Attachment::newInstance($pdfdoc, 'Invoice.pdf','application/pdf'));
     
        if($mailer->send($message)){
            echo "Successfully sent email";
        } else{
                 echo "Could not send email";
        }
         
// send message

}

function printthisreceipt($receiptno, $propid, $user) {
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
             $pmode = $row['pmode'];
            $invoiceno = $row['invoicenopaid'];
            $mode=getPayMode($pmode);
        }
        
    }
        $sql2 = $db->query("SELECT property_name,tenant_name,Apartment_tag FROM $tablename2 WHERE ( `Id`='$idno' AND `vacated` like '0')") or die($db->error());
        while ($row2 =$db->fetch_array($sql2)) {
            $propertyname = $row2['property_name'];
            $tname = $row2['tenant_name'];
            $tag = $row2['Apartment_tag'];
        }
        $sql4 = $db->query("SELECT invoicedate FROM $invoicetable WHERE invoiceno = '$invoiceno'") or die($db->error());
        while ($row4 =$db->fetch_array($sql4)) {
            $invoicedate = $row4['invoicedate'];
           
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
        
        $balance=  getCorrectBalance($idno,$latestinvoice='0',$invoicedate);
        
        $penaltysql=$db->query("SELECT amount FROM $invoicetable WHERE recpno='$recpno' AND `is_penalty`=1 ") or die($db->error());
        while ($row= $db->fetch_array($penaltysql)) {
            $penaltyamount = $row['amount'];
              }
   $transport = Swift_SmtpTransport::newInstance('smtp.gmail.com', 465,'ssl')->setUsername('hillsgateproperties1@gmail.com')->setPassword('rupbwawxlcwljgdo');

   // $transport = Swift_SmtpTransport::newInstance('mail.techsavanna.technology', 587)
 //->setUsername('francis@techsavanna.technology')
 //->setPassword('qwerty789.');
          $message = Swift_Message::newInstance();
          $mailer = Swift_Mailer::newInstance($transport);
         $mailer->registerPlugin(new Swift_Plugins_AntiFloodPlugin(100, 30));
// retreiving each posted accno in the array

    //foreach ($acnos as $key => $val) {
 $pdf = getPdfSettings();
     
    
      $disp = 'none';
$mydata = '
     <table class="tftable printable " width="90%" style="font-family:Arial,sans-serif;letter-spacing:2px;" >
         <tr height="1%"><td align="right" colspan="4"><b>RECEIPT</b></td></tr>
           <tr height="2%"><td colspan="4" style="height:1px;" align="right"><u>Date:</u>&nbsp;'.date("d-m-Y",strtotime($recpdate )).'</td></tr>
           <tr height="2%"><td colspan="4" style="height:1px;" align="right"><u>Receipt No:</u>&nbsp;&nbsp;&nbsp;&nbsp;'. $recpno.'&nbsp;&nbsp;</td></tr>
      
                        <tr height="1%"><td colspan="4"><b>Received From:</b>&nbsp;'. ucwords(@$tname) .'('.@$tag.')'.' <b>OF </b><br>'.ucwords(str_replace('_', " ", $propertyname)) .'</td></tr>
                        <tr height="2%"><td colspan="4" align="right" style="height:1px;"><b>Amount: '. number_format($amount, 2).'   <br> (Ksh&nbsp;'.convert_number_to_words($amount).' only)'.'</b></td></tr>
                        <tr height="1%"><td colspan="4"><b>Remarks</b>&nbsp;'. $remarks.'</td></tr> 
                        <tr height="1%"><td colspan="4"><table class="tdborder" width="90%" align="center">
                        <tr height="1%" border="1"  ><td style="border: 1px solid black;">Date</td><td style="border: 1px solid black;">Description</td><td style="border: 1px solid black;">Amount</td><td style="border: 1px solid black;">Balance</td></tr> 
                         <tr height="1%" border="1" ><td style="border: 1px solid black;">'.date("d-m-Y",strtotime($recpdate )).'</td><td style="border: 1px solid black;">B/F</td><td style="border: 1px solid black;">-</td><td style="border: 1px solid black;">-</td></tr> 
                        <tr height="1%"  ><td style="border: 1px solid black;"></td><td style="border: 1px solid black;">Rent</td><td style="border: 1px solid black;">'. number_format($amount, 2).'</td><td style="border: 1px solid black;">'. number_format($balance,2).'</td></tr>
                                </table></td></tr>
                        <tr height="1%"><td colspan="4"><b>Payment Mode</b>&nbsp;'.strtoupper($mode[0]['paymode']).'</td></tr> ';
 if(strtoupper($mode[0]['paymode'])=="CHEQUE"){
     $mydata .="<tr height='2%'><td colspan='4' style='height:1px;'><b>CHEQUE NO ". $chequeno.' OF '.$cheqdet."</b>&nbsp;<b>Chq.Date&nbsp;</b>".date("d-m-Y",strtotime($chequedate))."</td></tr>";   
    }else if(strtoupper($mode[0]['paymode'])=="BANK DEPOSIT"){  
      $mydata .="<tr height='2%'><td colspan='4' style='height:1px;'><b>". getReceiptBank($recpno,$recpdate)."</b></td></tr>";   
    }

     $mydata .='<tr height="2%"><td colspan="4" ><b>REFERENCE</b>&nbsp;'.strtoupper($reference).'</td></tr>';
      if($balance){
   $mydata .='<tr height="5%"><td colspan="4" style="text-align:center"><b>BAL C/FORWARD</b>&nbsp;'.number_format($balance,2).'</td></tr>';
      }
      $mydata .="<br><br><tr height='5%'><td colspan='4' align='right' style='font-size:5px' >". $user.'&nbsp;'.$date.'&nbsp;'.$time."</td></tr>
<tr height='1%'><td colspan='4' ><center><b><br></b></center></td></tr>
</table>";

// -----------------------------------------------------------------------------

        $pdf->AddPage("P");
        $pdf->SetFont('dejavusans', '', 10);
        $tbl = <<<EOD
        
   $mydata
        
EOD;

$filename = "rerer.pdf";

 $pdf->Ln(40);   
        $pdf->writeHTML($tbl, true, false, false, false, '');

        
        $pdfdoc = $pdf->Output($filename, "S");
// -----------------------------------------------------------------------------
//Close and output PDF document or email
$emails=getAdminEmail();
$name = $tenantdetails['name'];
    $to =$tenantdetails['email'];
    $from =$emails['from'];
     $cc =$emails['cc'];
 $subject = "HILLSGATE PROPERTY RECEIPT ";
$message1 = "Greetings ".$name." , 

Kindly find attached Receipt .

 Regards
".$user."
";    
$message = Swift_Message::newInstance()
              ->setFrom('hillsgateproperties1@gmail.com')
				//->setFrom(array($sentfrom => 'ACCOUNT STATEMENT')) // From: 
                ->setTo($to)
         ->setBody($message1)
                ->setSubject($subject);
$message->attach(Swift_Attachment::newInstance($pdfdoc, 'Receipt.pdf','application/pdf'));
     
        if($mailer->send($message)){
            echo "Successfully sent email";
        } else{
                 echo "Could not send email";
        }
        

}
function getPdfSettings() {

$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nicola Asuni');
$pdf->SetTitle('TCPDF Example 003');
$pdf->SetSubject('TCPDF Tutorial');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    $pdf->setPageOrientation('P');
// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}
    return $pdf;
}


?>

