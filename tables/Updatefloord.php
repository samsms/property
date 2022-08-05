<?php

include '../includes/database.php';
$db=new MySQLDatabase();
$db->open_connection();

$editedValue = mysql_real_escape_string( $_POST['value'] );
$id =  mysql_real_escape_string($_POST['id']);
$colID =  mysql_real_escape_string($_POST['columnId'] );
//$user_id= mysql_real_escape_string( $_SESSION['user_id']  );
$tablename='floorplan';

$bal= mysql_real_escape_string( $editedValue );

if($colID==3)
{
$status=mysql_query("update $tablename set units='$bal' where propertyid='$id'") or print "Database Error: ".mysql_error();
if($status == 1){
	echo "OK,Succesfuly updated units.";
}else
	echo "Failed to update units, try again";
	
}
	elseif($colID==4){

$status=mysql_query("update $tablename set apt_tag='$bal' where propertyid='$id'") or print "Database Error: ".mysql_error();
if($status == 1){
	echo "OK,Succesfuly updated apartment tag. ";
}else
	echo "Failed to update apartment tag, try again";
	
	}
	elseif($colID==5){

$status=mysql_query("update $tablename set monthlyincome='$bal' where propertyid='$id'") or print "Database Error: ".mysql_error();
if($status == 1){
	echo "OK,Succesfuly updated monthlyincome.";
}else
	echo "Failed to update monthlyincome, try again";
	
	}
	elseif($colID==6){

$status=mysql_query("update $tablename set totalmnthlyincome='$bal' where propertyid='$id'") or print "Database Error: ".mysql_error();
if($status == 1){
	echo "OK,Succesfuly monthly income.";
}else
	echo "Failed to monthly income, try again";
	
	}
	elseif($colID==7){

$status=mysql_query("update $tablename set marketvalue='$bal' where propertyid='$id'") or print "Database Error: ".mysql_error();
if($status == 1){
	echo "OK,Succesfuly updated marketvalue.";
}else
	echo "Failed to update marketvalue, try again";
	
	}

	else { echo "Server Error!"; }
	
?>
