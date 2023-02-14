<?php
//add new property
require 'functions.php';
function addproperty(){
   
    ?>

<script type="text/javascript" src="../js/jquery.tools.min.js"></script>
<script type="text/javascript" src="../js/jquery-ui.min.js"></script>
<script src="../js/jquery-1.9.1.js"></script>;

<link rel="stylesheet" type="text/css" media="screen" href="../css/jquery-ui-git.css" />
<link rel="stylesheet" type="text/css" media="screen" href="../css/inputcss.css" />


<script src="../js/jquery.validate.js" type="text/javascript"></script>
<script src="../js/jquery-ui-git.js"></script>

<script type="text/javascript">
$(document).ready(function() {

$('#commercial').show() && $('#noncomm').hide()&& $('#undeveloped').hide();

$('#proptype').change(function() {
if ($(this).val() == "commercial")
  $('#commercial').show()&& $('#noncomm').hide()&& $('#undeveloped').hide()&& $('#floors').show(); 
    else if($(this).val() == "non_commercial")
        $('#noncomm').show() && $('#commercial').hide()&& $('#undeveloped').hide()&& $('#floors').show();
    else if($(this).val() == "undeveloped")
    $('#undeveloped').show() && $('#noncomm').hide()&& $('#commercial').hide()&& $('#floors').hide();

else
$('#commercial').show() && $('#noncomm').hide()&& $('#undeveloped').hide();

});
});

$(document).ready(function() {

    $('#desc').hide()




$('#condition').change(function() {
if ($(this).val() == "Good")
  $('#desc').hide();
    else if($(this).val() == "Require_renovation"){
        $('#desc').show(); 
    }
    else if($(this).val() == "Require_redevelopment"){
    $('#desc').show();
    }
else
  $('#desc').hide();

});

$('#watercharge').keyup(function () {     
this.value = this.value.replace(/[^0-9\.]/g,'');
});//allow numeric values only

$('#agentcommission').keyup(function () {     
this.value = this.value.replace(/[^0-9\.]/g,'');
});//allow numeric values only
    
   
});

/*$(document).ready(function() {

$('#submit').click(function() {
// whatever code you want to run when submit is clicked.
alert('Property details Saved!');
})

 });*/

</script>


<script>
    //autofill input fields for squaremeters and sqft
function doMath() {
    var acres = parseFloat(document.getElementById('acres').value);
    var metresq=(acres/0.00024711) ;
    var sqft = 43560*acres;
    
    document.getElementById('sqmetres').value = metresq;
    document.getElementById('sqft').value =sqft;
    }
</script>

 <script>

     //populate data for autocomplete
$(function() {

var availableTags=<?php echo populatejsArray(); ?>;
$( "#pname" ).autocomplete({
source: availableTags
});
});

$(function() {

var Tags=<?php echo populatemohalla(); ?>;
$( "#mohalla" ).autocomplete({
source: Tags
});
});


</script>

<?php
include '../views/display.php';
$settings=  getSettings();



//add property form
echo '<form class="cmxform" id="commentForm" method="POST" action="add.php">';
//initialize variables for category1,2 and 3 --for use during js OnChange method
$noncommercial='<div id="noncomm"><select id="noncomm" name="category1" class="ui-widget-content" required type="select" >
            <option value>---</option>      
            <option value="Masjid">Masjid</option>
            <option value="Qasar Mubarak">Qasar Mubarak</option>
            <option value="Qasar Imarat">Qasar Imarat</option>
            <option value="Faiz mawaid">Faiz mawaid</option>
            <option value="Markaz">Markaz</option>
             <option value="Darul Imarat">Darul Imarat</option>
              <option value="Maskan lil Masool">Maskan lil Masool</option>
               <option value="Moallim Makaan">Moallim Makaan</option>
                <option value="Qabrastan">Qabrastan</option>
                <option value="Multi-purpose hall">Multi-purpose hall</option>
            <option value="Madrasa & School">Madrasa & School</option>
             <option value="Jamaat khana">Jamaat khana</option>
            <option value="Hall">Hall</option>
            <option value="others">Others</option>
          </select> </div>';
$commercial='<div id="commercial"><select id="commercial" name="category2" class="ui-widget-content" required type="select" >
             <option value>---</option>
            <option  selected value="Flats and shops">Flats and shops</option>
            <option value="shops and offices">Shops and Offices</option>
            <option value="Upliftment flats">Upliftment flats</option>
            <option value="Apartments">Apartments</option>
            <option value="Houses">Houses</option>
            <option value="school">School</option>
            <option value="Hospital /clinic">Hospital /clinic</option>
              <option value="shopping mall">Shopping mall</option>
              <option value="others">Others</option>
          </select></div>' ;

$undeveloped='<div id="undeveloped"> <select id="undeveloped" name="category3" class="ui-widget-content" required type="select" >
            <option value>---</option>
            <option value="Agricultural land">Agricultural land</option>
            <option value="Forest land">Forest land</option>
            <option value="Open land">Open land</option>
             <option value="Squatters">Squatters</option>
              <option value="others">Others</option>
          </select> </div>';



echo '
    <fieldset class="ui-widget ui-widget-content ui-corner-all">
            <legend class="ui-widget ui-widget-header ui-corner-all">BASIC PROPERTY INFORMATION</legend>'.
    '<table>
<tr>
    <td>
                    <label for="ptype">Property Type </label>
                    <select id="proptype" name="proptype" class="ui-widget-content" required type="select" >
                  <option value></option>
            <option selected value="commercial">Commercial</option>
            <option value="non_commercial">Non-commercial</option>
            <option value="undeveloped">Undeveloped-Squatters</option>   
            </select>
            '.$hr.'

           <label for="category">Category </label>'.$commercial.$noncommercial.$undeveloped.'


'.$hr.'

<label for="pname">Prop.Name(not listed)</label>
                    <input id="pname" name="pname" class="ui-widget-content" type="text" required />
'.$hr.'

<label for="buyername">Buyer/Owner Name</label>
                    <input id="boname" name="boname" class="ui-widget-content" type="text" required />
'.$hr.'

<label for="plotno">Plot No</label>
                    <input id="plotno" name="plotno" class="ui-widget-content" type="text" required />
'.$hr.'
<label for="titleno">TitleDeed No</label>
                    <input id="titleno" name="titleno"  value="n/a" class="ui-widget-content" type="text" required />
'.$hr.'

<label for="area">Area(Acres)</label>
                    <input id="acres" value="0" name="acres" class="ui-widget-content" type="number" required onBlur="doMath();" />
'.$hr.'

<label for="areasq">Area(Sq metres)</label>
                 <input type="text" id="sqmetres"  value="0" name="sqmetres" class="ui-widget-content" readonly="true" />'.
$hr.'

<label for="areasqft">Area(Sq ft)</label>'.'
                    <input type="text" id="sqft" name="sqft"  value="0" class="ui-widget-content" readonly="true" />
'.$hr.'

            <div id="floors">
            
            <input type="hidden" id="floors" name="floors" class="ui-widget-content"  value="1" readonly="true" / >
            </div>
            </td><td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
    <td>
    <label for="mohalla">Select Estate</label>
                    <input id="mohalla" name="mohalla" class="ui-widget-content" type="text" required />'.$hr.' 
                                         <input id="occupants" name="occupants" value="Mixed" type="hidden">
       <label for="occupants">V.A.T</label>
  <select id="vat" name="vat" class="ui-widget-content"  type="select" required >
            <option value=" ">Select VAT Option</option>
            <option value="0">No VAT</option>
            <option value="1">Has VAT</option>
                         </select>


'.$hr.'   
  
<label for="water">Water Charge/unit</label>
                 <input type="text" id="watercharge" value="0" name="watercharge" class="ui-widget-content" />'.
$hr.'
    '.$hr.'   
  
<label for="commission">Agent Commission</label>
                 <input type="text" id="agentcommission" name="agentcommission" value="0" class="ui-widget-content" />'.
$hr.'

            <label for="Structure Status">Structure Status</label>
            <select id="structstatus" name="structstatus" class="ui-widget-content" required type="select" >
            <option selected value="Permanent">Permanent</option>
            <option value="Semi_Permanent">Semi_Permanent</option>
            <option value="Makeshift">Makeshift</option>
            </select>
'.$hr.'

         <label for="curl">Property map URL</label>
                    <input id="propurl" name="propurl" class="ui-widget-content" required value="http://maps.google.com" type="url" />   

'.$hr.'

<label for="condition">Condition</label>
            <select id="condition" name="condition" class="ui-widget-content" required type="select" >
            <option value>---</option>
            <option selected value="Good">Good</option>
            <option value="Require_renovation">Require Renovation</option>
            <option value="Require_redevelopment">Require Redevelopment</option>
            </select>


            '.$hr.'
            <label for="description">Pay Day:'. str_repeat("&nbsp;", 15).'</label>
            <input type="number" max="31" min="1" name="pay_day"  oninput="javascript: if (this.value >30 || this.value<1) this.value =1" required/>
          
                     <label for="comment">&nbsp;&nbsp;</label>
                    <div id="desc">
                     <label for="desc">Please Describe the status of structure</label>
                    <textarea id="desc" name="desc" rows="4" cols="50" class="ui-widget-content" ></textarea></div>
            '.$hr.'
            
             <label for="address">Address</label>
             
                    <textarea id="address" name="address" rows="4" cols="30" class="ui-widget-content" required>'.$settings['address'].'</textarea>
            '.$hr.'

                    <button class="submit" id="submit" type="submit"><b>SAVE PROPERTY DETAILS</b></BR></button>



</td>
</tr>
</table>	


    </fieldset>
</form><div id="ack"></div>';




?>
<script type="text/javascript" src="../js/validateinput.js"></script>
<?php } 
//end of adding new property

