<?php
include 'functions.php';
$agentname=$_REQUEST['agentname'];
$agentpassword=$_REQUEST['agentpassword'];
$group=$_REQUEST['usergroup'];
$agentphone=$_REQUEST['agentphone'];
$agentaddress=$_REQUEST['agentaddress'];


echo registeragent($agentname,$agentpassword,$agentphone,$group,$agentaddress);

?>