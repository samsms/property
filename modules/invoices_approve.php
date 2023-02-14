<?php
@session_start();
/*a list of all tenants available/adding and deleting them
 */
include_once 'display.php';
@include_once '../modules/functions.php';
@date_default_timezone_set("Africa/Nairobi");
$settings =  getSettings();
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <title>Property Manager| Rivercourt Property Management</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="stylesheet" type="text/css" media="screen" href="/css/overall2.css">
    <link rel="stylesheet" type="text/css" media="print" href="/css/overall2.css">
    <link rel="stylesheet" type="text/css" media="print" href="/css/bootstrap.css">
    <script type="text/javascript" src="../js/jquery-1.9.1.js"></script>
    <script type="text/javascript" src="../js/jquery-ui.min.js"></script>
    <script type="text/javascript" src="../js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="../js/jquery.PrintArea.js"></script>
    <script type="text/javascript" src="../js/jquery.table2excel.js"></script>
    <script type="text/javascript" src="../js/core.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <style>
        a.export,
        a.export:visited {
            text-decoration: none;
            color: #000;
            background-color: #ddd;
            border: 1px solid #ccc;
            padding: 8px;
        }
    </style>
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
    <script>
        function view_invoice(id) {
          //  alert("hee");
            $.get("accountsprocess.php?id="+id+"+&getInvpoiceDetails=true").done(function (data){
                var data = JSON.parse(data);
                var content='';
                content += `<div class="row p-2"><div class="col">
<div class="row"> <label>Name</label><input type="text"
                            name="text"
                            disabled
                            class="form-control"
                            value="` + data.tenant.tenant_name + `"/>`;

                // content += `<label>Phone</label><div class="row m-1"><input type="text"
                //             name="text"
                //              disabled
                //             class="form-control"
                //             value="` + data.tenant.tenantphone + `"/></div>`;
                content += `<label>Propety</label><input type="readonly"
                            name="text"
                             disabled
                            class="form-control"
                            value="` + data.tenant.property_name + `"/>`;
                content += `<label>Invoice Date</label><input type="readonly"
                            name="text"
                             disabled
                            class="form-control"
                            value="` + data.details.invoicedate + `"/>`;
                content += `<label>Amount</label><input type="readonly"
                            name="text"
                             disabled
                            class="form-control"
                            value="` + data.details.amount + `"/>`
                content += `<label>Amount</label><input type="readonly"
                            name="text"
                            disabled
                            class="form-control"
                            value="` + data.details.amount + `"/></div></div>`
                            var chargenames = JSON.parse( data.details.chargenames);
                            var chargeamounts= JSON.parse( data.details.chargeamounts);

                content +="<div class='col'><div class='row'>";
                for(var i=0;i<chargenames.length;i++){
                    content += `<div class="col-sm m-2"> <div class="row"><label>`+chargenames[i]+`</label></div><div class="row"><label>
                           `+ chargeamounts[i] + `</label></div></div>`
                }
                content +="</div>";
                // chargenames.forEach(function (data){
                //     alert(data)
                // })
                $("#content").html(content);
                $("#approveInvice").click(function () {
                   if(confirm("Are you sure you want to approve?")){
                       approve_invice(id)
                   }

                })
            })
//                 .fail(function(failed) {
//                     alert("failed");
//                 })
        }
    </script>
    <script>
        function approve_invice($data) {
            //alert($data);
            $.ajax({
                type: "POST",
                //contentType: "application/json; charset=utf-8",
                url: "../modules/accountsprocess.php?approve_invoice=true&id=" + $data,
                data: "{}",
                //dataType: "json"
            }).done(function(data) {
                location.reload();
                if (data !== "failed") {
                    alert("approved successfully")
                    location.reload();
                } else {
                    alert("Failed to approve");
                    location.reload();
                }
            }).fail(function(e) {

                alert("approved successfully")
                location.reload();
            });
            return false;
        }
    </script>
</head>

<body oncontextmenu="return true">
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Invoice Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="content"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">disapprove</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal" id="approveInvice">Approve</button>
                </div>
            </div>
        </div>
    </div>
    <div id="formreport">
        <a href="#" id="printnow" class="print" rel="reportsdiv">Print</a>
        <a href="#" class="addbutton" style="float:right !important" id="export">

        </a><a href="../home.php">Close</a>
        <div id="reportsdiv"><u>

            </u>
            <table class="treport1 width70">
                <thead>
                    <tr>
                        <td colspan="14">
                            <center><span style="font-size:15px;font-weight:normal;float:left;">
                                    <b>RECEIPT LIST</b>
                                </span>
                                <span style="font-size:18px;font-weight:bold">Rivercourt Property Management</span>
                                <span style="font-size:18px;font-weight:normal; float:right;"></span>
                                <center>
                                    <br>

                                    <span style="font-size:16px;font-weight:normal;">
                                        <centreceiptlier>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Pending Receipts</b></centreceiptlier>
                                    </span>
                                </center>
                            </center>
                        </td>
                    </tr>
                    <tr>
                        <th>Tenant Id</th>
                        <th>Name</th>
                        <th>Property</th>
                        <th>Date</th>
                        <th>Paymode</th>
                        <th>Remarks</th>
                        <th>Approve</th>
                        <th>
                            Cancel </th>
                </thead>
                <tbody>
                    <?php
                    $data = getTempInvoices();
                    if ($data->count > 0) {
                       // var_dump($data->data);
                        foreach ($data->data as $entry) {

                            $entry_id = $entry->id;
                            $entry = (json_decode($entry->invoice));
                            //var_dump($entry);
                            $tenant = getTenantDetailsFromRow($entry->idno);
                            //var_dump($tenant);
                    ?>

                            <tr>

                                <td><?php echo ($tenant['Id']); ?></td>
                                <td><?php echo ($tenant['tenant_name']); ?></td>
                                <td><?php echo $tenant['property_name']; ?></td>
                                <td><?php echo date($entry->invoicedate); ?></td>
                                <td><?php echo (getPayMode($entry->paymode)[0]['paymode']); ?></td>
                                <td><?php echo ($entry->remarks); ?></td>
                                <td class="m-1"> <a href= "javasript:void(0)" onclick="view_invoice('<?php echo($entry_id)
                                    ?>')"  data-toggle="modal" data-target="#exampleModal">
                                        View       <?php echo($entry_id)
                                        ?>
                                    </a>
                                    <!-- <a href="javascript:void(0)" onclick="alert('<? php // view_invoice($entry_id) 
                                                                                        ?>')">View More</a> -->
                                </td>
                                <td></td>
                            </tr>
                    <?php
                        }
                    }
                    ?>
                </tbody>
            </table>


</body>

</html>