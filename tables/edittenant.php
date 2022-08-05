			<link rel="stylesheet" href="<?php echo $baseurl;?>tables/media/css/demo_page.css">
			<link rel="stylesheet" href="<?php echo $baseurl;?>tables/media/css/demo_table.css">
			<link rel="stylesheet" href="<?php echo $baseurl;?>css/dialog.css">
                        <link rel="stylesheet" href="../css/form.css">
			<link rel="stylesheet" href="<?php echo $baseurl;?>tables/media/css/demo_table_jui.css">
			
			<link rel="stylesheet" href="<?php echo $baseurl;?>tables/media/css/themes/smoothness/jquery-ui-1.7.2.custom.css">

                        <style type="text/css" media="screen">/*
			 * Override styles needed due to the mix of three different CSS sources! For proper examples
			 * please see the themes example in the 'Examples' section of this site
			 */
			.dataTables_info { padding-top: 0; }
			.dataTables_paginate { padding-top: 0; }
			.css_right { float: right; }
			.example_wrapper .fg-toolbar { font-size: 0.8em }
			.theme_links span { float: left; padding: 2px 10px; }

		</style>
       
		<script type="text/javascript" src="<?php echo $baseurl;?>tables/media/js/complete.js"></script>
		<script src="<?php echo $baseurl;?>js/jquery.min.js" type="text/javascript"></script>
		<script src="<?php echo $baseurl;?>tables/media/js/jquery.dataTables.min.js" type="text/javascript"></script>
		<script type="text/javascript" src="<?php echo $baseurl;?>tables/media/js/jquery.dataTables.editable.js"></script>
		<script src="<?php echo $baseurl;?>tables/media/js/jquery.jeditable.js" type="text/javascript"></script>
                <script src="<?php echo $baseurl;?>js/jquery-ui-git.js" type="text/javascript"></script>
                <script src="<?php echo $baseurl;?>tables/media/js/jquery.validate.js" type="text/javascript"></script>
                <script src="<?php echo $baseurl;?>js/ui/jquery.ui.core.js"></script><script src="<?php echo $baseurl;?>js/ui/jquery.ui.effect.js"></script>
               <script src="<?php echo $baseurl;?>js/ui/jquery.ui.dialog.js"></script>
        


<script>
$(document).ready(function () {
  
myDT = $('#drafts').dataTable({   // <== Change the Id of the table 
"bServerSide": true,
"iDisplayLength":16,
"bJQueryUI": true,
"bProcessing": true,
"sPaginationType": "full_numbers",
"aoColumnDefs": [
{ "sWidth": "10%", "aTargets": [ -1 ] }
],
"aaSorting":[[0,"desc"]],
"oLanguage":{
"sEmptyTable":"No Channels to display",
"sProcessing": "Fetching Channel Information",
"sZeroRecords":"No Channels Found"
},
"bAutoWidth":false,
"sAjaxSource": "<?php echo $baseurl;?>tables/tenants.php", //Fetch Data [ServerSide Script Path]

"aaSorting":[[0,"desc"]],
"aoColumns":[  // Displayed columns [Number of columns]
{"bVisible":false},
{},
{},
{},
{},
{},
{},      
{},
{}, 
{},      
{}, {},{},{},{}
]
}
).makeEditable({
//sDeleteURL: "<?php echo $baseurl;?>tables/vacatetenant.php",
sUpdateURL: "<?php echo $baseurl;?>/tables/updatetenant.php",  //Update Data [Update Data Server Side Script Path]
//sAddDeleteToolbarSelector: ".dataTables_length",
"aoColumns":[ // Edittable Columns [Number of Columns] and display Options
{indicator: 'Edit Name...',tooltip: 'Click to change Name',type: 'text',submit:'Edit Name'},
                                {indicator: 'Edit Phone...',tooltip: 'Click to change name',type: 'text',submit:'Edit Phone'},	
                                {indicator: 'Edit Email...',tooltip: 'Click to change phone',type: 'text',submit:'Edit Email'},
				{indicator: 'Edit Workplace...',tooltip: 'Click to change workplace',type: 'text',submit:'Edit Workplace'},
				{indicator: 'Edit ID NO...',tooltip: 'Click to change ID NO',type: 'text',submit:'Edit ID No'},
                                null,
                                null,
                                null,
                                null,
                                null,
                                null,{indicator: 'Edit Name...',tooltip: 'Click to change name',type: 'text',submit:'Edit name'},{indicator: 'Edit phone...',tooltip: 'Click to change Phone NO',type: 'text',submit:'Edit Phone No'},null
                        ],
oDeleteRowButtonOptions:false,
/*{
label: "Vacate Tenant?",
icons: { primary: 'ui-icon-trash' }
}*/							
});
setInterval("myDT.fnDraw()",45000);
});

