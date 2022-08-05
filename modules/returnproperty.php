<style type="text/css">

@import url("../css/paginate.css");

</style>
<?php
require_once("../includes/database.php");
$db=new MySQLDatabase();
$db->open_connection();

//////////////  QUERY THE MEMBER DATA INITIALLY 
$sql="SELECT properties.propertyid,properties.property_name, properties.category,properties.tm,properties.numfloors,properties.mohalla,pictorials.path FROM properties LEFT JOIN pictorials ON properties.propertyid=pictorials.propertyid  order by propertyid";
    
$query=mysql_query($sql)or die(mysql_error());
  
//////////////////////////////////// Pagination Logic ////////////////////////////////////////////////////////////////////////

  $nr = mysql_num_rows( $query);  // Get total of Num rows from the database query
if (isset($_GET['pn'])) { // Get pn from URL vars if it is present
    $pn = preg_replace('#[^0-9]#i', '', $_GET['pn']); // filter everything but numbers for security(new)
    //$pn = ereg_replace("[^0-9]", "", $_GET['pn']); // filter everything but numbers for security(deprecated)
} else { // If the pn URL variable is not present force it to be value of page number 1
    $pn = 1;
}
//This is where we set how many database items to show on each page
$itemsPerPage = 6;
// Get the value of the last page in the pagination result set
$lastPage = ceil($nr / $itemsPerPage);
// Be sure URL variable $pn(page number) is no lower than page 1 and no higher than $lastpage
if ($pn < 1) { // If it is less than 1
    $pn = 1; // force if to be 1
} else if ($pn > $lastPage) { // if it is greater than $lastpage
    $pn = $lastPage; // force it to be $lastpage's value
}
// This creates the numbers to click in between the next and back buttons
// This section is explained well in the video that accompanies this script
$centerPages = "";
$sub1 = $pn - 1;
$sub2 = $pn - 2;
$add1 = $pn + 1;
$add2 = $pn + 2;
if ($pn == 1) {
    $centerPages .= '&nbsp; <span class="pagNumActive">' . $pn . '</span> &nbsp;';
    $centerPages .= '&nbsp; <a href="' . $_SERVER['PHP_SELF'] . '?pn=' . $add1 . '#detailed">' . $add1 . '</a> &nbsp;';
} else if ($pn == $lastPage) {
    $centerPages .= '&nbsp; <a href="' . $_SERVER['PHP_SELF'] . '?pn=' . $sub1 . '#detailed">' . $sub1 . '</a> &nbsp;';
    $centerPages .= '&nbsp; <span class="pagNumActive">' . $pn . '</span> &nbsp;';
} else if ($pn > 2 && $pn < ($lastPage - 1)) {
    $centerPages .= '&nbsp; <a href="' . $_SERVER['PHP_SELF'] . '?pn=' . $sub2 . '#detailed">' . $sub2 . '</a> &nbsp;';
    $centerPages .= '&nbsp; <a href="' . $_SERVER['PHP_SELF'] . '?pn=' . $sub1 . '#detailed">' . $sub1 . '</a> &nbsp;';
    $centerPages .= '&nbsp; <span class="pagNumActive">' . $pn . '</span> &nbsp;';
    $centerPages .= '&nbsp; <a href="' . $_SERVER['PHP_SELF'] . '?pn=' . $add1 . '#detailed">' . $add1 . '</a> &nbsp;';
    $centerPages .= '&nbsp; <a href="' . $_SERVER['PHP_SELF'] . '?pn=' . $add2 . '#detailed">' . $add2 . '</a> &nbsp;';
} else if ($pn > 1 && $pn < $lastPage) {
    $centerPages .= '&nbsp; <a href="' . $_SERVER['PHP_SELF'] . '?pn=' . $sub1 . '#detailed">' . $sub1 . '</a> &nbsp;';
    $centerPages .= '&nbsp; <span class="pagNumActive">' . $pn . '</span> &nbsp;';
    $centerPages .= '&nbsp; <a href="' . $_SERVER['PHP_SELF'] . '?pn=' . $add1 . '#detailed">' . $add1 . '</a> &nbsp;';
}
// This line sets the "LIMIT" range... the 2 values we place to choose a range of rows from database in our query
$limit = 'LIMIT ' .($pn - 1) * $itemsPerPage .',' .$itemsPerPage;
// Now we are going to run the same query as above but this time add $limit onto the end of the SQL syntax
// $query2 is what we will use to fuel our while loop statement below

