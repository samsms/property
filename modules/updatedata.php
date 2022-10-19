<?php
include '../views/display.php';
include 'inputforms.php';
$property=  getSettings();
echo  $htmlheaders;
echo '<head><title>Property Manager| '.$property['company_name'].'</title>';
echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
echo '<script type="text/javascript" src="../js/jquery-1.9.1.js"></script>';
echo '<link rel="stylesheet" href="../css/themes/base/jquery.ui.all">';
echo '<link rel="stylesheet" href="../css/overall.css" type="text/css" />';?>
<link rel="stylesheet" href="../css/datatables/demo_page.css" type="text/css" />
<link rel="stylesheet" href="../css/datatables/demo_table.css" type="text/css" />
<link rel="stylesheet" href="../css/datatables/dataTables.tableTools.css" type="text/css" />
<?php echo '<script src="../js/logout.js"></script>';
//later set variable to session
$admin='<u>'.$_SESSION['username'].'</u>';
$propertyid=$_SESSION['propertyid'];
?>

<script>
    $(document).ready(function () {
        var id; 
        $(function() {
	$( "#menu" ).menu();
});

    $.getJSON("accountsprocess.php?propertyinfo=true&id="+<?php echo $propertyid;?>, function(data) {

          $.each(data, function(i, item) {
      		 $("#mapurl").val(item.mapurl);
			 $("#plot_no").val(item.plotno);
		 $("#titledeed_no").val(item.titledeed);
		  $("#prop_ty_name").val(item.property_name);
          });
        });
        
  $("#submitdata").click(function(e){ 
        	         e.preventDefault();
        	        var  mapurl = $("#mapurl").val();
        	        var plot_no = $("#plot_no").val();
        	        var titledeed_no = $("#titledeed_no").val();
        	        var propname = $("#prop_ty_name").val();
        	 $.getJSON("accountsprocess.php?update_prop=true&propid="+<?php echo $propertyid;?>+"&mapurl="+mapurl+"&plot_no="+plot_no+"&titledeed_no="+titledeed_no+"&propname="+propname, function(data) {
   //alert(data);
  if(data == '1'){
            alert('Updated Successfully');
			//$('#modal-editinv').modal('hide');
				// location.reload(true);
     }
        else{
           //$("#systmpriviledges").dialog("destroy"); 
		               alert('Problem updating details ..');

  
        }
      
  });		   

});
function updatemore(mapurl,plot_no,titledeed_no,propname){
   $.getJSON("accountsprocess.php?update_prop=true&propid="+<?php echo $propertyid;?>+"&mapurl="+mapurl+"&plot_no="+plot_no+"&titledeed_no="+titledeed_no+"&propname="+propname, function(data) {
   //alert(data);
  if(data == '1'){
            alert('Updated Successfully');
			//$('#modal-editinv').modal('hide');
				// location.reload(true);
     }
        else{
           //$("#systmpriviledges").dialog("destroy"); 
		               alert('Problem updating details ..');

  
        }
      
  });   
  } 
//load  table data
function reload_table(){
   $("#tabs-3").load("loadpropertydetails.php?table=floorplan",function(e){
           $('#treport').dataTable({ });
      //deposits
      $(".deposits").each(function(e){ 
          //records more than 10 on datatable
      $('body').on("click",".deposits",function(e){
     id=$(this).attr("id");
        $("#adddeposit").dialog({
			modal:false,
                        title:"House Deposits: "+id,
                        width:400,
			buttons: { 
"Save Deposits":populateDeposits,
Close: function() {
$("#adddeposit").dialog("close");
}
}
	});
     $("#adddeposit").load("callfunctions.php?deposits=true&apt_id="+id,function(e){
         var count=1; 
$('.adddeposit').click(function(e){
   
    $("#newdeposits").append("<tr><td><label>Description</label><input type='text' id='depositdesc"+count+"' title='#depositamount"+count+"' class='depositdesc'/></td><td><label>Amount</label><input id='depositamount"+count+"' type='text' class='depositamount'/></td></tr>"); //title attribute of first text field will be used as the id for the second
      count++;
       });
       //delete a deposit
      $(".deletedeposit").each(function(e){ 
      $(this).on("click",function(e){
          e.preventDefault();
     depid=$(this).attr("id");
     var myData;
    $.ajax({
type: "POST",
contentType: "application/json; charset=utf-8",
url:"callfunctions.php?deletedeposit=true&dep_id="+depid,
data: myData,
success: function(data) {if(data.status==true){alert('deleted');$("#adddeposit").dialog("close")}},error: function(data) {if(data.status !=true){alert('data not deleted,try again');}}
},'json');   
     
       });
        });
     });
   
   
   
   
   
      });
      });
    
 //edit floor plan     
$(".editfloor").each(function(e){           
 $('body').on("click",".editfloor",function(e){
     id=$(this).attr("id");
     //assign values to form
 $("#floornumber").val($("#floornumbertd"+id).html());  $("#housenumber").val($("#housenumbertd"+id).html());  $("#monthrent").val($("#monthrenttd"+id).html());
 $("#mktvalue").val($("#marketvaluetd"+id).html());$("#elecmeter").val($("#elecmetertd"+id).html());$("#watermeter").val($("#watermetertd"+id).html());$("#metereading").val($("#metereadingtd"+id).html());$("#receipt_due").val($("#receipt_duetd"+id).html())

 $(function() {
		$("#editfloor").dialog({
			modal:false,
                        title:"Update House Data: "+$("#housenumbertd"+id).html(),
                        width:400,
			buttons: {
"Save Floor Data":editFloorplan,
Cancel: function() {
$("#editfloor").dialog("close");
}
}, });
         
	});
    
     }); //click
      });//each function 
$(".deletefloor").each(function(e){   
 $('body').on("click",".deletefloor",function(e){
     id=$(this).attr("id");
 $(function() {
$("#deletefloor").dialog({
resizable: false,
position:"center",
title:"Confirm deletion?",
modal:false,
buttons: {
"Delete": function() {
deleteFloorplan();
},
Cancel: function() {
$(this).dialog( "close" );
}
}
});
});

     });
 });//each function 
 
 
 //add a floor
 $("#addnewfloor").on("click",function(e){
     //e.preventDefault();
	$("#addfloor").dialog({
			modal:false,
                        title:"Add House:",
                        width:400,
			buttons: {
"Save Floor Data":addFloorplan,
Cancel: function() {
$("#addfloor").dialog("close");
}
}

		});
  });

      }); //load
      
}//reload table

$(document).on("change","#uploadata",function(e){
   $("#form-csv").submit();
});
  $("#floorplan").on("click",function(e){
e.preventDefault();
reload_table();
            }); //click outer
         function editFloorplan(){   
    var myData;
    $.ajax({
type: "POST",
contentType: "application/json; charset=utf-8",
url:"callfunctions.php?editfloorplan=true&apt_id="+id+"&propertyid="+<?php echo $propertyid;?>+"&floornumber="+$("#floornumber").val()+"&apt_tag="+$("#housenumber").val()+"&monthlyincome="+$("#monthrent").val()+"&marketvalue="+ $("#mktvalue").val()+"&elec_meter="+$("#elecmeter").val()+"&water_meter="+$("#watermeter").val()+"&current_water_reading="+$("#metereading").val()+"&receipt_due="+$("#receipt_due").val(),
data: myData,
success: function(data) {if(data.status==true){alert('saved');$("#editfloor").dialog("close");reload_table();}},error: function(data) {if(data.status !=true){alert('data not saved,try again');}}
},'json');
         }
                  function addFloorplan(){ 
                  if($("#floornumberadd").val()=='' || $("#housenumberadd").val()=='' ||$("#monthrentadd").val()=='' || $("#mktvalueadd").val()=='' || $("#elecmeteradd").val()=='' || $("#watermeteradd").val()==''|| $("#metereadingadd").val()=='' ){ alert("please supply all details"); return false;}
    var myData;
    $.ajax({
type: "POST",
contentType: "application/json; charset=utf-8",
url:"callfunctions.php?addfloorplan=true&apt_id="+id+"&propertyid="+<?php echo $propertyid;?>+"&floornumber="+$("#floornumberadd").val()+"&apt_tag="+$("#housenumberadd").val()+"&monthlyincome="+$("#monthrentadd").val()+"&marketvalue="+ $("#mktvalueadd").val()+"&elec_meter="+$("#elecmeteradd").val()+"&water_meter="+$("#watermeteradd").val()+"&current_water_reading="+$("#metereadingadd").val()+"&receipt_due="+$("#receipt_dueadd").val(),
data: myData,
success: function(data) {if(data.status==true){alert('saved');$("#addfloor").dialog("close");reload_table();}},error: function(data) {if(data.status !=true){alert('data not saved,try again');}}
},'json');
         }
    //delete house     
      function deleteFloorplan(){   
    var myData;
      $.ajax({
type: "POST",
contentType: "application/json; charset=utf-8",
url:"callfunctions.php?deletefloorplan=true&apt_id="+id,
data: myData,
success: function(data) {if(data.status==true){alert('deleted');$("#deletefloor").dialog("close");reload_table();}},error: function(data) {if(data.status !=true){alert('data not saved,try again');}}
},'json')
         } 
         var alldeposits={}; var depositvalues=[];var selector; 
  //add a deposit       
 function populateDeposits(){
   $(".depositdesc").each(function(e){ 
       selector=$(this).attr("title");//assign title attribute to variable,will bes used as selector for text value
     desc=$(this).val();
           value=$(selector).val();
           if(desc !=='' && value !==''){ //check for nulls
           alldeposits[desc]=value;
       }
           });  
    var myData;
      $.ajax({
type: "POST",
contentType: "application/json; charset=utf-8",
url:"callfunctions.php?savedeposits=true&aptid="+id+"&alldeposits="+JSON.stringify(alldeposits),
data: myData,
success: function(data) {if(data.status==true){alert('saved');$("#adddeposit").dialog("close");alldeposits={}; //empty array
        }},error: function(data) {if(data.status !=true){alert('data not saved,try again');}}
},'json')          
         // alert(JSON.stringify(alldeposits));
 }
 //load photos for this property
 $("#propertyphotos").on("click",function(e){
     e.preventDefault();
    $("#gallery").load("lookupphoto.php?categories=all&propertyidphoto="+<?php echo $propertyid;?>,function(e){
        //get ids of photos
$(".photos").each(function(e){
$(this).on("click", function(event) {
   event.preventDefault();
   alert($(this).attr("id"));
  
    });});

     });//load
     });//click
     

     
     
 //get unique array
 Array.prototype.unique=function()
 {
     var tmp={},out=[];
     for (var i=0,n=this.length;i< n;++i)
     { if(!tmp[this[i]]){
             tmp[this[i]]=true;
             out.push(this[i]);}
     }
 return out;
    }; 
 $("#chargeitems").click(function(e){  
     e.preventDefault();
     getChargeItems();
 });
    function getChargeItems(){
 $("#tabs-5").load("loadpropertydetails.php?table=chargeitems",function(e){
      $('#treport1').dataTable({

        });
        //add chargeables
 $("#addchargeableitem").on("click",function(e){
     e.preventDefault();
	$("#addchargeables").dialog({
			modal:false,
                        title:"Add Chargeable Items",
                        width:400,
			buttons: {
"Save Item":addChargeables,
Cancel: function() {
$("#addchargeables").dialog("close");
}
}
        

		});
  });
  //delete chargeableitems
 $(".deletechargeitem").each(function(e){   
  $(this).on("click",function(e){
     id=$(this).attr("id");
 $(function() {
$("#deleteitem").dialog({
resizable: false,
position:"center",
title:"Confirm deletion?",
modal:false,
buttons: {
"Delete": function() {
deleteChargeItem(id);
},
Cancel: function() {
$(this).dialog( "close" );
}
}
});
});

     });
 });//each function
  
      });
  }
  //add chargeable items for a property
function addChargeables(){
if($("#itemadd").val()==''||$("#chargeamountadd").val()==''){
    
alert("please supply item details");}
else{
     var vat=0;
    var commission=0;
    var isdeposit=0;
  
if($("#chargeitemvat:checkbox:checked").length>0){vat=1;} 
if($("#chargeitemcommission:checkbox:checked").length>0){commission=1;} 
if($("#chargeitemdeposit:checkbox:checked").length>0){isdeposit=1;} 
    var myData;
      $.ajax({
type: "POST",
contentType: "application/json; charset=utf-8",
url:"callfunctions.php?savechargeables=true&propid="+<?php echo $propertyid;?>+"&itemid=0"+"&item="+$("#itemadd").val()+"&amount="+$("#chargeamountadd").val()+"&vat="+vat+"&commission="+commission+"&deposit="+isdeposit,
data: myData,
success: function(data) {if(data.status==true){alert('saved');
$("#itemadd").val('');$("#chargeamountadd").val('');
getChargeItems();
}},error: function(data) {if(data.status !=true){alert('data not saved,try again');}}
},'json');     

} }  

//delete chargeable items for a property
      function deleteChargeItem(id){   
    var myData;
      $.ajax({
type: "POST",
contentType: "application/json; charset=utf-8",
url:"callfunctions.php?deletechargeitem=true&itemid="+id,
data: myData,
success: function(data) {if(data.status==true){alert('deleted');$("#deleteitem").dialog("close");getChargeItems();}},error: function(data) {if(data.status !=true){alert('Record not deleted,try again');}}
},'json');
         } 

});
</script>
<style>
.ui-menu { width: 150px;
}
</style>
<?php 
echo '</head><body>';
echo '<div id="form">';
// echo '<h3>'.$wheat.$spacer.$logopath.$property['company_name'].' | Property Manager | <span style="color:black !important" >'.strtoupper(findpropertybyid($_SESSION['propertyid'])).'</span><div id="loggedin">'.$loggedin.' '.$admin.' | '.$clock.' '.$time.'</div>'.$endwheat.'</h3>' .'<hr/>'; 

