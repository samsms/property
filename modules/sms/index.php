<!DOCTYPE html>
<html>
<head>
<title>Bulky SMS</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="layout.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	
</head>
<body>
<div class="topnav" id="myTopnav">
		<a href="#home" class="active">Home</a>
		<a href="#news">News</a>
		<a href="#contact">Contact</a>
		<a href="#about">About</a>
		<a href="#sms" data-toggle="modal" data-target="#sms">Single SMS</a>
		<a href="#bulky" data-toggle="modal" data-target="#bulky">Bulky SMS</a>
		<a href="javascript:void(0);" class="icon" onclick="myFunction()">
		<i class="fa fa-bars"></i>
	  </a>
	</div>
<div class="container">
	
</div>

<div class="modal fade" id="sms" role="dialog">
	<div class="modal-dialog">

	  <!-- Modal content-->
	  <div class="modal-content">
		<div class="modal-header">
		<h3>Send Bulky SMS</h3>
		  <button type="button" class="close" data-dismiss="modal">&times;</button>
		</div>
		<div class="modal-body">
			<form action="sendSMS.php" method="POST">
				<h1>Send Single Message</h1>
				<div class="form-group">
					<label for="email">Phone Number:</label>
					<input type="text" class="form-control" id="phone" name="phone">
				</div>
				 <div class="form-group">
				  <label for="comment">Message:</label>
				  <textarea class="form-control" rows="10" id="message" name="message"></textarea>
				</div> 
				<button type="submit" class="btn btn-primary" name="send" value="single">Submit</button>
			</form>
		</div>
		<div class="modal-footer">
		<div class="alert alert-warning" role="alert" align="left">
			<b>Confirm file details before you send</b>
		</div>
		</div>
	  </div>
	</div>
</div>

<div class="modal fade" id="bulky" role="dialog">
	<div class="modal-dialog">

	  <!-- Modal content-->
	  <div class="modal-content">
		<div class="modal-header">
		<h3>Send Bulky Message</h3>
		  <button type="button" class="close" data-dismiss="modal">&times;</button>
		</div>
		<div class="modal-body">
			<form action="send.php" method="POST" enctype = "multipart/form-data">
				<div class="form-group">
				  <label for="comment">Message:</label>
				  <textarea class="form-control" rows="10" id="message" name="message"></textarea>
				</div>
				<div class="form-group">
					<label for="formFileDisabled" class="form-label">Select CSV file</label>
					<input class="form-control" type="file" name="myfile" />
				</div>
				<button type="submit" name="submit" class="btn btn-primary" value="bulky" >Send</button>			  
			</form>
		</div>
		<div class="modal-footer">
		<div class="alert alert-warning" role="alert" align="left">
			<b>Confirm file details before you send</b>
		</div>
		</div>
	  </div>
	</div>
</div>
</body>
</html> 
