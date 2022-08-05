<?php
include '../modules/functions.php';
//die($_POST["id"]);
//echo "<script type='text/javascript'>alert('me');</script>";
//header("Location: ../modules/defaultreports.php?report=fetchstatement&startdate=06/01/2017&enddate=06/01/2017&clientid=2&count=0&propid=1");
if($_SESSION['usergroup']==1){
echo vacate_apartment();
}
?>
