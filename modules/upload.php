<?php
$encoded=substr(preg_replace('/\s+/', '',$_POST['data']),22);
$decoded=base64_decode($encoded);
$filename=$_POST['filename'];
if(!$decoded){
    $filename='avatar.png';
    $decoded=file_get_contents('../images/avatar.png');
}
file_put_contents("../leasedoc/$filename",file_get_contents($_POST['data']));
//move_uploaded_file($decoded, "../leasedoc/$filename");
 echo $_POST['data'];