//update data


function updatedata(){

 include '../views/display.php';

echo '<link rel="stylesheet" type="text/css" media="screen" href="../themes/base/jquery-ui.css" />';
echo '<link rel="stylesheet" href="../js/style.css">';
echo '<script src="../js/jquery-1.9.1.js"></script>';
echo '<script src="../js/ui/jquery-ui.js"></script>';
echo '<link rel="stylesheet" type="text/css" media="screen" href="../css/jquery-ui-git.css" />';
 echo  '<link rel="stylesheet" type="text/css" media="screen" href="../css/update.css" />'; 
 echo  '<link rel="stylesheet" type="text/css" media="screen" href="../js/scrollpane/jquery.jscrollpane.css" />';
 echo '<link rel="stylesheet" href="../css/update.css" />';
  echo '<link rel="stylesheet" type="text/css" media="screen" href="../css/buttons.css" />';
echo '<script src="../js/jquery-ui-git.js"></script>';


 echo '<script type="text/javascript" src="../js/datepickr.js"></script>';

 ?>
<script src="../js/scrollpane/jquery.jscrollpane.min.js" type="text/javascript"></script>
<script src="../js/scrollpane/jquery.mousewheel.js"></script>
<script src="../js/scrollpane/mwheelIntent.js"></script>


<script> 
$(function() {
            $( "#tabs" ).tabs();
    });
    
    $(function()
{
	$('#scroll-pane').jScrollPane();
      });
   
   
</script>

 <script>
$(function() {
$( "#issuedate" ).datepicker({
changeMonth: true,
changeYear: true
});
});

$(function() {
$( "#issuedate1" ).datepicker({
changeMonth: true,
changeYear: true
});
});
</script>

   
       
<script>
   //change functions for legal docs
        $(document).ready(function() {
           var change;
           $( "#dialog" ).hide(); //hide photo dialog
           $( "#dialog1" ).hide();
           $('#property').change(function() {
               change= $("#property").val()
               $("#propertyidphoto").val(change);
               //$('#photo').load('lookupphoto.php?cat=ALL&propertyidphoto='+change);
               $('#tabs-5').load('lookupphoto.php?cat=H&propertyidphoto='+change);
                $('#tabs-6').load('lookupphoto.php?cat=C&propertyidphoto='+change);
                 $('#tabs-7').load('lookupphoto.php?cat=P&propertyidphoto='+change);
                 $('#tabs-8').load('lookupphoto.php?cat=CON&propertyidphoto='+change);
                 //initialise all fields to null first
                 
                               //available documents legal
               $.getJSON("availabledocs.php?propid="+change,
        function(data){
          $.each(data, function(i,item){
            if (item.field == "document1") {
              $("#availabledocs").val(item.value);
              $("#availabledocs").append('text')
            }
            if (item.field == "document2") {
             $("#availabledocs1").val(item.value);
            }
            if (item.field == "document3") {
              $("#availabledocs2").val(item.value);
            }
            else if (item.field == "document4") {
               $("#availabledocs3").val(item.value);
            }
            else if (item.field == "document5") {
              $("#availabledocs4").val(item.value);
            }
             else if (item.field == "document6") {
               $("#availabledocs5").val(item.value);
            }
             else if (item.field == "document7") {
              $("#availabledocs6").val(item.value);
            }
             else if (item.field == "document8") {
              $("#availabledocs7").val(item.value);
            }
             else if (item.field == "document9") {
              $("#availabledocs8").val(item.value);
            }
             else if (item.field == "document10") {
              $("#availabledocs9").val(item.value);
            }
          });
        });
        
        //available docs property
               $.getJSON("availabledocsprop.php?propid="+change,
        function(data){
          $.each(data, function(i,item){
            if (item.field == "document1") {
              $("#propertydocs1").val(item.value);
            }
            if (item.field == "document2") {
             $("#propertydocs2").val(item.value);
            }
            if (item.field == "document3") {
              $("#propertydocs3").val(item.value);
            }
            else if (item.field == "document4") {
               $("#propertydocs4").val(item.value);
            }
            else if (item.field == "document5") {
              $("#propertydocs5").val(item.value);
            }
             else if (item.field == "document6") {
               $("#propertydocs6").val(item.value);
            }
             else if (item.field == "document7") {
              $("#propertydocs7").val(item.value);
            }
             else if (item.field == "document8") {
              $("#propertydocs8").val(item.value);
            }
            
          });
        });
               //floor plan
             $( "#result" ).load( "editfloorplan.php?propid="+change, function() {

});
               
               
               //propertyid value remains on change
               $("#propertyid").val(change);
               //send value to photos tab
               // clear input fields
         $("#legaldocsstype").val('');
                 $("#desc1").val('');
      $("#docno").val('');
       $("#issuedate").val('');
       $("#issueofficer").val('');
       $("#legaldoc1").val('');
       $( "#legaldocpath" ).val(''); 
       $("#propertydocstype").val('');
        $("#descr").val('');
         $("#docno1").val('');
         $("#issuedate1").val('');
          $("#issueofficer1").val('');
          $("#propertydoc1").val('');
              $( "#propdocpath" ).val('' );
       
       
           });
        
$('#legaldocsstype').change(function() {
            var propid = change;
            $("#selected").val($('#legaldocsstype').val());
          $.getJSON("lookup.php?propid="+propid+"&docid="+$("#selected").val(),
        function(data){
          $.each(data, function(i,item){
                if (item.field == "legaldocsstype") {
             //$("#legaldocsstype").val(item.value);
            }
            else if (item.field == "desc1") {
              $("#desc1").val(item.value);
            }
            else if (item.field == "propertyid") {
             $("#propertyid").val(item.value);
            }
           
            else if (item.field == "docno") {
              $("#docno").val(item.value);
            }
            else if (item.field == "issuedate") {
              $("#issuedate").val(item.value);
            }
             else if (item.field == "issueofficer") {
              $("#issueofficer").val(item.value);
            }
             else if (item.field == "legaldoc") {
              $("#legaldoc1").val(item.value);
              
              $( "#legaldocpath" ).attr( "href", item.value )
             
            }
            
          });
        });
});

    
   

});

//change function for propertydocs

        $(document).ready(function() {
           var change1;
           $('#property').change(function() {
               change1= $("#property").val()
               $("#propertyid1").val(change1);
               
                            
           });
          
    
        
$('#propertydocstype').change(function() {
            var propid = change1;
            $("#selected1").val($('#propertydocstype').val());
            
          $.getJSON("lookupdocs.php?propid="+propid+"&docid="+$("#selected1").val(),
        function(data){
          $.each(data, function(i,item){
            if (item.field == "descr") {
              $("#descr").val(item.value);
            }
            else if (item.field == "propertyid1") {
             $("#propertyid1").val(item.value);
            }
            else if (item.field == "propertydocstype") {
             //$("#propertydocstype").val(item.value);
             //$("#selected").val(item.value);
            }
            else if (item.field == "docno1") {
              $("#docno1").val(item.value);
            }
            else if (item.field == "issuedate1") {
              $("#issuedate1").val(item.value);
            }
             else if (item.field == "issueofficer1") {
              $("#issueofficer1").val(item.value);
            }
             else if (item.field == "propertydoc1") {
              $("#propertydoc1").val(item.value);
              $( "#propdocpath" ).attr( "href", item.value )
            }
            
          });
        });
});


$("button#submit").click( function(event) {
 
  if( $("#property").val()=="" || $("#proptype").val() == "" || $("#pname").val() == "" )   {
//$("div#ack").html("<center><b><font color=red>Please enter required fields</font></b></center>");
 alert('please enter required fields');
event.preventDefault();
}

  else{
    $.post( $("#commentForm1").attr("action"),
	        $("#commentForm1 :input").serializeArray(),
			function(data) {
			  alert(data);
                          $('#commentForm1')[0].reset();
                          //$("div#ack").html(data);
		
                    });

	}
 
});
 
    

    
    
});


</script>


<?php

     include_once '../includes/database.php';
$db=new MySQLDatabase();
$db->open_connection();

echo '
    <fieldset class="ui-widget ui-widget-content ui-corner-all" style="width:100%">
            <legend class="ui-widget ui-widget-header ui-corner-all">UPDATE PROPERTY INFORMATION</legend>

       <table>
         <fieldset>
<tr>

    <td width="100%">
            <div id="tabs">
    <ul>
            <li><a href="#tabs-1">'.$wheat.'Legal Docs'.$endwheat.'</a></li>
            <li><a href="#tabs-2">'.$green.'Property Docs'.$endgreen.'</a></li>
            <li><a href="#moredata" >More Data</a><li>
            <li><a id="floorplan" href="#tabs-3">'.$red.'Floorplan detail'.$endred.'</a></li>
            <li><a id="propertyphotos" href="#tabs-4">'.$purple.'Gallery | photo upload'.$endpurple.'</a></li>
                <li><a id="chargeitems" href="#tabs-5">'.$purple.'Charge Items'.$endpurple.'</a></li>
             
    </ul>
    <div id="tabs-1">';
    //legal documents aform(adding and editing forms)
echo '<table id="updatedata"><tr><td>';
 echo '<form enctype="multipart/form-data" action="uploadlegal.php"  method="POST" class="legaldocs" id="legaldocs" >
                    
                <label for="document">Document*:'. str_repeat("&nbsp;",10).'</label>';
 echo '<select id="legaldocsstype" name="legaldocsstype" style="width:300px;" class="ui-widget-content" required type="select" >';
     echo '<option selected="selected" value="">---</option> ';                    
      echo populatelegaldocs();
      echo '</select>&nbsp;&nbsp;<br/>
                        
                       <input id="selected" name="selected" style="width:300px;" class="ui-widget-content" readonly="true" type="hidden"  />  
                       <br/>
                      
                      
                         <input id="propertyid" name="propertyid" style="width:300px;" class="ui-widget-content" readonly="true" type="hidden" />
                    <br/>
                  <label for="document">Document Number*:'. str_repeat("&nbsp;", 4).'</label>
                    <input id="docno" name="docno" style="width:300px;" class="ui-widget-content"  required type="text" />
                    <br/>

                       <label for="issuedate">Issue Date:'. str_repeat("&nbsp;",19).'</label>
                    <input id="issuedate" name="issuedate" style="width:300px;" readonly="readonly" class="ui-widget-content"  required  type="text" />
                    <br/> 


                <label for="issueofficer">Issuing Officer:'. str_repeat("&nbsp;", 12).'</label>
                    <input id="issueofficer" name="issueofficer" style="width:300px;" class="ui-widget-content"  required  type="text" />
                    <br/> 
                    <label for="description">Description:'. str_repeat("&nbsp;", 15).'</label>
                   <input id="desc1" name="desc1" style="width:300px;" class="ui-widget-content"  required type="text" style="width: 320px;" />
                    <br/> 
                    
                  <label for="legaldoc">Browse Document:'. str_repeat("&nbsp;",6).'</label>                    
                    <input name="legaldoc" id="legaldoc" style="width:300px;" class="ui-widget-content"  required  type="file" />
                    <label for="path"><a id="legaldocpath"  href="" target="_blank"  ><span style="border:2px solid orange; background-color:#fff" >Preview Document</span></a></label>
                    <input id="legaldoc1" name="legaldoc1" style="width:300px;"  readonly="true" type="text"/>
                 
                     
                    
                   
    </td><td>'.str_repeat("</br>",35).'<center> <button class="submit" id="submit" type="submit"><b>UPDATE <br/>LEGAL DETAILS</b></BR></button></center></form>'. str_repeat("&nbsp;",40).'</td><td>
        <font color="#E68A00"> <b>&nbsp;&nbsp;&nbsp;&nbsp;<u>Available Legal documents<img src="../images/cursors/available.png"></u></b></font>
       <div id="scroll-pane">
     <input id="availabledocs" style="width:300px; background-color:#FFEBD6;" name="availabledocs" type="text"  disabled />
     <input id="availabledocs1" style="width:300px;" name="availabledocs1" type="text"  disabled />
     <input id="availabledocs2" style="width:300px; background-color:#FFEBD6;" name="availabledocs2" type="text"  disabled />
      <input id="availabledocs3" style="width:300px;" name="availabledocs3" type="text"  disabled />
       <input id="availabledocs4" style="width:300px; background-color:#FFEBD6;" name="availabledocs4" type="text"  disabled />
        <input id="availabledocs5" style="width:300px;" name="availabledocs5" type="text"  disabled />
         <input id="availabledocs6" style="width:300px; background-color:#FFEBD6;" name="availabledocs6" type="text"  disabled />
          <input id="availabledocs7" style="width:300px;" name="availabledocs7" type="text"  disabled />
           <input id="availabledocs8" style="width:300px;background-color:#FFEBD6;" name="availabledocs8" type="text"  disabled />
           <input id="availabledocs9" style="width:300px;" name="availabledocs9" type="text"  disabled />
           <input id="availabledocs10" style="width:300px;background-color:#FFEBD6;" name="availabledocs10" type="text"  disabled />
           <input id="availabledocs11" style="width:300px;" name="availabledocs11" type="text"  disabled />
           <input id="availabledocs12" style="width:300px;background-color:#FFEBD6;" name="availabledocs12" type="text"  disabled />';         

echo '</div></td></tr></table>
    
</div>';?>
			   <div class="tab-pane" id="moredata">
			  	<div class="row">
			<div class="col-sm-6">
                <form class="form-horizontal">
                 
                  <div class="form-group">
                    <div class="col-sm-12">
					 <label for="Mapurl" class="control-label">Property Name:</label>
                     
					  <input type="text" style="width:300px;" class="ui-widget-content" placeholder="Enter ..." id="prop_ty_name" >
					  <br/>
                    </div>
				
                  </div>
				   <div class="form-group">
                   
                    <div class="col-sm-12">
					 <label for="Mapurl" class="control-label">Map Url:</label>
                     
					  <input type="text" style="width:300px;" class="ui-widget-content" placeholder="Enter ..." id="mapurl" >
					  <br/>
                    </div>
                  </div>
				
				  <div class="form-group">
                   
                    <div class="col-sm-12">
					 <label for="Mapurl" class="control-label">Plot No:</label>
                     
					  <input type="text" style="width:300px;" class="ui-widget-content" placeholder="Enter ..." id="plot_no" >
                    </div>
                  </div>
				  <div class="form-group">
                   
                    <div class="col-sm-12">
					 <label for="Mapurl" class="control-label">Title Deed No:</label>
                     
					  <input type="text" style="width:300px;" class="ui-widget-content" placeholder="Enter ..." id="titledeed_no" >
                    </div>
                  </div>
              
                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                      <button type="button" id="submitdata" class="btn btn-danger">Update</button>
                    </div>
                  </div>
                </form>
				</div>
				
              </div>

</div>
		
		
<?php
//upload/edit legal ends here
echo '<div id="tabs-2">';
    echo '<table id="updatedata"><tr><td>';
    echo '<form enctype="multipart/form-data" action="uploaddocs.php"  method="POST" class="legaldocs" id="property"  ><br/>
             <label for="document">Document*:'. str_repeat("&nbsp;",18).'</label>';
            echo '<select id="propertydocstype" name="propertydocstype" style="width:300px;" class="ui-widget-content" required type="select" >';
    echo '<option value>---</option> ';
            echo populatepropdocs();

            echo '</select><br/>
                       
                       <input id="selected1" name="selected1" style="width:300px;" class="ui-widget-content" readonly="true" type="hidden"  />  
                       <br/>    
                    <input id="propertyid1" name="propertyid1" style="width:300px;" class="ui-widget-content" readonly="true" type="hidden" />
                    <br/> 
                  <label for="document">Document Number*:'.str_repeat("&nbsp;",4).'</label>
                    <input id="docno1" name="docno1" style="width:300px;" class="ui-widget-content" value="" required type="text" />
                    <br/>

                       <label for="issuedate">Issue Date:(format dd-mm-yy)'. str_repeat("&nbsp;",8).'</label>
                    <input id="issuedate1" name="issuedate1" style="width:300px;" class="ui-widget-content" required  type="text" />
                    <br/>


<label for="issueofficer">Issuing Officer:'. str_repeat("&nbsp;", 12).'</label>
                    <input id="issueofficer1" name="issueofficer1" style="width:300px;" class="ui-widget-content" value="" required  type="text" />
                    <br/>
                    <label for="desc">Descriptions:'. str_repeat("&nbsp;", 15).'</label>
                    <input id="descr" name="descr"  class="ui-widget-content" style="width:300px;" value="" required type="text" style="width: 300px;" />
                    <br/> 
                  <label for="propertydoc">Browse Document:(jpg,gif,png,pdf,doc,docx)'. str_repeat("&nbsp;",1).'</label> 
                    <input id="propertydoc" name="propertydoc" style="width:300px;" class="ui-widget-content" required  type="file" />
                    <label for="path"><a id="propdocpath"  href="" target="_blank"  ><span style="border:2px solid green; background-color:#fff" >Preview Document</span></a></label>
                        <input id="propertydoc1" name="propertydoc1" style="width:300px;" readonly="true" type="text"  />
                    <br/> 
                    
                    
                    
               
    </td><td>'.str_repeat("</br>",35).'<center><button class="submit" id="submit" type="submit"><b>UPDATE PROPERTY DETAILS</b></BR></button></form></center>'. str_repeat("&nbsp;",40).'</td><td>
        <font color="green"> <b>&nbsp;&nbsp;&nbsp;<u>Available Property documents <img src="../images/cursors/available.png"></u></b></font>
     <div id="scroll-pane"><input id="propertydocs1" style="width:300px; background-color:#E2FFE2" name="propertydocs1" type="text"  disabled />
     <input id="propertydocs2" style="width:300px;" name="propertydocs2" type="text"  disabled />
     <input id="propertydocs3" style="width:300px; background-color:#E2FFE2" name="propertydocs3" type="text"  disabled />
      <input id="propertydocs4" style="width:300px;" name="propertydocs4" type="text"  disabled />
       <input id="propertydocs5" style="width:300px; background-color:#E2FFE2" name="propertydocs5" type="text"  disabled />
        <input id="propertydocs6" style="width:300px;" name="propertydocs6" type="text"  disabled />
         <input id="propertydocs7" style="width:300px;background-color:#E2FFE2" name="propertydocs7" type="text"  disabled />
          <input id="propertydocs8" style="width:300px;" name="propertydocs8" type="text"  disabled />
           <input id="propertydocs9" style="width:300px;background-color:#E2FFE2" name="propertydocs9" type="text"  disabled />
           <input id="propertydocs10" style="width:300px;" name="propertydocs10" type="text"  disabled />
           <input id="propertydocs11" style="width:300px;background-color:#E2FFE2" name="propertydocs11" type="text"  disabled />
           <input id="propertydocs12" style="width:300px;" name="propertydocs12" type="text"  disabled />
            <input id="propertydocs13" style="width:300px;background-color:#E2FFE2" name="propertydocs13" type="text"  disabled />';          

echo '</div></td></tr></table>
</div>';
?>

<?php 
//upload docs ends here

echo '<div id="tabs-3">';
    
echo '<div id="result"></div>';

echo '</div>';
//floor plan ends here
echo '<div id="tabs-5"> </div> ';
?>
   
 
<?php
//gallery tab
echo    '<div id="tabs-4">';
        
echo '<table>';
echo '<tr><td bgcolor="#f0f0f0" width="700px" min-height="300px"><div id="gallery">';


echo '</div>';
   echo ' <div id="dialog"> 
    <a href="#" id="delete" style="float:left"><u>delete Photo<u></a> <a href="#" id="close" style="float:right;margin-right:3px;"><u>close X</u></a>
<a href="#" id="movephoto" style="float:right;margin-right:18px;"><u>Move Photo</u></a>    
<select id="target" class="input" name="target" style="float:right;margin-right:8px">
  <option value="C">Current</option>
  <option value="P">Proposed</option>
  <option value="H">Historic</option>
  <option value="CON">Construction</option>
</select><label style="float:right;margin-right:20px">Move photo to >></label><br>
<div id="dialoginternal"></div>
</div>
</div>


</td>';


echo'<td bgcolor="#d28a8a" width="250px">';

echo '&nbsp;&nbsp; <form action="ajaximage.php" method="POST" enctype="multipart/form-data">
       
<input id="propertyidphoto" name="propertyidphoto" class="ui-widget-content" readonly="true" type="hidden" />
   <label for="upload"><u>Upload File::</u>'. '<br/>'.'
max file size:2MB;<br>  
max width:1960px <br>
max height:1200px 
</label>  <input type="file" name="fileup" />'.str_repeat("<br/>",1).'
       <label>Select category::</label>
 <select id="photocat" class="input" name="photocat" >
  <option value="C">Current</option>
  <option value="P">Proposed</option>
  <option value="H">Historic</option>
  <option value="CON">Construction</option>
  
  
</select>
<button class="swimbutton" type="submit" style="float:right; margin-top:10px;margin-right:90px;" name="sub">
<span>Upload Photo</span>
</button></div><br/></form>';

 

echo '</td>
    
</tr>';
    

echo '</table>';

   echo'</div>';
  
   echo '</div>';?>
    
    </td>
           </tr></table>

   
  <?php  echo '</fieldset>';//end of fieldset
} 

?>




   










