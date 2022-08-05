<?php
include '../includes/database.php';
$db=new MySQLDatabase();
$db->open_connection();
$id =  mysql_real_escape_string($_POST['id']);
//$user_id= mysql_real_escape_string( $_SESSION['user_id']  );
$tablename='properties';

$status=mysql_query("delete from $tablename where propertyid='$id'") or print "Database Error: ".mysql_error();
if($status == 1){
	echo "OK,Succesfuly deleted Property.";
        
}else
	echo "Failed to delete the property, try again";
$db->close_connection();
?>
