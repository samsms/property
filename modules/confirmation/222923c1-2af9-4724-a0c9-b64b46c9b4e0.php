<?php
 $callbackJsonData = file_get_contents('php://input');

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);
require '../../includes/database.php';
require '../functions.php';
    header("Content-Type: application/json");

    $response = '{
        "ResultCode": 0, 
        "ResultDesc": "Confirmation Received Successfully"
    }';

    // Response from M-PESA Stream
   
    //die("hee");
  
    // log the response
    $logFile = "Transaction.txt";
     // write to file
    $log = fopen($logFile, "a");
    fwrite($log, $callbackJsonData);
    fclose($log);

    $jsoncallback = json_decode($callbackJsonData);
//die(print_r($jsoncallback));
  
        $TransactionType     = $jsoncallback->TransactionType;
        $TransID             = $jsoncallback->TransID;
        $TransTime         = $jsoncallback->TransTime;
        $TransAmount       = $jsoncallback->TransAmount;
        $BusinessShortCode = $jsoncallback->BusinessShortCode;
        $BillRefNumber   = $jsoncallback->BillRefNumber;
        $InvoiceNumber     = $jsoncallback->InvoiceNumber;
        $OrgAccountBalance = $jsoncallback->OrgAccountBalance;
        $ThirdPartyTransID = $jsoncallback->ThirdPartyTransID;
        $MSISDN          = $jsoncallback->MSISDN;
        $FirstName         = $jsoncallback->FirstName;
        // '$MiddleName'           => $jsoncallback['MiddleName'],
        // '$LastName'             => $jsoncallback['LastName']

    
  
    $tenant=getTenantDetailsFromApt($BillRefNumber);
   
    if(!$tenant){
        
    }
    $db = getMysqliConnection();
   
    $tablename = "mobile_payments";

      $query = $db->query("INSERT into $tablename(`TransactionType`, `TransID`, `TransTime`, `TransAmount`, `BusinessShortCode`, `BillRefNumber`, `InvoiceNumber`, `OrgAccountBalance`, `ThirdPartyTransID`, `MSISDN`, `FirstName`, `MiddleName`, `LastName`) 
           VALUES ('$TransactionType', '$TransID', '$TransTime', '$TransAmount', '$BusinessShortCode', '$BillRefNumber', '$InvoiceNumber', '$OrgAccountBalance', '$ThirdPartyTransID', '$MSISDN', '$FirstName', '', '') ") or die(mysql_error());

   

    echo $response;
    create_mpesa_receipt($BillRefNumber, $TransAmount, $TransID);



    function create_mpesa_receipt($id,$paid_amount,$reference){
        $tenant = getTenantDetailsFromApt($id);
        $invoices = fetchinvoicedetailsPlain($tenant['Id']);
    //die(json_encode($invoices));
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
            $fperiod = "";
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
            $user = "mpesa";
            $paidby = '0';
            $period = getPeriodByDate(date('d/m/Y'));
            $fperiod;
          
            // if (is_array($period)) {
            //     $fperiod = $period[0]['idclose_periods'];
            // } else {
            //     $fperiod = $period['idclose_periods'];
            // }
        ob_start();
             update_invoice($invoicenos, $amount, $idno, $receiptdate, $paymode, $cashaccount, $bankaccount, $chequedate, $chequeno, $chequedetails, $remarks, $paidby, $user, 0, $propid, $penalty, $penaltygl, $fperiod, null, $reference);
        return  ob_get_clean();

            }

    //return $transaction;
?>

   