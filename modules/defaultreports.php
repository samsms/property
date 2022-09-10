<?php
//die("jj");
error_reporting(0);
include '../views/display.php';
include 'functions.php';
include 'searchbyname.php';
echo  $htmlheaders;
$settings =  getSettings();
echo '<head><title>Property Manager| ' . $settings['company_name'] . '</title>';
echo $meta;
echo '<link rel="stylesheet" type="text/css" media="screen" href="' . $baseurl . 'css/overall2.css" />';
echo '<link rel="stylesheet" type="text/css" media="print" href="' . $baseurl . 'css/overall2.css" />';
echo '<link rel="stylesheet" type="text/css" media="print" href="' . $baseurl . 'css/bootstrap.css" />';
echo '<script type="text/javascript" src="../js/jquery-1.9.1.js"></script>';
echo '<script type="text/javascript" src="../js/jquery-ui.min.js"></script>';
echo '<script type="text/javascript" src="../js/jquery.dataTables.min.js"></script>';
echo '<script type="text/javascript" src="../js/jquery.PrintArea.js"></script>';
echo '<script type="text/javascript" src="../js/jquery.table2excel.js"></script>';
echo '<script type="text/javascript" src="../js/core.js"></script>'; //handles printing and closing of window
echo '<script src="https://www.kryogenix.org/code/browser/sorttable/sorttable.js"></script>

<style>    
a.export, a.export:visited {
    text-decoration: none;
    color:#000;
    background-color:#ddd;
    border: 1px solid #ccc;
    padding:8px;
}</style>';
//later set variable to session
$admin = '<u>' . @$_SESSION['username'] . '</u>'; ?>
<script>
    $(document).ready(function() {
        $('.treport').dataTable({
            "paging": true,
            "ordering": true,
            "info": false,
            "iDisplayLength": 5000 "aLengthMenu": [
                [5000, 10000, -1],
                [5000, 10000, "All"]
            ]
        });

        function exportTableToCSV($table, filename) {

            var $rows = $table.find('tr:has(td)'),

                // Temporary delimiter characters unlikely to be typed by keyboard
                // This is to avoid accidentally splitting the actual contents
                tmpColDelim = String.fromCharCode(11), // vertical tab character
                tmpRowDelim = String.fromCharCode(0), // null character

                // actual delimiter characters for CSV format
                colDelim = '","',
                rowDelim = '"\r\n"',

                // Grab text from table into CSV formatted string
                csv = '"' + $rows.map(function(i, row) {
                    var $row = $(row),
                        $cols = $row.find('td');

                    return $cols.map(function(j, col) {
                        var $col = $(col),
                            text = $col.text();

                        return text.replace('"', '""'); // escape double quotes

                    }).get().join(tmpColDelim);

                }).get().join(tmpRowDelim)
                .split(tmpRowDelim).join(rowDelim)
                .split(tmpColDelim).join(colDelim) + '"',

                // Data URI
                csvData = 'data:application/csv;charset=utf-8,' + encodeURIComponent(csv);

            $(this)
                .attr({
                    'download': filename,
                    'href': csvData,
                    'target': '_blank'
                });
        }

        // This must be a hyperlink
        $(".export").on('click', function(event) {
            // CSV
            exportTableToCSV.apply(this, [$('.dvData>table'), 'export.csv']);

            // IF CSV, don't do event.preventDefault() or return false
            // We actually need this to be a typical hyperlink
        });


        $("#export").on('click', function(event) {

            $(".exportlist").table2excel({
                //exclude: ".noExl",
                name: "Exported File",
                filename: "exportedList"
            });
        });



    });
</script>

<?php
echo '</head><body oncontextmenu="return false">';
echo '<div id="formreport">';
//for peta
$invoiceno = $invoiceno = @$_REQUEST['invoiceno'];
$username = @$_SESSION['username'];
$propid = $_SESSION['propertyid'];
//echo '<a href="../fpdf/tutorial/createpdf.php?document=invoice&invoiceno='.$invoiceno.'&propid='.@$propid.'&username='.@$username.'" id="email" class="email buttonblue" >Email</a>';  
echo '<a href="#" id="printnow" class="print" rel="reportsdiv" >Print</a>'
    . '<a href="#" class="addbutton" style="float:right !important" id="export"><img src="../images/excelicon.png"/></a>';
