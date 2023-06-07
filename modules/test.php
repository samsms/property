<?php
function sync_invoices(){

        $db = new MySQLDatabase();
        $db->open_connection();
        $invoices = $db->query("SELECT * FROM invoices WHERE sync='0' ") or die($db->error()); 
        while($invoice=mysqli_fetch_assoc($invoices)){
            $id=$invoice['idno'];
            $invoiceamount=$invoice['amount'];
            $creditamount=$invoice['creditamount'];
            $remarks=$invoices['remarks'];
            $tablenam = "tenants";
            $queryy = $db->query("SELECT tenant_name FROM $tablenam WHERE id='$id' ") or die($db->error());
            $rowtenant = mysql_fetch_array($queryy);
            $headers = array(
                'Authorization: Basic ' . base64_encode('api-user:admin'),
            );
            $tnt = $rowtenant['tenant_name'];
    
            if ($creditamount > 0) {
                $items = array(
                    array(
                        'account_code' => 30000001,
                        'amount' => $invoiceamount - $creditamount,
                        'memo' => $id
                    ),
                    array(
                        'account_code' => 40000001,
                        'amount' => $creditamount,
                        'memo' => $remarks
                    ),
                    array(
                        'account_code' => 20000001,
                        'amount' => $invoiceamount,
                        'memo' => $remarks
                    )
                );
            } else {
    
                $items = array(
                    array(
                        'account_code' => 30000001,
                        'amount' => $invoiceamount,
                        'memo' => $remarks
                    ),
                    array(
                        'account_code' => 20000001,
                        'amount' => $invoiceamount,
                        'memo' => $remarks
                    )
                );
            }
            $json2 = array(
                'currency' => 'KS',
                'source_ref' => 30000001,
                'reference' => 30000001,
                'memo' => "Rent invoice",
                'amount' => $invoiceamount,
                'bank_act' => 20000001,
                'items' => $items
            );
            $json_data2 = json_encode($json2);
            die($json_data2);
          
        }
    }
      
        //Perform curl post request to add gl to the accounts erp
        // $curl2 = curl_init();

        // curl_setopt_array($curl2, array(
        //     CURLOPT_URL => "https://techsavanna.technology/river-court-palla/api/endpoints/journal.php?action=add-journal&company-id=RIVER",
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_ENCODING => "",
        //     CURLOPT_MAXREDIRS => 10,
        //     CURLOPT_TIMEOUT => 0,
        //     CURLOPT_FOLLOWLOCATION => true,
        //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        //     CURLOPT_CUSTOMREQUEST => "POST",
        //     CURLOPT_POSTFIELDS => $json_data2,
        //     CURLOPT_HTTPHEADER => $headers,
        // ));

        // $response2 = curl_exec($curl2);
        // // die(print_r($response2));
        // curl_close($curl2);

        // $new_id2 = json_decode($response2)->id;

        // if ($new_id2) {
        //     $response = array("success" => "1", "message" => "Success");
        //     //$response = array("success" => "1", "message" => "Short detected and added total_short = " . $total_short. " total_comments = ". $total_comments);
        // } else {
        //     $response = array("success" => "0", "message" => "Failed to add invoice  to ERP");
        // }
    //     $itms[] = array(
    //         'product_id' => 1,
    //         'product_code' => 1000,
    //         'product_name' => "Landlord amount and Chargables",
    //         'unit_price' => $invoiceamount - $creditamount,
    //         'quantity' => 1
    //     );
    //     $items2[] = array(
    //         'product_id' => 2,
    //         'product_code' => 1005,
    //         'product_name' => "Agent Commission",
    //         'unit_price' => $creditamount,
    //         'quantity' => 1
    //     );
    //     array_push($itms, $items2);
    //     $itm = array(
    //         array(
    //             'product_id' => 1,
    //             'product_code' => 1000,
    //             'product_name' => "Landlord amount and Chargables",
    //             'unit_price' => $invoiceamount - $creditamount,
    //             'quantity' => 1
    //         ),
    //         array(
    //             'product_id' => 2,
    //             'product_code' => 1005,
    //             'product_name' => "Agent Commission",
    //             'unit_price' => $creditamount,
    //             'quantity' => 1
    //         )
    //     );


    //     $json = array();

    //     $data2 = array(
    //         'InvoiceNo' => $result2,
    //         'CustId' => $id,
    //         'RefNo' => $result2,
    //         'comments' => 'some comment',
    //         'OrderDate' => $entrydate,
    //         'DeliverTo' => $tnt,
    //         'DeliveryAddress' => "River",
    //         'DeliveryCost' => '0',
    //         'DeliveryDate' => $entrydate,
    //         'InvoiceTotal' => $invoiceamount,
    //         'agentamount' => $creditamount,
    //         'DueDate' => $entrydate,
    //         'items' => $itm
    //     );

    //     $json[] = $data2;
    //     $json_data = json_encode($json);
    //     $myfile = fopen("invoice.txt", "a");
    //     fwrite($myfile, $json_data);
    //     fclose($myfile);
    //     $username = "api-user";
    //     $password = "admin";
    //     $headers = array(
    //         'Authorization: Basic ' . base64_encode($username . ':' . $password),
    //     );

    //     //Perform curl post request to add item to the accounts erp
    //     $curl = curl_init();

    //     curl_setopt_array($curl, array(
    //         CURLOPT_URL => "https://techsavanna.technology/river-court-palla/api/endpoints/invoice.php?action=add-invoice&company-id=RIVER",
    //         CURLOPT_RETURNTRANSFER => true,
    //         CURLOPT_ENCODING => "",
    //         CURLOPT_MAXREDIRS => 10,
    //         CURLOPT_TIMEOUT => 0,
    //         CURLOPT_FOLLOWLOCATION => true,
    //         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //         CURLOPT_CUSTOMREQUEST => "POST",
    //         CURLOPT_POSTFIELDS => $json_data,
    //         CURLOPT_HTTPHEADER => $headers,
    //     ));

    //     $response = curl_exec($curl);

    //     curl_close($curl);

    //     $response_data = json_decode($response);
    //     // Further processing ...
    //     foreach ($response_data as $itemObj) {
    //         $status = $itemObj->Status;
    //     }

    //     if ($status == 'ok') {
    //         $response = array("success" => "1", "message" => "Sale added", "data" => $payments);
    //     } else {
    //         $response = array("success" => "0", "message" => "Sale not added. Erp fail.");
    //     }

    //     header('Content-Type: application/json');
    //     $response_array['status'] = 'Invoice/Credit Note ' . $result2 . ' created!';
    //     $response_array['invoiceno'] = $result2;
    //     echo json_encode($response_array);
    // }

    // $db->close_connection();