$query2 = mysql_query("$sql $limit");
//////////////////////////////// Pagination Logic ////////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////  Pagination Display Setup /////////////////////////////////////////////////////////////////////
$paginationDisplay = ""; // Initialize the pagination output variable
// This code runs only if the last page variable is ot equal to 1, if it is only 1 page we require no paginated links to display
if ($lastPage != "1"){
    // This shows the user what page they are on, and the total number of pages
    $paginationDisplay .= 'Page <strong>' . $pn . '</strong> of ' . $lastPage. '&nbsp;  &nbsp;  &nbsp; ';
    // If we are not on page 1 we can place the Back button
    if ($pn != 1 && $pn>0) {
        $previous = $pn - 1;
        $paginationDisplay .=  '&nbsp;  <a href="' . $_SERVER['PHP_SELF'] . '?pn=' . $previous . '#detailed"> Back</a> ';
    }
    // Lay in the clickable numbers display here between the Back and Next links
    $paginationDisplay .= '<span class="paginationNumbers">' . $centerPages . '</span>';
    // If we are not on the very last page we can place the Next button
    if ($pn != $lastPage) {
        $nextPage = $pn + 1;
        $paginationDisplay .=  '&nbsp;  <a href="' . $_SERVER['PHP_SELF'] . '?pn=' . $nextPage . '#detailed"> Next</a> ';
    }
}
///////////////////////////////////// END  Pagination Display Setup ///////////////////////////////////////////////////////////////////////////>   
$outputlist=array();
while ($row =@mysql_fetch_array($query2)) {
    $resultid=$row['propertyid'];$resultname=$row['property_name'];$resultcategory=$row['category'];  $resultpath=$row['path']; $resulttm=$row['tm']; $resultfloors=$row['numfloors'];$resultlocation=$row['mohalla'];
    
    $floors='&nbsp;&nbsp;'.'<img src="../images/cursors/floors.png "/>'.'&nbsp;'.$resultfloors.'';
    
   $propertydetails ="propertydetails.php?name=$resultname&mohalla=$resultlocation";

    $pic_path='<div id="photo"><img src="'.$resultpath.'"width="150" height="120" /> </div>';
    $newoutput='<a href="' .  $propertydetails. '&propid=' . $resultid . '">' . $pic_path . '</a> ';
		    
    
    array_push($outputlist,'<td><div id="details" >'.$newoutput ."<h2>&nbsp;&nbsp;$resultname</h2><h3>&nbsp;&nbsp;".'<div style="font-weight:bold; color:black">&nbsp;'.$resultcategory. " in ". $resultlocation .'<div style="font-weight:bold; color:grey">'.$floors.'</h3>&nbsp;&nbsp;'.$resulttm.'</div></td>');

    
    }
$db->close_connection();
    
 // close while loop


//endforeach; 





     echo '<div id="total">';
     echo '<h3>'.$nr.' property(s) found</h3>';
     echo '</div>';
     echo '<div id="new">Page: '.$pn .'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$paginationDisplay.'</div>';
     echo '<table  width="1000"><tr>';
     //arrange records in rows of 2
          for($i=0;$i<count($outputlist);$i++)
     if($i%2!=0){
         
     echo $outputlist[$i];
     }
    else {
     echo '<tr>'.$outputlist[$i];
         
     }
     echo '</table>';
     echo '<div id="new1">Page: '.$pn .'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$paginationDisplay.'</div>';

?>