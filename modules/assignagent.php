<?php
include 'functions.php';
$agentid=$_REQUEST['agentid'];


if($_REQUEST['singleproperty']){
    $propid=$_REQUEST['propid'];
$propname=$_REQUEST['propname'];
$commission=$_REQUEST['commission'];
echo assignproperty($agentid,$propid,$propname,$commission);
}
elseif ($_REQUEST['multipleproperties']) {
echo assignProperties($agentid);
}

else{
    die('No condition supplied:agent-property');
}
