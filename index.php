

<?php error_reporting(E_ERROR | E_WARNING | E_PARSE);?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
<?php
include 'views/display.php';
include  'includes/database.php';
//die('document');
include './modules/functions.php';
echo  $htmlheaders;
$property=getSettings();

echo '<head><title>Property Manager|'.$property['company_name'].'</title>';


?>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	
	<link rel="stylesheet" type="text/css" href="css/form.css" media="screen" />
	<link rel="stylesheet" href="css/login.css" type="text/css" />
	<!--check for form response-->
       <script src="js/jquery-1.9.1.js"></script>
    <script type='text/javascript'>
    
   
    function userSubmit1(){
    	var result=document.getElementById('username').value;
    	var result1=document.getElementById('password').value;
    	var element=document.getElementById('error');
    	if (result=='' || result1==''){
    		 element.HTML='<P><font color=white>Enter valid username/password</font>';
    		 element.style.backgroundColor='#800';
    	    	element.style.color='white';
    	    	element.style.textAlign='center';
    	}
    	else{
    	element.HTML='<P><font color=white>Confirm username: '+result+'</font>';
    	element.style.backgroundColor='#800';
    	element.style.color='white';
    	element.style.textAlign='center';
    	}
    	}
        $(document).ready(function() {
	
//	$('#username').click(function() {
//            if($('#username').val()!==""){
//            alert('loading properties...');
//          //$("#propertyselect").html('loading field...');
//        $("#propertyselect").load("./modules/agentproperty.php?agentname="+$("#username").val(), function(data) {
//	});}
//});
});
        </script>




</head>
<body>
<div id="form">
<form id="myForm" action="login.php" method="POST">
			<center>
<h2><?php echo  $wheat . '<img src="' . $baseurl . 'images/cursors/logo.jpeg' . '" width="200" height="auto"></img>';?></h2><hr/></br>
<img src="images/cursors/login.png" /></img></center></br>
	<!--<fieldset>-->
	<!--	<label>Company Code: </label><input  type="text" name="code" class="effect" id="code"/>-->
 <!--       </fieldset>  -->
	<fieldset>
		<label>User Name: </label><input onKeyUp="userSubmit1()" type="text" name="username" class="effect" id="username"/>
        </fieldset>  
        
<!--<fieldset style="color:grey;font-weight:bold;"><label>Property&nbsp;</label>
<span  id="propertyselect" ></span></fieldset>-->
	<fieldset> 
		<label>Password: </label><input onKeyUp="userSubmit1()"  type="password" name="password" class="effect" id="password"/>
	</fieldset>

	<center><BR/>
             <button id="submit">Login</button><BR>
                 <span id="error" style="color:orangered"> </span>

 

<script type="text/javascript" src="js/my_script.js"></script>
		</center>
	
</form>
    </div>
    <div id="ack"></div>



</body>
</html>
