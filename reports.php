<?php
@session_start();
include '../views/display.php';
include 'functions.php';
include 'searchbyname.php';
$property=  getSettings();
echo  $htmlheaders;
echo '<head><title>'.$property['company_name'].'|Proper Properties</title>';
echo $meta;
echo '<link rel="stylesheet" type="text/css" media="screen" href="'.$baseurl.'css/overall2.css" />';
echo '<link rel="stylesheet" href="../themes/base/jquery.ui.all.css">';
echo '<link rel="stylesheet" href="../css/demos.css">';
echo '<link rel="stylesheet" href="../css/popupbox.css" type="text/css" />';
echo '<script type="text/javascript" src="../js/jquery-1.9.1.js"></script>';
echo '<script type="text/javascript" src="../js/jquery.cycle.all.js"></script>';
echo '<script type="text/javascript" src="../js/popup.js"></script>';
echo '<script src="../js/logout.js"></script>';
echo $jquery;
//later set variable to session
$admin='<u>'.$_SESSION['username'].'</u>';


?>

<script>
$(function() {
	$( "#menu" ).menu();
});
var propid;

//load checkboxes dynamically
$(document).ready(function() {
    $("#sorttenants").hide();//hide field for soting tenants
    $("#sortproperty").hide();//hide field for soting property
    
   $('#properties').change(function() { 
       propid=$("#properties").val();
     $.get("loadcheckbox.php?propid="+$("#properties").val(), function(data){ 
         if($('#docsavailable').empty()){
        $('#docsavailable').append(data); 
    }
    else{
        $('#docsavailable').replaceWith(data); 
        
    }
    });
 
       $( "#load" ).load( "searchbynamecall.php?propid="+$("#properties").val(), function() {
  //alert( "Load was performed." );
});  
    })    


//this function picks the selected values of a checkbox and places them into an array


$('#groupchecked').on('click', function(){
  var names="";
  
  $('input:checked').each(function() { names+=($(this).attr("value")+'$');});
  if (names===""){
   alert("Select property or documents for reporting!"); 
  }else{
    if($('#footer').val()==""){alert("Please insert footer");  }else{
       // alert(names);
        var path=$('#searchfield').val();
        if (path==""){path=0;}
       window.open("prepare_report.php?propid="+propid+"&footer="+$('#footer').val()+"&docstring="+names+"&path="+path);
       
    }
  }
});

$('#load').on("click","a[id='ejamaatlink']", function(event) {
   event.preventDefault();
   alert("photo "+$(this).attr("class")+" will be used in reports"); 
   $('#searchfield').val($(this).attr("title"));
    });
    
    //dialog box
    $( "#tenanttarget" ).click(function() {
  
        $("#sorttenants").show(); });
            
           
     $( "#closetenant" ).click(function() {
  
        $("#sorttenants").hide();});//close window on X
            
          
    
    $( "#propertytarget" ).click(function() {
  
        $("#sortproperty").show(); });
            
       	   $( "#closeproperty" ).click(function() {
  
        $("#sortproperty").hide(); }); //close window on X
	
   
    
  $( "#propertylist" ).click(function() {
    
       window.open("defaultreports.php?report=propertylist&id="+$("#propertysort").val()+"&sort="+$("#ascdescproperty").val())
		
	
    });
    
     $( "#tenantlist" ).click(function() {
    
       window.open("defaultreports.php?report=tenantlist&propertyid="+$("#propertyname2").val()+"&id="+$("#tenantsort").val()+"&sort="+$("#ascdesctenant").val())
		
	
    });

$("#unithistory").hide();
 $( "#unittarget" ).click(function() {
    $("#unithistory").show('body');
      	
    });
   $( "#closeunithistory" ).click(function() {
         	$("#unithistory").hide();
    });
    $("#propertyname3").change(function(e){
       $("#apartments").load("accountsprocess.php?apartmentid=true&propid="+$("#propertyname3 :selected").val(), function() {
});    
   });   
 $( "#unitlist" ).click(function() {
    
       window.open("defaultreports.php?report=unithistory&propertyid="+$("#propertyname3").val()+"&aptid="+$("#unitname").val()+"&sort="+$("#ascdescunit").val())
		
	
    });

});
</script>

<style>
.ui-menu { width: 150px;
}
</style>
<?php 
echo '</head><body>';?>
<div id="form">
<?php echo '<h2>'.$wheat.$spacer.$logopath.$property['company_name'].' | Property Manager <span style="color:black"><img src="../images/cursors/agent.png"> '.findpropertybyid($_SESSION['propertyid']).'</span><div id="loggedin">'.$loggedin.' '.$admin.' | '.$clock.' '.$time.'</div>'.$endwheat.'</h2>' .'<hr/>'; 
echo $wheat.'&nbsp;&nbsp;'.$endwheat;
//sort tenant report
echo '<div id="sorttenants"><center><h2><u> TENANT REPORT</u><a href="#" id="closetenant" style="float:right;font-size:24px;"><img src="../images/lightbox-btn-close.gif"></a></h2></center><br/><TABLE>';
echo '<tr><td><label><u><b>SELECT PROPERTY</b><u></label></td><td><select id="propertyname2" name="propertyname2"  style="width:305px;" class="input">';
echo '<option selected="selected" value="all">---</option>';    
echo   populateproperties();
    echo '</select></td></tr>';
    echo '<tr><td><label><u><b>SORT BY</b><u></label></td><td><select id="tenantsort" name="tenantsort"  style="width:305px;" class="input">';
    echo '<option selected="selected" value="">---</option>';
    echo searchparameterstenant();
    echo '</select></td></tr><br/>';
    echo '<tr><td><label><u><b>ASC/DESC</b><u></label></td><td><select id="ascdesctenant" name="ascdesctenant"  style="width:305px;" class="input">
        <option selected="selected" value="ASC">ASCENDING</option>
        <option selected="selected" value="DESC">DESCENDING</option>