</script>
<script>
$(document).ready(function () {
var propertytext;
var propertytextedit;
$('#propertyname').change(function() {
propertytext=$('#propertyname option:selected').text();
$( "#apartments" ).load( "../modules/getapartments.php?propid="+$("#propertyname").val(), function() {
});    

});/*on change of property name populate dropdownlist to show which apartments are empty

$('#images').change(function() {
var imagetext=$('#images').val();
alert(imagetext); 

})*/
$('#propertyname2').change(function() {
propertytextedit=$('#propertyname2 option:selected').text();
$( "#tenantname" ).load( "../modules/getactions.php?action=tenantsprop&propid="+$("#propertyname2").val(), function() {
});    

});
$('#tenantname').change(function () { 
    
 $.post( "../modules/getactions.php?action=tenantseditdetails&tenantid="+$("#tenantname").val(), function(data) {
                    if (data==="nothing"){ alert ('Error');}
                    else { 
                       $.each(data, function(i, item) { 
                           $("#email2").val(item.tenantemail);
                           $("#phoneno2").val(item.tenantphone);
                           $("#tenantbnk").val(item.bank_id);
                           $("#work2").val(item.workplace);
                           $("#idno2").val(item.idno);
                           $("#pin2").val(item.tenantpin);
                           $("#leasestart2").val(item.fromdate);
                           $("#leaseend2").val(item.todate);
                           $("#physicaddress2").val(item.physcaladdress);
                           $("#postaddress2").val(item.postaladdress);
                           $("#kinsname2").val(item.kins_name);
                           $("#kinstelcontact2").val(item.kinstel);
                           $("#kinsemail2").val(item.kinsemail); 
                      });
        
                            } } );
                        });
$(function() {
email = $("#email" ),
phone = $("#phoneno"),
allFields = $( [] ).add( email ).add( phone ),
tips = $( ".validateTips" );

function updateTips( t ) {
tips
.text( t )
.addClass( "ui-state-highlight" );
setTimeout(function() {
tips.removeClass( "ui-state-highlight", 1500 );
}, 500 );
}

function checkLength( o, n, min, max ) {
if ( o.val().length > max || o.val().length < min ) {
o.addClass( "ui-state-error" );
updateTips( "Length of " + n + " must be between " +
min + " and " + max + "." );
return false;
} else {
return true;
}
}

function checkRegexp( o, regexp, n ) {
if ( !( regexp.test( o.val() ) ) ) {
o.addClass( "ui-state-error" );
updateTips( n );
return false;
} else {
return true;
}
}

$("#dialog-form").dialog({
autoOpen: false,
height:680,
width: 900,
modal: true,
closeOnEscape:true,
buttons: {
"Add Tenant": function() {
var bValid = true;
allFields.removeClass( "ui-state-error" );

bValid = bValid && checkLength( email, "email", 6, 80 );
bValid = bValid && checkLength( phone, "phone", 5, 16 );

//bValid = bValid && checkRegexp( name, /^[a-z]([a-z_])+$/i, "Tenant name may consist of a-z, underscores, begin with a letter." );
// From jquery.validate.js (by joern), contributed by Scott Gonzalez: http://projects.scottsplayground.com/email_address_validation/
bValid = bValid && checkRegexp( email, /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i, "eg. someone@mail.com" );
bValid = bValid && checkRegexp( phone, /^([\+][0-9]{1,3}([ \.\-])?)?([\(]{1}[0-9]{3}[\)])?([0-9A-Z \.\-]{1,32})((x|ext|extension)?[0-9]{1,4}?)$/, "Phone field must be valid" );

if ( bValid ) {
/*validate*/
if ($("#propertyname" ).val()=="" || $( "#aptname" ).val()=="" || $( "#idno" ).val()==""  || $( "#leasestart" ).val()=="" || $( "#leaseend" ).val()=="" || $( "#idno" ).val()=="" ||$('#physicaddress').val()==""||$('#postaddress').val()=="" ||$('#kinsname').val()=="" ||$('#kinstelcontact').val()==""||$('#kinsemail').val()==""){ alert('fill in all fields'); }
else{
/*post form values*/
 
$.ajax({
type:'POST',
url:'../modules/addtenants.php',
data:{Propertyid: $("#propertyname").val(),PropertyName:propertytext,AptId: $("#aptname").val(),AptName:$('#aptname option:selected').text(),TenantName:$("#name").val(),TenantPhone:$("#phoneno").val(),TenantEmail:$("#email").val(),work: $("#work").val(),IDNO: $("#idno").val(),PIN: $("#pin").val(),LeaseStart: $("#leasestart").val(),LeaseEnd: $("#leaseend").val(),Ejamaat:$("#ejno").val(),PHOTO:$('#photos').val(),Leasedoc:$("#images").val(),AgentName:$("#agentname").val(),Address:$('#physicaddress').val(),PostAddress:$('#postaddress').val(),kinsName:$('#kinsname').val(),KinsTel:$('#kinstelcontact').val(),kinsEmail:$('#kinsemail').val(),Date:$('#regdate').val()},
success:function(){
alert ('success');
$( "#tenantform")[0].reset();
$( "#dialog-form" ).hide();              

}
});

}



}
},
Cancel: function() {
$("#dialog-form").dialog( "close" );
}
},
close: function() {
allFields.val( "" ).removeClass( "ui-state-error" );
$("#dialog-form").dialog( "close" );
}
});

$("#dialog-form-edit").dialog({
autoOpen: false,
height:680,
width: 900,
modal: true,
closeOnEscape:true,
buttons: {
"Edit Tenant": function() {
var bValid = true;
allFields.removeClass( "ui-state-error" );

//bValid = bValid && checkLength( email, "email", 6, 80 );
bValid = bValid && checkLength( phone, "phone", 5, 16 );

//bValid = bValid && checkRegexp( name, /^[a-z]([a-z_])+$/i, "Tenant name may consist of a-z, underscores, begin with a letter." );
// From jquery.validate.js (by joern), contributed by Scott Gonzalez: http://projects.scottsplayground.com/email_address_validation/
bValid = bValid && checkRegexp( email, /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i, "eg. someone@mail.com" );
bValid = bValid && checkRegexp( phone, /^([\+][0-9]{1,3}([ \.\-])?)?([\(]{1}[0-9]{3}[\)])?([0-9A-Z \.\-]{1,32})((x|ext|extension)?[0-9]{1,4}?)$/, "Phone field must be valid" );

if ( bValid ) {
/*validate*/
if ($( "#propertyname2" ).val()=="" || $( "#aptname2" ).val()=="" || $( "#idno2" ).val()==""  || $( "#leasestart2" ).val()=="" || $( "#leaseend2" ).val()=="" || $( "#idno2" ).val()=="" ||$('#physicaddress2').val()==""||$('#postaddress2').val()=="" ||$('#kinsname').val()=="" ||$('#kinstelcontact2').val()==""||$('#kinsemail2').val()==""){ alert('fill in all fields'); }
else{
/*post form values*/
$.ajax({
type:'POST',
url:'../modules/update_tenant.php',
data:{Propertyid: $("#propertyname2").val(),PropertyName:propertytextedit,AptId: $("#aptname").val(),AptName:$('#aptname option:selected').text(),TenantName:$("#tenantname").val(),TenantPhone:$("#phoneno2").val(),Bankacct:$("#bank_id").val(),TenantEmail:$("#email2").val(),work: $("#work2").val(),IDNO: $("#idno2").val(),PIN: $("#pin2").val(),LeaseStart: $("#leasestart2").val(),LeaseEnd: $("#leaseend2").val(),Ejamaat:$("#ejno2").val(),PHOTO:$('#photos2').val(),Leasedoc: $("#images2").val().replace(/.*(\/|\\)/, ''),AgentName:$("#agentname2").val(),Address:$('#physicaddress2').val(),PostAddress:$('#postaddress2').val(),kinsName:$('#kinsname2').val(),KinsTel:$('#kinstelcontact2').val(),kinsEmail:$('#kinsemail2').val(),Date:$('#regdate2').val()},
success:function(){
alert ('success');
$( "#tenantform")[0].reset();
//$( "#dialog-form-edit" ).hide();  
$("#dialog-form-edit").dialog( "close" );
location.reload();  
}
});

}



}
},
Cancel: function() {
$("#dialog-form-edit").dialog( "close" );
}
},
close: function() {
allFields.val( "" ).removeClass( "ui-state-error" );
$("#dialog-form-edit").dialog( "close" );
}
});

$( "#addtenant" )
.button()
.click(function() {

$("#dialog-form" ).dialog( "open" );
});

$( "#editenant" )
.button()
.click(function() {
    //alert('ttttt');
$("#dialog-form-edit" ).show();
$("#dialog-form-edit" ).dialog( "open" );
});
});
/*datepickers*/
$(function() {
$( "#leasestart" ).datepicker({
changeMonth: true,
changeYear: true,
dateFormat: 'dd-mm-yy'
});
});

$(function() {
$( "#leaseend" ).datepicker({
changeMonth: true,
changeYear: true,
dateFormat: 'dd-mm-yy'
});
});

$(function() {
$( "#leasestartrel" ).datepicker({
changeMonth: true,
changeYear: true,
dateFormat: 'dd-mm-yy'
});
});

$(function() {
$( "#leaseendrel" ).datepicker({
changeMonth: true,
changeYear: true,
dateFormat: 'dd-mm-yy'
});
});

$(function() {
$( "#billingdate" ).datepicker({
changeMonth: true,
changeYear: true
});
});/*datepicker*/

/*upload file*/
$("#images").change(function(event) {
$.each(event.target.files, function(index, file) {
var reader = new FileReader();
files = [];
reader.onload = function(event) { 
object = {};
object.filename = file.name;
object.data = event.target.result;
files.push(object);
}; 

alert(reader.readAsDataURL(file));
});

$.each(files, function(index, file) {
$.ajax({url: "../modules/upload.php",
type: 'POST',
data: {filename: file.filename, data: file.data},
success: function(data, status, xhr) { /*alert('success');*/}
});     
});


});

$("#images2").change(function(event) {
$.each(event.target.files, function(index, file) {
var reader = new FileReader();
files = [];
reader.onload = function(event) { 
object = {};
object.filename = file.name;
object.data = event.target.result;
files.push(object);
}; 

alert(reader.readAsDataURL(file));
});

$.each(files, function(index, file) {
$.ajax({url: "../modules/upload.php",
type: 'POST',
data: {filename: file.filename, data: file.data},
success: function(data, status, xhr) { /*alert('success');*/}
});     
});


});
$("#photos").change(function(event) {
$.each(event.target.files, function(index, file) {
var reader = new FileReader();
files = [];
reader.onload = function(event) { 
object = {};
object.filename = file.name;
object.data = event.target.result;
files.push(object);
}; 

alert(reader.readAsDataURL(file)); //why is it working on alert() only
});

$.each(files, function(index, file) {
$.ajax({url: "../modules/uploadtenantphoto.php",
type: 'POST',
data: {filename: file.filename, data: file.data},
success: function(data, status, xhr) { /*alert('success');*/}
});     
});


});

$(function() {

var availableTags=<?php echo populateagents(); ?>;
$( "#agentname" ).autocomplete({
source: availableTags
});
});

//relocation
$("#formrelocate").hide();
$("#relocatetenant").click(function() {

$("#formrelocate").show();

});
$("#close").click(function() {

$("#formrelocate").hide();
$('#relocateform')[0].reset();
});
//date pickers

//get lease agreement
$("#leaseagree").change(function(event) {
$.each(event.target.files, function(index, file) {
var reader = new FileReader();
files = [];
reader.onload = function(event) { 
object = {};
object.filename = file.name;
object.data = event.target.result;
files.push(object);
}; 

alert(reader.readAsDataURL(file));
});

$.each(files, function(index, file) {
$.ajax({url: "../modules/upload.php",
type: 'POST',
data: {filename: file.filename, data: file.data},
success: function(data, status, xhr) { /*alert('success');*/}
});     
});


});


$('#propertynamerel').change(function() {
propertytext=$('#propertynamerel option:selected').text();
$( "#apartmentsrel" ).load( "../modules/getapartments.php?propid="+$("#propertynamerel").val(), function() {
});    

})

//relocate a tenant
$("#btnrelocate").click(function(e) {
e.preventDefault();
if($('#propertynamerel').val()=="" || $('#fromapt').val()==""  || $('aptname').val()=="" ||$('leasestartrel').val()=="" ||$('leaseendrel').val()=="" ||$('leaseagree').val()==""  ){
$(".validateTips").replaceWith("<font size='2' color='red'><center>All fields required!</center></font>");
}else{
var jqxhr = $.get( "../modules/relocate.php?tenantid="+$("#tname").val()+"&propertyid="+$("#propertynamerel").val()+"&propertyname="+$('#propertynamerel option:selected').text()+"&apartmentid="+$("#aptname").val()+"&apttag="+$('#aptname option:selected').text()+"&leasestart="+$("#leasestartrel").val()+"&leaseend="+$("#leaseendrel").val()+"&leasedoc="+$("#leaseagree").val(), function() {

})
.done(function() {
$(".validateTips").replaceWith("<font size='2' color='green'><center>Successfully relocated</center></font>");
$('#relocateform')[0].reset();
})
.fail(function() {
$(".validateTips").replaceWith("<font size='2' color='red'><center>Error in relocating</center></font>");
})
}
});

});


