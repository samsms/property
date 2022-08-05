<?php


    // require '../includes/config.php';
    require 'functions.php';
    header("Content-Type: application/json");

    $response = '{
        "ResultCode": 0, 
        "ResultDesc": "Confirmation Received Successfully"
    }';

    // Response from M-PESA Stream
    $jsoncallbackData = file_get_contents('php://input');

    // log the response
    $logFile = "Transaction.txt";
    // write to file
    $log = fopen($logFile, "a");
    fwrite($log, $jsoncallbackData);
    fclose($log);

    $callbackData = json_decode($jsoncallbackData, true); // We will then use this to save to database
	
    $transaction = array(
            ':TransactionType'      => $callbackData['TransactionType'],
            ':TransID'              => $callbackData['TransID'],
            ':TransTime'            => $callbackData['TransTime'],
            ':TransAmount'          => $callbackData['TransAmount'],
            ':BusinessShortCode'    => $callbackData['BusinessShortCode'],
            ':BillRefNumber'        => $callbackData['BillRefNumber'],
            ':InvoiceNumber'        => $callbackData['InvoiceNumber'],
            ':OrgAccountBalance'    => $callbackData['OrgAccountBalance'],
            ':ThirdPartyTransID'    => $callbackData['ThirdPartyTransID'],
            ':MSISDN'               => $callbackData['MSISDN'],
            ':FirstName'            => $callbackData['FirstName'],
            ':MiddleName'           => $callbackData['MiddleName'],
            ':LastName'             => $callbackData['LastName']

	);

    // this will insert to database.
    insert_response($transaction);

    echo $response;
?>