<?php /** @noinspection ForgottenDebugOutputInspection */

use Shuchkin\SimpleXLSX;

include  'modules/functions.php';
ini_set('error_reporting', E_ALL);
ini_set('display_errors', true);

require_once __DIR__.'/simplexlsx/src/SimpleXLSX.php';
// getPropByName()
//echo '<h1>Parse books.xslx</h1><pre>';

if ($xlsx = SimpleXLSX::parse('Book1.xlsx')) {
    $json= ($xlsx->rows());
} else {
    echo SimpleXLSX::parseError();
}
foreach($json as $row){
    if( getPropByName($row[3])==null){
        echo json_encode($row)."</br>";;
    }
    else{
        
        $propid= getPropByName($row[3]);
        $debit=$row['4'];
        $credit=$row['5'];
        $balance=$row['6'];
        $invoice_date=$row['0'];
        $house=$row['2'];
        
    echo $propid.'- '.$house.' - '. getTenantfromApt($propid,$house)."<br>";
    }
   
}
//echo '<pre>';