</script>




<!-- add new tenant info-->
<div id="dialog-form" style="display:none" title="Add New Tenant">
<p class="validateTips">All form fields are required.</p>

<form id="tenantform" method="post" enctype="multipart/form-data"  action="../modules/upload.php">
<fieldset>
    <table> <tr><td>  
<table>
  <tr><td><label for="propertyname"><u>Property Name</u> &nbsp;</label></td>
<td><select id='propertyname' name='propertyname'  style="width:300px;">
<option selected="selected" value="">---</option>
<?php echo populateproperties();?>
</select>  </td></tr>  
<tr><td><label for="aptno"><u>Apartment No</u> &nbsp;</label></td>
<td><div id="apartments"></div> </td></tr>      

<tr><td><label for="name"><u>Tenant Name</u> &nbsp;</label></td>
<td><input type="text" name="name" id="name" class="text ui-widget-content ui-corner-all" /></td></tr>
<tr><td><label for="email"><u>Tenant Email</u> &nbsp;</label></td>
    <td><input type="text" name="email" value="no@gmail.com" id="email" class="text ui-widget-content ui-corner-all" /></td></tr>
<tr><td><label for="phone"><u>Phone number</u> &nbsp;</label></td>
    <td><input type="text" name="phoneno" id="phoneno"  value="0000000" class="text ui-widget-content ui-corner-all" /></td></tr>
