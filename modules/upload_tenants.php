<?php
@session_start();
/*a list of all tenants available/adding and deleting them
 */

 include  'functions.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if a file was uploaded

    if (isset($_FILES['csv'])) {
        $fname = $_FILES['csv']['tmp_name'];
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
            $rent=trim($_POST['RentPM']);
            // $sql = "select apt_id,properties.property_name,properties.propertyid from floorplan inner join properties on properties.propertyid=floorplan.propertyid where apt_tag='$apt_tag' and properties.property_name='$prop_name' ";
            // $rs = $db->query($sql);
            $sql="select propertyid,property_name from properties where property_name='$prop_name'";
           
            $rs = $db->query($sql);
            $result = mysql_fetch_assoc($rs);
            if (mysql_num_rows($rs) < 1) {
                $sql = "INSERT INTO properties (property_type, category, property_name) VALUES (" .
                PrepSQL("commercial") . ", " .
                PrepSQL("Flats and shops") . ", " .
                PrepSQL( $prop_name ) . ")";
               
            // Execute the query
                $result = $db->query($sql) or die(mysql_error());
                $lastInsertId = mysql_insert_id();
               
                if(!$result){
                    fputcsv($fps, $row);
                }else{
                    $sql = "Insert into floorplan (propertyid,property_name,floornumber,units,apt_tag,monthlyincome,marketvalue) values 
                    ('$lastInsertId','$prop_name','$i','0','$apt_tag ','$rent','0');";
                            $result = $db->query($sql) or die(mysql_error());
                            if(!$result){
                                fputcsv($fps, $row);
                            }  else{
                                $apt_id = mysql_insert_id();
                                $tenant = getTenantDetailsFromApt($apt_id);
                                if($tenant){
                                    fputcsv($fps, $row);
                                }else{
                                    echo (addtenantBulk($apt_id, $apt_tag, $lastInsertId, $prop_name, mysql_real_escape_string($_POST['TenantName']), $_POST['TenantPhone'], $_POST['TenantEmail'], $_POST['PIN'], $_POST['work'], $_POST['IDNO'], $photo, $_POST['LeaseStart'], $_POST['LeaseEnd'], $_POST['Leasedoc'], $_POST['AgentName'], $_POST['Address'], $_POST['PostAddress'], $_POST['kinsName'], $_POST['KinsTel'], $_POST['kinsEmail'], $_POST['Date']));
                           
                                }
                             
                            }
                }
                
            } else {
                $sql = "select apt_id,properties.property_name,properties.propertyid from floorplan inner join properties on properties.propertyid=floorplan.propertyid where apt_tag='$apt_tag' and properties.property_name='$prop_name' ";
                $rs = $db->query($sql);
                $pre_result=$result;
               $result = mysql_fetch_assoc($rs);
                if($result)
                {
                    $apt_id = $result['apt_id'];
                    $prop_name = mysql_real_escape_string($result['property_name']);
                    $propertyid = $result['propertyid'];
                    $tenant = getTenantDetailsFromApt($apt_id);
                    if($tenant){
                        fputcsv($fps, $row);
                    }else{
                        echo (addtenantBulk($apt_id, $apt_tag, $propertyid, $prop_name, mysql_real_escape_string($_POST['TenantName']), $_POST['TenantPhone'], $_POST['TenantEmail'], $_POST['PIN'], $_POST['work'], $_POST['IDNO'], $photo, $_POST['LeaseStart'], $_POST['LeaseEnd'], $_POST['Leasedoc'], $_POST['AgentName'], $_POST['Address'], $_POST['PostAddress'], $_POST['kinsName'], $_POST['KinsTel'], $_POST['kinsEmail'], $_POST['Date']));
                
                    }
                 
                
               }else{
                // die(print_r());
                $prop_name= $pre_result['property_name'];
                $prop_id= $pre_result['propertyid'];
             
                $sql = "Insert into floorplan (propertyid,property_name,floornumber,units,apt_tag,monthlyincome,marketvalue) values 
                ('$prop_id','$prop_name','$i','0','$apt_tag ', '$rent','0');";
                        $result = $db->query($sql) or die(mysql_error());
                if(  !$result){
                    fputcsv($fps, $row);
                }else{
                    $apt_id = mysql_insert_id();
                    $prop_name = mysql_real_escape_string($result['property_name']);
                    $propertyid = $result['propertyid'];
                    $tenant = getTenantDetailsFromApt($apt_id);
                    if($tenant){
                        fputcsv($fps, $row);
                    }else{
                        echo (addtenantBulk($apt_id, $apt_tag, $propertyid, $prop_name, mysql_real_escape_string($_POST['TenantName']), $_POST['TenantPhone'], $_POST['TenantEmail'], $_POST['PIN'], $_POST['work'], $_POST['IDNO'], $photo, $_POST['LeaseStart'], $_POST['LeaseEnd'], $_POST['Leasedoc'], $_POST['AgentName'], $_POST['Address'], $_POST['PostAddress'], $_POST['kinsName'], $_POST['KinsTel'], $_POST['kinsEmail'], $_POST['Date']));
                 }
                  
                }
            }
            sync_tenant();
        }
        fclose($fps);

    }
}
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Property CSV Upload</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        h2 {
            margin-bottom: 20px;
        }

        .form-container {
            max-width: 500px;
            border: 1px solid #ccc;
            padding: 20px;
            border-radius: 5px;
        }

        .form-container label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .form-container input[type=file] {
            margin-bottom: 10px;
        }

        .form-container input[type=submit] {
            background-color: #4CAF50;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h2>Property CSV Upload</h2>
    <div class="form-container">
        <form action="upload_tenants.php" method="post" enctype="multipart/form-data">
            <label for="csv">Select CSV File:</label>
            <input type="file" id="csv" name="csv" accept=".csv" required>
            <input type="submit" value="Upload">
        </form>
    </div>
</body>
</html>

