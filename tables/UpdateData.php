<?php

include '../includes/database.php';
$db=new MySQLDatabase();
$db->open_connection();

$editedValue = mysql_real_escape_string( $_POST['value'] );
$id =  mysql_real_escape_string($_POST['id']);
$colID =  mysql_real_escape_string($_POST['columnId'] );
//$user_id= mysql_real_escape_string( $_SESSION['user_id']  );
$tablename='properties';

$bal= mysql_real_escape_string( $editedValue );

if($colID==1)
{
$status=mysql_query("update $tablename set property_name='$bal' where propertyid='$id'") or print "Database Error: ".mysql_error();
if($status == 1){
	echo "OK,Succesfuly updated name.";
}else
	echo "Failed to update Name, try again";
	
}
	elseif($colID==2){

$status=mysql_query("update $tablename set plotno='$bal' where propertyid='$id'") or print "Database Error: ".mysql_error();
if($status == 1){
	echo "OK,Succesfuly updated Plot No. ";
}else
	echo "Failed to update Plot No, try again";
	
	}
	elseif($colID==3){

		$status=mysql_query("update $tablename set pay_day='$bal' where propertyid='$id'") or print "Database Error: ".mysql_error();
		if($status == 1){
			echo "OK,Succesfuly updated Pay Date.";
		}else
			echo "Failed to updatePay Date try again";
			
			}
	elseif($colID==4){

$status=mysql_query("update $tablename set property_type='$bal' where propertyid='$id'") or print "Database Error: ".mysql_error();
if($status == 1){
	echo "OK,Succesfuly updated Property Type.";
}else
	echo "Failed to update Property type, try again";
	
	}
	elseif($colID==5){

$status=mysql_query("update $tablename set address='$bal' where propertyid='$id'") or print "Database Error: ".mysql_error();
if($status == 1){
	echo "OK,Succesfuly updated Address.";
}else
	echo "Failed to update Address, try again";
	
	}
	elseif($colID==6){

$status=mysql_query("update $tablename set category='$bal' where propertyid='$id'") or print "Database Error: ".mysql_error();
if($status == 1){
	echo "OK,Succesfuly updated category.";
}else
	echo "Failed to update category, try again";
	
	}
	elseif($colID==7){

$status=mysql_query("update $tablename set owner='$bal' where propertyid='$id'") or print "Database Error: ".mysql_error();
if($status == 1){
	echo "OK,Succesfuly updated Owner.";
}else
	echo "Failed to update Owner, try again";
	
	}
	elseif($colID==8){

$status=mysql_query("update $tablename set mohalla='$bal' where propertyid='$id'") or print "Database Error: ".mysql_error();
if($status == 1){
	echo "OK,Succesfuly updated mohalla.";
}else
	echo "Failed to update mohalla, try again";
	
	} 
        
        elseif($colID==9){

$status=mysql_query("update $tablename set water_rate='$bal' where propertyid='$id'") or print "Database Error: ".mysql_error();
if($status == 1){
	echo "OK,Succesfuly updated rate.";
}else
	echo "Failed to update rate, try again";
	
	} 
        elseif($colID==10){

$status=mysql_query("update $tablename set agent_commission='$bal' where propertyid='$id'") or print "Database Error: ".mysql_error();
if($status == 1){
	echo "OK,Succesfuly updated commission.";
}else
	echo "Failed to update, try again";
	
	}        
           elseif($colID==12){
$newbal=($bal/0.00024711);
$newbal1=(43560*$bal);
//$status=mysql_query("update $tablename set area='$bal',areasq='$newbal',areasqft='$newbal1' where propertyid='$id'") or print "Database Error: ".mysql_error();
$status=mysql_query("update $tablename set has_vat='$bal' where propertyid='$id'") or print "Database Error: ".mysql_error();
if($status == 1){
	echo "OK,Succesfuly updated VAT.";
}else
	echo "Failed to update VAT, try again";
	
	} 
        
        elseif($colID==13){

$status=mysql_query("update $tablename set titledeed='$bal' where propertyid='$id'") or print "Database Error: ".mysql_error();
if($status == 1){
	echo "OK,Succesfuly updated title.";
}else
	echo "Failed to update title, try again";
	
	} 
	else { echo "Server Error!"; }
	
?>
