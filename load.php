<?php
@include 'includes/database.php';
include  'modules/functions.php';
include "modules/landlordpay.php";
ob_start();
ini_set('error_reporting', E_ALL);
ini_set('display_errors', true);

if(isset($_FILES['import_file'])){
    // Fork a child process to handle the file upload and processing in the background
    // $pid = pcntl_fork();

        // Child process - perform the file upload and processing

        $fname = $_FILES['import_file']['tmp_name'];
        if (!($fp = fopen($fname, 'r'))) {
            die("Can't open file...");
        }

        // parse csv rows into array
        $data = array();
        $result = array();
        $headers = fgetcsv($fp);
        $houseTagIndex = array_search("house tag", $headers);

        // Extract the desired headers, excluding "house tag"
        $chargenames = array_slice($headers, $houseTagIndex + 1);
        
        // Print the reduced headers
      //  print_r($reducedHeaders);
        while ($row = fgetcsv($fp, 1024, ",")) {
            $chargables=array_slice($row,$houseTagIndex + 1);   
            $invoice = array();
            foreach ($headers as $index => $header) {
                $invoice[$header] = $row[$index];
            }
            $invoice_date = $invoice["date"];
            $propid = trim(getPropertyId(($invoice["property"])));
            $house = $invoice["house tag"];
            $debit=array_sum($chargables); 
                 
            $date = DateTime::createFromFormat('d/m/Y', trim($invoice_date));
            // print_r( $chargables);
            // print(array_sum($chargables));
            if ($propid == null || getTenantfromApt($propid, trim($house)) == null || $date == false) {
                // Property or tenant not found, write row to new CSV file
                $newfp = fopen("invoices_errors.csv", "a");
                fputcsv($newfp, $row);
                fclose($newfp);
            } else if ( $debit != 0) {
                $tenant = getTenantfromApt($propid, $house);
                $number_without_comma = str_replace(",", "",  $debit);
                $double_number = floatval($number_without_comma);
                $charges = array($double_number);
                // $chargesname = array("rent");
                $invoiceno = create_invoice_Bulky(
                    $tenant->Id,
                    $date->format("d/m/Y"),
                    0,
                    $double_number,
                    0,
                    "import",
                    $propid,
                    "imported",
                    $chargenames,
                    $chargables,
                    count($chargenames),
                    0,
                    $tenant->apartmentid,
                    33,
                    "",
                    0
                );
            }
        }
        // fclose($fp);

    //    sync_invoices();   
    
        // ob_end_clean();
        // header('Content-Type: application/csv');
        // header('Content-Disposition: attachment; filename="invoices_errors.csv"');
        // readfile('invoices_errors.csv');

        // Delete the not_found.csv file
        // unlink('invoices_errors.csv');
    }