<tr><td><label for="id"><u>Place of Work</u> &nbsp;</label></td>
<td><input type="text" name="work" id="work"  class="text ui-widget-content ui-corner-all" /></td></tr>
<tr><td><label for="idno"><u>ID No</u> &nbsp;</label></td>
    <td><input type="text" name="idno" id="idno"  value="00000" class="text ui-widget-content ui-corner-all" /></td></tr>
<tr><td><label for="pin"><u>PIN NO</u> &nbsp;</label></td>
    <td><input type="text" name="pin" id="pin" value="0" class="text ui-widget-content ui-corner-all" /></td></tr>
<tr><td><label for="pin"><u>User Name</u> &nbsp;</label></td>
<td><input type="text" name="agentname" id="agentname" class="text ui-widget-content ui-corner-all" /></td></tr>
<tr><td><label for="photo"><u>Photo</u> &nbsp;</label></td>
<td><input type="file" name="photos" class="text ui-widget-content ui-corner-all" id="photos" /></td></tr>
<tr><td><label for="leaseStart"><u>Lease Start Date</u> &nbsp;</label></td>
<td><input type="text" name="leasestart" id="leasestart" value="01/01/2015"  class="text ui-widget-content ui-corner-all" /></td></tr>
<tr><td><label for="leaseend"><u>Lease End Date</u> &nbsp;</label></td>
    <td><input type="text" name="leaseend" id="leaseend" value="01/01/2016" class="text ui-widget-content ui-corner-all" /></td></tr>
