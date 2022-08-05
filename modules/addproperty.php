<?php
include '../views/display.php';
include 'inputforms.php';
$property=  getSettings();
$baseurl=$baseurl;
echo  $htmlheaders;
echo '<head><title>Property Manager|'.$property['company_name'].'</title>';
echo $meta;
echo $overallcss;
echo $jquery;
//later set variable to session
$admin='<u>'.$_SESSION['username'].'</u>';


?>

<script>
$(function() {
	$( "#menu" ).menu();
});


</script>
<script src="../js/logout.js"></script>
<style>
.ui-menu { width: 150px;}
</style>
<?php 

echo '</head><body>';
echo '<div id="form">';
// echo '<h2>'.$wheat.$spacer.$logopath.$property['company_name'].' | Property Manager<div id="loggedin">'.$loggedin.' '.$admin.' | '.$clock.' '.$time.'</div>'.$endwheat.'</h2>' .'<hr/>'; 
echo '<div id="header">' . $spacer . $logopath . '<div id="loggedin">' . $loggedin . ' ' . $admin . ' | ' . $clock . ' ' . $time . '</div>';

$agentid = get_agent_id_from_username(trim(htmlspecialchars($_SESSION['username'])));
if (!$agentid) {
	header('Location: logout.php');
}
echo '</div>';
echo '<table id ="menulayout"><tr><td valign="top">';
echo $sidebar;
echo '</td><td>';
echo addproperty();//inputforms
echo '</td></tr></table></div>';
echo '</body>';
?>
