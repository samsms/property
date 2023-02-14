<?php
include 'functions.php';
$username=$_REQUEST['username'];
$usergroup=$_REQUEST['usergroup'];
$password=$_REQUEST['password'];
echo registeruser($username,$usergroup,$password);

?>