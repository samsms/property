<?php

include 'functions.php';

$photoid=$_REQUEST['photoid'];
$target=$_REQUEST['target'];
echo movephotos($photoid,$target);

?>
