<?php
@session_start();
if(!@$_REQUEST['propertyid']){
        $_SESSION['propertyid']= @$_SESSION['propertyid'];
}
 else {
  $_SESSION['propertyid']=@$_REQUEST['propertyid'];   
}


//text colors
$green='<div style="font-weight:bold; color:green">';
$endgreen='</div>';
$red='<div style="font-weight:bold; color:red">';
$endred='</div>';
$purple='<div style="font-weight:bold; color:purple">';
$endpurple='</div>';
$wheat='<div style="font-weight:bold; color:#f6c01d">';
$endwheat='</div>';
$blue='<div style="font-weight:bold; color:blue">';
$endblue='</div>';

$inputvalues='<div style="font-weight:bold; color:#c6c6c6>';
$endinput='</div>';

//application base /root folder
if($_SERVER['REMOTE_ADDR']=="127.0.0.1"){

$baseurl='/property-rivercourt/';}
else{
	$baseurl="/";
}
$htmlheaders='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">';
$meta='<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';


$jquery='<link rel="stylesheet" href="'.$baseurl.'themes/base/jquery.ui.all.css">
<script src="'.$baseurl.'js/jquery-1.9.1.js"></script>
<script src="'.$baseurl.'js/ui/jquery.ui.core.js"></script>
   <script src="'.$baseurl.'/js/jquery.ui.sortable.js" type="text/javascript"></script>;
<script src="'.$baseurl.'js/jquery.dataTables.min.js" type="text/javascript"></script>;
<script src="'.$baseurl.'js/ui/jquery.ui.widget.js"></script>
<script src="'.$baseurl.'js/ui/jquery.ui.position.js"></script>
<script src="'.$baseurl.'js/ui/jquery.ui.tabs.js"></script>
<script src="'.$baseurl.'js/ui/jquery.ui.menu.js"></script>
<link rel="stylesheet" href="'.$baseurl.'css/demos.css">
';
//for use in inputforms
$inputformcss='<link rel="stylesheet" type="text/css" media="screen" href="'.$baseurl.'css/jquery-ui-git.css" />';



$overallcss='<link rel="stylesheet" href="'.$baseurl.'css/overall.css" type="text/css" />';
 //logo
$logopath='<img src="'.$baseurl.'images/cursors/logo1.png'.'" width="200" height="auto"></img>';
$hr='<img src="'.$baseurl.'images/cursors/hr.png'.'"></img>';
//html spacer
$spacer='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';

//js loader
$loader='$(window).load(function(){
  $(\'#dvLoading\').fadeOut(2000);
});';
//icons
$home='<img src="'.$baseurl.'images/cursors/home.png'.'"></img>';
$addnew='<img src="'.$baseurl.'images/cursors/add.png'.'"></img>';
$edit='<img src="'.$baseurl.'images/cursors/edit.png'.'"></img>';
$list='<img src="'.$baseurl.'images/cursors/list.png'.'"></img>';
$report='<img src="'.$baseurl.'images/cursors/report.png'.'"></img>';
$tenants='<img src="'.$baseurl.'images/cursors/tenant_portal.png'.'"></img>';
$accounts='<img src="'.$baseurl.'images/cursors/accounts.png'.'"></img>';
$repairs='<img src="'.$baseurl.'images/cursors/repair.png'.'"></img>';
$close='<img src="'.$baseurl.'images/cursors/repair.png'.'"></img>';
$loggedin='<img src="'.$baseurl.'images/cursors/loggedin.png'.'"></img>';
$logout='<img src="'.$baseurl.'images/cursors/logout.png'.'"></img>';
$clock='<img src="'.$baseurl.'images/cursors/clock.png'.'"></img>'; 

