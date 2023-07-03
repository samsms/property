<?php
include '../modules/functions.php';
$test="976#H1";
$codes=explode("#",$test);
$propid=trim($codes[0]);
$apttag=trim($codes[1]);
$sql="select * from floorplan where propertyid='$propid' and apt_tag='$apttag'";
$mysqli = getMysqliConnection();
$list=$mysqli->query($sql);
if($list->num_rows>0){
    $tenant=$list->fetch_assoc()['tenant_id'];
    echo $tenant;
}
else{
    echo "no";
}
?>