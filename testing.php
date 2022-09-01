<?php
session_start();
ini_set('display_errors',1);     # don't show any errors...
error_reporting(E_ALL | E_STRICT);
include './includes/database.php';
include './modules/functions.php';
print(landlord_statement(338));