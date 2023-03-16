<?php
include 'functions.php';
$agentid = addslashes($_REQUEST['agentid']);
if($_REQUEST['singleproperty']){
   
$propid = addslashes($_REQUEST['propid']);
$propname = addslashes($_REQUEST['propname']);
$commission = addslashes($_REQUEST['commission']);

echo assignproperty($agentid,$propid,$propname,$commission);
}
elseif ($_REQUEST['multipleproperties']) {
echo assignProperties($agentid);
}

else{
    die('No condition supplied:agent-property');
}
