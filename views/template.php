<?php
@session_start();
/*a list of all tenants available/adding and deleting them
 */
include_once 'display.php';
@include_once '../modules/functions.php';
@date_default_timezone_set("Africa/Nairobi");
$settings=  getSettings();

echo  $htmlheaders;
echo '<head><title>Property Manager|'.$settings['company_name'].'</title>';
echo '<link rel="stylesheet" href="../css/overall.css" type="text/css" />';
echo '<script type="text/javascript" src="../js/jquery-1.9.1.js"></script>';

echo '<link rel="stylesheet" href="../css/themes/base/jquery.ui.all">';
echo '<link rel="stylesheet" href="../css/overall.css" type="text/css" />';?>
<link rel="stylesheet" href="../css/jquery-ui-git.css" type="text/css" />
<link rel="stylesheet" href="../css/datatables/demo_page.css" type="text/css" />
<link rel="stylesheet" href="../css/datatables/demo_table.css" type="text/css" />
<link rel="stylesheet" href="../css/datatables/dataTables.tableTools.css" type="text/css" />

<link rel="stylesheet" type="text/css" media="screen" href="../css/overall2.css" />

<?php

//later set variable to session
$admin='<u>'.$_SESSION['username'].'</u>';


?>
<script>

var propid;
</script>
<script src="../js/logout.js"></script>

<style>
.ui-menu { width: 150px;
}
</style>
 <?php 
echo '</head><body>';
//echo '<div id="form">';
//echo '<div id="header">'.$wheat.$spacer.$settings['company_name'].'| Property Manager <span style="color:black"><img src="../images/cursors/agent.png"> '.findpropertybyid($_SESSION['propertyid']).'</span><div id="loggedin">'.$loggedin.' '.$admin.' | '.$clock.' '.$time.'</div>'.$endwheat.'</div>'; 
echo '<div id="form">';
echo '<div id="header">' . $spacer . $logopath . '<div id="loggedin">' . $loggedin . ' ' . $admin . ' | ' . $clock . ' ' . $time . '</div>'; 

$agentid = get_agent_id_from_username(trim(htmlspecialchars($_SESSION['username'])));
if (!$agentid) {
  header('Location: logout.php');
}

echo "</div>".$wheat.'&nbsp;&nbsp;'.$endwheat;
echo '<table><tr><td>';
echo $sidebar;
echo '</td><td>&nbsp;&nbsp;</td><td class="fullwidth">';
if ($_REQUEST['page']=="repairs") {
    include 'forms/repairsform.php';  
}
else if ($_REQUEST['page']=="closeperiods") {
    include 'forms/closeperiods.php';  
}
else if ($_REQUEST['page']=="paylandlord") {
    include 'forms/paylandlord.php';  
}
else if ($_REQUEST['page']=="journals") {
    include 'forms/journalentries.php';  
}
else{
  die("bad request");
}

?>

<script type="text/javascript" src="../js/jquery-ui-git.js"></script>

<script src="../js/ui/jquery.ui.core.js"></script>
	<script src="../js/ui/jquery.ui.widget.js"></script>
	<script src="../js/ui/jquery.ui.mouse.js"></script>
	<script src="../js/ui/jquery.ui.button.js"></script>
	<script src="../js/ui/jquery.ui.draggable.js"></script>
	<script src="../js/ui/jquery.ui.position.js"></script>
	<script src="../js/ui/jquery.ui.resizable.js"></script>
	<script src="../js/ui/jquery.ui.button.js"></script>