echo '<a href="#" id="closenow" class="close" >Close</a>';
echo '<div id="reportsdiv">';
$reportpost = $_REQUEST['report'];
if ($reportpost === 'tenantlist') {
    if ($_GET["search"]) {
        $options = array("tenant_name" => @$_GET["tenantname"], "houseno" => @$_GET["houseno"]);
        echo getalltenants("all", "tenant_name", "ASC", $_SESSION['username'], $options);
    } else {
        echo getalltenants($_REQUEST['propertyid'], $_REQUEST['id'], $_REQUEST['sort'], $_SESSION['username'], NULL);
    }
} elseif ($reportpost === 'propertylist') {
    echo getallproperties($reportpost, $_REQUEST['id'], $_REQUEST['sort'], $_SESSION['username']);
} elseif ($reportpost === 'printinvoice') {

    $invoiceno = $_REQUEST['invoiceno'];
    $propid = $_SESSION['propertyid'];
    $username = $_SESSION['username'];
    echo  printinvoice($invoiceno, $propid, $username);
} elseif ($reportpost === 'printhillsinvoice') {

    $invoiceno = $_REQUEST['invoiceno'];
    $propid = $_SESSION['propertyid'];
    $username = $_SESSION['username'];
    //echo '<a href="../fpdf/tutorial/createpdf.php?document=invoice&invoiceno='.$invoiceno.'&propid='.@$propid.'&username='.@$username.'" id="email" class="email buttonblue" >Email</a>'; 
    echo '<a href="../tcpdf/create_tcpdf.php?document=invoice&invoiceno=' . $invoiceno . '&propid=' . @$propid . '&username=' . @$username . '" id="email" class="email buttonblue" >Email</a>';

    echo  printhillsinvoice($invoiceno, $propid, $username);
} elseif ($reportpost === 'invoicelist') {
    $startdate = date("Y-m-d", strtotime($_REQUEST['startdate']));
    $enddate = date("Y-m-d", strtotime($_REQUEST['enddate']));
    $allpropertiesflag = $_REQUEST['allpropertiesflag'];
    echo  getinvoicelist($startdate, $enddate, $_REQUEST['accid'], $_REQUEST['accname'], $_REQUEST['propid'], $_SESSION['username'], $allpropertiesflag);
} elseif ($reportpost === 'landlordstatement') {
    $fdate =  DateTime::createFromFormat("d/m/Y", $_REQUEST['fromdate']);
    $todate =  DateTime::createFromFormat("d/m/Y", $_REQUEST['enddate']);

    $startdate = $fdate->format("Y-m-d");
    $enddate = $todate->format("Y-m-d");

    $propid = $_REQUEST['propid'];
    $total_invoices = invoiceAmount($propid, $startdate, $enddate);
    $watchmantotal = array();
    $paidamounts = array();
    $depositamounts = array();
    $rent = array();
    $invoice_amount = array();
    $commissionamounts = array();
    $chargeables =  getChargeItems($propid);
    $chargeablescount = count($chargeables);
    $landlordchargeitems = array('rent', 'watchman', 'security', 'vat', 'garbage'); //'water','deposit','rent_deposit'
    //$invoices=getinvoicelistChargeables($startdate,$enddate,$_REQUEST['accid'],$_REQUEST['accname'],$_REQUEST['propid'],$_SESSION['username']);

    $itemnames = array();

?>
    <div class="dvData">
        <?php
        echo '<table class="treport1 exportlist" style="width:90%" ><thead style="font-weight:bold;">
<tr><td colspan="24"><center><span style="font-size:14px;font-weight:bold"> ' . $settings['company_name'] . '</span><center><br>
    <span style="font-size:14px;font-weight:bold">ADDRESS: ' . $settings['address'] . '</span><center>
</td></tr>';
        echo '<tr><td colspan="24"><center><span style="font-size:13px;font-weight:normal;"> <b>LANDLORD STATEMENT -' . $accname . '</b></span><span style="font-size:18px;font-weight:normal; ">' . findpropertybyid($propid) . '</span><br/><span style="font-size:12px;font-weight:normal;float:right">' . str_repeat('&nbsp;', 25) . 'Statement From <b> ' . date('d-m-Y',  strtotime($startdate)) . '</b>  To  <b>' . date('d-m-Y',  strtotime($enddate)) . '</b></span><center></tr>';

        echo '<tr>
<u><td>S/no</td><td>House</td><td>Name</td><td>Rent/PM</td><td>Deposit Paid</td><td>RCP</td>';
        foreach ($chargeables as $value) {
            array_push($itemnames,  strtolower($value['accname']));
            echo '<td>' .  strtoupper($value['accname']) . '</td>';
        }
        //   <td>MGT FEE('.getPropertyCommissionRate($propid).' %)</td>
        echo '<td>Rent BBF</td><td>Total Due</td><td>RCT No</td><td>Total Paid</td><td>BCF</td>
   
    </u></tr></thead>';
        echo '<tbody><tr>';
        $count = 1;
        $floordetails =   floorplan($propid);
        foreach ($floordetails as $plan) {
            if ($plan['isoccupied'] == 0) {
                $vacantrent = $plan['monthlyincome'];
            } else {
                $vacantrent = "";
            }

            $tenantdetails = findtenantDetailsbyapt($plan['apt_id']);
            $depositsfortenant = getTenantDeposit($tenantdetails['Id'], $startdate, $enddate);
            foreach ($depositsfortenant as $deposit) {
                $amounts[] = $deposit['amount'];
                $dates[] = $deposit['rdate'];
                $recpnos[] = $deposit['recpno'];
                if ($deposit['amount'] > 0) {
                    array_push($depositamounts, $deposit['amount']);
                }
            }
            $rent_amount = $plan['monthlyincome'];
            if ($plan['isoccupied'] == 1) {
                array_push($rent, $plan['monthlyincome']);

                echo '<tr><td>' . $count . '</td><td>' . $plan['apt-tag'] . '</td><td>' . $plan['tenant_name'] . '</td><td>' . $plan['monthlyincome'] . '</td><td>' . implode(",", $dates) . '</td><td>' . @implode(",", $recpnos) . '</td>';
            } else {
                echo '<tr><td>' . $count . '</td><td>' . $plan['apt-tag'] . '</td><td>' . $plan['tenant_name'] . '</td><td>-</td><td>' . implode(",", $dates) . '</td><td>' . @implode(",", $recpnos) . '</td>';
            }
            $receipts =  getreceiptlistTenant($startdate, $enddate, $accid, $accname, $propid, $tenantdetails['Id']);
            //$invoice=  getinvoicelist($startdate, $enddate, $accid, $accname, $propid, $tenantdetails['Id']) ;//

            $tenantid = $tenantdetails['Id'];

            //if item in chargeables ==item in chargeitems

            $countitems = count($itemnames);

            for ($i = 0; $i < $countitems; $i++) {
                echo '<td id="' . $itemnames[$i] . '">';

                foreach ($receipts[0]['chargeables'] as $itemcharged) {
                    if (strtolower($itemcharged['name']) == strtolower($itemnames[$i])) {
                        echo $itemcharged['amount'];

                        if (strtoupper($itemcharged['name']) == "RENT") {

                            $rentbalance = $itemcharged['amount'];
                            //rent amount

                        }


                        if (strtolower($itemcharged['name']) == "watchman") {
                            $vat = 0; //$itemcharged['amount'];

                            array_push($watchmantotal, $itemcharged['amount']);
                        }
                        $stotal = $stotal + ($itemcharged['paidamount']);
                    }
                }
                $stotal = ($itemcharged['paidamount']);
            }

            //bbf
            $balanceminuslastrentinvoice = getCorrectBalance($tenantid) - $rentbalance;
            if ($balanceminuslastrentinvoice < 0) {
                $balanceminuslastrentinvoice = 0;
            }
            echo '</td><td>' . number_format($balanceminuslastrentinvoice, 2) . '</td>' .
                //amount due
                '<td>' . number_format((getCorrectBalance($tenantid)), 2) . '</td><td>';

            foreach ($receipts as $singlereceipt) {
                $receiptsdetails[] = getReceiptsFromInvoice($singlereceipt['invoiceno'], $enddate);
            }


            //  $recps=array_unique($receiptsdetails);
            $paidamount = 0;
            foreach ($receipts as $value) {

                echo $value['recpno'] . '#';
                $paidamount += $value['receiptpaidamount'];
            }


            echo ' </td>';
            //  foreach ($receiptsdetails as $value) {
            // echo $value[0]['rdate'].'#';
            //  }
            //rent+vat+chargeables paid
            echo '<td>';
            //   print_r($receipts[0]);
            //  foreach ($receipts as $receipt) {

            // $paidamount=$receipts[0]['chargeables'][0]['paidamount'];
            $balance = $receipts[0]['chargeables'][0]['amount'];
            //}
            //   echo $rent_amount;
            //check if paidamount>0 to calculate commission
            //  if($paidamount>=$balance){
            // $commissionamount= (getPropertyCommissionRate($propid)*$paidamount)/100;
            if ($plan['isoccupied'] == 1) {
                $commissionamount = (getPropertyCommissionRate($propid) * $rent_amount) / 100;
            }
            //  var_dump($commissionamount);

            /// echo ($commissionamount)."dd";
            //    echo ('d'.getPropertyCommissionRate($propid).'d');
            //   }
            //  else{
            //get percentage amount of rent in relation to total paid amount
            // $commissionamount=round //(($balance/$paidamount)*(getPropertyCommissionRate($propid)*$paidamount)/100,2);
            // }
            print_r($paidamount);
            array_push($paidamounts, $paidamount);
            echo '</td>';
            // '<td>'.number_format($receipts[0]['chargeables']['paidamount'] ,2).'</td>'.
            //BCF
            echo '<td>' . number_format(getCorrectBalance($tenantid), 2) . '</td>';
            //$commissionamount=  (getPropertyCommissionRate($propid)*$paidamount)/100;//+(getPropertyCommissionRate($propid)*array_sum($amounts)/100);
            array_push($commissionamounts, $commissionamount);
            // echo '<td>'.$commissionamount.'</td>';
            //extract item values
            //unset items for each row-for deposits

            unset($recpnos);
            unset($paidamount);
            unset($balance);
            unset($receiptsdetails);
            unset($dates);
            unset($amounts);
            unset($rentbalance);
            unset($commissionamount);
            //unset($receipts);

            $count++;
        }

        echo '</tbody>';
        echo '<tfoot>
<tr><td><b>TOTAL Rent Payable</b></td><td></td><td></td><td>' . array_sum($rent) . '</td>' .  str_repeat('<td></td>', 5);
        // echo '<tr><td>dd</td></tr>';
        // foreach ($chargeables as $value) {

        //     echo '<td></td>';
        // }
        $totalcollected = array_sum($rent); //array_sum($paidamounts);
        $total_chargables = $total_invoices - $totalcollected;
        //total commission
        $comm = array_sum($commissionamounts);
        // array_sum($watchmantotal)
        echo  '<td><b>' . number_format(array_sum($paidamounts), 2) . '</b></td></tr>';//<td></td><td><b>' .  number_format($comm, 2) . '</b></td></tr>';
        echo '<tr><td><b>Other Chargables</b></td><td></td><td></td><td><b>' . $total_chargables . '</b></td></tr>';
        
        $data=  json_decode(getPrepayment(338));
        $prep=0;
        $houses="";
        foreach($data as $dt){
           $prep+= $dt->monthlyincome;
          
           $houses.=$dt->aptid;
           if($houses!=""){
            $houses.=" and ";
           }
        }
        echo '<tr>
            
            <td><b>Prepayments</b></td><td><td></td><td><b>'.$prep.'</b></td><td colspan="3">'.$houses.'
            </tr>';
        echo '<tr><td><b>Total Amount</b></td><td></td><td></td><td><b>' . $total_invoices . '</b></td></tr>';
        echo '<tr><td><b>Loan </b></td><td></td><td></td><td><b>' . loanPaid($propid, $startdate, $enddate) . '</b></td></tr>';
        $totalcollected = $total_invoices;
        //spacing
        //echo '<tr><td><b>LESS WATCHMAN</b></td>'.str_repeat('<td></td>',11);
        // foreach ($chargeables as $value) {
        //      
        //        echo '<td></td>';        
        //    }
        //     $totalminuswatchman=$totalcollected-array_sum($watchmantotal);
        //echo  '<td>'.array_sum($watchmantotal).'</td><td><b>' . number_format($totalminuswatchman, 2) . '</b></td><td></td><td></td><td></td></tr>';
        //extract commission
        $commissiondetail = get_commissions_listProperty($propid, $startdate, $enddate);

        //expenses
        $expenses =  getPaymentsForProperty(array('propid' => $propid, 'startdate' => $startdate, 'enddate' => $enddate, 'count' => 1));
        $totalbill = array();
        echo '<tr><td><b>Less&nbsp;' . $commissiondetail[0]['commission'] . '%&nbsp; Commission</b></td>' ;;//. str_repeat('<td></td>', 10);
        // foreach ($chargeables as $count) {
        //     echo '<td></td>';
        // }
        $lesscommission = $totalminuswatchman - $comm;
        $vat = getVAT("housevat");
        $lessvat = 0; //  round(($vat*$comm)/100,2);
        echo '<td></td><td></td><td><b>' .  number_format($lesscommission, 2) . '</b></td></tr>';
        //   echo '<tr><td><td></td><td></td><td></td>'.str_repeat('<td></td>',7).'<td><b>'.number_format($lessvat,2).'</b></td><td></td><td></td><td></td></tr>'; 
        //extract expenses
        foreach ($expenses as $expense => $value) {
            array_push($totalbill, $value['billpaid']);
            echo '<tr><td><b>' . ucfirst($value['bill_items']) . '</b></td>' .  str_repeat("<td></td>", 10);
            foreach ($chargeables as $count) {
                echo '<td></td>';
            }
            echo '<td><b>' .  number_format(-$value['billpaid'], 2) . '</b></td></tr>';
        }

        //spacing
        // echo '<tr>' . str_repeat('<td></td>', 12);
        // foreach ($chargeables as $value) {

        //     echo '<td></td>';
        // }
        // echo  '</tr>';
        //amount banked

        $banked = $totalcollected - ($comm + array_sum($totalbill)+$prep + loanPaid($propid, $startdate, $enddate) + $lessvat);
        echo '<tr><td><b> Landlord Amount</b>';// . str_repeat('<td></td>', 9);
        // foreach ($chargeables as $value) {

        //     echo '<td></td>';
        // }
        echo  '<td></td><td></td><td><b>' . number_format($banked, 2) . '</b></td></tr>';

        $payments = getLandLordPaidAmountsForMonth($todate->format("Y-m"), $propid);
        $paidamounts = 0;
        foreach ($payments as $payment) {
            echo '<tr><td><b> Paid to Landlord B/Ac</b>'; //. str_repeat('<td></td>', 11);
            // foreach ($chargeables as $value) {

            //     echo '<td></td>';
            // }
            //add paid amounts to array
            $paidamounts = $paidamounts + $payment["amount"];
            echo  '<td></td><td></td><td><b>' . number_format($payment["amount"], 2) . '</b></td><td>Cheque No' . $payment["chequeno"] . '</td><td>Cheque Date' . $payment["chequedate"] . '</td></tr>';
        }
        echo '<tr><td><b>Balance as at end of' . $todate->format("m-Y") . ' </b>' ;//. str_repeat('<td></td>', 9);
        // foreach ($chargeables as $value) {

        //     echo '<td></td>';
        // }
        echo  '<td></td><td></td><td><b>' . number_format($banked - $paidamounts, 2) . '</b></td> </tr>';


        echo '</tfoot></table>';
        ?>
    </div>
    <?php echo '<hr/>';
    echo '<tr><i>Printed by:</i> ' . $user . '&nbsp;&nbsp;&nbsp;&nbsp;' . date("d-m-y") . '&nbsp;' . $time;
    ?>
    <br><br>
    <a href="#" class="export" style="float:right">Export Table data into Excel</a>
<?php
} elseif ($reportpost === 'fetchaccountstatement') {
    $fromdate = $_GET['fromdate'];
    $todate = $_GET['todate'];
    $acctid = $_GET['account'];
    $type = $_GET['acctype'];
    $amountsum = array();
    $statements =  getBankStatement(array('fromdate' => $fromdate, 'todate' => $todate, 'acctid' => $acctid, 'acctype' => $type));
    $accountdetails = findAccountById($acctid);
?>
    <table class="treport">
        <thead>
            <tr>
                <td><img src="../images/cursors/logo1.png" style="height:50px;width:70px;"></td>
                <td colspan="7" style="background-color:beige">
                    <h3>
                        <center>BANK STATEMENT FOR <i><?php echo $accountdetails[0]['acname']; ?></i> &nbsp;BETWEEN&nbsp;<?php echo date('F j,Y',  strtotime($fromdate)) . '&nbsp;AND &nbsp;' . date('F j,Y',  strtotime($todate)) ?></center>
                    </h3>
                </td>
            </tr>
            <tr>
                <th>
                    <center><u> No</u></center>
                </th>
                <th>
                    <center><u>Date</u></center>
                </th>
                <th>
                    <center><u>Recp/Pay No</u></center>
                </th>
                <th>
                    <center><u>Mode</u></center>
                </th>
                <th>
                    <center><u>Cheque No</u></center>
                </th>
                <th>
                    <center><u>Cheque Date</u></center>
                </th>

                <th>
                    <center><u>Amount</u></center>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php

            $count = 1;
            foreach ($statements as $statement) {
                array_push($amountsum, $statement['amount']);
                if (strtoupper($type) != 'E') {
                    $date = date("d-m-Y", strtotime($statement['rdate']));
                    $payrecp = $statement['recpno'];
                    $chequedate = $statement['chequedate'];
                    $chqno = $statement['chqno'];
                } else {
                    $date = date("d-m-Y", strtotime($statement['paydate']));
                    $payrecp = $statement['payno'];
                    $chequedate = $statement['chequedate'];
                    $chqno = $statement['chqno'];
                }
                //get the payment mode
                $paymode =  getPayMode($statement['pmode']);
                echo '<tr><td>' . $count . '</td><td>' . $date . '</td><td>&nbsp;' . $payrecp . '</td><td>&nbsp;&nbsp;' . $paymode[0]['paymode'] . '</td><td>&nbsp;&nbsp;' . $chequeno . '</td><td>&nbsp;&nbsp;' . $chequedate . '</td><td>&nbsp;' . number_format($statement['amount'], 2) . '</td></tr>';
                $count++;
            }
            echo '</tbody><tfoot><tr><td><b>GRAND TOTAL</b></td><td></td><td></td><td></td><td></td><td></td><td><b>' . number_format(array_sum($amountsum), 2) . '</b></td></tr></tfoot>';
            ?>
    </table>

<?php
} elseif ($reportpost === 'printreceipt') {

    $receiptno = $_REQUEST['receiptno'];
    $username = $_SESSION['username'];
    //echo '<a  href="../fpdf/tutorial/createpdf.php?document=receipt&recpno='.$receiptno.'&propid='.$propid.'&username='.$username.'" id="email" class="email buttonblue" >Email</a>';  
    echo '<a  href="../tcpdf/create_tcpdf.php?document=receipt&recpno=' . $receiptno . '&propid=' . $propid . '&username=' . $username . '" id="email" class="email buttonblue" >Email</a>';
    echo  printreceipt($receiptno, $username);
} elseif ($_REQUEST['report'] === 'expiredleases') {
    $expiredleases = getExpiredLeases();
?>

    <table class="treport">
        <thead>

            <tr>
                <td><b>#</b></td>
                <td><b>Tenant</b></td>
                <td><b>Tenant Phone</b></td>
                <td><b>Tenant Email</b></td>
                <td><b>Building</b></td>
                <td><b>Apartment/Suite</b></td>
                <td><b>Lease From</b></td>
                <td><b>Lease To</b></td>
                <td><b>Lease Document</b></td>
            </tr>
        </thead>
        <tbody>
            <?php
            $count = 1;
            foreach ($expiredleases as $value) {
                echo '<tr><td>' . $count . '</td><td>' . $value["tenant_name"] . '</td><td>' . $value["tenantphone"] . '</td><td>' . $value["tenantemail"] . '</td><td>' . $value["property_name"] . '</td><td>' . $value["Apartment_tag"] . '</td><td>' . $value["fromdate"] . '</td><td>' . $value["todate"] . '</td><td>' . $value["leasedoc"] . '</td></tr>';


                $count++;
            }


            ?>




        </tbody>

    </table>
<?php

} elseif ($_REQUEST['report'] === 'printreceiptcustomer') {
    $receiptno = $_REQUEST['receiptno'];
    $username = $_SESSION['username'];
    echo '<a class="displaynone" href="../fpdf/tutorial/createpdf.php?document=receipt&recpno=' . $receiptno . '&propid=' . $propid . '&username=' . $username . '" id="email" class="email buttonblue" >Email</a>';
    echo printreceiptother($receiptno, $username);
} elseif ($_REQUEST['report'] === 'receiptlist') {
    $startdate = date("Y-m-d", strtotime($_REQUEST['startdate']));
    $enddate = date("Y-m-d", strtotime($_REQUEST['enddate']));
    $allpropertiesflag = $_REQUEST['allpropertiesflag'];
    if ($_REQUEST['receiptype'] == "rentreceipts") {
        $tenant = @$_REQUEST["tenant"];
        echo  getreceiptlist($startdate, $enddate, $_REQUEST['propid'], $_SESSION['username'], $allpropertiesflag, $tenant);
    } else if ($_REQUEST['receiptype'] == "otherreceipts") {
        echo  getreceiptlistother($startdate, $enddate, $_REQUEST['propid'], $_SESSION['username'], $allpropertiesflag);
    }
} elseif ($_REQUEST['report'] === 'tenantdeposits') {
    $startdate = date("Y-m-d", strtotime($_REQUEST['startdate']));
    $myDateTime1 = DateTime::createFromFormat('d/m/Y', trim($_REQUEST['startdate']));
    $startdate = $myDateTime1->format('d-m-Y');
    // $enddate=date("Y-m-d", strtotime($_REQUEST['enddate']));
    $myDateTime = DateTime::createFromFormat('d/m/Y', trim($_REQUEST['enddate']));
    $enddate = $myDateTime->format('d-m-Y');
    $allpropertiesflag = $_REQUEST['allpropertiesflag'];
    echo getTenantDeposits($startdate, $enddate, $_REQUEST['propid'], $_SESSION['username'], $allpropertiesflag);
} elseif ($reportpost === 'depositrefundlist') {
    $startdate = date("Y-m-d", strtotime($_REQUEST['fromdate']));
    $enddate = date("Y-m-d", strtotime($_REQUEST['enddate']));
    $allpropertiesflag = $_REQUEST['allpropertiesflag'];
    $propid = $_REQUEST['propid'];
    $deposits = getDepositRefundList($startdate, $enddate, $propid);
?>
    <table class="treport">
        <thead>
            <tr>
                <td><img src="../images/cursors/logo1.png" style="height:50px;width:70px;"></td>
                <td colspan="9" style="background-color:beige">
                    <h3>
                        <center>DEPOSIT REFUND STATEMENT <i><?php echo $accountdetails[0]['acname']; ?></i> &nbsp;BETWEEN&nbsp;<?php echo date('F j,Y',  strtotime($startdate)) . '&nbsp;AND &nbsp;' . date('F j,Y',  strtotime($enddate)) ?></center>
                    </h3>
                </td>
            </tr>
            <tr>
                <th>
                    <center><u> No</u></center>
                </th>
                <th>
                    <center><u>Date</u></center>
                </th>
                <th>
                    <center><u>Pay No</u></center>
                </th>
                <th>
                    <center><u>Mode</u></center>
                </th>
                <th>
                    <center><u>Cheque No</u></center>
                </th>
                <th>
                    <center><u>Cheque Date</u></center>
                </th>
                <th>
                    <center><u>Paymode</u></center>
                </th>
                <th>Tenant</th>
                <th>
                    <center><u>Amount</u></center>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php

            $count = 0;
            foreach ($deposits as $deposit) {
                $tenantdetails =  getTenantDetails($deposit['supp_id']);
            ?>

                <tr>
                    <td><?php echo $count; ?></td>
                    <td><?php echo date("d-m-Y",  strtotime($deposit['paydate'])); ?></td>
                    <td><?php echo $deposit['payno']; ?></td>
                    <td><?php echo $deposit['pmode']; ?></td>
                    <td><?php echo $deposit['chqno']; ?></td>
                    <td><?php echo $deposit['chequedate']; ?></td>
                    <td><?php echo $deposit['pmode']; ?></td>
                    <td><?php echo $tenantdetails['name']; ?></td>
                    <td><?php echo $deposit['amount']; ?></td>
                </tr>

            <?php
                $count++;
            }

            ?>
        </tbody>
    </table>

<?php

} elseif ($reportpost === 'dailycash') {
    $startdate = date("Y-m-d", strtotime($_REQUEST['date']));

    echo getDailyCash($startdate);
} elseif ($reportpost == 'fetchstatement') {

    $tenantid = htmlspecialchars($_REQUEST['clientid']);
    $propid = htmlspecialchars($_REQUEST['propid']);
    $startdate = htmlspecialchars($_REQUEST['startdate']);
    $enddate = htmlspecialchars($_REQUEST['enddate']);
    $count = htmlspecialchars($_REQUEST['count']);
    $allpropertiesflag = htmlspecialchars($_REQUEST['allpropertiesflag']);
    if ($count == '3') {

        echo fetchstatement3($tenantid, $propid, $startdate, $enddate, $count, $allpropertiesflag = 'F');
    } else if ($count == '0') {

        echo fetchstatement2($tenantid, $propid, $startdate, $enddate, $count, $allpropertiesflag = 'F');
    } else {
        //die("i see");
        echo fetchstatement3($tenantid, $propid, $startdate, $enddate, $count, $allpropertiesflag = 'F');
    }
} elseif ($reportpost == 'fetchstatement2') {

    $tenantid = htmlspecialchars($_REQUEST['clientid']);
    $propid = htmlspecialchars($_REQUEST['propid']);
    $startdate = htmlspecialchars($_REQUEST['startdate']);
    $enddate = htmlspecialchars($_REQUEST['enddate']);
    $count = htmlspecialchars($_REQUEST['count']);
    $allpropertiesflag = htmlspecialchars($_REQUEST['allpropertiesflag']);

    echo fetchstatement2($tenantid, $propid, $startdate, $enddate, $count, $allpropertiesflag = 'F');
} elseif ($reportpost == 'agentstatement') {
    $startdate = htmlspecialchars($_REQUEST['fromdate']);
    $enddate = htmlspecialchars($_REQUEST['enddate']);
    $propid = htmlspecialchars($_REQUEST['propid']);

    echo getAgentStatement($startdate, $enddate, $propid, $_SESSION['username']);
?>
    <br><br>
    <a href="#" class="export">Export Table data into Excel</a>
<?php } elseif ($reportpost == 'fetchbypercentage') {

    $propid = htmlspecialchars($_REQUEST['propid']);
    $fromdate = htmlspecialchars($_REQUEST['fromdate']);
    $enddate = htmlspecialchars($_REQUEST['enddate']);
    //die($enddate);
    // die($enddate);
    $flag = htmlspecialchars($_REQUEST['flag']);
    $percentage = htmlspecialchars($_REQUEST['percentage']);
    $percentageto = htmlspecialchars($_REQUEST['percentageto']);

    if ($flag == "one") {
        echo fetchplotperformancebypercentageOne($propid, $fromdate, $enddate, $percentage, $percentageto);
    } else {

        echo fetchplotperformancebypercentageall($propid, $fromdate, $enddate, $flag, $percentage, $percentageto);
    }
} elseif ($reportpost == 'fetchplotperformance') {
    // die("hhe");
    $propid = htmlspecialchars($_REQUEST['propid']);
    $fromdate = htmlspecialchars($_REQUEST['fromdate']);
    $enddate = htmlspecialchars($_REQUEST['enddate']);
    //die($enddate);
    // die($enddate);
    $flag = htmlspecialchars($_REQUEST['flag']);
    if ($flag == "one") {
        //  die("dd");
        echo fetchplotperformanceOne($propid, $fromdate, $enddate);
    } else if ($flag == "two") {
        echo fetchplotperformanceAgent($propid, $fromdate, $enddate);
    } else{

        echo fetchplotperformanceAll($propid, $fromdate, $enddate, $flag);
    } 
    
} elseif ($reportpost == 'fetcharrearsprepayments') {

    $tenantid = htmlspecialchars($_REQUEST['clientid']);
    $propid = htmlspecialchars($_REQUEST['propid']);
    $fromdate = htmlspecialchars($_REQUEST['fromdate']);
    $enddate = htmlspecialchars($_REQUEST['enddate']);
    //die($enddate);
    $count = htmlspecialchars($_REQUEST['count']);
    $flag = htmlspecialchars($_REQUEST['flag']);

    echo fetcharrearsprepayment($tenantid, $propid, $fromdate, $enddate, $count, $flag);
}
//penalties
elseif ($reportpost == 'penalties') {

    $tenantid = htmlspecialchars($_REQUEST['clientid']);
    $propid = htmlspecialchars($_REQUEST['propid']);
    $accountsopening = getAccountsOpeningDate();
    $startdate = date("d-m-Y", strtotime($accountsopening));
    $enddate = htmlspecialchars($_REQUEST['enddate']);
    $count = htmlspecialchars($_REQUEST['count']);
    if ($count == 2) {
        $unpaidpenalties = fetchUnPaidPenalties($enddate, $count);
        $allinvoicedetails = array();
        $suminvoiceamount = array();
        $sumpaidamount = array();
        $sumbal = array();
        echo '<table class="treport1" style="width:800px"><thead>
<tr><td colspan="9" style="background-color:beige"><h3><center>PENALTY REPORT FOR ALL PROPERTIES &nbsp;FOR THE PERIOD&nbsp;' . $startdate . '&nbsp;TO&nbsp;' . $enddate . '</center></h3></td></tr>
<tr>
<th><center><u>Property</u></center></th>
<th><center><u>Invoice No</u></center></th>
<th><center><u>Date</u></center></th>
<th><center><u>Customer/Tenant Name</u></center></th>
<th><center><u>House</u></center></th>
<th><center><u>Narration</u></center></th>
<th><center><u>Credit</center></u></th>
<th><center><u>Debit</center></u></th>
<th><center><u>Penalty</center></u></th></tr></thead><tbody>';

        foreach ($unpaidpenalties as $invoicedetail) {
            $paidamount = $invoicedetail['paidamount'];
            $bal = $invoicedetail['credit'] - $paidamount;
            $tenantdetails =  getTenantDetails($invoicedetail['idno']);
            $invoicedetail['name'] = $tenantdetails['name'];
            $aptid = getApartmentFromTenant($invoicedetail['idno']);
            $aptdetails = getApartmentDetails($aptid);
            $invoicedetail['aptname'] = $aptdetails[0]['apt_tag'];
            $invoicedetail['remarks'] = $invoicedetail['narration'];

            //arrears

            array_push($suminvoiceamount, $invoicedetail['credit']);
            array_push($sumpaidamount, $paidamount);
            array_push($sumbal, $bal);
            echo '<tr><td>' . findpropertybyid($invoicedetail['property_id']) . '</td><td>' . $invoicedetail['invoiceno'] . '</td><td>' . date('d-m-Y',  strtotime($invoicedetail['invoicedate'])) . '</td><td>' . $invoicedetail['name'] . '</td><td>' . $invoicedetail['aptname'] . '</td><td>&nbsp;&nbsp;&nbsp;&nbsp;' . $invoicedetail['remarks'] . '</td><td style="color:red">&nbsp;&nbsp;' . number_format($invoicedetail['credit']) . '</td><td style="color:green">&nbsp;&nbsp;' . number_format($paidamount) . '</td><td>&nbsp;&nbsp;' . $bal . '</td></tr>';
        }
        echo '</tbody>';

        echo '<tfoot><tr><td><b>GRAND TOTAL</b></td><td></td><td></td><td></td><td></td><td></td><td><b>' . number_format(@array_sum($suminvoiceamount), 2) . '</b></td><td><b>' . number_format(@array_sum($sumpaidamount), 2) . '</b></td><td><b>' . number_format(@array_sum($sumbal), 2) . '</b></td></tr></tfoot>';
        echo '</table><br><br>';
    } else {
        echo fetchpenalty($tenantid, $propid, $enddate, $count);
    }
} elseif ($reportpost == 'incomestatement') {

    $propid = htmlspecialchars($_REQUEST['propid']);
    $startdate = htmlspecialchars($_REQUEST['startdate']);
    $enddate = htmlspecialchars($_REQUEST['enddate']);

    echo incomestatement($propid, $startdate, $enddate);
} elseif ($reportpost == 'unithistory') {

    $propertyid = htmlspecialchars($_REQUEST['propertyid']);
    $aptid = htmlspecialchars($_REQUEST['aptid']);
    $sort = htmlspecialchars($_REQUEST['sort']);

    echo getunithistory($propertyid, $aptid, $sort);
} elseif ($reportpost == 'printvoucher') {

    $payno = htmlspecialchars($_REQUEST['voucherno']);
    $propid = htmlspecialchars($_REQUEST['propid']);
    $user = htmlspecialchars($_REQUEST['user']);

    echo printvoucher($payno, $propid, $user);
} elseif ($reportpost == 'printlandlordvoucher') {

    $payno = htmlspecialchars($_REQUEST['voucherno']);
    $propid = htmlspecialchars($_REQUEST['propid']);
    $user = htmlspecialchars($_REQUEST['user']);

    echo printlandlordvoucher($payno, $propid, $user);
} elseif ($reportpost == 'printdepositvoucher') {

    $payno = htmlspecialchars($_REQUEST['voucherno']);
    $propid = htmlspecialchars($_REQUEST['propid']);
    $tenant = htmlspecialchars($_REQUEST['tenant']);
    $user = htmlspecialchars($_REQUEST['user']);

    echo printdepositvoucher($payno, $propid, $user, $tenant);
} elseif ($reportpost == 'paymentslist') {
    $startdate = htmlspecialchars($_REQUEST['startdate']);
    $enddate = htmlspecialchars($_REQUEST['enddate']);
    $officeexpense = htmlspecialchars($_REQUEST['officeexpenseflag']);
    $filterexpense = htmlspecialchars($_REQUEST['filterexpense']);

    $propid = htmlspecialchars($_REQUEST['propid']);
    $user = htmlspecialchars($_REQUEST['user']);

    echo paymentslistsupplier($startdate, $enddate, $propid, $user, $officeexpense, $filterexpense);
} elseif ($reportpost == 'paymentslistcloseperiod') {
    $closeperiod =  getClosePeriod($_REQUEST['closeperiod']);
    $startdate = htmlspecialchars($closeperiod['start_date']);
    $enddate = htmlspecialchars($closeperiod['end_date']);
    $officeexpense = htmlspecialchars($_REQUEST['officeexpenseflag']);
    $filterexpense = htmlspecialchars($_REQUEST['filterexpense']);
    $propid = htmlspecialchars($_REQUEST['propid']);
    $user = htmlspecialchars($_SESSION['username']);

    echo paymentslistsupplier($startdate, $enddate, $propid, $user, $suppid = '', $officeexpense, $filterexpense);
}
//supplierstatement
elseif ($reportpost == 'supplieracct') {
    $startdate = htmlspecialchars($_REQUEST['startdate']);
    $enddate = htmlspecialchars($_REQUEST['enddate']);
    $suppid = htmlspecialchars($_REQUEST['supp_id']);
    $propid = htmlspecialchars($_REQUEST['propid']);
    $user = htmlspecialchars($_REQUEST['user']);

    $suppacct = getSupplierAcctStatement(array('startdate' => $startdate, 'enddate' => $enddate, 'property_id' => $propid, 'supp_id' => $suppid));
    //print_r($suppacct);
}

