<?php

error_reporting(1);
// die($_SERVER['REMOTE_ADDR']);
$path= $_SERVER["DOCUMENT_ROOT"];
$filePath = "$path/.env";

if (!file_exists($filePath)) {
    $filePath = '.env';
}
if (file_exists($filePath)) {
    $file = fopen($filePath, 'r');
    // Rest of your code to read the file contents
    while (!feof($file)) {
        $line = fgets($file);
        $line = trim($line);
        if (feof($file)) {
            break; // Break the loop when end of file is reached
        }
        if (!empty($line) && strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $env[$key] = $value;
        }
    }
    fclose($file);
} else {
    echo "File does not exist.";
} 


if($_SERVER['REMOTE_ADDR']!="::1"&&$_SERVER['REMOTE_ADDR']!="127.0.0.1"){

defined('DB_SERVER') ? null : define("DB_SERVER",  $env['HOST']);
defined('DB_USER')   ? null : define("DB_USER",  $env['USER']);
defined('DB_PASS')   ? null : define("DB_PASS", $env['PASSWORD']);
//defined('DB_NAME')   ? null : define("DB_NAME", "techsava_property_htest");
defined('DB_NAME')   ? null : define("DB_NAME", $env['DATABASE']);

//die("local");
}else{
   //die("online");
   defined('DB_SERVER') ? null : define("DB_SERVER",  $env['HOST']);
   defined('DB_USER')   ? null : define("DB_USER",  $env['USER']);
   defined('DB_PASS')   ? null : define("DB_PASS", $env['PASSWORD']);
   //defined('DB_NAME')   ? null : define("DB_NAME", "techsava_property_htest");
   defined('DB_NAME')   ? null : define("DB_NAME", $env['DATABASE']);
    
}
date_default_timezone_set("Africa/Nairobi");
//$root="https://localhost/property-rivercourt-test/";

?> 