<script type="text/javascript" src="../js/ui/jquery.ui.dialog.js"></script>
  <script type="text/javascript" src="../js/jquery.dataTables.min.js"></script>
  
  <script>
  $(function(){
    $(".accounts").dataTable();
  });
   $(function(){
    $(".financialyear").dataTable();
  })
   $(function() {
$( "#tabs" ).tabs();
});
$(function() {
$(".datepicker" ).datepicker({
changeMonth: true,
changeYear: true
});
});
   $(".addaccount").on("click",function(e){
     e.preventDefault();
     //$("#addaccount").css("display","block");
	$("#addaccount").dialog({
			modal:false,
                        title:"Add GL Account",
                        width:400
			       

		});
  });
   $(".addfy").on("click",function(e){
     e.preventDefault();
     //$("#addaccount").css("display","block");
	$("#addfinancialyear").dialog({
			modal:false,
                        title:"Add Financial Year",
                        width:400
			       

		});
  });
  
  
  //delete fy
  $(".deletefy").each(function(e){   
 $('body').on("click",".deletefy",function(e){
     id=$(this).attr("id");
 $(function() {
$("#deleteyear").dialog({
resizable: false,
position:"center",
title:"Confirm deletion?",
modal:false,
buttons: {
"Delete": function() {
deleteFinancialYear();
},
Cancel: function() {
$(this).dialog( "close" );
}
}
});
});

//delete gl


     });
 });//each function 
 //
 //delete gl
   //$(".deletegl").each(function(e){   
 $('body').on("click",".deletegl",function(e){
     id=$(this).attr("id");
 $(function() {
$("#deleteaccount").dialog({
resizable: false,
position:"center",
title:"Confirm deletion?",
modal:false,
buttons: {
"Delete": function() {
deleteGlAccount(id);
},
Cancel: function() {
$(this).dialog( "close" );
}
}
});
});

//delete gl


     });
 //});//each function 
 
 //add close period
    $(".addclosep").on("click",function(e){
     e.preventDefault();
     //$("#addaccount").css("display","block");
	$("#addcloseperiod").dialog({
			modal:false,
                        title:"Add Close Period",
                        width:400
			       

		});
  });
  //activate/deactivate financial period
   //$(".editclosep").each(function(e){   
       $('body').on("click",".editclosep",function(e){
                var id=$(this).attr("title");
                var status=$(this).attr("id");
    changeperiodstatus(id,status);
 });
   //})
  //delete close period
   //delete gl
   
 $('body').on("click",".deleteclosepa",function(e){
     var id=$(this).attr("id");
   $(function() {
$("#deleteclosep").dialog({
resizable: false,
position:"center",
title:"Confirm deletion?",
modal:false,
buttons: {
"Delete": function() {
deleteClosePeriod(id);
},
Cancel: function() {
$(this).dialog( "close" );
}
}
});
});

//delete gl


     });
//pay landlord
$('body').on("click",".paylandlord",function(e){
     var id=$(this).attr("id");
     var title=$(this).attr("title");
   $(function() {
var content=$("#payland").dialog({
resizable: false,
width:500,
position:"center",
title:"Confirm Payment?",
modal:false,
buttons: {
"Approve Payment": function() {
  var radioattr=$("input[name='paylandlordradio']:checked");
  var payamount=radioattr.val();
  var propertyid=radioattr.attr("tabindex");
  var closeperiod=radioattr.attr("title");
    var refs=radioattr.attr("src");
      payLandlord(payamount,propertyid,closeperiod,refs);
},
Cancel: function() {
$(this).dialog( "close" );
}
}
});
//content.html("Landlord balance is "+title+" proceed with payment?");
});

//delete gl


     });
 
 //activate period
 
 function payLandlord(payamount,propertyid,closeperiod,refs){
     if(!payamount){
        alert("Please select period to pay");
        return 0;
    }
      var myData;
    $.ajax({
type: "POST",
url:"../modules/accountsprocess.php?paylandlord=true&idclose_periods="+closeperiod+"&amount="+payamount+"&property_id="+propertyid+"&journal_refs="+refs,
data: myData,
success: function(data) {if(data.status==true){alert('Landlord Payment processed');reload_window();}},error: function(data) {if(data.status !=true){alert('Landlord payment not processed,try again');}}
},'json');    
  }
 
  function changeperiodstatus(id,status){
      var myData;
    $.ajax({
type: "POST",
url:"../modules/accountsprocess.php?changeperiodstatus=true&idclose_periods="+id+"&status="+status,
data: myData,
success: function(data) {if(data.status==true){alert('status change');reload_window();}},error: function(data) {if(data.status !=true){alert('Status not changed,try again');}}
},'json');    
  }
 //delete close period
 function deleteClosePeriod(id){   
    var myData;
      $.ajax({
type: "POST",
url:"../modules/accountsprocess.php?deletecloseperiod=true&idclose_periods="+id,
data: myData,
success: function(data) {if(data.status==true){alert('deleted');$("#deleteperiod").dialog("close");reload_window();}},error: function(data) {if(data.status !=true){alert('Not deleted,try again');}}
},'json');
         }
 
 
 
 
  function deleteFinancialYear(id){   
    var myData;
      $.ajax({
type: "POST",
url:"../modules/accountsprocess.php?deleteyear=true&fy="+id,
data: myData,
success: function(data) {if(data.status==true){alert('deleted');$("#deleteyear").dialog("close");reload_window();}},error: function(data) {if(data.status !=true){alert('Not deleted,try again');}}
},'json');
         }   
//delete gl
  function deleteGlAccount(id){   
    var myData;
      $.ajax({
type: "POST",
url:"../modules/accountsprocess.php?deletegl=true&gl="+id,
data: myData,
success: function(data) {if(data.status==true){alert('deleted');$("#deleteaccount").dialog("close");reload_window();}},error: function(data) {if(data.status !=true){alert('Not deleted,try again');}}
},'json');
         }  
  function reload_window(){
      location.reload(true);
  }
  
  </script>





</body>
</html>