<tr><td><label for="leasedoc"><u>Lease Agreement(image file only)</u> &nbsp;</label></td>
<td><input type="file" name="images" class="text ui-widget-content ui-corner-all" id="images" /></td></tr>
</table></td><td><td>&nbsp;</td><td>
        
        <label for="id"><u>Physical Address</u> &nbsp;</label>
        <input type="text" name="physicaddress" id="physicaddress" value="n/a" style="width:350px" class="text ui-widget-content ui-corner-all" />
<label for="idno"><u>Postal address</u> &nbsp;</label>
<input type="text" name="postaddress" id="postaddress" style="width:350px" value="n/a" class="text ui-widget-content ui-corner-all" />
<label for="pin"><u>Next Of Kin's Name</u> &nbsp;</label>
<input type="text" name="kinsname" id="kinsname" style="width:350px"  value="n/a" class="text ui-widget-content ui-corner-all" /> 
<label for="pin"><u>Telephone Contact</u> &nbsp;</label>
<input type="text" name="kinstelcontact" id="kinstelcontact" value="000000" style="width:350px" class="text ui-widget-content ui-corner-all" /> 
<label for="pin"><u>email address</u> &nbsp;</label>
<input type="text" name="kinsemail" id="kinsemail" value="no@gmail.com" style="width:350px" class="text ui-widget-content ui-corner-all" /> 
  <label for="pin"><u>Date</u> &nbsp;</label>
  <input type="text" name="regdate" id="regdate" style="width:350px" value="<?php echo date('d-m-Y');?>" class="text ui-widget-content ui-corner-all" />      