//left menu
if($_SESSION['usergroup'] !=3){
$sidebar='<div style="border:1px solid #ccc;border-radius:10px;padding:10px">
	<a class="nodecoration" href="'.$baseurl.'modules/list.php">'.$list.'<span>&nbsp;&nbsp;</span>List Properties</a><hr/>


	<a class="nodecoration" href="'.$baseurl.'modules/addproperty.php">'.$addnew.'<span>&nbsp;&nbsp;</span>Add new</a><hr/>
	<a class="nodecoration" href="'.$baseurl.'modules/updatedata.php">'.$edit.'<span>&nbsp;&nbsp;</span>Update data</a><hr/>
	<a class="nodecoration" href="'.$baseurl.'modules/reports.php">'.$report.'<span>&nbsp;&nbsp;</span>Reports</a><hr/>
	  <a class="nodecoration" href="'.$baseurl.'loan">'.$tenants.'<span>&nbsp;&nbsp;</span>Loans</a><hr/>
        <a class="nodecoration" href="'.$baseurl.'views/tenantlist.php">'.$tenants.'<span>&nbsp;&nbsp;</span>Tenant Portal</a><hr/>
		<a class="nodecoration" href="'.$baseurl.'modules/feedbacks.php">'.$list.'<span>&nbsp;&nbsp;</span>Feeds </a><hr/>
		<a class="nodecoration" href="'.$baseurl.'modules/tenantlist.php">'.$list.'<span>&nbsp;&nbsp;</span>Pendding tenants </a><hr/>
	
            <a class="nodecoration" href="'.$baseurl.'views/template.php?page=repairs">'.$repairs.'<span>&nbsp;&nbsp;</span>Ledgers/Settings</a><hr/>
			<a class="nodecoration" href="'.$baseurl.'views/template.php?page=closeperiods">'.$close.'<span>&nbsp;&nbsp;</span>Close Period/Settings</a><hr/>

	<a class="nodecoration" href="'.$baseurl.'views/accounts.php">'.$accounts.'<span>&nbsp;&nbsp;</span>Accounts...</a><hr/>
            	<a class="nodecoration" href="'.$baseurl.'logout.php">'.$logout.'<span>&nbsp;&nbsp;</span>Logout</a><hr/>
            <a class="nodecoration" href="'.$baseurl.'home.php?propertyid='.$_SESSION['propertyid'].'">'.$home.'<span>&nbsp;</span>`</a>
	

</div>';
} else{
    $sidebar='<div style="border:1px solid #ccc;border-radius:10px;padding:10px">
	<a class="nodecoration" href="'.$baseurl.'modules/reports.php">'.$report.'<span>&nbsp;&nbsp;</span>Reports</a><hr/>
        <a class="nodecoration" href="'.$baseurl.'views/tenantlist.php">'.$tenants.'<span>&nbsp;&nbsp;</span>Tenant Portal</a><hr/>
           <a class="nodecoration" href="'.$baseurl.'views/tenantlist.php">'.$tenants.'<span>&nbsp;&nbsp;</span>Loans</a><hr/>
	<a class="nodecoration" href="'.$baseurl.'views/accounts.php">'.$accounts.'<span>&nbsp;&nbsp;</span>Accounts...</a><hr/>
            	<a class="nodecoration" href="'.$baseurl.'logout.php">'.$logout.'<span>&nbsp;&nbsp;</span>Logout</a><hr/>
            <a class="nodecoration" href="'.$baseurl.'home.php?propertyid='.$_SESSION['propertyid'].'">'.$home.'<span>&nbsp;</span>`</a>
	

</div>';
}
date_default_timezone_set('Africa/Nairobi'); //time
$currentyear=date("Y");//year
$time = date('h:i A');


$inputcss='<style type="text/css">


body { font-size: 62.5%; }
label { display: inline-block; width: 100px; }
legend { padding: 0.5em; }
fieldset fieldset label { display: block; }
#commentForm { width: 800px; margin-left:250px; margin-top:-400px }
#commentForm label { width: 150px; }
#commentForm label.error, #commentForm button.submit { margin-left: 253px; }
#signupForm { width: 670px; }
#signupForm label.error {
	margin-left: 10px;
	width: auto;
	display: inline;
}
#newsletter_topics label.error {
	display: none;
	margin-left: 103px;
}
</style>';

$updatecss='<style type="text/css">

label { display: inline-block; width: 100px; }
legend { padding: 0.5em; }
fieldset fieldset label { display: block; }
#commentForm { width: 600px;  margin:-400px auto 0 auto;}

#commentForm label { width: 250px; }
#commentForm label.error, #commentForm button.submit { margin-left: 253px; }
#signupForm { width: 670px; }
#signupForm label.error {
	margin-left: 10px;
	width: auto;
	display: inline;
}
#newsletter_topics label.error {
	display: none;
	margin-left: 103px;
}
</style><style type="text/css">
body { font-size: 62.5%; }
label { display: inline-block; width: 100px; }
legend { padding: 0.5em; }
fieldset fieldset label { display: block; }
#commentForm { width: 900px; margin-left:30px; margin-top:-70px; }
#commentForm label { width: auto; }
#commentForm label.error, #commentForm button.submit { margin-left: 253px; }
#signupForm { width: 670px; }
#signupForm label.error {
	margin-left: 10px;
	width: auto;
	display: inline;
}
#newsletter_topics label.error {
	display: none;
	margin-left: 103px;
}
#buttons {
border:1px solid #ccc;
-moz-border-radius: 15px;
border-radius: 15px;
background: #f6efef;
height:43px;
}


   
</style>';

