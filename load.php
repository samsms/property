<?php

/** @noinspection ForgottenDebugOutputInspection */

use Shuchkin\SimpleXLSX;

@include 'includes/database.php';
include  'modules/functions.php';
include "modules/landlordpay.php";

ini_set('error_reporting', E_ALL);
ini_set('display_errors', true);

require_once __DIR__ . '/simplexlsx/src/SimpleXLSX.php';
// getPropByName()
//echo '<h1>Parse books.xslx</h1><pre>';

if ($xlsx = SimpleXLSX::parse('techsavanna.xlsx')) {
    $json = ($xlsx->rows());
} else {
    echo SimpleXLSX::parseError();
}
foreach ($json as $row) {
    // if( getPropByName($row[3])==null){
    //    echo json_encode($row)."</br>";;
    // }
    // else{

    $propid = getPropByName($row[3]);
    $debit = $row['5'];
    $credit = $row['6'];
    $balance = $row['7'];
    $invoice_date = $row['0'];
    $house = $row['2'];
    $tenant=getTenantfromApt($propid, $house);
    $date = DateTime::createFromFormat('d/m/y', trim( $row['0']));
    

    if (trim($row['4']) != "Vacant"&&$tenant!="") {
     // die( 'te'.var_dump($debit));
        if(  trim($tenant->tenant_name) == trim($row['4'])){
        
            $invoiceno= create_invoice(
            $tenant->Id,
            $date->format("d/m/Y") ,
            0,
            $debit,
            0,
           "import",
            $propid,
            "imported",
            null,
            null,
            null,
           0,
            $tenant->apartmentid,
            33,
            "",
            0
        );
        // die("invoice no =$rec");
        update_invoice(
        $invoiceno, 
        $credit,
        $tenant->Id,
        $date->format("d/m/Y") ,
        0,
       0, 
        null, 
        null, 
        null, 
        null, 
        "imported", 
        "imported", 
        "imported", 
        1, 
        $propid, 
        0, 
        0, 
        33, 
        0,
        "null");
        //die(json_encode($tenant));
    }

        // echo $propid.'-         -'.$house.'-- '. getTenantfromApt($propid,$house)."-    $debit : $credit = $balance  <br>";
    }


}
