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
// posts csv data
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
       
        // echo addtenant($apt_id,$_POST['AptName'],$_POST['Propertyid'],$prop_name,$_POST['TenantName'],$_POST['TenantPhone'],$_POST['TenantEmail'],$_POST['PIN'],$_POST['work'],$_POST['IDNO'],$photo,$_POST['LeaseStart'],$_POST['LeaseEnd'],$_POST['Leasedoc'],$_POST['AgentName'],$_POST['Address'],$_POST['PostAddress'],$_POST['kinsName'],$_POST['KinsTel'],$_POST['kinsEmail'],$_POST['Date']);  
  
}
// posts form data

}else
if(isset($_POST['PropertyName']) && isset($_POST['AptId']) && isset($_POST['IDNO'])&& isset($_POST['LeaseStart'])&& isset($_POST['LeaseEnd'])&& isset($_POST['Leasedoc']))
{
    if($_POST['PHOTO']==''){$photo='avatar.png';}else{$photo=$_POST['PHOTO'];}
  
    if($_SESSION['username']=='admin'){
        echo($_SESSION['username']);
        // die();
          echo addtenant($_POST['AptId'],$_POST['AptName'],$_POST['Propertyid'],$_POST['PropertyName'],$_POST['TenantName'],$_POST['TenantPhone'],$_POST['TenantEmail'],$_POST['PIN'],$_POST['work'],$_POST['IDNO'],$photo,$_POST['LeaseStart'],$_POST['LeaseEnd'],$_POST['Leasedoc'],$_POST['AgentName'],$_POST['Address'],$_POST['PostAddress'],$_POST['kinsName'],$_POST['KinsTel'],$_POST['kinsEmail'],$_POST['Date']);  
    }else{
        $tenant_data=array("AptId"=>$_POST['AptId'],"AptName"=>$_POST['AptName'],"Propertyid"=>$_POST['Propertyid'],"PropertyName"=>$_POST['PropertyName'],"TenantName"=>$_POST['TenantName'],"TenantPhone"=>$_POST['TenantPhone'],"TenantEmail"=>$_POST['TenantEmail'],"PIN"=>$_POST['PIN'],"work"=>$_POST['work'],"IDNO"=>$_POST['IDNO'],"photo"=>$photo,"LeaseStart"=>$_POST['LeaseStart'],"LeaseEnd"=>$_POST['LeaseEnd'],"Leasedoc"=>$_POST['Leasedoc'],"AgentName"=>$_POST['AgentName'],"Address"=>$_POST['Address'],"PostAddress"=>$_POST['PostAddress'],"kinsName"=>$_POST['kinsName'],"KinsTel"=>$_POST['KinsTel'],"kinsEmail"=>$_POST['kinsEmail'],"Date"=>$_POST['Date']);
        $tenant_data_obj=json_encode($tenant_data);
        // echo($tenant_data_obj);
        // die($tenant_data_obj);
        // echo('aaaaaa');
        echo addtenant2($tenant_data_obj);  

    }
   
    
}

else
if($approveTenants=True&&isset($_GET['PropertyName']) && isset($_GET['AptId']) && isset($_GET['IDNO'])&& isset($_GET['LeaseStart'])&& isset($_GET['LeaseEnd'])&& isset($_GET['Leasedoc']))
{

    if($_SESSION['username']=='admin'){
        echo($_SESSION['username']."  posted ........");
        // die();
          echo addtenant($_GET['AptId'],$_GET['AptName'],$_GET['Propertyid'],$_GET['PropertyName'],$_GET['TenantName'],$_POST['TenantPhone'],$_GET['TenantEmail'],$_GET['PIN'],$_GET['work'],$_GET['IDNO'],$photo,$_GET['LeaseStart'],$_GET['LeaseEnd'],$_GET['Leasedoc'],$_GET['AgentName'],$_POST['Address'],$_GET['PostAddress'],$_GET['kinsName'],$_GET['KinsTel'],$_GET['kinsEmail'],$_GET['Date']); 

    }    

}
else {
        echo 'failed to post values';}
?>
