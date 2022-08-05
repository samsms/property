<?php
include 'functions.php';
if(isset($_POST['PropertyName']) && isset($_POST['AptId']) && isset($_POST['IDNO'])&& isset($_POST['LeaseStart'])&& isset($_POST['LeaseEnd'])&& isset($_POST['Leasedoc']))
{
    if($_POST['PHOTO']==''){$photo='avatar.png';}else{$photo=$_POST['PHOTO'];}
 echo addtenant($_POST['AptId'],$_POST['AptName'],$_POST['Propertyid'],$_POST['PropertyName'],$_POST['TenantName'],$_POST['TenantPhone'],$_POST['TenantEmail'],$_POST['PIN'],$_POST['work'],$_POST['IDNO'],$photo,$_POST['LeaseStart'],$_POST['LeaseEnd'],$_POST['Leasedoc'],$_POST['AgentName'],$_POST['Address'],$_POST['PostAddress'],$_POST['kinsName'],$_POST['KinsTel'],$_POST['kinsEmail'],$_POST['Date']);  
    
}
else {
        echo 'failed to post values';}
?>
