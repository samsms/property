<?php
include 'functions.php';// die(json_encode($_POST));
if(isset($_FILES['tenants'])){
$fname=$_FILES['tenants']['tmp_name'];
if (!($fp = fopen($fname, 'r'))) {
    die("Can't open file...");
}
//read csv headers
$db = new MySQLDatabase();
$db->open_connection();
$key = fgetcsv($fp,"1024",",");
// parse csv rows into array
$data = array();
$result=array();
    while ($row = fgetcsv($fp,"1024",",")) {
    $data= array_combine($key, $row);
        $_POST=$data;
        $apt_tag=$_POST['AptName'];
        $property_id=$_POST['Propertyid'];

 $sql="select apt_id,properties.property_name from floorplan inner join properties on properties.propertyid=floorplan.propertyid where apt_tag='$apt_tag' and floorplan.propertyid='$property_id' ";
$result=$db->query($sql);
$result=mysql_fetch_assoc($result);
// die(json_encode($apt_id));
$apt_id=$result['apt_id'];
$prop_name=$result['property_name'];
        echo addtenant($apt_id,$_POST['AptName'],$_POST['Propertyid'],$prop_name,$_POST['TenantName'],$_POST['TenantPhone'],$_POST['TenantEmail'],$_POST['PIN'],$_POST['work'],$_POST['IDNO'],$photo,$_POST['LeaseStart'],$_POST['LeaseEnd'],$_POST['Leasedoc'],$_POST['AgentName'],$_POST['Address'],$_POST['PostAddress'],$_POST['kinsName'],$_POST['KinsTel'],$_POST['kinsEmail'],$_POST['Date']);  
  
}
}else
if(isset($_POST['PropertyName']) && isset($_POST['AptId']) && isset($_POST['IDNO'])&& isset($_POST['LeaseStart'])&& isset($_POST['LeaseEnd'])&& isset($_POST['Leasedoc']))
{
    if($_POST['PHOTO']==''){$photo='avatar.png';}else{$photo=$_POST['PHOTO'];}
   
 echo addtenant($_POST['AptId'],$_POST['AptName'],$_POST['Propertyid'],$_POST['PropertyName'],$_POST['TenantName'],$_POST['TenantPhone'],$_POST['TenantEmail'],$_POST['PIN'],$_POST['work'],$_POST['IDNO'],$photo,$_POST['LeaseStart'],$_POST['LeaseEnd'],$_POST['Leasedoc'],$_POST['AgentName'],$_POST['Address'],$_POST['PostAddress'],$_POST['kinsName'],$_POST['KinsTel'],$_POST['kinsEmail'],$_POST['Date']);  
    
}
else {
        echo 'failed to post values';}
?>
