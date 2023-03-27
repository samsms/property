<?php
@include 'includes/database.php';
include  'modules/functions.php';
include "modules/landlordpay.php";
ob_start();
ini_set('error_reporting', E_ALL);
ini_set('display_errors', true);

if(isset($_FILES['import_file'])){
    $fname=$_FILES['import_file']['tmp_name'];
    if (!($fp = fopen($fname, 'r'))) {
        die("Can't open file...");
    }

    //read csv headers
    $key = fgetcsv($fp, 1024, ",");

    // parse csv rows into array
    $data = array();
    $result = array();

    while ($row = fgetcsv($fp, 1024, ",")) {
       
        $propid = getPropertyId(($row[1]));
        //die($propid);
        if ($propid == null || getTenantfromApt($propid, $row[2]) == null) {
        // die(print_r($row));
            // Property or tenant not found, write row to new CSV file
            $newfp = fopen("not_found.csv", "a");
            fputcsv($newfp, $row);
            fclose($newfp);
        } else {
            // Property and tenant found, create invoice
            //die(print_r($row));
            $debit = $row[3];
            $credit = 0;
            $balance = $row[3];
            $invoice_date = $row[0];
            $house = $row[2];
            $tenant = getTenantfromApt($propid, $house);
            $number_without_comma = str_replace(",", "",  $debit);
            $double_number = floatval($number_without_comma);
            $date = DateTime::createFromFormat('d-M-y', trim($invoice_date));
            $charges = array($double_number);
            $chargesname = array("rent");
            $invoiceno = create_invoice(
                $tenant->Id,
                $date->format("d/m/Y"),
                0,
                $double_number,
                0,
                "import",
                $propid,
                "imported",
                $chargesname,
                $charges,
                1,
                0,
                $tenant->apartmentid,
                33,
                "",
                0
            );
            // die('h');
        }
    }
    fclose($fp);
    
    // Download the not_found.csv file
    ob_end_clean();
    header('Content-Type: application/csv');
    header('Content-Disposition: attachment; filename="not_found.csv"');
    readfile('not_found.csv');
    

    // Delete the not_found.csv file
    unlink('not_found.csv');
}
