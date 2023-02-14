<?php
include '../views/display.php';
include 'functions.php';
$settings=  getSettings();
echo  $htmlheaders;
echo '<head><title>Property Manager|Peta Agency</title>';
echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
echo '<link rel="stylesheet" href="../themes/base/jquery.ui.all.css">';
echo '<link rel="stylesheet" href="../css/overall1.css" type="text/css" />';
echo '<link rel="stylesheet" href="../css/popupbox.css" type="text/css" />';//popup 
echo '<link rel="stylesheet" href="../css/demos.css">';
echo '<link rel="stylesheet" type="text/css" media="screen" href="../css/jquery-ui-git.css" />';
echo '<link rel="stylesheet" type="text/css" media="screen" href="../css/inputcss.css" />';
echo '<script type="text/javascript" src="../js/jquery-1.9.1.js"></script>';
echo '<script src="../js/jquery.validate.js" type="text/javascript"></script><script src="../js/jquery-ui-git.js"></script>';
echo '<script src="../js/ui/jquery.ui.core.js"></script><script src="../js/ui/jquery.ui.widget.js"></script><script src="../js/ui/jquery.ui.tabs.js"></script>';

//popup  requires...

echo '<script type="text/javascript" src="../js/jquery.cycle.all.js"></script>';
echo '<script type="text/javascript" src="../js/popup.js"></script>';
?>
<style type="text/css">

@import url("../css/paginate.css");

</style>

<script>
    $(function() {
	$( "#menu" ).menu();
});

//image slider
$(document).ready(function(){
   $('#slider1').cycle({
   fx: 'scrollHorz', // Here you can change the effect
   speed: 'slow', 
   timeout: 0,
   next: '#next', 
   prev: '#prev',
   pager: '#thumb',
   pagerAnchorBuilder: function(idx, slide) { 
     return '<li><a href="#"><img src="' + slide.src + '" /></a></li>'; 
   } 
  });
});

</script>


<?php 
//later set variable to session
$admin='<u>Admin</u>';
@$time = date('h:i A');

echo '</head><body>';
echo '<div id="form">';
echo '<h2>'.$wheat.$spacer.$logopath.$settings['company_name'].' | Property Manager<div id="loggedin">'.$loggedin.' '.$admin.' | '.$clock.' '.$time.'</div>'.$endwheat.'</h2>' .'<hr/>'; 
echo $wheat.'&nbsp;&nbsp;'.$endwheat;
echo '<table><tr><td>';
echo $sidebar;
echo '</td><td>'. str_repeat("&nbsp;", 4).'</td><td>';
$propertydetails=loadpropertydetails($_REQUEST['propid']);
echo    '<div id="propertydetails">';
echo '<center><div id="propname"><h3>'.strtoupper($propertydetails["property_name"]).'&nbsp;&nbsp;&nbsp;&nbsp;in&nbsp;&nbsp;&nbsp;&nbsp;'.strtoupper($propertydetails["mohalla"]).'</h3></div></center>';


//echo '<div id="prophoto"><img src="'.$propertydetails["photo1"].'"width="400" height="300" /> </div><br/>';//array[6-9] are photos
//echo '<div id="smallphoto"><img src="'.$propertydetails["photo1"].'"width="60" height="55" /></div>';echo '<div id="smallphoto1"><img src="'.$propertydetails["photo2"].'"width="60" height="55" /></div>';echo '<div id="smallphoto3"><img src="'.$propertydetails["photo3"].'"width="60" height="55" /></div>';echo '<div id="smallphoto4"><img src="'.$propertydetails["photo4"].'"width="60" height="55" /></div>';echo '<div id="smallphoto5"><img src="'.$propertydetails["photo5"].'"width="60" height="55" /></div>';
echo '<section>
<div class="container">
<div class="slider">
<div id="slider1">
<img border="0" src="'.$propertydetails["photo1"].'" width="500" height="345" alt="" />
<img border="0" src="'.$propertydetails["photo2"].'" width="500" height="345" alt="" />
<img border="0" src="'.$propertydetails["photo3"].'" width="500" height="345" alt="" />
<img border="0" src="'.$propertydetails["photo4"].'" width="500" height="345" alt="" />
<img border="0" src="'.$propertydetails["photo5"].'" width="500" height="345" alt="" />
</div>

<div id=\'next\' class="slider_next"><img src="../images/next.png" alt=""/></div>
<div id=\'prev\' class="slider_prev"><img src="../images/prev.png" alt=""/></div>';
echo '</div></div></section>';
echo '<fieldset id="propdetails">
  <legend><b>Property At a Glance</b></legend>
  <br><br>
    <b>PLOT NO:</b>&nbsp;&nbsp;'.$propertydetails["plotno"].'<br>'.$hr.
  '<br><b>TITLE DEED:</b>&nbsp;&nbsp;'.$propertydetails["title"].'<br>'.$hr.
  '<br><b>PROPERTY TYPE:</b>&nbsp;&nbsp;'.$propertydetails["proptype"].'<br>'.$hr.
  '<br><b>PROPERTY CATEGORY:</b>&nbsp;&nbsp;'.$propertydetails["category"].'<br>'.$hr.
  '<br><b>PROPERTY OWNER:</b>&nbsp;&nbsp;'.$propertydetails["owner"].'<br>'.$hr.
  '<br><b>OCCUPANTS:</b>&nbsp;&nbsp;'.$propertydetails["occupants"].'<br>'.$hr.
  '<br><b>AREA(SQ.FEET):</b>&nbsp;&nbsp;'.$propertydetails["area"].'<br>'.$hr.
  '<br><b>ADDRESS:</b>&nbsp;&nbsp;'.$propertydetails["address"].'<br>'.$hr.
  '<br><b>CONDITION:</b>&nbsp;&nbsp;'.$propertydetails["propcondition"].'<br>'.$hr.
  '<br><b>PROPERTY MAP:</b>&nbsp;&nbsp;<a class="popup-link-1"><u><b>(click to view map)</b></u></a><br>'.$hr.
 '</fieldset>';
echo '</div>';//end of <div> details
echo '
<div class="popup-box" id="popup-box-1"><div class="close">X</div><div class="top"><h5>Property Map</h5></div><div class="bottom"><iframe width="750" height="350" frameborder="0"
scrolling="no" marginheight="0" marginwidth="0"
src="'.@$propertydetails["map"].'">
</iframe></div></div>
<div id="blackout"></div>';

echo '</td></tr></table>';
echo '</div>';



echo '</body>';

?>