<?php


include '../includes/database.php';
$db=new MySQLDatabase();
    $db->open_connection();
    
   $propid=$_REQUEST['propid'];
   $docid=$_REQUEST['docid'];
     
    
    
    $sql = mysql_query("SELECT * FROM propertydocs WHERE propertyid='$propid' AND document_id='$docid' ")or die (mysql_error());
    
$numrows = mysql_num_rows ($sql);
if ($numrows!=0)
{
while ($row = mysql_fetch_array($sql)){
 
    $document=$row['propdoc'];
    $documentno=$row['propdocno'];
    $issuedate=$row['propdocissuedate'];
    $issuer=$row['propdocissuer'];
    $descrip=$row['propdescr'];
    $path=$row['propdocpath'];
    
    $json = array(
              array('field' => 'propertydocstype','value' => $document), 
              array('field' => 'docno1','value' => $documentno),
              array('field' => 'issuedate1','value' => $issuedate),
              array('field' => 'issueofficer1','value' => $issuer),
              array('field' => 'descr','value' => $descrip),
              array('field' => 'propertydoc1','value' => $path),
         );     
       
    }
  }
//fill up array with null values
else{
$json = array(
              
              array('field' => 'docno1','value' => ''),
              array('field' => 'issuedate1','value' => ''),
              array('field' => 'issueofficer1','value' => ''),
              array('field' => 'descr','value' => ''),
              array('field' => 'propertydoc1','value' => ''), );
}
    echo json_encode($json );
    $db->close_connection();



?>



