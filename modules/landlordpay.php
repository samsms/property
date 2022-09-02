<?php

function landlord_statement($propid){
    $a_date =date("Y-m");
    $fdate=  DateTime::createFromFormat("d/m/Y",date("d/m/Y", strtotime($a_date)));
    $todate=  DateTime::createFromFormat("d/m/Y",date("t/m/Y", strtotime($a_date)));
    $startdate=$fdate->format("Y-m-d");
     $enddate=$todate->format("Y-m-d");
     $total_invoices=invoiceAmount($propid,$startdate,$enddate);
     $watchmantotal=array();
     $paidamounts=array();
     $depositamounts=array();
     $rent=array();
     $invoice_amount=array();
     $commissionamounts=array();
     $chargeables=  getChargeItems($propid);
     $chargeablescount=count($chargeables);
     $landlordchargeitems=array('rent','watchman','security','vat','garbage'); //'water','deposit','rent_deposit'
     $itemnames=array();
    $count=1;
    $floordetails=   floorplan($propid) ;
foreach ($floordetails as $plan) {
    if($plan['isoccupied']==0){
        $vacantrent=$plan['monthlyincome'];
    }else{$vacantrent="";}
    // die($);
    $tenantdetails=findtenantDetailsbyapt($plan['apt_id']);
    $depositsfortenant=getTenantDeposit($tenantdetails['Id'],$startdate,$enddate);
foreach($depositsfortenant as $deposit){
    $amounts[]=$deposit['amount'];
    $dates[]=$deposit['rdate'];
    $recpnos[]=$deposit['recpno'];
    if($deposit['amount']>0){
    array_push($depositamounts,$deposit['amount']);
    }
}
$rent_amount=$plan['monthlyincome'];
if($plan['isoccupied']==1){
    array_push($rent,$plan['monthlyincome']);
}
$receipts=  getreceiptlistTenant($startdate, $enddate, $accid, $accname, $propid, $tenantdetails['Id']) ;

$tenantid=$tenantdetails['Id'];

$countitems=count($itemnames);

     for($i=0;$i<$countitems;$i++){
     
foreach($receipts[0]['chargeables'] as $itemcharged){  
   if(strtolower($itemcharged['name'])==strtolower($itemnames[$i])){
          
              
              if(strtoupper($itemcharged['name'])== "RENT" ){
                  
                $rentbalance=$itemcharged['amount'];
       
                  
              }
           
              
              if(strtolower($itemcharged['name'])=="watchman"){
                  $vat=0;
          
                  array_push($watchmantotal,$itemcharged['amount']);
              }
             $stotal=$stotal+($itemcharged['paidamount']);
          }
 }
   $stotal=($itemcharged['paidamount']);
     }
     
     //bbf
     $balanceminuslastrentinvoice=getCorrectBalance($tenantid)-$rentbalance;
             if($balanceminuslastrentinvoice<0){
                 $balanceminuslastrentinvoice=0;
             }
  
                    foreach ($receipts as $singlereceipt) {
                        $receiptsdetails[]=getReceiptsFromInvoice($singlereceipt['invoiceno'],$enddate); 
                    }
                

              //  $recps=array_unique($receiptsdetails);
                $paidamount=0;
                 foreach ( $receipts as $value) {
                     
                     //echo $value['recpno'].'#';
                     $paidamount+=$value['receiptpaidamount'];
                 }
                 
          

                                $balance=$receipts[0]['chargeables'][0]['amount'];

                               if($plan['isoccupied']==1){
                               $commissionamount= (getPropertyCommissionRate($propid)*$rent_amount)/100;
                               }
                            array_push($paidamounts,$paidamount);
                         
                    //$commissionamount=  (getPropertyCommissionRate($propid)*$paidamount)/100;//+(getPropertyCommissionRate($propid)*array_sum($amounts)/100);
                    array_push($commissionamounts,$commissionamount);
                    // //echo '<td>'.$commissionamount.'</td>';
    //extract item values
//unset items for each row-for deposits
unset($recpnos);
unset($paidamount);
unset($balance);
unset($receiptsdetails);
unset($dates);
unset($amounts);
unset($rentbalance);
unset($commissionamount);
//unset($receipts);

$count++;
}

    $totalcollected=array_sum($rent);//array_sum($paidamounts);
    $total_chargables=$total_invoices-$totalcollected;
$comm=array_sum($commissionamounts);

$totalcollected=$total_invoices;

$commissiondetail=get_commissions_listProperty($propid, $startdate, $enddate);

//expenses
$expenses=  getPaymentsForProperty(array('propid'=>$propid,'startdate'=>$startdate,'enddate'=>$enddate,'count'=>1));
$totalbill=array();

$lesscommission=$totalminuswatchman-$comm;
$vat=getVAT("housevat");
$lessvat=0;//  round(($vat*$comm)/100,2);

    foreach ($expenses as $expense=>$value) {
        array_push($totalbill,$value['billpaid']);
 
    }
    

    $banked=$totalcollected-($comm+array_sum($totalbill)+ loanPaid($propid,$startdate,$enddate)+$lessvat);

$payments=getLandLordPaidAmountsForMonth($todate->format("Y-m"),$propid);
$paidamounts=0;
foreach ($payments as $payment) {
    $paidamounts=$paidamounts+$payment["amount"];
}
$amount=$banked-$paidamounts;
tobepaid($propid,$amount);
return $amount ;

    
}

    ?>



