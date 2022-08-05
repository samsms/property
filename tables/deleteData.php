<?php
include '../includes/database.php';
$db=new MySQLDatabase();
$db->open_connection();
$id =  mysql_real_escape_string($_POST['id']);
$user_id= mysql_real_escape_string( $_SESSION['user_id']);
//$user=getUserById($user_id);
$tablename='properties';
$result=$db->query("select active from properties where propertyid='$id'");
$active=0;
if($row=mysql_fetch_array($result)){
$active=$row["active"]	;
}
	
if($active){
$status=$db->query("update properties SET active=0 where propertyid='$id'") or print "Database Error: ".mysql_error();
}
else{
$status=$db->query("update properties SET active=1 where propertyid='$id'") or print "Database Error: ".mysql_error();	
}
if($status == 1){
    //cascade deletion
    //$db->query("DELETE FROM agentproperty WHERE property_id='$id'") or die($db->error());
    // $db->query("DELETE FROM floorplan WHERE propertyid='$id'") or die($db->error());
    
    //$db->query("DELETE FROM tenants WHERE property_id='$id'") or die($db->error());
    
	echo "OK,Succesfuly updated Property.";
        
}else
	echo "Failed to update the property, try again";
$db->close_connection();
?>
