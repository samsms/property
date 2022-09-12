<?php


include 'views/display.php';

include  'modules/functions.php';
// unset($landlord);

$_SESSION['timestamp'] = time();
echo  $htmlheaders;
echo '<head><title>Property Manager|Proper Properties</title>';
echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
echo '<script type="text/javascript" src="' . $baseurl . 'js/jquery.min.js"></script>';
echo '<link rel="stylesheet" href="themes/base/jquery.ui.all.css">';
echo '<link rel="stylesheet" href="css/overall.css" type="text/css" />';
$pedding_receipts = getTempReceipts();
$pedding_invoices = getTempInvoices();
//later set variable to session
$admin = "<u>" . $_SESSION['username'] . '</u>';
$property =  getSettings();
$expiredleases =  getExpiredLeases();

?>
<link rel="stylesheet" type="text/css" href="css/buttons.css" media="screen" />
<script src="js/jquery.msgBox.js" type="text/javascript"></script>

<script>
  $(document).ready(function() {

    $(function() {
      $("#menu").menu();
    });


    <?php echo $loader; ?>
    //register agent
    $("#formregisteragent").hide();
    $("#registeragent").click(function() {

      $("#formregisteragent").show();

    });
    $("#close").click(function() {

      $("#formregisteragent").hide();
      $('#agentform')[0].reset();
    });
    //registration..
    $("#btnregister").click(function(e) {
      e.preventDefault();
      if ($('#agentname').val() == "" || $('#agentpassword').val() == "" || $('#agentphone').val() == "" || $('#agentaddress').val() == "") {
        $(".validateTips").replaceWith("<font size='2' color='red'><center>All fields required!</center></font>");
      } else {
        var jqxhr = $.get("modules/registeragent.php?agentname=" + $("#agentname").val() + "&agentpassword=" + $('#agentpassword').val() + "&usergroup=" + $("#usergroup").val() + "&agentphone=" + $("#agentphone").val() + "&agentaddress=" + $("#agentaddress").val(), function() {

          })
          .done(function() {
            $(".validateTips").replaceWith("<font size='2' color='green'><center>Successfully Registered</center></font>");
            $('#agentform')[0].reset();
            location.reload(true);
          })
          .fail(function() {
            $(".validateTips").replaceWith("<font size='2' color='red'><center>Error in registering</center></font>");
          })
      }
    });


  });
</script>
<script>
  $(document).ready(function() {
    //register user
    $("#userregister").hide();
    <?php if ($_SESSION['usergroup'] == 1) { ?>
      $("#adduser").click(function() {
        $("#userregister").show();

        $("#systemusers").load("./modules/accountsprocess.php?systemusers=true", function() {
          $(".edituser").click(function() {
            id = $(this).attr('title');
            var jqxhrpost = $.post("modules/accountsprocess.php?edituser=true&user_id=" + $("#accessgrpid" + id).val() + "&username=" + $("#username" + id).val() + "&group=" + $("#usergroup" + id).val() + "&password=" + $('#userpassword' + id).val() + "&status=" + $("#userstatus" + id + " :selected").val(), function() {

              })
              .done(function(data) {
                $(".validateTips1").html("<font size='2' color='green'><center>" + data.status + "</center></font>");

              })
              .fail(function(data) {
                $(".validateTips1").html("<font size='2' color='red'><center>" + data.status + "</center></font>");
              });
          });
        });

      });

    <?php } ?>
    $("#closeuser").click(function() {

      $("#userregister").hide();
      $('#useraddform')[0].reset();
    });
    //registration..
    $("#confpassword").change(function(e) {
      if ($("#password").val() == $("#confpassword").val()) {
        $(".validateTips1").html("<font size='2' color='green'><center>Passwords match</center></font>");
      } else {
        $(".validateTips1").html("<font size='2' color='red'><center>Passwords do not match!</center></font>");
      }

    });
    $("#btnadduser").click(function(e) {
      e.preventDefault();
      if ($('#username').val() == "" || $('#usergroup').val() == "" || $('#password').val() == "") {
        $(".validateTips1").html("<font size='2' color='red'><center>All fields required!</center></font>");
      } else {
        var jqxhrpost = $.post("modules/adduser.php?username=" + $("#username").val() + "&usergroup=" + $("#usergroup").val() + "&password=" + $("#confpassword").val(), function() {

          })
          .done(function() {
            $(".validateTips1").html("<font size='2' color='green'><center>Successfully Registered</center></font>");
            $('#useraddform')[0].reset();
          })
          .fail(function() {
            $(".validateTips1").html("<font size='2' color='red'><center>Error in registering</center></font>");
          })
      }
    });

    //on load pick first selected property
    $("#loggedinproperty").val($("#propertyid :selected").val());

    //on change of property select,change sessions


  });