<br> <br> <br> <br> <br> <br> <br> <br>            
</td></tr></table>
</fieldset>
</form>
</div>
<!-- add new tenant info ends-->	

<!-- add new tenant info-->
<div id="dialog-form-edit" style="display:none" title="Edit Tenant">
<p class="validateTips">All form fields are required.</p>

<form id="tenantform" method="post" enctype="multipart/form-data"  action="../modules/upload.php">
<fieldset>
    <table> <tr><td>  
<table>
  <tr><td><label for="propertyname"><u>Property Name</u> &nbsp;</label></td>
<td><select id='propertyname2' name='propertyname2'  style="width:300px;">
<option selected="selected" value="">---</option>
<?php echo populateproperties();?>
</select>  </td></tr>  
<tr><td><label for="name"><u>Tenant Name</u> &nbsp;</label></td>
            <td><div id="tenants">
<select id="tenantname" name="tenantname"  style="width:300px;">
       </select> </div> </td></td></tr>
<tr><td><label for="name"><u>Invoice Bank</u> &nbsp;</label></td> <td><select id='tenantbnk'  name='tenantbnk'  style='width:250px;'><option value='0' selected='selected'>Select Bank Account</option>
    <?php 
    $bankspays=  getBanks("b");
    
    foreach ($bankspays as $value) {
    echo "<option value='".$value['id']."' >" . htmlspecialchars($value['bank_name']). " : ".$value['property_name']." - ".$value['acct_no']."</option>";
    
    }
    ?>
    </td>
