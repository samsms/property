<?php
include 'functions.php';
$action=$_GET['action'];
 if($action=="tenantsprop"){
echo property_tenants($_REQUEST['propid']);
 }
 elseif ($action=="tenantseditdetails") {
 echo tenantseditdetails($_REQUEST['tenantid']);
}
?>
