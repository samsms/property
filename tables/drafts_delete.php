<?php

include '../includes/connection.php';
$editedValue =  mysql_escape_string( $_POST['value'] );
$id =  mysql_escape_string($_POST['id']);
$colId =  mysql_escape_string($_POST['columnId'] );
$user_id= mysql_escape_string( $_SESSION['user_id']  );

$bal= mysql_escape_string( $editedValue );

$status=mysql_query("delete * from account where id='$id'") or print "Database Error: ".mysql_error();
if($status == 1){
	echo "Succesfuly updated Balance.";
}else
	echo "Failed to update Balance, try again";
?>
