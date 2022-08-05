<?php
   # header("Content-Type: application/json");

    // Save the M-PESA input stream. 
    $data = file_get_contents('php://input');

   /// $json_decode = json_decode($data);

   // $paybillNo = $data['BusinessShortCode'];

    //if ($paybillNo == '60000'){
        $response = '{
            "ResultCode": "C2B00011"
            "ResultDesc": "Rejected"
          }';

        $json_response = json_encode($response);

        echo $json_response;
  //  }
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
    fwrite($log, $data);
    fclose($log);
    // will be used when we want to save the response to database for our reference
   // $content = json_decode($mpesaResponse, true); 

    // write the M-PESA Response to file
  

?>
