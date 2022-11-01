<html>
    <head>
        <title></title>
    </head>
    <body>
    <?php
    $id=$_GET['id'];
include 'functions.php';
$record=tenantsApproval($id);
$aptid=$record['aptid'];
$propertyid=$record['propid'];
$name=$record['name'];
$phone=$record['phone'];
$idno=$record['idno'];
$propertyname=$record['propertyname'];
$regdate='9999-12-31';
$kinsemail='hello';
$kinstel='07844844';
$kinsname='Evans';
$postaddress='4fyf';
$physcaddress='44dd';
$agentname='ark';
$leasedoc='leo';
$leaseend='9999-12-31';
$leasestart='9999-12-31';
$photo="iuiui";
$work='mboga';
$pin='1344';
$email='echo123';
$aptname=$record['aptname'];
addtenant($aptid, $aptname, $propertyid, $propertyname, $name, $phone, $email, $pin,
 $work, $idno,$photo,$leasestart,$leaseend, $leasedoc, $agentname, $physcaddress, 
 $postaddress, $kinsname, $kinstel, $kinsemail, $regdate);
 tenantApprovalUpdate($id);
 tenantDisapprovalUpdate($id);
?>

 </body>
</html>