</script>
<script>
  $(document).ready(function() {
    //register user
    $("#assignagent").hide();
    $("#assignproperty").click(function() {

      $("#assignagent").show();

    });
    $("#closeagent").click(function() {

      $("#assignagent").hide();
      $('#assignagentform')[0].reset();
    });
    //registration..

    $("#btnassignagent").click(function(e) {
      e.preventDefault();
      if ($('#agentassignname').val() == "" || $('#commission').val() == "") {
        $(".validateTips2").html("<font size='2' color='red'><center>All fields required!</center></font>");
      } else {
        //if all properties are checked
        //if($("#assignagentform input[name='allpropertiescheck']:checked").val() !=0){
        if ($('#allproperties').is(':checked')) {
          var jqxhrpost = $.post("modules/assignagent.php?agentid=" + $("#agentassignname").val() + "&propid=" + $("#propertyassignname").val() + "&propname=" + $('#propertyassignname option:selected').text() + "&commission=" + $("#commission").val() + "&multipleproperties=true", function() {
              $("#dvLoading").html('<img src="/images/ajax_loader_green.gif"></img>');
            })
            .done(function(data) {
              $(".validateTips2").html("<font size='2' color='green'><center>" + data.status + "</center></font>");
              $('#assignagentform')[0].reset();
              $("#dvLoading").hide();
            })
            .fail(function(data) {
              $(".validateTips2").html("<font size='2' color='red'><center>" + data.status + "</center></font>");
              $("#dvLoading").html();
            })

        }
        //if single property                   
        else {
          if ($('#propertyassignname').val() == "") {
            $(".validateTips2").html("<font size='2' color='red'><center>All fields required!</center></font>");
          }
          var jqxhrpost = $.post("modules/assignagent.php?agentid=" + $("#agentassignname").val() + "&propid=" + $("#propertyassignname").val() + "&propname=" + $('#propertyassignname option:selected').text() + "&commission=" + $("#commission").val() + "&singleproperty=true", function() {

            })
            .done(function(data) {
              $(".validateTips2").html("<font size='2' color='green'><center>" + data.status + "</center></font>");
              $('#assignagentform')[0].reset();
            })
            .fail(function(data) {
              $(".validateTips2").html("<font size='2' color='red'><center>" + data.status + "</center></font>");
            })
        }
      }
    });


  });
</script>

<style>
  .ui-menu {
    width: 150px;
  }
</style>
<?php
echo '</head><body>';
echo '<div id="form">'; ?>
<div style="background-color:orange;height:15px;border-radius:8px">>>Insights:<a href="modules/defaultreports.php?report=expiredleases"><?= count($expiredleases) ?>Expired leases)</a></div>
<?php if (@$_REQUEST['error']) {
  echo '<center>' . @$_REQUEST['error'] . '</center>';
}
echo '<div id="header">' . $wheat . $spacer . $logopath . '<div id="loggedin">' . $loggedin . ' ' . $admin . ' | ' . $clock . ' ' . $time . '</div>'; ?>
<?php
$agentid = get_agent_id_from_username(trim(htmlspecialchars($_SESSION['username'])));
if (!$agentid) {
  header('Location: logout.php');
}

?>

<?php echo $endwheat . '</div>';

echo $wheat . '&nbsp;&nbsp;' . $endwheat;

echo '<table id ="menulayout"><tr>
<td valign="top">';
echo $sidebar;
echo '</td><td>' . str_repeat("&nbsp;", 10);
echo '&nbsp;</td><td><table border=0>';
if ($_SESSION['usergroup'] == 1)
  echo '<tr>
  <td width=60%>
  <a href="modules/receipts_approve.php" class="button big green"></br>New Receipts <br>Pending Approval<br>' . $pedding_receipts->count . '</a>
  </td>
  <td>' . str_repeat("&nbsp;", 20) . '
</td>
  <td width=60%>
  <a href="modules/invoices_approve.php" class="button big green"></br>New Invoices <br>Pending Approval<br>' . $pedding_invoices->count . '</a>
  </td>
  <td>' . str_repeat("&nbsp;", 20) . '</td>';
