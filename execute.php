<?php
// session_start();
// ini_set('display_errors',1);     # don't show any errors...
// error_reporting(E_ALL | E_STRICT);
//die("dd");
// ini_set('display_errors',1);
// ini_set('display_startup_errors',1);
// error_reporting(-1);
@session_start();

@include 'includes/database.php';
include  'modules/functions.php';
include "modules/landlordpay.php";
$list=json_decode(payout_list());
$total_amount=0;
foreach ($list as $prop){
    // echo $."<br>";
    $total_amount+=landlord_statement($prop->propertyid);
}

    echo $total_amount;
//echo total_amount();