echo '<div id="header">' . $spacer . $logopath . '<div id="loggedin">' . $loggedin . ' ' . $admin . ' | ' . $clock . ' ' . $time . '</div>';

$agentid = get_agent_id_from_username(trim(htmlspecialchars($_SESSION['username'])));
if (!$agentid) {
    header('Location: logout.php');
}

echo "</div>".$wheat.'&nbsp;&nbsp;'.$endwheat;

echo '<table id ="menulayout"><tr><td valign="top">';
echo $sidebar;
echo '</td><td>'.str_repeat("&nbsp;", 10).'</td><td>';
echo updatedata(); //called from input forms
echo '</td></tr></table>';
echo '</div>';?>
<div id="editfloor" style="display:none;">
    <table><tr><td><label>Floor </label></td><td><input type="text"  id="floornumber" style="width:200px !important"></td></tr>
        <tr><td><label>House No </label></td><td><input type="text" class="ui-widget-content" id="housenumber" style="width:200px !important"></td></tr>
        <tr><td><label>Rent/Month </label></td><td><input type="text" class="ui-widget-content" id="monthrent" style="width:200px !important"></td></tr>
        <tr><td><label>Mkt. Value </label></td><td><input type="text" class="ui-widget-content" id="mktvalue" value="1" style="width:200px !important"></td></tr>
        <tr><td><label>Elec Meter </label></td><td><input type="text" class="ui-widget-content" value="n/a" id="elecmeter" style="width:200px !important"></td></tr>
