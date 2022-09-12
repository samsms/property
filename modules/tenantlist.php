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
<div id="formreport">
    <a href="#" id="printnow" class="print" rel="reportsdiv">Print</a>
    <a href="#" class="addbutton" style="float:right !important" id="export">

</a><a href="../home.php"  >Close</a>
<div id="reportsdiv"><u>

</u>
<table class="treport1 width70">
    <thead>
<tr>
    <td colspan="14">
        <center><span style="font-size:15px;font-weight:normal;float:left;">
         <b>Tenant List</b>
        </span>
        <span style="font-size:18px;font-weight:bold">Rivercourt Property Management</span>
        <span style="font-size:18px;font-weight:normal; float:right;"></span>
        <center>
<br>

<span style="font-size:16px;font-weight:normal;">
<centreceiptlier>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tenant List</b></centreceiptlier></span></center></center></td></tr>
<tr><th>Property Name</th><th>Apartment</th> <th>name</th><th>ID NO</th><th>phone number</th></thead><tbody>
<?php 

$data=json_decode(gettenants_temp());
foreach ($data as $dt){
   // echo(  die($dt->propertyid));
?>
<tr>
        <td><?php echo($dt->property_name); ?></td>
       
        <td><?php echo ($dt->aptid); ?></td>
        <td><?php echo ($dt->name); ?></td>
        <td><?php echo ($dt->idno); ?></td>
        <td><?php echo ($dt->phone); ?></td>
        <td><a href="#">approve</a></td>
      
      
    </tr>
<?php
}
?>
    </tbody>
</table>
   </body></html>