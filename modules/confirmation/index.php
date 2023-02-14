<?php
 $callbackJsonData = file_get_contents('php://input');

ini_set('display_errors',1);
ini_set('display_startup_errors',1);
error_reporting(-1);
@include '.../includes/database.php';
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

    
  
     

    $db = getMysqliConnection();
   
    $tablename = "mobile_payments";

      $query = $db->query("INSERT into $tablename(`TransactionType`, `TransID`, `TransTime`, `TransAmount`, `BusinessShortCode`, `BillRefNumber`, `InvoiceNumber`, `OrgAccountBalance`, `ThirdPartyTransID`, `MSISDN`, `FirstName`, `MiddleName`, `LastName`) 
           VALUES ('$TransactionType', '$TransID', '$TransTime', '$TransAmount', '$BusinessShortCode', '$BillRefNumber', '$InvoiceNumber', '$OrgAccountBalance', '$ThirdPartyTransID', '$MSISDN', '$FirstName', '', '') ") or die(mysql_error());

   

    echo $response;
    //return $transaction;
?>

   