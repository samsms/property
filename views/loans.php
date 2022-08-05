<?php
@session_start();
/*a list of all tenants available/adding and deleting them
 */
include 'display.php';
@include '../modules/functions.php';
@date_default_timezone_set("Africa/Nairobi");

echo  $htmlheaders;
 $property=  getSettings();
echo '<head><title>Property Manager |'.$property['company_name'].'</title>';
echo $meta;
//echo $javascript;
echo '<link rel="stylesheet" type="text/css" media="screen" href="'.$baseurl.'css/overall2.css" />';
echo '<script src="'.$baseurl.'/js/jquery.ui.sortable.js" type="text/javascript"></script>';

echo $jquery;
//later set variable to session
$admin='<u>'.$_SESSION['username'].'</u>';


?>

<script>
$(function() {
	$( "#menu" ).menu();
});

var propid;
</script>
<!-- <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css"> -->
<script src="../js/logout.js"></script>

<style>
.ui-menu { width: 150px;
}
</style>

 <?php 

echo '</head><body>';
echo '<div id="form1">';
	//echo '<h2>'.$wheat.$spacer.$logopath.$property['company_name'].' | Property Manager <span style="color:black"><img src="../images/cursors/agent.png"> '.findpropertybyid($_SESSION['propertyid']).'</span><div id="loggedin">'.$loggedin.' '.$admin.' | '.$clock.' '.$time.'</div>'.$endwheat.'</h2>' .'<hr/>'; 
	echo '<div id="header">' . $spacer . $logopath . '<div id="loggedin">' . $loggedin . ' ' . $admin . ' | ' . $clock . ' ' . $time . '</div>';

	$agentid = get_agent_id_from_username(trim(htmlspecialchars($_SESSION['username'])));
	if (!$agentid) {
		header('Location: logout.php');
	}
echo '</div>';
echo $wheat.'&nbsp;&nbsp;'.$endwheat;
echo '<table><tr><td>';
echo $sidebar;
echo '</td><td>&nbsp;&nbsp;</td><td>';
echo '<fieldset class="fieldsettenants">';

echo '<legend><b>Loans LISTING</b></legend>';
?>
<div>
<button id="myBtn">Open Modal</button>

<!-- The Modal -->
<div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <span class="close">&times;</span>
    <p>Some text in the Modal..</p>
  </div>

</div>

	<button> New Loan</button>
	<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog">
	<div class="modal-content">
		<div class="modal-header">Apply new Loan</div>
		<div class="modal-body">
			<form>
				<div>
					<select class="form-control">
						<option>Select LandLord</option>
					</select>
				</div>
				<div class="m-1">
					<input class="form-control" type="text" name="amount" placeholder="amount" />
				</div>
				<div >

				<textarea name="reason">
					
				</textarea>

				<textarea name="reason">
					
				</textarea>
				</div>
			</form>
		</div>
	</div>
	<div id="id01" class="w3-modal">
  <div class="w3-modal-content">

    <header class="w3-container w3-teal">
      <span onclick="document.getElementById('id01').style.display='none'"
      class="w3-button w3-display-topright">&times;</span>
      <h2>Modal Header</h2>
    </header>

    <div class="w3-container">
      <p>Some text..</p>
      <p>Some text..</p>
    </div>

    <footer class="w3-container w3-teal">
      <p>Modal Footer</p>
    </footer>

  </div>
</div>
</div>
</div>
</div>
<table cellpadding="0" cellspacing="0" border="0" class="display" id="drafts">
	<tr>
	<th>Date</th>
	<th>Property</th>
	<th>Land Lord</th>
	<th>Amount</th>
	<th>Reason</th>
	<th>Status</th>
	<th>Action</th>
</tr>
<tr>
	<td>P<td>
		
	</td></tr>

</table>
<script>
// Get the modal
var modal = document.getElementById("myModal");

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal 
btn.onclick = function() {
  modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
</script>
<?php

// require('../tables/edittenant.php');

echo '</fieldset>';
echo '</body>';
?>