</tr>
<tr><td><label for="email"><u>Tenant Email</u> &nbsp;</label></td>
    <td><input type="text" name="email2" value="no@gmail.com" id="email2" class="text ui-widget-content ui-corner-all" /></td></tr>
<tr><td><label for="phone"><u>Phone number</u> &nbsp;</label></td>
    <td><input type="text" name="phoneno2" id="phoneno2"  value="0000000" class="text ui-widget-content ui-corner-all" /></td></tr>
<tr><td><label for="id"><u>Place of Work</u> &nbsp;</label></td>
<td><input type="text" name="work2" id="work2"  class="text ui-widget-content ui-corner-all" /></td></tr>
<tr><td><label for="idno"><u>ID No</u> &nbsp;</label></td>
    <td><input type="text" name="idno2" id="idno2"  value="00000" class="text ui-widget-content ui-corner-all" /></td></tr>
<tr><td><label for="pin"><u>PIN NO</u> &nbsp;</label></td>
    <td><input type="text" name="pin2" id="pin2" value="0" class="text ui-widget-content ui-corner-all" /></td></tr>
<tr><td><label for="pin"><u>User Name</u> &nbsp;</label></td>
<td><input type="text" name="agentname2" id="agentname2" class="text ui-widget-content ui-corner-all" /></td></tr>
<tr><td><label for="photo"><u>Photo</u> &nbsp;</label></td>
<td><input type="file" name="photos2" class="text ui-widget-content ui-corner-all" id="photos2" /></td></tr>
<tr><td><label for="leaseStart"><u>Lease Start Date</u> &nbsp;</label></td>
<td><input type="text" name="leasestart2" id="leasestart2" value="01/01/2015"  class="text ui-widget-content ui-corner-all" /></td></tr>
<tr><td><label for="leaseend"><u>Lease End Date</u> &nbsp;</label></td>
    <td><input type="text" name="leaseend2" id="leaseend2" value="01/01/2016" class="text ui-widget-content ui-corner-all" /></td></tr>
<tr><td><label for="leasedoc"><u>Lease Agreement(image file only)</u> &nbsp;</label></td>
<td><input type="file" name="images2" class="text ui-widget-content ui-corner-all" id="images2" /></td></tr>
</table></td><td><td>&nbsp;</td><td>
        
        <label for="id"><u>Physical Address</u> &nbsp;</label>
        <input type="text" name="physicaddress2" id="physicaddress2" value="n/a" style="width:350px" class="text ui-widget-content ui-corner-all" />
<label for="idno"><u>Postal address</u> &nbsp;</label>
<input type="text" name="postaddress2" id="postaddress2" style="width:350px" value="n/a" class="text ui-widget-content ui-corner-all" />
<label for="pin"><u>Next Of Kin's Name</u> &nbsp;</label>
<input type="text" name="kinsname2" id="kinsname2" style="width:350px"  value="n/a" class="text ui-widget-content ui-corner-all" /> 
<label for="pin"><u>Telephone Contact</u> &nbsp;</label>
<input type="text" name="kinstelcontact2" id="kinstelcontact2" value="000000" style="width:350px" class="text ui-widget-content ui-corner-all" /> 
<label for="pin"><u>email address</u> &nbsp;</label>
<input type="text" name="kinsemail2" id="kinsemail2" value="no@gmail.com" style="width:350px" class="text ui-widget-content ui-corner-all" /> 
  <label for="pin"><u>Date</u> &nbsp;</label>
  <input type="text" name="regdate2" id="regdate2" style="width:350px" value="<?php echo date('d-m-Y');?>" class="text ui-widget-content ui-corner-all" />      
