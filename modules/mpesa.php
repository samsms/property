<?php
//die("dd". $_SESSION['username']);]
$log = fopen("sam.txt", "a");
fwrite($log, "ssa");
fclose($log);
die("hello");
require 'functions.php';
function pay($id,$invoicenos,$amount){
 
  $fperiod=$fperiod;
 $penalty=null;
  $penaltygl=null;
  $idno=$id;
  $receiptdate=date('d/m/Y');
  $paymode="4";
  $cashaccount=null; 
  $bankaccount=null ;
   $chequedate=null ;
   $chequeno=null ; 
    $chequedetails=null;
    $remarks="received via mpesa paybill" ;
    $recpamount=null;// ($amount - $pdamount)  // htmlspecialchars($_REQUEST['recpamountarray']);
    $reference="MXN7BGDY67";
    $propid=$invoice['property_id'] ;
    $user=$_SESSION['username'];
     $paidby='0'; 
     $period=getPeriodByDate(date('d/m/Y'));
$fperiod;
if(is_array($period)){
  $fperiod=$period[0]['idclose_periods'];
} 
else{
  $fperiod=$period['idclose_periods'];
}
     echo update_invoice($invoicenos,$amount,$idno,$receiptdate,$paymode,$cashaccount,$bankaccount,$chequedate,$chequeno,$chequedetails,$remarks,$paidby,$user,$counter,$propid,$penalty,$penaltygl,$fperiod,$bankdeposit,$reference);
}
//(set_charge_items("8"));
$tenant= getTenantDetailsFromId("31544766");
//die($Id);
$invoices=fetchinvoicedetailsPlain($tenant['Id']);

//echo $period['idclose_periods'] ;
$paid_amount=20000;
$count_invoices=count($invoices);
$counter=0;
foreach($invoices as $invoice){
  $counter++;
  $bankdeposit=null;
  $invoicenos=  $invoice['invoiceno'];    //json_decode($_REQUEST['invoicenosarray'],true);
   $amount= ($invoice['amount'] - $invoice['paidamount']);//json_decode($_REQUEST['recpamountarray'],true);
    if($count_invoices==1){
      pay($tenant['Id'],$invoicenos,$paid_amount);
    }
  else if($paid_amount> $amount){
    //$amount=$amount;
    //die("s".$amount);
    pay($tenant['Id'],$invoicenos,$amount);
   
  }
  else if(($paid_amount<=$amount)&&$paid_amount!=0){
    //die("s".$amount);
    $amount=$paid_amount;
    pay($tenant['Id'],$invoicenos,$amount);
  }
  else{

  }
 
   // htmlspecialchars($_REQUEST['paidby']);
}


//      $user=$_SESSION['username'];
     
//      for($i=0;$i<$counter;$i++) {
//          if($bankdeposit){
// update_invoice($invoicenos[$i],$amount[$i],$idno,$receiptdate,$paymode,$cashaccount,$bankaccount,$chequedate,$chequeno,$chequedetails,$remarks,$paidby,$user,$counter,$propid,$penalty,$penaltygl,$fperiod,$bankdeposit,$reference);
//          }
//          else{
//              update_invoice($invoicenos[$i],$amount[$i],$idno,$receiptdate,$paymode,$cashaccount,$bankaccount,$chequedate,$chequeno,$chequedetails,$remarks,$paidby,$user,$counter,$propid,$penalty,$penaltygl,$fperiod,$bankdeposit=0,$reference);
//          }
// }











// if(isset ($_REQUEST['receiptclient'])){
   
//     $bankdeposit=$_REQUEST['bankdeposit'];
//    $invoicenos=json_decode($_REQUEST['invoicenosarray'],true);
//     $amount=1000;
//     $counter=htmlspecialchars($_REQUEST['counter']);
//     $fperiod=htmlspecialchars($_REQUEST['fperiod']);
//     $penalty=htmlspecialchars($_REQUEST['applypenalty']);
//      $penaltygl=htmlspecialchars($_REQUEST['penaltygl']);
//      $idno=htmlspecialchars($_REQUEST['tenantid']);
//       $receiptdate=date("Y-m-d H:i:s");
//       $paymode="Mpesa";
//        $recpamount=  htmlspecialchars($_REQUEST['recpamountarray']);
//        $reference=htmlspecialchars($_REQUEST['reference']);
//        $propid=  htmlspecialchars($_REQUEST['propid']);
//         $paidby='0'; // htmlspecialchars($_REQUEST['paidby']);
//         $user=$_SESSION['username'];
      
          
// update_invoice($invoicenos[$i],$amount[$i],$idno,$receiptdate,$paymode,$cashaccount,$bankaccount,$chequedate,$chequeno,$chequedetails,$remarks,$paidby,$user,$counter,$propid,$penalty,$penaltygl,$fperiod,$bankdeposit=0,$reference);
            
  
    
// }

// ?>
