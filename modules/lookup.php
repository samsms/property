<?php
include '../includes/database.php';
$db=new MySQLDatabase();
    $db->open_connection();
    
    $propid=$_REQUEST['propid'];
  $docid=$_REQUEST['docid'];
   if (empty($docid)){
   	$sql = mysql_query("SELECT * FROM legaldocs WHERE propertyid='$propid'")or die (mysql_error());
   }
   else    
    
    $sql = mysql_query("SELECT * FROM legaldocs WHERE propertyid='$propid' AND document_id='$docid'")or die (mysql_error());
    
$numrows = mysql_num_rows ($sql);
if ($numrows!=0)
{
while ($row = mysql_fetch_array($sql)){
    $propertyid=$row['propertyid'];
    $document=$row['doc'];
    $documentno=$row['docno'];
    $issuedate=$row['issuedate'];
    $issuer=$row['issuer'];
    $descrip=$row['descr'];
    $path=$row['path'];
    
    $json = array(
                array('field' => 'propertyid','value' => $propertyid),
              array('field' => 'legaldocsstype','value' => $document), 
              array('field' => 'docno','value' => $documentno),
              array('field' => 'issuedate','value' => $issuedate),
              array('field' => 'issueofficer','value' => $issuer),
              array('field' => 'desc1','value' => $descrip),
              array('field' => 'legaldoc','value' => $path),
         );     
       
    }
  }
//fill up array with null values
else{
$json = array(
			 // array('field' => 'propertyid','value' => ''),
              array('field' => 'docno','value' => ''),
              array('field' => 'issuedate','value' => ''),
              array('field' => 'issueofficer','value' => ''),
              array('field' => 'desc1','value' => ''),
              array('field' => 'legaldoc','value' => ''), );
}
    echo json_encode($json );
    $db->close_connection();



?>

