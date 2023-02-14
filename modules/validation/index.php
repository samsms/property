<?php
  header("Content-Type: application/json");
  ini_set('display_errors',1);
  ini_set('display_startup_errors',1);
  error_reporting(-1);
    // Save the M-PESA input stream.
    @include '../includes/database.php';
   include '../functions.php'; 
    $raw_data = file_get_contents('php://input');

    $data = json_decode($raw_data);

    $accountNo = $data->BillRefNumber;
    //die($accountNo);
    $tenant=getTenantDetailsFromId($accountNo);
    //die(print_r($tenant));
    if (!$tenant){
     
          $response = array("ResultCode"=> "C2B00011" ,"ResultDesc"=>"Rejected");

        $json_response = json_encode($response);

        echo $json_response;
   }
   else{
    $response = array("ResultCode"=> 0 ,"ResultDesc"=>"Accepted");

  $json_response = json_encode($response);

  echo $json_response;
   }
//   Headers
//   Key: Authorization
//   Value: Basic cFJZcjZ6anEwaThMMXp6d1FETUxwWkIzeVBDa2hNc2M6UmYyMkJmWm9nMHFRR2xWOQ==
//   ​
//   Body
//     {
//       "ShortCode": 600988,
//       "ResponseType": "Completed",
//       "ConfirmationURL": "https://160f-102-167-73-200.eu.ngrok.io/property-rivercourt/modules/validation/",
//       "ValidationURL": "https://160f-102-167-73-200.eu.ngrok.io/property-rivercourt/modules/validation/"
//     }
    // log the response
    $logFile = "sam.txt";
    $log = fopen($logFile, "a");
    fwrite($log, $raw_data);
    fclose($log);
    // will be used when we want to save the response to database for our reference
   // $content = json_decode($mpesaResponse, true); 

    // write the M-PESA Response to file
  

?>
