<?php
session_start();
//die(print_r($_SESSION));
//die( $_SESSION['propertyid']);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'display.php';
include '../modules/functions.php';
echo  $htmlheaders;
echo '<head><title>' . $property['company_name'] . '| Jamar Properties</title>';
echo $meta;
echo '<link rel="stylesheet" type="text/css" media="screen" href="' . $baseurl . 'css/overall2.css" />';
echo '<link rel="stylesheet" href="../css/jquery-ui.css">';
echo '<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>';
// echo $jquery;
//later set variable to session
$admin = '' . $_SESSION['username'] . '';
date_default_timezone_set('Africa/Nairobi');

$user =  getUserById($_SESSION['userid']);

$pendingP=getAllPendingPrepayments();
foreach($pendingP as $pendingP){
    echo '
  
    <tr>
      <th scope="col">'.$pendingP['propid'].'&nbsp &nbsp &nbsp &nbsp;'.$pendingP['aptid'].'&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp;'.$pendingP['status'].' &nbsp &nbsp &nbsp &nbsp  
      <button  type="button" class="Approve btn btn-primary">Aprove</button></br></th>
    </tr>
  </thead>
  ';
}


?>