<tr><td><label>Water Meter </label></td><td><input type="text" class="ui-widget-content"  id="watermeter" style="width:200px !important"> </td></tr>   
<tr><td><label>Meter Reading </label></td><td><input type="text" class="ui-widget-content" id="metereading" style="width:200px !important"></td></tr> 
<tr><td><label>Receipt_due </label></td><td><input type="text" class="ui-widget-content" id="receipt_due" value="8" style="width:200px !important"></td></tr> 
</table>
</div>
<div id="addfloor" style="display:none;">
    <table><tr><td><label>Floor </label></td><td><input type="text"  id="floornumberadd" style="width:200px !important"></td></tr>
        <tr><td><label>House No </label></td><td><input type="text" class="ui-widget-content" id="housenumberadd" style="width:200px !important"></td></tr>
        <tr><td><label>Rent/Month </label></td><td><input type="text" class="ui-widget-content" id="monthrentadd" style="width:200px !important"></td></tr>
        <tr><td><label>Mkt. Value </label></td><td><input type="text" class="ui-widget-content" id="mktvalueadd" value="1" style="width:200px !important"></td></tr>
        <tr><td><label>Elec Meter </label></td><td><input type="text" class="ui-widget-content" id="elecmeteradd" value="n/a" style="width:200px !important"></td></tr>
        <tr><td><label>Water Meter </label></td><td><input type="text" class="ui-widget-content" id="watermeteradd" value="n/a" style="width:200px !important"> </td></tr>   
        <tr><td><label>Meter Reading </label></td><td><input type="text" class="ui-widget-content" id="metereadingadd" value="n/a" style="width:200px !important"></td></tr> 
