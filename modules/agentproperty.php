<?php
@session_start();

//include_once 'functions.php';
//$id=get_agent_id_from_username(trim(htmlspecialchars($_REQUEST['agentname'])));
//if(!$id){die('Invalid agent name,please try again');}
//echo '<select id="propertyid" name="propertyid">';
//foreach (get_agent_property($id) as  $value) {
//  $individualresult=explode('#', $value);
//  echo "<option value='$individualresult[0]' title='$individualresult[2]'>".$individualresult[1]."</option>";
//}
//echo '</select>';
if($_SESSION['propertyid']){
    unset($_SESSION['propertyid']); 
    $_SESSION['propertyid']=htmlspecialchars($_REQUEST['propertyid']);
}
//else{
//      $_SESSION['propertyid']=htmlspecialchars($_REQUEST['propertyid']);  
//}
