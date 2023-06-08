<?php

// header('content-type:application/json');
include 'functions.php'; // die(json_encode($_POST));
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(-1);
if(isset($_GET['sync'])){
  //  die("hello");
    echo sync_tenant();
}else
{
    if (isset($_FILES['tenants'])) {
    $fname = $_FILES['tenants']['tmp_name'];
    if (!($fp = fopen($fname, 'r'))) {
        die("Can't open file...");
    }
    //read csv headers
    $db = new MySQLDatabase();
    $db->open_connection();
    $key = fgetcsv($fp, "1024", ",");
    $i = 0;
    // parse csv rows into array
    $fps = fopen('dummy.csv', 'w');
    $data = array();
    $result = array();
    while ($row = fgetcsv($fp, "1024", ",")) {
        $i++;
        $data = array_combine($key, $row);
        $_POST = $data;
        $prop_name = mysql_real_escape_string($_POST['AptName']);
        $apt_tag = trim($_POST['HouseNo']);
        $sql = "select apt_id,properties.property_name,properties.propertyid from floorplan inner join properties on properties.propertyid=floorplan.propertyid where apt_tag='$apt_tag' and properties.property_name='$prop_name' ";
        $rs = $db->query($sql);
        $result = mysql_fetch_assoc($rs);
        if (mysql_num_rows($rs) < 1) {
            fputcsv($fps, $row);
        } else {
            $apt_id = $result['apt_id'];
            $prop_name = mysql_real_escape_string($result['property_name']);
            $propertyid = $result['propertyid'];
            echo (addtenantBulk($apt_id, $apt_tag, $propertyid, $prop_name, mysql_real_escape_string($_POST['TenantName']), $_POST['TenantPhone'], $_POST['TenantEmail'], $_POST['PIN'], $_POST['work'], $_POST['IDNO'], $photo, $_POST['LeaseStart'], $_POST['LeaseEnd'], $_POST['Leasedoc'], $_POST['AgentName'], $_POST['Address'], $_POST['PostAddress'], $_POST['kinsName'], $_POST['KinsTel'], $_POST['kinsEmail'], $_POST['Date']));
        }
    }
    fclose($fps);
} else if (isset($_POST['PropertyName']) && isset($_POST['AptId']) && isset($_POST['IDNO']) && isset($_POST['LeaseStart']) && isset($_POST['LeaseEnd']) && isset($_POST['Leasedoc'])) {
    if ($_POST['PHOTO'] == '') {
        $photo = 'avatar.png';
    } else {
        $photo = $_POST['PHOTO'];
    }
    echo addtenant($_POST['AptId'], $_POST['AptName'], $_POST['Propertyid'], $_POST['PropertyName'], $_POST['TenantName'], $_POST['TenantPhone'], $_POST['TenantEmail'], $_POST['PIN'], $_POST['work'], $_POST['IDNO'], $photo, $_POST['LeaseStart'], $_POST['LeaseEnd'], $_POST['Leasedoc'], $_POST['AgentName'], $_POST['Address'], $_POST['PostAddress'], $_POST['kinsName'], $_POST['KinsTel'], $_POST['kinsEmail'], $_POST['Date']);
} else {
    echo 'failed to post values';
}
}