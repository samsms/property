 <div id="demo">
<?php
@session_start();
include 'functions.php';
if($_REQUEST['table']==='floorplan'){
$rows=floorplan($_SESSION['propertyid']);
if($_SESSION['usergroup']==1){
?><a  id="addnewfloor" href="#" ><div style="padding:10px;float:right;border:1px solid grey;"><img src="../images/add.png">Add New</div></a>
<?php }?>
     <br><br>
<table id="treport" style="width:100%" class="display dataTable"><br>
<thead><tr>
    <tr><th>No</th><th>Floor</th><th>House No</th><th>Rent/month</th><th>Mkt Value</th><th>Annual rent</th><th>Elec Meter</th><th>Water Meter</th><th>Meter Reading</th><th>Deposit</th><th>Receipt Due</th><th>Edit</th><th>Delete</th></tr>
</thead><tbody>
    <?php  $count=1;
       foreach($rows as $row){ ?>
    <tr class="floorplanrow" id="<?php echo $row['apt_id']?>"><td><?php echo $count?></td><td id="floornumbertd<?php echo $row['apt_id']?>"><?php echo $row['floornumber']?></td><td id="housenumbertd<?php echo $row['apt_id']?>"> <?php echo $row['apt-tag']?></td><td id="monthrenttd<?php echo $row['apt_id']?>"><?php echo $row['monthlyincome']?></td><td id="marketvaluetd<?php echo $row['apt_id']?>"><?php echo $row['marketvalue']?></td><td><?php echo $row['yearlyincome']?></td><td id="elecmetertd<?php echo $row['apt_id']?>"><?php echo $row['elecmeter']?></td><td id="watermetertd<?php echo $row['apt_id']?>"><?php echo $row['watermeter']?></td><td id="metereadingtd<?php echo $row['apt_id']?>"><?php echo $row['metereading']?></td><td><a style="color:blue" href="#" id="<?php echo $row["apt_id"] ?>" class="deposits">deposits</a></td><td id="receipt_duetd<?php echo $row['apt_id']?>"><?php echo $row['receipt_due']?></td><td><?php if($_SESSION['usergroup']==1){?><a href="#" id="<?php echo $row["apt_id"] ?>" class="editfloor"><img alt="edit" src="../images/edit.png"></a><?php }?></td><td><?php if($_SESSION['usergroup']==1){?><a href="#" id="<?php echo $row["apt_id"]?>" class="deletefloor"><img alt="del" src="../images/del.png"> </a><?php }?></td></tr>
     
<?php $count++;
    } 
   exit();
     ?>
</tbody></table>
     
<?php
}

if($_REQUEST['table']==='chargeitems'){
    $rows=  getChargeItems($_SESSION['propertyid']);
    ?>
     
    <a  id="addchargeableitem" href="#" ><div style="padding:10px;float:right;border:1px solid grey;"><img src="../images/add.png">Add New</div></a>
     <br><br>
<table id="treport1" style="width:100%" class="display dataTable"><br>
<thead><tr>
<tr><th>No</th><th>Item</th><th>Amount</th><th>Has VAT</th><th>Has Commission</th><th>Is deposit</th><th>edit</th><th>delete</th></tr>
</thead><tbody>
    <?php  $count=1;
       foreach($rows as $row){ ?>
    <tr class="floorplanrow" id="<?php echo $row['id']?>"><td><?php echo $count?></td><td id="accnametd<?php echo $row['id']?>"><?php echo $row['accname']?></td><td id="amounttd<?php echo $row['id']?>"> <?php echo $row['amount']?></td><td id="vattd<?php echo $row['id']?>"><?php echo $row['has_vat']?></td><td id="commissiontd<?php echo $row['id']?>"><?php echo $row['commission']?></td><td id="deposittd<?php echo $row['id']?>"><?php echo $row['is_deposit']?></td><td><a href="#" id="<?php echo $row["id"] ?>" class="editchargeitem"><img alt="edit" src="../images/edit.png"></a></td><td><a href="#" id="<?php echo $row["id"]?>" class="deletechargeitem"><img alt="del" src="../images/del.png"> </a></td></tr>
     
<?php $count++;
    } 
     ?>
</tbody></table>
  
    
<?php } ?>
 </div> 