?>
<td>
  <div class="row" style="padding: 10px;">

    <select class="propertyid form-control" style="width:100% ;" name="propertyid">
      <option value=" ">Select Property</option>
      <?php foreach (get_agent_property($agentid) as  $value) {
        $individualresult = explode('#', $value);
        echo '<option  value="' . $individualresult[0] . '"' . ($individualresult[0] == $_REQUEST["propertyid"] ? ' selected="selected"' : '') . '>' . strtoupper($individualresult[1]) . '</option>';
        // echo "<option value='$individualresult[0]' title='$individualresult[2]'>".$individualresult[1]."</option>";
      } ?>
    </select>
    <input style="margin-top:3px;width:100% " type="text" class="ui-widget-content ui-autocomplete-input" placeholder="Tenant Name" id="tenantnamesearch">
    <input style="width:100% ;margin-top:3px" type="text" placeholder="House No" class="ui-widget-content ui-autocomplete-input" id="housenosearch">
    <input style="margin-top:3px;width:100% " type="submit" id="searchtenant" class="text ui-widget-content ui-corner-aEll" value="Search">
  </div>

  <input type="hidden" id="loggedinproperty" value="<?php echo $_REQUEST['propertyid'] ?>">
  <?php if (!$_SESSION['propertyid']) {
    $_SESSION['propertyid'] = @$_REQUEST['propertyid'];
  } ?>
<td>
  </tr>
  <?php
  echo ' <tr>';



  if ($_SESSION['usergroup'] != 3) {
    echo   ' <td width=60%>
  <a href="modules/list.php" class="button big orange"></br>&nbsp;Properties List</a>  
    </td>
    <td>' . str_repeat("&nbsp;", 20) . '</td>
        ';

    echo '<td>
  <a href="#" id="registeragent" class="button big blue"></br>Register User</a>
    </td>
    <td>' . str_repeat("&nbsp;", 10) . '</td>
    <td>
  <a href="#" id="assignproperty" class="button big blue"></br>Assign User Property</a>
    </td>

  </tr>
  
  <tr>
    <td width=60%>
   <a href="modules/landlord_payout.php" class="button big green">
   </br>Today\'s Payout   <br> 
    ' . (landlord_tobepaid()) . ' Landlords<br/>
 
<div id="landlord_pay"></div>  </a>
    </td>
    <td>' . str_repeat("&nbsp;", 10) . '</td>
    <td width=60%>
   <a href="modules/landlord_payout_cumilated.php" class="button big green">
   </br>Cummilated Payout   <br>
    ' . (json_decode(total_accumilated())->number) . ' Landlords<br/>
 
<div id="landlord_pay">
' . json_decode(total_accumilated())->amount . '
</div>
  </a>
    </td>
 </tr>
 ';
  } else {

    //   echo '
    // <tr>

    //   <td>
    //    <a href="modules/reports.php" class="button big orange"></br>Reports Manager</a>
    //   </td>
    //     <td>' . str_repeat("&nbsp;", 15) . '</td>
    //   <td width=70%>
    //   <a href="views/tenantlist.php" class="button big blue"></br>Tenants Manager </a>
    //   </td><td>' . str_repeat("&nbsp;", 15) . '</td>
    //   <td>
    //    <a href="views/accounts.php" class="button big orange"></br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Financials</a>
    //   </td>';
  }
  echo '

</table></td></tr></table></div>';

  echo '<div id="dvLoading"></div>';


  echo '</body>';
  //javascript
  echo '<link rel="stylesheet" href="themes/base/jquery.ui.all.css">
