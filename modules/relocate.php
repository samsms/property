<?php
include 'functions.php';
$tenantid=$_REQUEST['tenantid'];
$propid=$_REQUEST['propertyid'];
$propname=$_REQUEST['propertyname'];
$aptid=$_REQUEST['apartmentid'];
$apttag=$_REQUEST['apttag'];
$leasestart=$_REQUEST['leasestart'];
$leaseend=$_REQUEST['leaseend'];
$leasedoc=$_REQUEST['leasedoc'];

echo relocatetenant($tenantid,$propid,$propname,$aptid,$apttag,$leasestart,$leaseend,$leasedoc);

?>
