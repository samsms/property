<?php
$action = $_POST['submit'];
$msg = $_POST['message'];

$a = 'Hello codestar';
$b = '254726856965';

function sms($a, $b){
	$baseUrl="https://api.mobitechtechnologies.com/sms/sendsms";

	$ch = curl_init($baseUrl);
	$data= array('api_key' =>'2bebe266ea653868cacc48a2cc26892f30e3bf3b9ba9092d3b519171b0ea1550',
	'username' =>'horine12@gmail.com' ,
	'sender_id' =>'22136' ,
	'message' =>$a ,
	'phone' =>$b );
	$payload = json_encode($data);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Accept:application/json'));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$result = curl_exec($ch);
	echo "Result ".$result;
	curl_close($ch);
	
}

switch($action) {
	case 'bulky':
	$fileName = $_FILES["myfile"]["tmp_name"];

	if ($_FILES["myfile"]["size"] > 0) {
		
		$file = fopen($fileName, "r");

		set_time_limit(300);
		
		while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {

			if($column[1] == "1"){
				$column[1] = 0;
				}
			$to = $column[1];
			sms($msg, $to);
		}
	echo '<script language="javascript">';
	echo 'alert("Marks added successfully")';
	echo '</script>';
	}
	
	break;
	
	case 'single':
		$to = $_POST['phone'];
		sms($msg, $to);
	break;
}
		
?>
