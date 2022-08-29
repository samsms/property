<?php 
require "../includes/config.php";
$conn= new mysqli(DB_SERVER,DB_USER,DB_PASS,DB_NAME)or die("Could not connect to mysql".mysqli_error($con));
