<?php
function printreceipt($receiptno, $user)
{   for($i=0;$i<2;$i++){


    include '../includes/numberformatter.php'; //format amount in words
    $db = new MySQLDatabase();
    date_default_timezone_set('Africa/Nairobi');
    $date = date("d/m/y");
    $time = date('h:i A');
    $db->open_connection();
    $tablename = "recptrans";
    $tablename2 = "tenants";
    $tablename3 = "invoiceitems";
    $tableinv = "invoices";
    $invoicetable = getInvoiceTable();
    $tableitems = [];
    $chargeablesamount = array();
    $carfrwd = 0;

    $sql = $db->query("SELECT * FROM $tablename WHERE `recpno` like '$receiptno'") or die($db->error());
    if ($db->num_rows($sql) > 0) {
        while ($row = $db->fetch_array($sql)) {
            $recpno = $row['recpno'];
            $recpdate = $row['rdate'];
            $amount = $row['amount'];
            $chequedate = $row['chequedate'];
            $chequeno = $row['chqno'];
            $cheqdet = $row['chqdet'];
            $idno = $row['idno'];
            $remarks = $row['rmks'];
            $pmode = $row['pmode'];
            $mode = getPayMode($pmode);
            $invoiceno = $row['invoicenopaid'];
            $reference = $row['reference'];
        }
        $sql2 = $db->query("SELECT property_name,tenant_name,Apartment_tag FROM $tablename2 WHERE ( `Id`='$idno' AND `vacated` like '0')") or die($db->error());
        while ($row2 = $db->fetch_array($sql2)) {
            $propertyname = $row2['property_name'];
            $tname = $row2['tenant_name'];
            $tag = $row2['Apartment_tag'];
        }
        $sql4 = $db->query("SELECT invoicedate FROM $tableinv WHERE invoiceno = '$invoiceno'") or die($db->error());
        while ($row4 = $db->fetch_array($sql4)) {
            $invoicedate = $row4['invoicedate'];

        }
        $sql3 = $db->query("SELECT item_name,amount FROM $tablename3 WHERE invoiceno='$invoiceno' ORDER BY `priority` ASC") or die($db->error());
        while ($row3 = $db->fetch_array($sql3)) {
            $item_name = $row3['item_name'];
            $item_amount = $row3['amount'];
            array_push($chargeablesamount, $item_amount);
            array_push($tableitems, '<tr><td></td><td style="color:black;" colspan="2">' . $item_name . '</u></td><td>Ksh:' . number_format($item_amount, 2) . '</td></tr>');
        }
// here............
        // $sql5 = $db->query("SELECT deposit_payed,reason FROM recptrans_deposit_refunded WHERE reciept_no='$receiptno'");

        //      get outstanding balance/pre<tr><td></td><td style="color:black;" colspan="2">'payment
        // while ($row4 =$db->fetch_array($sql5)) {
        //     $propertyname = $row2['property_name'];
        //     $deposit_payed = $row4['deposit_payed'];
        //     $reason = $row4['reason'];

        //     array_push($payed_amount,$deposit_payed);
        //     array_push($payed_amount,$reason);
        // }
        //    die(print_r($payed_amount));

        $balance = getCorrectBalance($idno, $latestinvoice = '0', $invoicedate);
        $settings = getSettings();

        $penaltysql = $db->query("SELECT amount FROM $invoicetable WHERE recpno='$recpno' AND `is_penalty`=1 ") or die($db->error());
        while ($row = $db->fetch_array($penaltysql)) {
            $penaltyamount = $row['amount'];
        }
        //echo '<br>';
        ?>
        <style>
            .tftable td {
                font-size: 16px;
            }

            .tdborder {
                border-collapse: collapse;
            }

            .tdborder td {
                border: 1px solid black;
            }

        </style>
        <table class="  " width="90%" style="font-family:Arial,sans-serif;letter-spacing:2px;">
            <br><br>
            <!--<tr style="border-top:1px solid black"><td colspan="4" ></td></tr>-->
            <tr >
                <td colspan="4"><img src="../images/cursors/LHEAD.png" height="100px"/></td>
            </tr>
            <tr height="1%">
                <td align="right" colspan="4"><b>RECEIPT</b></td>
            </tr>
            <tr height="2%">
                <td colspan="4" style="height:1px;" align="right">
                    <u>Date:</u>&nbsp;<?php echo date("d-m-Y", strtotime($recpdate)) ?></td>
            </tr>
            <tr height="2%">
                <td colspan="4" style="height:1px;" align="right"><u>Receipt
                        No:</u>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $recpno ?>&nbsp;&nbsp;
                </td>
            </tr>

            <tr height="1%">
                <td colspan="4"><b>Received From:</b>&nbsp;<?php echo ucwords(@$tname) . '(' . @$tag . ')' ?> <b>OF </b><br><?php echo ucwords(str_replace('_', " ", $propertyname)) ?>
                </td>
            </tr>
            <tr height="2%">
                <td colspan="4" align="right" style="height:1px;">
                    <b>Amount: <?php echo number_format($amount, 2) . '&nbsp <br> (Ksh&nbsp;' . convert_number_to_words($amount) . ' only)' ?></b>
                </td>
            </tr>
            <tr height="1%">
                <td colspan="4"><b>Remarks</b>&nbsp;<?php echo $remarks ?></td>
            </tr>
            <tr height="1%">
                <td colspan="4">
                    <table class="tdborder" width="90%" align="center">
                        <tr height="1%" border="1">
                            <td style="border: 1px solid black;">Date</td>
                            <td style="border: 1px solid black;">Description</td>
                            <td style="border: 1px solid black;">Amount</td>
                            <td style="border: 1px solid black;">Balance</td>
                        </tr>
                        <tr height="1%" border="1">
                            <td style="border: 1px solid black;"><?php echo date("d-m-Y", strtotime($recpdate)) ?></td>
                            <td style="border: 1px solid black;">B/F</td>
                            <td style="border: 1px solid black;">-</td>
                            <td style="border: 1px solid black;"><?php echo '0'; ?></td>
                        </tr>
                        <tr height="1%">
                            <td></td>
                            <td>Rent</td>
                            <td><?php echo number_format($amount, 2); ?></td>
                            <td><?php echo number_format($balance, 2); ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="4"><b>Payment Mode&nbsp;&nbsp;</b><?php echo strtoupper($mode[0]['paymode']); ?></b></td>
            </tr>
            <?php if (strtoupper($mode[0]['paymode']) == "CHEQUE") { ?>
                <tr height="2%">
                    <td colspan="4" style="height:1px;"><b>CHEQUE NO<?php echo $chequeno . ' OF ' . $cheqdet ?></b>&nbsp;<b>Chq.Date&nbsp;</b><?php echo date("d-m-Y", strtotime($chequedate)); ?>
                    </td>
                </tr>
            <?php } else if (strtoupper($mode[0]['paymode']) == "BANK DEPOSIT") { ?>

                <tr height="2%">
                    <td colspan="4" style="height:1px;"><b><?php echo getReceiptBank($recpno, $recpdate) ?></b></td>
                </tr>
            <?php } ?>
            <tr height="2%">
                <td colspan="4" style="height:1px;"><b>REFERENCE:</b><?= strtoupper($reference); ?></b></td>
            </tr>

            <?php
            //for peta
            // echo '<tr><td colspan="4"><hr/></td></tr>'
            echo '<tr><td></td><td colspan="3">';

            // foreach ($tableitems as $key => $value1) {

            // echo $key . $value1;

            //  }
            echo '<td></tr>';
            if ($penaltyamount) {
                echo '<tr height="5%"><td></td><td colspan="3"><u>Penalty For Late Payment</u></td><td>Ksh:' . number_format($penaltyamount, 2) . '</td></tr>';

            }

            if ($balance) { ?>
                <tr height="5%">
                    <td colspan="4" style="text-align:center" class="blackfont">BAL
                        C/FORWARD:&nbsp;&nbsp;<?php echo 'Ksh: ' . number_format($balance, 2); ?></td>
                </tr>
            <?php }
            ?>
            <tr height="5%">
                <td colspan="4" align="right"
                    style="font-size:10px"><?php echo $user . '&nbsp;&nbsp;' . $date . '&nbsp;&nbsp;' . $time ?></td>
            </tr>
            <tr height="1%">
                <td colspan="4">
                    <center><b><br></b></center>
                </td>
            </tr>
            <tr height="1%">
                <td colspan="4"></td>
            </tr>
        </table>
        <?php
        /* echo '<center><table class="printable" style="width:800px;"><div id="printheader"><tr><td colspan="3" style="width:800px"><span id="copy">Copy</span><center><span id="invoice">RECEIPT</span></center></td></tr>

</div>';
        echo '<tr><td colpan="2"><span id="invoiceno">RECEIPT NO&nbsp;' . $recpno . '</span></td><td></td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date ' .date("d-m-Y",strtotime($recpdate)) . '</td></tr>';
        echo '<tr><td colspan="3"><br/><td></tr>';
        echo '<tr><td colspan="2"><b>Received from&nbsp;</b><u>' . strtoupper(@$tname) . '</u>&nbsp;of&nbsp;<b>' . ucwords(str_replace('_', " ", @$propertyname)) . ' (' . @$tag . ') ' . '</b>
    <br>the sum of Kshs <u><b>' . convert_number_to_words($amount) . ' Only<br/>being payment for: </b>' . $remarks . '</u> </b></td><td><h4>AMOUNT: Kshs ' . number_format($amount, 2) . '</h4></td></tr>';
        echo '<tr><td colspan="3"><hr/></td></tr><tr><td colspan="3">';

        foreach ($tableitems as $key => $value1) {

            echo $key . $value1;

        }
        if($penaltyamount){
           echo '<tr><td><u>Penalty For Late Payment</u></td><td>Ksh:' . number_format($penaltyamount, 2) . '</td></tr>';

        }
        if($balance){
        echo '<tr><td class="blackfont">BALANCE CARRIED FORWARD:</td><td colspan="1"> Ksh: '.number_format($balance,2).'</td></tr>';
        }
        echo '<tr><td colspan="3">' . $user . '&nbsp;&nbsp;' . $date . '&nbsp;&nbsp;' . $time . '</td></tr>';

          echo '</table>';*/

    } else {
        // return false;
    }
    echo "ddd";
}
echo "ddd";
}
?>