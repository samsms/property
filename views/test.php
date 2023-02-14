<?php
//error_reporting(E_ALL);
//ini_set("display_errors", 1);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
require 'phpmailer/PHPMailerAutoload.php';
// Database configuration
$host = "localhost:6603";
$username = "root";
$password = "Trymenot#123$";
$database_name = "melvinshr";

// Get connection object and set the charset
$conn = mysqli_connect($host, $username, $password, $database_name);
//$conn->set_charset("utf8");

// Get All Table Names From the Database
$tables = array();
$sql = "SHOW TABLES";
$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_row($result)) {
    $tables[] = $row[0];
}

$sqlScript = "";
foreach ($tables as $table) {

    // Prepare SQLscript for creating table structure
    $query = "SHOW CREATE TABLE $table";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_row($result);

    $sqlScript .= "\n\n" . $row[1] . ";\n\n";


    $query = "SELECT * FROM $table";
    $result = mysqli_query($conn, $query);

    $columnCount = mysqli_num_fields($result);

    // Prepare SQLscript for dumping data for each table
    for ($i = 0; $i < $columnCount; $i ++) {
        while ($row = mysqli_fetch_row($result)) {
            $sqlScript .= "INSERT INTO $table VALUES(";
            for ($j = 0; $j < $columnCount; $j ++) {
                $row[$j] = $row[$j];

                if (isset($row[$j])) {
                    $sqlScript .= '"' . $row[$j] . '"';
                } else {
                    $sqlScript .= '""';
                }
                if ($j < ($columnCount - 1)) {
                    $sqlScript .= ',';
                }
            }
            $sqlScript .= ");\n";
        }
    }

    $sqlScript .= "\n";
}

if(!empty($sqlScript))
{


// Save the SQL script to a backup file
    $backup_file_name = "backups/".$database_name . '_backup_' . time() . '.sql';
    $fileHandler = fopen($backup_file_name, 'w+');
    $number_of_lines = fwrite($fileHandler, $sqlScript);
    fclose($fileHandler);


    $zip = new ZipArchive;
    $backup_db_file_name = "backups/".$database_name .'_backup_db_' . time() . '.zip';
    if ($zip->open($backup_db_file_name, ZipArchive::CREATE) === TRUE)
    {
        //  Add files to the zip file
        $zip->addFile($backup_file_name);

        // All files are added, so close the zip file.
        $zip->close();
    }

    //create an instance of PHPMailer
    $mail = new PHPMailer();

    //set a host
    $mail->Host = "mail.techsavanna.technology";

    //enable SMTP
    $mail->isSMTP();
    $mail->SMTPDebug = 1;

    //set authentication to true
    $mail->SMTPAuth = true;

    //set login details for Gmail account
    $mail->Username = "melvins@techsavanna.technology";
    $mail->Password = "melvins@2022";

    //set type of protection
    //  $mail->SMTPSecure = "ssl"; //or we can use TLS

    //set a port
    $mail->Port = 525; //or 587 if TLS

    //set subject
    $mail->Subject = "Melvins hr database weekly backup";

    //melvins database
    $mail->addAttachment($backup_db_file_name);

    //set HTML to true
    $mail->isHTML(true);

    //set body
    $mail->Body = "The attached document is the weekly melvins hr database.";



    //set who is sending an email
    $mail->setFrom('melvins@techsavanna.technology', 'Techsavanna');
    $mail->addAddress('samsaf674@gmail.com');
    //set where we are sending email (recipients)
    //$mail->addAddress('pa@melvinstea.com');
    // $mail->addAddress('pamsiwa@yahoo.com');

    //send an email
    $mail->send();

}
?>