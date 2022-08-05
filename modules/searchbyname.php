<?php

/*multiple search fields
 * @author:fred kairu
 */

Class SearchbynameClass {
 
 public $dbconnect;
 public $tablename='pictorials';
 public $path,$id;
 public $variable;
 private $selectname='id';
 private $selectname1='path';
 
         
public function searchbyname(){
 include '../includes/config.php';

//@$this->variable=$_REQUEST['q'];
$this->trimmed = mysql_real_escape_string(trim($this->variable)); 
//check for an empty string and display a message.
if($this->trimmed== "" || empty($this->trimmed)) {
    echo '<p>Enter search name.</p>';
    exit;
}
//check for a search parameter
if(!isset($this->variable)){
echo "<p>No search parameter!</p>";
    exit;
}

$dbhandle = mysql_connect(DB_SERVER,DB_USER, DB_PASS) or die("Unable to connect to MySQL");
$selected = mysql_select_db("propdb",$dbhandle)  or die("Could not select db");
//Build SQL Query
$query = "SELECT $this->selectname,$this->selectname1 FROM $this->tablename WHERE propertyid LIKE '%$this->trimmed%' ORDER BY id";
$numresults = mysql_query($query);$numrows = mysql_num_rows($numresults);
if ($numrows==0){
    //echo"<h3>Results</h3>";
    //echo"<p>Sorry, your search: &quot;" .$this->trimmed . "&quot; returned zero results</p>";
    }
$result = mysql_query($query) or die("Couldn't execute query");
//display what was searched for
//echo"<p>Searching for: &quot;<b>" .$this->variable . "</b>&quot;</p>";

while ($row = mysql_fetch_array($result)) {
$this->path = $row['path'];$this->id=$row['id'];
if (file_exists($this->path)) {

echo  '<a id="ejamaatlink" class="'.$this->id.'" href="#" title="'.$this->path.'" ><img src="'.$this->path.'" style="height:50px;width=50px;"/>&nbsp;&nbsp;</a>';
}

}
//echo "<br>";
//echo '<p>Showing '.$numrows.' results</p>';

} }

?>