<br> <br> <br> <br> <br> <br> <br> <br>            
</td></tr></table>
</fieldset>
</form>
</div>

<!-- relocate tenant-->
<div id="formrelocate" style="display:none" title="Relocate Tenant">
    <p class="titletr">Tenant Relocation Form.<a href="#" style="float:right" id="close">Close [X]</a></p>
<p class="validateTips">All form fields are required.</p>

<form id="relocateform" method="post" enctype="multipart/form-data"  action="../modules/upload.php">
<fieldset>
<table>                                                   
<tr><td><label for="tname"><u>Tenant Name</u> &nbsp;</label></td>
<td><select id="tname" name="tname"   style="width:300px;"> <option selected="selected" value="">---</option>
<?php echo populatetenants();?>
</select>  </td></tr>
<tr><td><label for="propertyname"><u>To Property Name</u> &nbsp;</label></td>
<td><select id='propertynamerel' name='propertynamerel'  style="width:300px;">
<option selected="selected" value="">---</option>
<?php echo populateproperties();?>
</select>  </td></tr>  
<tr><td><label for="aptnorel"><u>To Apartment No</u> &nbsp;</label></td>
<td><div id="apartmentsrel"></div> </td></tr>
<tr><td><label for="leaseStartrel"><u>New Lease Start Date</u> &nbsp;</label></td>
    <td><input type="text" name="leasestartrel" id="leasestartrel"  value="01/01/2015" class="text ui-widget-content ui-corner-all" /></td></tr>
<tr><td><label for="leaseendrel"><u>New Lease End Date</u> &nbsp;</label></td>
<td><input type="text" name="leaseendrel" id="leaseendrel" value="01/01/2016"   class="text ui-widget-content ui-corner-all" /></td></tr>
<tr><td><label for="leasedoc"><u>Lease Agreement(image file only)</u> &nbsp;</label></td>
<td><input type="file" name="leaseagree" class="text ui-widget-content ui-corner-all" id="leaseagree" /></td></tr>
<tr><td></td><td><input type="submit" id="btnrelocate"  class="text ui-widget-content ui-corner-all" value="RELOCATE" style="width:300px;"/></td></tr>
</table>
</fieldset>
</form>
</div>
<!--tenant relocation ends-->

<div class="ui-tabs ui-widget ui-widget-content ui-corner-all" id="tabs" align="center">
<a href="#" id="addtenant"></a><div class="add"><b><u>Add Tenant</u></b></div>
<a href="#" id="editenant"></a><div class="edit"><b><u>Edit Tenant</u></b></div>
<div class="relocate"><a href="#" id="relocatetenant"><b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<u>RelocateTenant</u></b></a></div>
<!-- Define Table Header with it's Id attribute <= (Important!!)' [<thead></thead>] and the table's header columns [<th></th>]'-->
<table cellpadding="0" cellspacing="0" border="0" class="display" id="drafts">
<thead>
<tr align="left" border = '1'>
<th>P.ID</th>
<th>Tenant.Name</th>
<th>Phone number</th>
<th>email</th>
<th>Work Place</th>
<th>ID NO</th>
<th>House No</th>
<th>Property Name</th>
<th>LeaseStart date</th>
<th>LeaseEnd date</th>
<th>lease Document</th>
<th>Statement</th>
<th>Next Of Kin</th>
<th>Phone</th>
<th>Email</th>
</tr>
</thead>
<tbody>

</tbody>
</table>

</div>

<div id="photo"></div>

