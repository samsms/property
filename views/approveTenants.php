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

$pendingT=getAllPendingTenants();

echo '    <fieldset style="width: 900px;margin:auto; panding:auto;border:#ffcc99 2px solid"class="myTable"><legend id="myTable"><b><h1>PENDING TENANTS</h1></b></legend>
<table  class="myTable" style="background-color:beige;width: 900px;margin:auto; panding:auto; border-bottom: 1px solid coral;">
<tr style="background-color:#e7e7e7;font-size: small;">
<td style="width: 20px;"><b>Property</b></td>
    <td style="width: 20px;"><b>Apartment</b></td>
  
    <td><b>Tenant Name</b></td>

    <td><b>phone number</b></td>
    <td><b> status</b></td>
    <td><b>Aprove</b></td>
    </tr>
    
';

foreach($pendingT as $pendingT){
  $tenant=$pendingT;
  // getTenantDetailsFromRow($pendingP['tenantid']);
  // echo json_encode($tenant);
  
    echo '
   
    <tr style="background-color:white;font-size: small;">
    
    <td style="display:none">'.$tenant['id'].'</td> 
    <td >'.(json_decode($tenant['tenants'])->AptName).'</td> 
    <td >'.(json_decode($tenant['tenants'])->PropertyName).'</td> 
    <td >'.(json_decode($tenant['tenants'])->TenantName).'</td> 

    <td style="display:none">'.(json_decode($tenant['tenants'])->AptId).'</td> 
    <td style="display:none">'.(json_decode($tenant['tenants'])->Propertyid).'</td> 
    <td style="display:none">'.(json_decode($tenant['tenants'])->TenantEmail).'</td> 
    <td style="display:none">'.(json_decode($tenant['tenants'])->PIN).'</td> 
    <td style="display:none">'.(json_decode($tenant['tenants'])->work).'</td> 
    <td style="display:none">'.(json_decode($tenant['tenants'])->IDNO).'</td> 
    <td style="display:none">'.(json_decode($tenant['tenants'])->photo).'</td> 
    <td style="display:none">'.(json_decode($tenant['tenants'])->LeaseStart).'</td> 
    <td style="display:none">'.(json_decode($tenant['tenants'])->LeaseEnd).'</td> 
    <td style="display:none">'.(json_decode($tenant['tenants'])->Leasedoc).'</td> 
    <td style="display:none">'.(json_decode($tenant['tenants'])->AgentName).'</td> 
    <td style="display:none">'.(json_decode($tenant['tenants'])->Address).'</td> 
    <td style="display:none">'.(json_decode($tenant['tenants'])->PostAddress).'</td> 
    <td style="display:none">'.(json_decode($tenant['tenants'])->kinsName).'</td> 
    <td style="display:none">'.(json_decode($tenant['tenants'])->KinsTel).'</td> 
    <td style="display:none">'.(json_decode($tenant['tenants'])->kinsEmail).'</td> 
    <td style="display:none">'.(json_decode($tenant['tenants'])->Date).'</td> 

      <td >'.(json_decode($tenant['tenants'])->TenantPhone).'</td>
      <td >'.$tenant['status'].'</td>
     <td style="width: 100px;"> <button type="button" style="outline:none;border:none;background-color: #e7e7e7; color: black; cusor:pointer" class="btnSelect btn btn-primary">Approve</button></br></td>
     <td style="width: 100px;"> <button type="button" style="outline:none;border:none;background-color: #e7e7e7; color: black; cusor:pointer" class="btnSelect btn btn-primary">Cancel</button></br></td>
   
     </tr>
  </thead>




  ';
}


?>
  </table>
<script>

$(".myTable").on('click','.btnSelect',function(){
         // get the current row
         var currentRow=$(this).closest("tr"); 
         
         var id=currentRow.find("td:eq(0)").text(); // get current row 1st TD value
         var AptName=currentRow.find("td:eq(1)").text(); // get current row 1st TD value
         var PropertyName=currentRow.find("td:eq(2)").text(); // get current row 2nd TD
         var TenantName=currentRow.find("td:eq(3)").text(); // get current row 3rd TD
         var AptId=currentRow.find("td:eq(4)").text(); // get current row 3rd TD
         var Propertyid=currentRow.find("td:eq(5)").text(); // get current row 3rd TD
         var TenantEmail=currentRow.find("td:eq(6)").text(); // get current row 3rd TD
         var PIN=currentRow.find("td:eq(7)").text(); // get current row 3rd TD
         var work=currentRow.find("td:eq(8)").text(); // get current row 3rd TD
         var IDNO=currentRow.find("td:eq(9)").text(); // get current row 3rd TD
         var photo=currentRow.find("td:eq(10)").text(); // get current row 3rd TD
         var LeaseStart=currentRow.find("td:eq(11)").text(); // get current row 3rd TD
         var LeaseEnd=currentRow.find("td:eq(12)").text(); // get current row 3rd TD
         var Leasedoc=currentRow.find("td:eq(13)").text(); // get current row 3rd TD
         var AgentName=currentRow.find("td:eq(14)").text(); // get current row 3rd TD
         var Address=currentRow.find("td:eq(15)").text(); // get current row 3rd TD
         var PostAddress=currentRow.find("td:eq(16)").text(); // get current row 3rd TD
         var kinsName=currentRow.find("td:eq(17)").text(); // get current row 3rd TD
         var KinsTel=currentRow.find("td:eq(18)").text(); // get current row 3rd TD
         var kinsEmail=currentRow.find("td:eq(19)").text(); // get current row 3rd TD
         var Date=currentRow.find("td:eq(20)").text(); // get current row 3rd TD
         var TenantPhone=currentRow.find("td:eq(21)").text(); // get current row 3rd TD
         var status=currentRow.find("td:eq(22)").text(); // get current row 3rd TD
         
    if(AptName){
            
            var jqxhrpost = $.get("../modules/addtenants.php?approveTenants=True&AptName="+AptName+"&PropertyName="+PropertyName+"&id="+id+"&PropertyName="+PropertyName+"&TenantName="+TenantName+"&AptId="+AptId+"&Propertyid="+Propertyid+"&TenantEmail="+TenantEmail+"&PIN="+PIN+"&work="+work+"&IDNO="+IDNO+"&photo="+photo+"&LeaseStart="+LeaseStart+"&LeaseEnd="+LeaseEnd+"&Leasedoc="+Leasedoc+"&AgentName="+AgentName+"&Address="+Address+"&PostAddress="+PostAddress+"&kinsName="+kinsName+"&KinsTel="+KinsTel+"&kinsEmail="+kinsEmail+"&Date="+Date+"&TenantPhone="+TenantPhone+"&status="+status, function() {
              console.log(AptName)
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