<?php
include 'functions.php';
include '../views/display.php';
echo '<head>';
echo '<link rel="stylesheet" type="text/css" media="screen" href="'.$baseurl.'css/overall2.css" />';
echo '<link href="' . $baseurl .'css/overall2.css" rel="stylesheet" media="print" type="text/css" />';
echo '<script type="text/javascript" src="../js/jquery-1.9.1.js"></script>'; //jquery std library
echo '<script type="text/javascript" src="../js/jquery.PrintArea.js"></script>
<script type="text/javascript" src="../js/core.js"></script>';//for printarea //core.js also for pdf

echo '</head>';
$propid = $_REQUEST['propid'];
$stringdocs= $_REQUEST['docstring'];
$clientname='PETA-AGENCY';
$footer='<div class="footer">'.$_REQUEST['footer'].'</div>';
$hirji=Greg2Hijri($day='00', $month='00', $year=$currentyear, $string = false);//get hirji year
$count= sizeof(\preg_split("/[\s $]+/", $stringdocs))-1;//get count of pages
/* foreach (prepare_legal_documents(31,$stringdocs) as $key => $value) {
  echo  $key.'&nbsp;'.$value.'&nbsp;';
  } */
echo '<a href="#" id="print" class="print" rel="printable">Print</a>';
echo '<a href="#" id="pdf" class="pdf">PDF</a>';
echo '<div id="printable">';
echo '<div id="frontpagetop"></div>';
echo '<div id="frontpagemid">';
echo '<table class="tablereport"><tr><td class="td">';

        

$pname= strtoupper(str_replace("_", " ",propertyname($propid))) ;
echo '<center> <h2>' .$pname. '</h2>';
           
echo ' <h3>Property Document Folder</h3>';
echo ' <h4>'.$hirji['year'].'H-' . $currentyear . '</h4></center>';
echo '</td><td></td><td class="td" >
 <center><img src="../images/cursors/logo1.png" width="180" height="180"><br/><h6>'.$clientname.'
MOMBASA</h6>
 </center>  


</td></tr></table></div>';
echo '<div id="frontpagebottom"></div>';
//property information
echo '<div id="detailspage"><div class="detailspage">';
echo   '<h1><center>'.ucwords(strtolower($pname)). '<center></h1>';
$success=loadpropertydetails($propid);
    $propname=$success["property_name"];$title=$success["title"];$condition=$success["propcondition"];$mohalla=$success["mohalla"];$address=$success["address"];$owner=$success["owner"];$nature=$success["category"];$specs=$success["proptype"];$photo=$success["photo1"];

echo '<div class="photoheading"><br/><img src="'.$_REQUEST['path'].'" width="650" height="373"></div>';
echo '<div class="bottominfo">
    <table id="propertyinfo">
<tr>
  <th>Serial No</th>
  <th>Details</th>
  <th></th>
</tr>
<tr>
<td class="info">1</td>
<td class="info">Property Name</td>
<td>'.strtoupper(str_replace("_", " ", $propname)).'</td>
</tr>
<tr class="alt">
<td class="info">2</td>
<td class="info">Title - Plot No</td>
<td>'.$title.'</td>
</tr>
<tr>
<td class="info">3</td>
<td class="info">Place/Mohalla </td>
<td>'.$address.' ('.$mohalla.' )</td>
</tr>
<tr class="alt">
<td class="info">4</td>
<td class="info">Title Deed Name </td>
<td class="owner">'.$owner.'</td>
</tr>
<tr>
<td class="info">5</td>
<td class="info">Nature of Property </td>
<td>'.$nature.'</td>
</tr>
<tr class="alt">
<td class="info">6</td>
<td class="info">Specification </td>
<td>'.$specs.'</td>
</tr>
<tr>
<td class="info">7</td>
<td class="info">Yearly Income</td>
<td>N/A</td>
</tr>
<tr class="alt">
<td class="info">8</td>
<td class="info">Yearly Expense</td>
<td>N/A</td>
</tr>
<tr>
<td class="info">9</td>
<td class="info">Land Rates (govt)</td>
<td>N/A</td>
</tr>
<tr class="alt">
<td class="info">10</td>
<td class="info">Property Condition</td>
<td>'.$condition.'</td>
</tr>
</table></div>';


echo $footer.'</div></div><div class="pagebreak"></div>';
//rest of report


$successes = prepare_legal_documents($propid, $stringdocs); /* where $array is the variable holding the result */
$size=  count($successes);
$page=1;
foreach ($successes as $success) {
    echo '<div id="docreport">';
    echo "<center><b>" . str_replace("_", " ", $success['document']) . '</b><hr/><img src="' . $success['path'] . '" width="830" height="960" />Page '.$page++.' of '.$size.str_repeat('&nbsp;',7).$footer.'</center>';

    echo '</div><div class="pagebreak"></div>';
}
    

$successes1 = prepare_property_documents($propid, $stringdocs); /* where $array is the variable holding the result */
foreach ($successes1 as $success1) {
    echo '<div id="docreport">';
    echo "<center><b>" . str_replace("_", " ", $success1['document']) . '</b><hr/><img src="' . $success1['path'] . '" width="830" height="960" />Page '.$page++.' of '.$size.str_repeat('&nbsp;',7).$footer.'</center>';
     echo '</div><div class="pagebreak"></div>';
    
}


echo '</div>'; //printable area

?>