//commissions
elseif ($reportpost == 'commissionlist') {
    $startdate = htmlspecialchars($_REQUEST['startdate']);
    $enddate = htmlspecialchars($_REQUEST['enddate']);
    $commissionsum = array();
    $renttotal = array();
?>
    <table class="treport">
        <thead>
            <tr>
                <td><img src="../images/cursors/logo1.png" style="height:50px;width:70px;"></td>
                <td colspan="4" style="background-color:beige">
                    <h3>
                        <center>COMMISSIONS LIST &nbsp;BETWEEN&nbsp;<?php echo date('F j,Y',  strtotime($startdate)) . '&nbsp;AND &nbsp;' . date('F j,Y',  strtotime($enddate)) ?></center>
                    </h3>
                </td>
            </tr>
            <tr>
                <th>
                    <center><u> No</u></center>
                </th>
                <th>
                    <center><u>Property</u></center>
                </th>
                <th>
                    <center><u>Total Rent</u></center>
                </th>
                <th>
                    <center><u>Commission</u></center>
                </th>
                <th>
                    <center><u>Commission Amount</u></center>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
            $commissiondetails = get_commissions_list($startdate, $enddate);
            $count = 1;
            foreach ($commissiondetails as $value) {
                $totalcommission = (($value['commission'] * $value['rentsum']) / 100);
                array_push($commissionsum, $totalcommission);
                array_push($renttotal, $value['rentsum']);
                echo '<tr><td>' . $count . '</td><td>' . $value['propertyname'] . '</td><td>&nbsp;' . number_format($value['rentsum'], 2) . '</td><td style="color:red">&nbsp;' . $value['commission'] . '</td><td style="color:green">&nbsp;&nbsp;' . number_format($totalcommission, 2) . '</td></tr>';
                $count++;
            }
            echo '</tbody><tfoot><tr><td><b>GRAND TOTAL</b></td><td></td><td><b>' . number_format(@array_sum($renttotal), 2) . '</b></td><td></td><td><b>' . number_format(@array_sum($commissionsum), 2) . '</b></td></tr></tfoot>';
            ?>
    </table>
<?php
} elseif ($reportpost == 'vacancy') {

?>

    <table class="treport exportlist">
        <thead>
            <tr>
                <td><img src="../images/cursors/logo1.png" style="height:50px;width:70px;"></td>
                <td colspan="7" style="background-color:beige">
                    <h3>
                        <center>VACANCY REPORT</center>
                    </h3>
                </td>
            </tr>
            <tr>
                <th>
                    <center><u> No</u></center>
                </th>
                <th>
                    <center><u>Property Name</u></center>
                </th>
                <th>
                    <center><u>Vacancies</u></center>
                </th>


            </tr>
        </thead>
        <tbody>
            <?php
            $agentid = get_agent_id_from_username(trim(htmlspecialchars($_SESSION['username'])));
            $allproperties =  getallagentProperties($agentid);
            $count = 1;
            foreach ($allproperties as $property) {
                $vacants =  vacant_apartments_list($property["property_id"]);
                // foreach ($property as $value) { 
            ?>
                <tr>
                    <td><?= $count ?></td>
                    <td><?= $property["property_name"] ?></td>
                    <td><?php foreach ($vacants as $value) {
                            echo $value["apt_tag"] . "|&nbsp;Rent PM:" . $value["monthlyincome"] . "<br>";
                        } ?></td>
                </tr>



                <?php
                // }

                $count++;
                ?>


            <?php }


            ?>



        </tbody>

    </table>

<?php } elseif ($reportpost == 'accounttransactions') {
    $startdate = htmlspecialchars($_REQUEST['fromdate']);
    $enddate = htmlspecialchars($_REQUEST['todate']);
    $account = htmlspecialchars($_REQUEST['account']);
    $startdate = date("Y-m-d",  strtotime($startdate));
    $enddate = date("Y-m-d",  strtotime($enddate));

    $transactions = getBankTransactions($startdate, $enddate, $account);
?>
    <table class="treport exportlist">
        <thead>
            <tr>
                <td><img src="../images/cursors/logo1.png" style="height:50px;width:70px;"></td>
                <td colspan="7" style="background-color:beige">
                    <h3>
                        <center>INTERNAL BANK TRANSACTIONS &nbsp;BETWEEN&nbsp;<?php echo date('F j,Y',  strtotime($startdate)) . '&nbsp;AND &nbsp;' . date('F j,Y',  strtotime($enddate)) ?></center>
                    </h3>
                </td>
            </tr>
            <tr>
                <th>
                    <center><u> No</u></center>
                </th>
                <th>
                    <center><u>Recp/PayNo</u></center>
                </th>
                <th>
                    <center><u>Account</u></center>
                </th>

                <th>
                    <center><u>Amount</u></center>
                </th>
                <th>
                    <center><u>Date/Time</u></center>
                </th>
                <th>
                    <center><u>Credit/Debit</u></center>
                </th>
                <th>
                    <center><u>Narration</u></center>
                </th>
                <th>
                    <center><u>Transacted By</u></center>
                </th>
            </tr>
        </thead>
        <tbody>
            <?php
            $count = 1;
            $totalcredits = 0;
            $totaldebits = 0;
            foreach ($transactions as $transaction) {
                if ($transaction["is_debit"]) {
                    $creditdebit = "D";
                    $totaldebits = $totaldebits + $transaction["amount"];
                    $link = '<a href="defaultreports.php?report=printvoucher&voucherno=' . $transaction['recpno'] . '&propid=' . $propid . '&user=' . $_SESSION['username'] . '" target="blank">' . $transaction['recpno'] . '</a>';
                } elseif ($transaction["is_credit"]) {
                    $creditdebit = "C";
                    $totalcredits = $totalcredits + $transaction["amount"];
                    $link = '<a class="whitetext"  href="defaultreports.php?report=printreceipt&receiptno=' . $transaction['recpno'] . '" target="blank"><span style="color:blue">' . $transaction['recpno'] . '</span></a>';
                }
                echo '<tr><td>' . $count . '</td><td>' . $link . '</td><td>' . $transaction["bank_type"] . '</td><td>' . number_format($transaction["amount"], 2) . '</td><td>' . $transaction["date"] . '</td><td>' . $creditdebit . '</td><td>' . $transaction["narration"] . '</td><td>' . $transaction["user"] . '</td></tr>';

                $count++;
            }
            ?>

        </tbody>
        <tfoot>
            <tr>
                <td>ZTOTAL</td>
                <td></td>
                <td></td>
                <td><?= number_format($totalcredits + $totaldebits) ?></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>

        </tfoot>
    </table>
    <br><br>
    <a href="#" class="export">Export Table data into Excel</a>
<?php } else {
    die('<center><h2>Relevant report not found!</h2></center>');
}
echo '</div>';
echo '</body>';
?>