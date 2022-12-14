<?php
session_start();
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
include 'display.php';
include '../modules/functions.php';
echo  $htmlheaders;
echo '<head><title>' . $property['company_name'] . '| Jamar Properties</title>';
echo $meta;
echo '<link rel="stylesheet" type="text/css" media="screen" href="' . $baseurl . 'css/overall2.css" />';
echo '<link rel="stylesheet" href="../css/jquery-ui.css">';
echo '<script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>';
echo $jquery;
//later set variable to session
$admin = '' . $_SESSION['username'] . '';
date_default_timezone_set('Africa/Nairobi');

$user =  getUserById($_SESSION['userid']);
echo'<h2><a style="color:black;" href="../home.php"><<<< Back Home</a></h2>';

$pendingP=getAllPendingPrepayments();
echo '    <fieldset style="width: 900px;margin:auto; panding:auto;border:#ffcc99 2px solid"class="myTable"><legend id="myTable"><b><h1>PENDING PREPAYMENTS</h1></b></legend>
<table  class="myTable" style="background-color:beige;width: 900px;margin:auto; panding:auto; border-bottom: 1px solid coral;">
<tr style="background-color:#e7e7e7;font-size: x-small;">
    <td style="width: 20px;"><b>Apartment</b></td>
    <td><b>Status</b></td>
    <td><b>Property Name</b></td>
    <td><b>Address</b></td>
    <td><b>Owner</b></td>
    <td><b>Property Type</b></td>
    <td><b>Aprove</b></td>
    </tr>
    </table>
';
foreach($pendingP as $pendingP){
    echo '
    <table  class="myTable" style="background-color:beige;width: 900px;margin:auto; panding:auto; border-bottom: 1px solid coral;">
    
    <tr style="background-color:#e7e7e7">
    <td style="display:none">'.$pendingP['id'].'</td> 
    <td style="width: 50px;">'.$pendingP['aptid'].'</td> 
    <td style="">'.$pendingP['status'].'</td> 
      <td style="padding:0px 30px 0px 30px; margin:0">'.$pendingP['property_name'].'</td>
      <td style="padding:0px 30px 0px 40px; margin:0">'.$pendingP['address'].'</td>
      <td style="padding:0px 30px 0px 0px; margin:0;">'.$pendingP['owner'].'</td>
      <td style="padding:0px 30px 0px 0px; margin:0;width: 130px;">'.$pendingP['property_type'].'</td>
     <td style="width: 100px;"> <button type="button" style="outline:none;border:none;background-color: #e7e7e7; color: black; cusor:pointer" class="btnSelect btn btn-primary">Approve</button></br></td>
    </tr>
  </thead>
  </table>
  ';
}


?>
<script>

$(".myTable").on('click','.btnSelect',function(){
         // get the current row
         var currentRow=$(this).closest("tr"); 
         
         var col1=currentRow.find("td:eq(0)").text(); // get current row 1st TD value
         var col2=currentRow.find("td:eq(1)").text(); // get current row 2nd TD
         var col3=currentRow.find("td:eq(2)").text(); // get current row 3rd TD
         var col4=currentRow.find("td:eq(3)").text(); // get current row 3rd TD
         
    if(col1){
            
            var jqxhrpost = $.get("../modules/accountsprocess.php?ApprovethisPrepayment=true&ApprovethisPrep="+col1, function() {
              console.log(col1)
            })
            .done(function(data) {
            // alert(data.msg);
            $.ajax({
                  url: location. reload(),  
                  success: function(data) {
                    alert('Approved successfuly');
                  }
                });
            });
         }
         return false;
               
        });

</script>
<?php 
?>