<script src="js/ui/jquery.ui.core.js"></script>
<script src="js/ui/jquery.ui.widget.js"></script>
<script src="js/ui/jquery.ui.position.js"></script>
<script src="js/ui/jquery.ui.tabs.js"></script>
<script src="js/ui/jquery.ui.menu.js"></script>
<link rel="stylesheet" href="css/demos.css">';
  ?>
  <!-- add agent-->
  <div id="formregisteragent" title="Register User">
    <p class="titletr">User Registration <a href="#" style="float:right;" id="close">Close [X]</a></p>
    <p class="validateTips">All form fields are required.</p>

    <form id="agentform" method="post" enctype="multipart/form-data">
      <fieldset>
        <table>
          <tr>
            <td><label for="agentname"><u>User Name</u> &nbsp;</label></td>
            <td><input id='agentname' type="text" name='agentname' style="width:300px;">
            </td>
          </tr>
          <tr>
            <td><label for="agentpassword"><u>User Password</u> &nbsp;</label></td>
            <td><input id='agentpassword' type="password" name='agentpassword' style="width:300px;">
            </td>
          </tr>
          <tr>
            <td><label for="usergroup"><u>User Group</u> &nbsp;</label></td>
            <td><select id='usergroup' type="text" name='usergroup' style="width:300px;">
                <option value="1">ADMIN</option>
                <option value="4">CASHIER</option>
                <option value="2">OFFICE OFFICER</option>
                <option value="5">OFFICE MANAGER</option>
                <option value="3">LANDLORD</option>
              </select></td>
          </tr>
          <tr>
            <td><label for="agentphone"><u>Phone</u> &nbsp;</label></td>
            <td><input id='agentphone' type="text" name='agentphone' style="width:300px;">
            </td>
          </tr>
          <tr>
            <td><label for="address"><u>Address</u> &nbsp;</label></td>
            <td><input id='agentaddress' type="text" value="n/a" name='address' style="width:300px; height:40px;">
            </td>
          </tr>
          <tr>
            <td></td>
            <td><input type="submit" id="btnregister" class="text ui-widget-content ui-corner-all" value="REGISTER USER" style="width:200px;font-weight:bold;" /></td>
          </tr>
        </table>
      </fieldset>
    </form>
  </div>
  <!--add agent ends-->


  <!-- add user-->
  <div id="userregister" title="Register User">
    <p class="titletr">Manage Users<a href="#" style="float:right" id="closeuser">Close [X]</a></p>
    <p class="validateTips1"></p>
    <fieldset id="systemusers">

    </fieldset>

  </div>
  <!--add user ends-->

  <!-- assign agent to property-->
  <div id="assignagent" title="Assign User Property">
    <p class="titletr">Assign User Property <a href="#" style="float:right;" id="closeagent">Close [X]</a></p>
    <p class="validateTips2">All form fields are required.</p>

    <form id="assignagentform" method="post" enctype="multipart/form-data">
      <fieldset>
        <table>
          <tr>
            <td><label for="usergroup"><u>User Name</u> &nbsp;</label></td>
            <td><label><u>Property Name</u> &nbsp;</label></td>
</td>
</tr>
<tr>
  <td><select id='agentassignname' type="text" name='agentassignname' style="width:200px;">
      <option selected="selected" value="">---</option>
      <?php populateallagents() ?>
    </select></td>
  <td><select id='propertyassignname' type="text" name='propertyassignname' style="width:200px;">
      <option selected="selected" value="">---</option>
      <?php populatecommercialproperties(); ?>
    </select></td>
  <input id='commission' type="hidden" value="0" name='commission' style="width:80px;">
</tr>

<tr>
  <td><label for="usergroup"><u>Assign All Properties ?</u> &nbsp;</label></td>
  <td><input id='allproperties' type="checkbox" name='allpropertiescheck' style="width:80px;">
  </td><br />
</tr>

<tr>
  <td></td>
  <td><input type="submit" id="btnassignagent" class="text ui-widget-content ui-corner-all" value="ASSIGN PROPERTY" style="width:200px;font-weight:bold;" /></td>
</tr>
</table>
</fieldset>
</form>
</div>
<!--agent property ends-->

<script>
  $(document).ready(function() {
    $.ajax({
      type: "POST",
      url: "testing.php",

      success: function(data) { //reload div
        $("#landlord_pay").html(data);
      },
      error: function(data) {
        //alert(data);
      }
    });
    $(".propertyid").change(function(e) {
      propid = $(".propertyid :selected").val();
      var myData = "";
      $.ajax({
        type: "POST",
        url: "modules/agentproperty.php?propertyid=" + propid,
        data: myData,
        success: function(data) { //reload div
        },
        error: function(data) {
          //alert(data);
        }
      });

      window.location.replace("home.php?propertyid=" + propid);
    });

    $("#searchtenant").click(function(e) {
      e.preventDefault();

      if ($("#tenantnamesearch").val() !== "" || $("#housenosearch").val() !== "") {
        $tenantname = $("#tenantnamesearch").val();
        $houseno = $("#housenosearch").val();
        window.open("modules/defaultreports.php?report=tenantlist&search=true&tenantname=" + $tenantname + "&houseno=" + $houseno);
      } else {
        alert("Please input search parameters!");
      }
    })


  });
</script>