<tr><td><label>Receipt_due </label></td><td><input type="text" class="ui-widget-content" id="receipt_dueadd" value="8" style="width:200px !important"></td></tr> 
    </table>
</div>

<div id="deletefloor" title="Confirm Delete" style="display:none;">Do you want to delete record?  </div>
<div id="deleteitem" title="Confirm Delete" style="display:none;">Do you want to delete record?  </div>
<div id="adddeposit" title="Add deposit" style="display:none;">
    <!--load deposits here -->
    
</div>
<!--add charge items-->
<div id="addchargeables" style="display:none;">
    <input type="text" placeholder="item" id="itemadd" />
    <input type="text"  placeholder="amount" id="chargeamountadd" />
   <p> Has Vat <input type="checkbox" value="1"  id="chargeitemvat" />
    Has Commission<input type="checkbox" value="1"  id="chargeitemcommission" />
   Is Deposit<input type="checkbox" value="1"  id="chargeitemdeposit" /></p>
</div>


<script type="text/javascript" src="../js/jquery.dataTables.min.js"></script>
<script src="../js/ui/jquery.ui.core.js"></script>
	<script src="../js/ui/jquery.ui.widget.js"></script>
	<script src="../js/ui/jquery.ui.mouse.js"></script>
	<script src="../js/ui/jquery.ui.button.js"></script>
	<script src="../js/ui/jquery.ui.draggable.js"></script>
	<script src="../js/ui/jquery.ui.position.js"></script>
	<script src="../js/ui/jquery.ui.resizable.js"></script>
	<script src="../js/ui/jquery.ui.button.js"></script>
<script type="text/javascript" src="../js/ui/jquery.ui.dialog.js"></script>
</body>

