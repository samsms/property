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

$pendingP=getAllPendingPrepayments();
echo '    <fieldset style="width: 700px;margin:auto; panding:auto;border:#ffcc99 2px solid"class="myTable"><legend id="myTable"><b><h1>PENDING PREPAYMENTS</h1></b></legend>
';
foreach($pendingP as $pendingP){
    echo '
    <table  class="myTable" style="background-color:beige;width: 700px;margin:auto; panding:auto; border-bottom: 1px solid coral;">

    <tr style="background-color:#e7e7e7">
      <td  id="pid">'.$pendingP['propid'].'</td> 
      <td style="width: 200px;">'.$pendingP['aptid'].'</td> 
      <td id="sid">'.$pendingP['status'].'</td> 
      <td style="display:none">'.$pendingP['id'].'</td>
     
     <td> <button type="button" style="outline:none;border:none;background-color: #e7e7e7; color: black;" class="btnSelect btn btn-primary">Aprove</button></br></td>
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
            
            var jqxhrpost = $.get("../modules/accountsprocess.php?ApprovethisPrepayment=true&ApprovethisPrep="+col4, function() {

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