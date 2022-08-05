<?php
include_once 'functions.php';
if($_REQUEST["editfloorplan"]){
    $floordetails=array("apt_id"=>strip_tags($_REQUEST["apt_id"]),"propertyid"=>strip_tags($_REQUEST["propertyid"]),"floornumber"=>strip_tags($_REQUEST["floornumber"]),"apt_tag"=>strip_tags($_REQUEST["apt_tag"]),"monthlyincome"=>strip_tags($_REQUEST["monthlyincome"]),"marketvalue"=>strip_tags($_REQUEST["marketvalue"]),"elecmeter"=>strip_tags($_REQUEST["elec_meter"]),"watermeter"=>strip_tags($_REQUEST["water_meter"]),"metereading"=>strip_tags($_REQUEST["current_water_reading"]),"receipt_due"=>strip_tags($_REQUEST["receipt_due"]));
    header('Content-type: application/json');
    $result=editfloorplan($floordetails);
    $responsearray['status']=$result;
    echo json_encode($responsearray);

}
else if($_REQUEST["addfloorplan"]){
    $floordetails=array("apt_id"=>strip_tags($_REQUEST["apt_id"]),"propertyid"=>strip_tags($_REQUEST["propertyid"]),"floornumber"=>strip_tags($_REQUEST["floornumber"]),"apt_tag"=>strip_tags($_REQUEST["apt_tag"]),"monthlyincome"=>strip_tags($_REQUEST["monthlyincome"]),"marketvalue"=>strip_tags($_REQUEST["marketvalue"]),"elecmeter"=>strip_tags($_REQUEST["elec_meter"]),"watermeter"=>strip_tags($_REQUEST["water_meter"]),"metereading"=>strip_tags($_REQUEST["current_water_reading"]),"receipt_due"=>strip_tags($_REQUEST["receipt_due"]));
    header('Content-type: application/json');
    $result=  addapartments($floordetails);
    $responsearray['status']=$result;
    echo json_encode($responsearray);

}
else if($_REQUEST["deletefloorplan"]){
    $floordetails=array("apt_id"=>strip_tags($_REQUEST["apt_id"]));
    header('Content-type: application/json');
    $result=  deletefloorplan($floordetails);
    $responsearray['status']=$result;
    echo json_encode($responsearray);

}

else if($_REQUEST["deposits"]){
    $aptid=strip_tags($_REQUEST["apt_id"]);
  $deposits=getDeposits($aptid);
  ?><table style="width:100%">
     <tr><td><b>No</b></td><td><b>Deposit</b></td><td><b>Amount</b></td><td></td></tr>
 <?php  $count=1;
 foreach ($deposits as $deposit) {
     $depid=$deposit['dep_id'];
  echo '<tr><td>'.$count.'</td><td>'.$deposit['dep_description'].'</td>'.'<td>'.$deposit['amount'].'</td><td><a href="#" style="color:red" id="'.$depid.'" class="deletedeposit" > X </a></td></tr>';    
  $count++;
  
 }?>
     <tr><td colspan="5"><a href="#" class="adddeposit">+Add a deposit</a></tr>
          </table>
<table id="newdeposits" style="width:100%">
    
</table>
     <?php 
}
elseif ($_REQUEST["savedeposits"]) {
       $deposits=strip_tags($_REQUEST["alldeposits"]);
       $aptid=strip_tags($_REQUEST["aptid"]);
    $alldeposits=json_decode($deposits);
    header('Content-type: application/json');
    foreach ($alldeposits as $deposit => $value) {
     $result=saveDeposits($aptid,$deposit,$value) ;
    }
    $responsearray['status']=$result;
    echo json_encode($responsearray);
}
elseif ($_REQUEST["deletedeposit"]) {
       $depositid=strip_tags($_REQUEST["dep_id"]);
        header('Content-type: application/json');
       $result=deleteDeposits($depositid);
        $responsearray['status']=$result;
    echo json_encode($responsearray);
}
elseif ($_REQUEST["savechargeables"]) {
  $propertyid=strip_tags($_REQUEST["propid"]);
   $itemid=strip_tags($_REQUEST["itemid"]); 
    $vat=strip_tags($_REQUEST["vat"]);
     $commission=strip_tags($_REQUEST["commission"]);
     $isdeposit=strip_tags($_REQUEST["deposit"]);
 $item=strip_tags($_REQUEST["item"]);      
       $amount=strip_tags($_REQUEST["amount"]);
      header('Content-type: application/json');
         $result=  saveChargeItem($propertyid,$itemid,$item,$amount,$vat,$commission,$isdeposit) ;
        $responsearray['status']=$result;
    echo json_encode($responsearray);
}
elseif ($_REQUEST["deletedeposit"]) {
       $depositid=strip_tags($_REQUEST["dep_id"]);
        header('Content-type: application/json');
       $result=deleteDeposits($depositid);
        $responsearray['status']=$result;
    echo json_encode($responsearray);
}
elseif ($_REQUEST["deletechargeitem"]) {
       $itemid=strip_tags($_REQUEST["itemid"]);
        header('Content-type: application/json');
       $result=deleteChargeItem($itemid);
        $responsearray['status']=$result;
    echo json_encode($responsearray);
}


else{die("Incorrect parameter passed");}
