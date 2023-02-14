<?php
$encoded=substr(preg_replace('/\s+/', '',$_POST['data']),22);
$decoded=base64_decode($encoded);
$filename=$_POST['filename'];
if(!$decoded){
    $filename='avatar.png';
    $decoded=file_get_contents('../images/avatar.png');
}
file_put_contents("../images/tenantphotos/$filename",$decoded);

 //echo $_POST['data'];