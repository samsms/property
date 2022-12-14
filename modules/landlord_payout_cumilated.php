<?php
@session_start();
/*a list of all tenants available/adding and deleting them
 */
include_once 'display.php';
// @include 'includes/database.php';
include  'functions.php';
include "landlordpay.php";
@date_default_timezone_set("Africa/Nairobi");
$settings=  getSettings();
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"><head><title>Property Manager| Rivercourt Property Management</title><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><link rel="stylesheet" type="text/css" media="screen" href="/css/overall2.css"><link rel="stylesheet" type="text/css" media="print" href="/css/overall2.css"><link rel="stylesheet" type="text/css" media="print" href="/css/bootstrap.css"><script type="text/javascript" src="../js/jquery-1.9.1.js"></script><script type="text/javascript" src="../js/jquery-ui.min.js"></script><script type="text/javascript" src="../js/jquery.dataTables.min.js"></script><script type="text/javascript" src="../js/jquery.PrintArea.js"></script><script type="text/javascript" src="../js/jquery.table2excel.js"></script><script type="text/javascript" src="../js/core.js"></script>
<style>    
a.export, a.export:visited {
    text-decoration: none;
    color:#000;
    background-color:#ddd;
    border: 1px solid #ccc;
    padding:8px;
}</style><script>
$(document).ready(function() {
    $('.treport').dataTable( {
        "paging":   true,
        "ordering": true,
        "info":false,
        "iDisplayLength":5000
    "aLengthMenu": [[5000, 10000, -1], [5000, 10000, "All"]]
    } );
    
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
            csv = '"' + $rows.map(function (i, row) {
                var $row = $(row),
                    $cols = $row.find('td');

                return $cols.map(function (j, col) {
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
    $(".export").on('click', function (event) {
        // CSV
        exportTableToCSV.apply(this, [$('.dvData>table'), 'export.csv']);

        // IF CSV, don't do event.preventDefault() or return false
        // We actually need this to be a typical hyperlink
    });


    $("#export").on('click', function (event) {

$(".exportlist").table2excel({
					//exclude: ".noExl",
					name: "Exported File",
					filename: "exportedList"
				});
    });  
   
    
    
} );
</script>

</head>
<body oncontextmenu="return false">

<div style="float: right;" class="navbtns">
<a href="#" id="printnow" class="print" rel="reportsdiv">Print</a>
    <a href="#" class="addbutton" style="float:right !important" id="export">

</a><a  href="../home.php"  >Close</a>
</div>
<div id="formreport">
 
<div id="reportsdiv"><u>

</u>
<fieldset style="width: 900px;margin:auto; padding:auto;border:#ffcc99 2px solid"class="myTable"><legend id="myTable"><b><h1>PENDING PREPAYMENTS</h1></b></legend>
<table class="treport1 width70" style=" width: 80%; margin: 0; float: left;">
    <thead>
<tr>
    <td colspan="14">
        <center><span style="font-size:15px;font-weight:normal;float:left;">
        </span>
        <span style="font-size:18px;font-weight:bold">Rivercourt Property Management</span>
        <span style="font-size:18px;font-weight:normal; float:right;"></span>
        <center>
<br>

<span style="font-size:16px;font-weight:normal;">
<centreceiptlier>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Payment List</b></centreceiptlier></span></center></center></td></tr>
<tr><th>Property Name</th><th>Amount</th><th>Pay date</th> </thead><tbody>
<?php 

$data=json_decode(payout_list_cumilated());

foreach ($data as $dt){
  // echo(  die(print_r($dt)));
?>
<tr>
        <td style=" padding-left:240px;padding-right:0px;"><?php echo($dt->property_name); ?></td>
        
        <td><?php echo $dt->amount; ?></td>
        <td><?php echo $dt->pay_day; ?></td>
    </tr>
<?php
}
?>
    </tbody>
</table>
   </body></html>