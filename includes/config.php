<?php

error_reporting(1);
// die($_SERVER['REMOTE_ADDR']);
if($_SERVER['REMOTE_ADDR']!="::1"&&$_SERVER['REMOTE_ADDR']!="127.0.0.1"){

defined('DB_SERVER') ? null : define("DB_SERVER", "localhost");
defined('DB_USER')   ? null : define("DB_USER", "root");
defined('DB_PASS')   ? null : define("DB_PASS", "Trymenot#123$");
//defined('DB_NAME')   ? null : define("DB_NAME", "techsava_property_htest");
defined('DB_NAME')   ? null : define("DB_NAME", "rivercourt_prop_management");
defined('DB_PORT')?     null: define("DB_PORT","6603");

//die("local");
}else{
   //die("online");
   defined('DB_SERVER') ? null : define("DB_SERVER", "localhost");
    defined('DB_USER')   ? null : define("DB_USER","sam");
    defined('DB_PASS')   ? null : define("DB_PASS", "samsaf");
    //defined('DB_NAME')   ? null : define("DB_NAME", "techsava_property_htest");
    defined('DB_NAME')   ? null : define("DB_NAME", "rivercourt_prop_management");
    
}
date_default_timezone_set("Africa/Nairobi");
//$root="https://localhost/property-rivercourt-test/";

?> 