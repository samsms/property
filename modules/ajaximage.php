<?php
@session_start();
include('functions.php');
echo uploadpictorials($propertyid=$_SESSION['propertyid'],$photocat=$_POST['photocat']);
?>