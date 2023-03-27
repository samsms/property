<?php
require 'functions.php';
ob_start();
ini_set('error_reporting', E_ALL);
ini_set('display_errors', true);

if (isset($_FILES['receipt_file'])) {
    $fname = $_FILES['receipt_file']['tmp_name'];
    if (!($fp = fopen($fname, 'r'))) {
        die("Can't open file...");
    }

    //read csv headers
    $key = fgetcsv($fp, 1024, ",");

    // parse csv rows into array
    $data = array();
    $result = array();

    while ($row = fgetcsv($fp, 1024, ",")) {
        $tenant = getTenantfromApt(getPropertyId($row[1]), $row[2]);
     // die(print_r($row));
        if (!$tenant) {
            // Property or tenant not found, write row to new CSV file
            $newfp = fopen("not_found.csv", "a");
            fputcsv($newfp, $row);
            fclose($newfp);
        } else {
            // Property and tenant found, create receipt
        //    die(print_r( $tenant));
            $amount_paid = floatval(str_replace(",", "", $row[3]));
            create_mpesa_receipt($row[0],$tenant->Id, $amount_paid, "imported");
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

function create_mpesa_receipt($date,$id , $paid_amount, $reference) {
     //$tenant = getTenantDetailsFromId($id);
    
    $invoices = fetchinvoicedetailsPlain($id);

    $last_invoice = end($invoices);

    foreach ($invoices as $invoice) {
        $amount_due = $invoice['amount'] - $invoice['paidamount'];
        $amount_to_pay = ($invoice === $last_invoice) ? $paid_amount : min($paid_amount, $amount_due);

        if ($amount_to_pay > 0) {
            pay($date,$id, $invoice, $amount_to_pay, $reference);
            $paid_amount -= $amount_to_pay;
        }

        if ($paid_amount <= 0) {
            break;
        }
    }
}

function pay($date,$id, $invoice, $amount, $reference) {
    //die("$id, $invoice, $amount, $reference");
    $date = DateTime::createFromFormat('m/d/y', trim($date));
    $invoicenos = $invoice['invoiceno'];
    $fperiod = $fperiod;
    $penalty = null;
    $penaltygl = null;
    $idno = $id;
    $receiptdate =  $date->format("d/m/Y");
    //die($receiptdate);
    $paymode = "4";
    $cashaccount = null; 
    $bankaccount = null;
    $chequedate = null;
    $chequeno = null;
    $chequedetails = null;
    $remarks = $reference;
    $recpamount = $amount;
    $propid = $invoice['property_id'];
    $user = $_SESSION['username'];
    $paidby = '0';
    $period = getPeriodByDate( $date->format("d/m/Y"));
    $fperiod;

    if (is_array($period)) {
        $fperiod = $period[0]['idclose_periods'];
    } else {
        $fperiod = $period['idclose_periods'];
    }

    echo update_invoice($invoicenos, $amount, $idno, $receiptdate, $paymode, $cashaccount, $bankaccount, $chequedate, $chequeno, $chequedetails, $remarks, $paidby, $user, $counter, $propid, $penalty, $penaltygl, $fperiod, $bankdeposit, $reference);
}
