<?php
include 'functions.php';
$propid=strip_tags($_REQUEST['propertyidphoto']);
$photocat=strip_tags($_REQUEST['cat']);
$categories= strip_tags($_REQUEST['categories']);

if($categories=="one"){
 foreach (lookupphotos($propid,$photocat) as $value) {
     echo $value.'&nbsp;&nbsp;';
    
}}
else if ($categories=="all"){
    $photocats=array("Current property Photos"=>"C","Historic Property Photos"=>"H","Proposed Construction Photos"=>"P");
    foreach ($photocats as $desc=>$photocat) {
        echo '<div><center>***************************************'.$desc.'*********************************</center></div><br>'; 
  foreach (lookupphotos($propid,$photocat) as $value) {
    
  echo $value.'&nbsp;&nbsp;';
    }
    echo '<br>';
    }
}


