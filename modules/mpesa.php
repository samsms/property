<?php
require 'functions.php';
function create_mpesa_receipt($id,$paid_amount,$reference){
$tenant = getTenantDetailsFromId($id);
$invoices = fetchinvoicedetailsPlain($tenant['Id']);

$last_invoice = end($invoices);

foreach ($invoices as $invoice) {
    $amount_due = $invoice['amount'] - $invoice['paidamount'];
    $amount_to_pay = ($invoice === $last_invoice) ? $paid_amount : min($paid_amount, $amount_due);

    if ($amount_to_pay > 0) {
        pay($tenant['Id'], $invoice, $amount_to_pay, $reference);
        $paid_amount -= $amount_to_pay;
    }

    if ($paid_amount <= 0) {
        break;
    }
}

}
function pay($id, $invoice, $amount, $reference) {
    $invoicenos = $invoice['invoiceno'];
    $fperiod = $fperiod;
    $penalty = null;
    $penaltygl = null;
    $idno = $id;
    $receiptdate = date('d/m/Y');
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
    $period = getPeriodByDate(date('d/m/Y'));
    $fperiod;

    if (is_array($period)) {
        $fperiod = $period[0]['idclose_periods'];
    } else {
        $fperiod = $period['idclose_periods'];
    }

    echo update_invoice($invoicenos, $amount, $idno, $receiptdate, $paymode, $cashaccount, $bankaccount, $chequedate, $chequeno, $chequedetails, $remarks, $paidby, $user, $counter, $propid, $penalty, $penaltygl, $fperiod, $bankdeposit, $reference);
}
?>