</select>';
echo '<tr><td></td><td>&nbsp;&nbsp;&nbsp;<input type="button" id="tenantlist" value="GENERATE REPORT" class="input"></td>'. '</tr></TABLE></div>';
//sorting tenant report

//unit history report
echo '<div id="unithistory"><center><h2><u>UNIT HISTORY REPORT</u><a href="#" id="closeunithistory" style="float:right;font-size:24px;"><img src="../images/lightbox-btn-close.gif"></a></h2></center><br/><TABLE>';
echo '<tr><td><label><u><b>SELECT PROPERTY</b><u></label></td><td><select id="propertyname3" name="propertyname3"  style="width:305px;" class="input">';
echo '<option selected="selected" value="all">---</option>';    
echo   populateproperties();
    echo '</select></td></tr>';
    echo '<tr><td><label><u><b>HOUSE NO</b><u></label></td><td>';
      echo '<div id="apartments"></div>';
    echo '</td></tr><br/>';
    echo '<tr><td><label><u><b>ASC/DESC</b><u></label></td><td><select id="ascdescunit" name="ascdescunit"  style="width:305px;" class="input">
        <option selected="selected" value="ASC">ASCENDING</option>
        <option selected="selected" value="DESC">DESCENDING</option>
</select>';
echo '<tr><td></td><td>&nbsp;&nbsp;&nbsp;<input type="button" id="unitlist" value="GENERATE REPORT" class="input"></td>'. '</tr></TABLE></div>';
//unit history
//sort propertyreport
echo '<div id="sortproperty"><center><h2><u>PROPERTY REPORT</u><a href="#" id="closeproperty" style="float:right;font-size:24px;"><img src="../images/lightbox-btn-close.gif"> </a></h2></center><br/><TABLE>';

   echo '<tr><td><label><u><b>SORT BY</b></u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label></td><td><select id="propertysort" name="propertysort"  style="width:305px;" class="input">';
    echo searchparametersproperty();
    echo '</select></td></tr><br/>';
    echo '<tr><td><label><u><b>ASC/DESC</b></u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label></td><td><select id="ascdescproperty" name="ascdesproperty"  style="width:305px;" class="input">
        <option selected="selected" value="ASC">ASCENDING</option>
        <option selected="selected" value="DESC">DESCENDING</option>
</select>';
echo '<tr><td></td><td>&nbsp;&nbsp;&nbsp;<input type="button" id="propertylist" value="GENERATE REPORT" class="input"></td>'. '</tr></TABLE></div>';
//sorting property report

 echo '<table id ="menulayout"><tr><td valign="top">';
echo $sidebar;
echo '</td><td>&nbsp;&nbsp;</td><td>';
?>
    <fieldset class="fieldsetreports"><legend>Reports</legend>
    <?php

echo '<div class="popup-box" id="popup-box-1"><div class="close">X</div><center><div class="top"><h5>Documents On Property Report</h5></div></center>
<div class="bottom">';
echo '<table id="preport">';
echo "<b> <font color='orange'>SELECT PROPERTY FOR REPORT :</b></font><select id='properties' name='propertyname' class='input'>";
echo '<option selected="selected" value="">---</option> ';  
echo populateproperties();
echo "</select>";
echo '<hr/>';
echo ' DOCUMENTS TO INCLUDE: | FOOTER:<input type="text" id="footer" class="input"/><BR/>';
echo '<div id="docsavailable">';
echo '</div>';
echo '<B><font color=\'orange\'>SELECT PHOTO TO APPEAR ON REPORT</font></B>';
echo '<div id="load"></div>';
echo '<center><input id="searchfield" type="hidden" /></center>';
echo '<br/>';
echo '<center><input type="button" id="groupchecked" value="GENERATE REPORT" class="input"></center></b> </table></div>';
echo '</div>';//property  report
?>
    <table cellspacing="10" style="height:400px;"><tr><td><a href="#" class="popup-link-1"><img src="../images/Report.png"/><br><b>Property Report</b></a></td>    
<td><a href="#" id="tenanttarget"><img src="../images/Report.png"/><br><b>Tenants list Report</b></a></td>
<td><a href="#" id="propertytarget"><img src="../images/Report.png"/><br><b>Propertylist Report</b></a></td>
<td><a href="#" id="unittarget"><img src="../images/Report.png"/><br><b>Unit History</b></a></td>
<td><a href="defaultreports.php?report=vacancy" id="unittarget"><img src="../images/Report.png"/><br><b>Vacancy Report</b></a></td>
</tr></table>
</fieldset></div>
</body>

