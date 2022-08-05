<?php


    include '../includes/database.php';
    //require 'functions.php';
    header("Content-Type: application/json");

    $response = '{
        "ResultCode": 0, 
        "ResultDesc": "Confirmation Received Successfully"
    }';

    // Response from M-PESA Stream
    $callbackJsonData = file_get_contents('php://input');

    // log the response
    $logFile = "Transaction.txt";
     // write to file
    $log = fopen($logFile, "a");
    fwrite($log, $callback);
    fclose($log);

    $transaction = json_decode($callbackJsonData, TRUE);

    $transaction = array(
        '$TransactionType'      => $jsoncallback['TransactionType'],
        '$TransID'              => $jsoncallback['TransID'],
        '$TransTime'            => $jsoncallback['TransTime'],
        '$TransAmount'          => $jsoncallback['TransAmount'],
        '$BusinessShortCode'    => $jsoncallback['BusinessShortCode'],
        '$BillRefNumber'        => $jsoncallback['BillRefNumber'],
        '$InvoiceNumber'        => $jsoncallback['InvoiceNumber'],
        '$OrgAccountBalance'    => $jsoncallback['OrgAccountBalance'],
        '$ThirdPartyTransID'    => $jsoncallback['ThirdPartyTransID'],
        '$MSISDN'               => $jsoncallback['MSISDN'],
        '$FirstName'            => $jsoncallback['FirstName'],
        '$MiddleName'           => $jsoncallback['MiddleName'],
        '$LastName'             => $jsoncallback['LastName']

    );


    $db = new MySQLDatabase();
    $db->open_connection();
    $tablename = "mobile_payments";

    //   $query = $db->query("INSERT into $tablename(`TransactionType`, `TransID`, `TransTime`, `TransAmount`, `BusinessShortCode`, `BillRefNumber`, `InvoiceNumber`, `OrgAccountBalance`, `ThirdPartyTransID`, `MSISDN`, `FirstName`, `MiddleName`, `LastName`) 
    //            VALUES ('$TransactionType', '$TransID', '$TransTime', '$TransAmount', '$BusinessShortCode', '$BillRefNumber', '$InvoiceNumber', '$OrgAccountBalance', '$ThirdPartyTransID', '$MSISDN', '$FirstName', '$MiddleName', '$LastName') ") or die(mysql_error());

    $db->close_connection();

    echo $response;
    //return $transaction;
?>

   