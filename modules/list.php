<?php
include '../views/display.php';
include './functions.php';
session_start();
echo  $htmlheaders;
$settings=  getSettings();
echo '<head><title>Property Manager|'.$settings['company_name'].'</title>';
echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
echo '<link rel="stylesheet" href="../themes/base/jquery.ui.all.css">';
echo '<link rel="stylesheet" href="../css/overall1.css" type="text/css" />';
echo '<link rel="stylesheet" href="../css/demos.css">';
echo '<link rel="stylesheet" type="text/css" media="screen" href="../css/jquery-ui-git.css" />';
echo '<link rel="stylesheet" type="text/css" media="screen" href="../css/inputcss.css" />';
echo '<script type="text/javascript" src="../js/jquery-1.9.1.js"></script>';
echo '<script src="../js/jquery.validate.js" type="text/javascript"></script><script src="../js/jquery-ui-git.js"></script>';
echo '<script src="../js/ui/jquery.ui.core.js"></script><script src="../js/ui/jquery.ui.widget.js"></script><script src="../js/ui/jquery.ui.tabs.js"></script>';
echo '<script src="../js/logout.js"></script>';	
?>
<script>
    $(function() {
	$( "#menu" ).menu();
});
</script>
<script>
	$(function() {
		$( "#tabs" ).tabs();
	});
	

</script>
<style>
.ui-menu { width: 150px;
}
</style>
<?php 
	
//later set variable to session
$admin='<u>'.$_SESSION['username'].'</u>';


echo '</head><body>';
echo '<div id="form">';
// echo '<h2>'.$wheat.$spacer.$logopath.$settings['company_name'].' | Property Manager '.'<div id="loggedin">'.$loggedin.' '.$admin.' | '.$clock.' '.$time.'</div>'.$endwheat.'</h2>' .'<hr/>'; 
// echo $wheat.'&nbsp;&nbsp;'.$endwheat;
echo '<div id="header">' . $spacer . $logopath . '<div id="loggedin">' . $loggedin . ' ' . $admin . ' | ' . $clock . ' ' . $time . '</div>';

$agentid = get_agent_id_from_username(trim(htmlspecialchars($_SESSION['username'])));
if (!$agentid) {
	header('Location: logout.php');
}

echo "</div>" . $wheat . '&nbsp;&nbsp;' . $endwheat;
echo  '<table id ="menulayout"><tr><td valign="top">';
echo $sidebar;
echo '</td><td>'. str_repeat("&nbsp;", 1).'</td><td>';

echo '<div id="tabs">
	<ul>
		<li><a href="#grid">'.$green.'Grid View'.$endgreen.'</a></li>
		<li><a href="#detailed">'.$red.'Detailed View'.$endred.'</a></li></ul>'
        . '<div id="grid">';
	include '../tables/editproperty.php';	
        
echo  '</div>';

echo    '<div id="detailed">';
include_once 'returnproperty.php';		
'</div>    



';


echo '</td></tr></table>';
echo '</div>';



echo '</body>';

?>