<?php
//ini_set('display_errors', '1');
//ini_set('display_startup_errors', '1');
//error_reporting(E_ALL);
       include 'includes/database.php';
       include 'modules/clientip.php';
       
$db=new MySQLDatabase();
$db->open_connection();

//die($_POST["username"]);
date_default_timezone_set('Africa/Nairobi'); 
$timenow=date("Y-m-d H:m:s");
//$timenow=time();
$ip=@get_client_ip();
session_start();

//mail("krufed@gmail.com","property management system login","login from ip ".$ip);
		@$username = mysql_real_escape_string( $_POST["username"] );
		@$password = mysql_real_escape_string( $_POST["password"] );
                @$propertyid = mysql_real_escape_string( $_POST["propertyid"] );
                
		if( empty($username) || empty($password) )
			echo "<center>Username and Password Mandatory - from server</center>";
		else
		{
		$sql = "SELECT count(*) FROM accesslevels WHERE( username='$username' AND password='$password')";
             
	    $res = mysql_query($sql);
            while ($row = mysql_fetch_array($res)) {
                $id=$row[0];
          // if($_POST["username"]=='admin'){$propertyid='2';}
		if( $row[0] > 0 ){
                
                 $query1=mysql_query("INSERT into loginhistory (`username`,`password`,`logintime`,`loginip`) VALUES('$username','$password','$timenow','$ip')") or print "Database Error: ".mysql_error();
		    $sql1 = "SELECT * FROM accesslevels  WHERE(`username`='$username' AND `password`='$password')";
               $res2 = mysql_query($sql1);               
               while ($row1 = mysql_fetch_array($res2)) {
                   $accessgroup=$row1['group'];$accessgroupid=$row1['accessgrpid'];
               }
               $_SESSION['usergroup']=$accessgroup; //set session user
                $_SESSION['username']=$username; 
                 $_SESSION['userid']=$accessgroupid; 
                $_SESSION['propertyid']=$propertyid; //set propertyid on login
                 echo "<center><font color=white> Login Successful</font></center>";
                echo '<script type=\'text/javascript\'>window.location=\'home.php\';</script>';
                 }
		else
		 echo "<center><font color=red>Wrong Username/Password</font></center>";
   		} }		
 
                ?>

