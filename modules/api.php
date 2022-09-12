<?php
require '../includes/config.php';
include 'functions.php';
 $resource=$_GET['resource'];
// error_reporting(1);
// error_reporting(1);
// ini_set('error_reporting', 1);
//
if(!(isset($_GET["resource"])&&$_GET["resource"]=="login")){
//authorize();
}
function error(){
	return	json_encode(array("success"=>false,"message"=>"error occured"));
}
function authorize(){
	$token=json_decode(base64_decode(base64_decode($_GET['token'])));
// die("SELECT * FROM `accesslevels` WHERE (`username` = '$token->username' AND `password` = '$token->password')");
	$result= generete_data("SELECT * FROM `accesslevels` WHERE (`username` = '$token->username' AND `password` = '$token->password')");
	
	if(!$result['success']){
		$result=array("success"=>false,"message"=>"Access Denied");
		die( json_encode($result));	
		//array("success"=>true,""=>));
	}else{
		return true;
	}
}
if(!isset($_GET['resource'])){
	$result=array("success"=>false,"message"=>"Access Denied");
	die( json_encode($result));
	
}
else {
	$resource=$_GET['resource'];
	$prop_id=$_GET['property_id'];
	$username=$_GET['username'];
	$password=$_GET['password'];

}

function generete_data($sql){
//     defined('DB_SERVER') ? null : define("DB_SERVER", "localhost");
// defined('DB_USER')   ? null : define("DB_USER", "techsava_rpms");
// defined('DB_PASS')   ? null : define("DB_PASS", "rivercourt#123");
// defined('DB_NAME')   ? null : define("DB_NAME", "techsava_rivercourt");
	$conn=mysqli_connect(DB_SERVER,DB_USER,DB_PASS,DB_NAME);
	$result=mysqli_query($conn,$sql) ;
	$number=mysqli_num_rows($result);
	$data=[];
	if($number>0){
		while ($res=mysqli_fetch_assoc($result)) {
				$data[]=$res;
		}
			$result=array("success"=>true,"data"=>$data);
	}
	else{
	$result=array("success"=>false,"message"=>"No data available at the moment");
	}
return $result;
}

function execute($sql){
	//     defined('DB_SERVER') ? null : define("DB_SERVER", "localhost");
	// defined('DB_USER')   ? null : define("DB_USER", "techsava_rpms");
	// defined('DB_PASS')   ? null : define("DB_PASS", "rivercourt#123");
	// defined('DB_NAME')   ? null : define("DB_NAME", "techsava_rivercourt");
		$conn=mysqli_connect(DB_SERVER,DB_USER,DB_PASS,DB_NAME);
		if(mysqli_query($conn,$sql)){
			return json_encode(array("success"=>true,"data"=>"data added successifully"));
		}
			
		else{
			return json_encode(array("success"=>false,"data"=>"failed to add data"));
		}
		
	}
if($resource=="propertie"){
	echo json_encode(generete_data("SELECT *,
	(select count(*) FROM `floorplan` f WHERE f.`propertyid`=p.propertyid ) as  total_houses,
	(select count(*) FROM `floorplan` f WHERE f.`propertyid`=p.propertyid and isoccupied=1) as  occupied ,
	(select count(*) FROM `floorplan` f WHERE f.`propertyid`=p.propertyid and isoccupied=0) as  vaccant
	 from agentproperty a inner join properties p on p.propertyid=a.property_id  
	where a.agent_id=4"));
}
else if($resource=="properties"){
	$startdate=date("Y-m-01");
	$enddate=date("Y-m-t");
	echo json_encode(generete_data("select p.*,(select count(*) FROM `floorplan` f WHERE f.`propertyid`=p.propertyid ) as  total_houses,
	(select count(*) FROM `floorplan` f WHERE f.`propertyid`=p.propertyid and isoccupied=1) as  occupied ,
	(select count(*) FROM `floorplan` f WHERE f.`propertyid`=p.propertyid and isoccupied=0) as  vaccant
	,a.*, prop.property_id , sum(prop.debit)as debit,sum(prop.credit) as credit,sum(prop.bal) as bal 
	from (select property_id,x.idno,ifnull(sum(x.credit),0) as debit,ifnull(sum(x.debit),0) as credit,(ifnull(sum(x.credit),0)-ifnull(sum(x.debit),0))
	 as bal from (SELECT property_id,invoices.idno,invoices.amount as credit,(SELECT sum(amount) as debit FROM recptrans
	  WHERE invoicenopaid=invoices.invoiceno AND revsd=0 ) as debit FROM invoices where invoices.revsd=0 AND invoicedate between 
	  '$startdate' AND '$enddate' )x group by x.idno) prop join agentproperty a on a.property_id=prop.property_id
	   join properties p on p.propertyid=prop.property_id  group by prop.property_id order by bal desc  "));

}

else if($resource=="tenants"&&isset($_GET['property_id']))
{	

	echo json_encode(generete_data("SELECT * FROM `tenants` WHERE `property_id`=$prop_id"));
}

// nnnn
else if($resource=="agentproperties"&&isset($_GET['agentid']))
{	
	$startdate=date("Y-m-01");
	$enddate=date("Y-m-t");
//	die($enddate);
	// display property list per agent id provided. ---->>
	echo json_encode(generete_data("SELECT *,(select count(*) FROM `floorplan` f WHERE f.`propertyid`=p.propertyid ) as  total_houses,
	(select count(*) FROM `floorplan` f WHERE f.`propertyid`=p.propertyid and isoccupied=1) as  occupied ,(select count(*) FROM `floorplan` f 
	WHERE f.`propertyid`=p.propertyid and isoccupied=0) as  vaccant from agentproperty a inner join properties p on p.propertyid=a.property_id  
	iner join 
	
	
	where a.agent_id=4 "));
	//echo json_encode(generete_data("SELECT * ,(sum count (*) FROM `floorplan` f WHERE f.`propertyid`=p.propertyid and isoccupied=1)as  occupied,SELECT * ,(sum count (*) FROM `floorplan` f WHERE f.`propertyid`=p.propertyid and isoccupied=0)as  vaccant  FROM `properties p` WHERE `agentid`=$prop_id"));
	
}
else if($resource=="tenant_statement"&&isset($_GET["prop_id"])&&isset($_GET['tenant_id'])&&isset($_GET['start_date'])&&isset($_GET['end_date'])){
//    die("ii"); die("ss");	
 $tenantid=$_GET['tenant_id'];//5993;
 $propid=$_GET["prop_id"];//338;
 $startdate = date("Y-m-d", strtotime($_GET['start_date']));
 $enddate =  date("Y-m-d", strtotime($_GET['end_date']));
//  $mysqli = getMysqliConnection();
 $mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME, 3306) ;
 //die("ii");
//  if($mysqli){
// 	// die($mysqli);
//  }
 date_default_timezone_set('Africa/Nairobi');
//  $startdate = date("Y-m-d", strtotime($startdate));
//  $enddate = date("Y-m-d", strtotime($enddate));
 $invoicenos = array();
 $invoicedetails = array();
 $allinvoicedetails = array();
 $suminvoiceamount = array();
 $sumpaidamount = array();
 $sumbal = array();

 $bftot=0; $dtot =0; $ctot=0; $gtot=0;
 if ($mysqli->connect_errno) {
	 echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") ";
 }
 //$res = $mysqli->query("SELECT recptrans.recpno,recptrans.idno,recptrans.rdate,recptrans.rmks as narration,recptrans.is_deposit as transactiontype,recptrans.invoicenopaid FROM recptrans WHERE recptrans.idno='$tenantid' AND recptrans.revsd=0 AND property_id='$propid' AND rdate between '$startdate' AND '$enddate' ") or die($mysqli->error);
 $querybf = $mysqli->query("SELECT SUM(amount) as bal,idno, tno FROM invoices WHERE invoices.idno='$tenantid' AND invoices.revsd=0 AND property_id='$propid' AND invoicedate < '$startdate' AND invoicecredit ='0'
 UNION SELECT SUM(amount*-1) as bal,idno, tno FROM invoices WHERE invoices.idno='$tenantid' AND invoices.revsd=0 AND property_id='$propid' AND invoicedate < '$startdate' AND invoicecredit ='1'
 UNION SELECT SUM(-1*recptrans.amount) as bal,idno, tno FROM recptrans WHERE recptrans.idno='$tenantid' AND property_id='$propid' AND recptrans.rdate < '$startdate' AND recptrans.revsd=0  GROUP BY idno") or  die($mysqli->error);
//  die("SELECT SUM(amount) as bal,idno, tno FROM invoices WHERE invoices.idno='$tenantid' AND invoices.revsd=0 AND property_id='$propid' AND invoicedate < '$startdate' AND invoicecredit ='0'
// //  UNION SELECT SUM(amount*-1) as bal,idno, tno FROM invoices WHERE invoices.idno='$tenantid' AND invoices.revsd=0 AND property_id='$propid' AND invoicedate < '$startdate' AND invoicecredit ='1'
// //  UNION SELECT SUM(-1*recptrans.amount) as bal,idno, tno FROM recptrans WHERE recptrans.idno='$tenantid' AND property_id='$propid' AND recptrans.rdate < '$startdate' AND recptrans.revsd=0  GROUP BY idno");
	 $res = $mysqli->query("SELECT invoices.idno,invoices.invoiceno,invoices.invoicedate,IF(invoicecredit ='1',invoices.amount,'0')as credit,IF(invoicecredit ='1','0',invoices.amount)  as debit,invoices.remarks as narration,IF(invoicecredit ='1','Credit Note','Invoice') as transaction_type FROM invoices WHERE invoices.idno='$tenantid' AND invoices.revsd=0 AND property_id='$propid' AND invoicedate between '$startdate' AND '$enddate'
							 UNION SELECT recptrans.idno,recptrans.recpno,rdate,amount as credit,'0'as debit,rmks,'Receipt' as transaction_type FROM recptrans WHERE recptrans.idno='$tenantid' AND recptrans.revsd=0 AND property_id='$propid' AND rdate between '$startdate' AND '$enddate' ORDER BY invoicedate") or die($mysqli->error);
	 $entity = strtoupper(findtenantbyid($tenantid));
 
//  echo '<table class="treport" ><thead>
//  <tr><td><img src="../images/cursors/logo1.png" style="height:100px;width:140px;"></td><td colspan="8" style="background-color:beige"><h3><center>STATEMENT FOR <u>' . $entity . '</u> OF <i>' . strtoupper(findpropertybyid($propid)) . '</i><br><br>&nbsp;FOR THE PERIOD&nbsp;' .date('d-m-Y',strtotime($startdate)). '&nbsp;TO&nbsp;' . date('d-m-Y',strtotime($enddate)) . '</center></h3></td></tr>
//  <tr>
//  <th><center><u>Date</u></center></th>
//  <th><center><u>Invoice/Receipt No</u></center></th>
//  <th><center><u>Transaction</u></center></th>
//  <th><center><u>Narration</u></center></th>
//  <th><center><u>Unit Amount</center></u></th>
//  <th><center><u>Debit</center></u></th>
//  <th><center><u>Credit</center></u></th>
//  <th><center><u>Balance</center></u></th></tr></thead><tbody>';
 //die($querybf1);
 while ($row1 = $querybf->fetch_assoc()) {
 $bftot = $bftot + $row1['bal'];
 } 
//  echo '<tr><td colspan="4" align="right"><b>B/F Amount</b></td><td></td><td></td><td align=right>'.number_format($bftot,2).'&nbsp;&nbsp;</td></tr>';
$statements=array(); 

while ($row = $res->fetch_assoc()) {
 //get all payments from recptrans for a given invoice 
 
   
	 $tenantdetails = getTenantDetails($row['idno']);
	 $aptid = getApartmentFromTenant($row['idno']);
	 $aptdetails = getApartmentDetails($aptid);
 
	 $invoice = getInvoiceDetails($row['invoiceno']);
	 $rent=getRentItemFromInvoice($row['invoiceno']);
	$dtot = $dtot + $row['debit'];
	$ctot = $ctot + $row['credit'];  
	$gtot = $gtot + $row['debit']- $row['credit'];
	$data=array(
	"date"=>date("d-m-Y", strtotime($row['invoicedate'])),
	"invoiceno"=>$row['invoiceno'],
	"trans_type"=> $row['transaction_type'] ,
	"narration"=> $row['narration'] ,
	"unit_amount"=>$rent,
	"debit"=>number_format($row['debit']),
	"credit"=>number_format($row['credit']),
	"balance"=> number_format($gtot+$bftot));
	array_push($statements,$data);

 }
 
 
//  echo '</tbody>';
 
//  echo '<tfoot><tr><td><b>GRAND TOTAL</b></td><td></td><td></td><td></td><td></td><td align=right><b>' . number_format($dtot, 2) . '&nbsp;&nbsp;</b></td><td align=right><b>' . number_format($ctot, 2) . '&nbsp;&nbsp;</b></td><td align=right><b>' . number_format(($dtot-$ctot)+$bftot, 2) . '&nbsp;&nbsp;</b></td></tr></tfoot>';
//  echo '</table>';
$total=array("debit"=>$dtot,"credit"=>$ctot,"balance"=>($dtot-$ctot)+$bftot);
mysqli_close($mysqli);
echo json_encode(
	array(
		"success"=>true,
		"entity_id"=>$tenantid,
		"entity"=>$entity,
		"details"=>$aptdetails[0],
		"statement"=>$statements,
		"summary"=>$total,
		"bff"=>$bftot
	)
);
 
    //  echo json_encode(generete_data($sql));  
}
else if($resource=="feedback"){
	//die("dd");
	if(isset($_GET['propid'])&&isset($_GET['aptid']) &&isset($_GET['message'])){
		$propid=$_GET['propid'];
		$aptid=$_GET['aptid'];
		$message=$_GET['message'];
		echo execute("insert into feedback(`propid`,`aptid`,`message`) values('$propid','$aptid','$message')");

	}
else{
echo error();
}
	
}
else if($resource=="addtenant"){
	//die("dd");

		$propid=$_POST['propid'];
		$aptid=$_POST['aptid'];
		$name=$_POST['name'];
		$idno=$_POST['idno'];
		$phone=$_POST['phone'];
		$sql="INSERT INTO `tenants_temp` (`id`, `propid`, `aptid`, `name`, `idno`, `phone`) VALUES (NULL, '$propid', '$aptid', '$name', '$idno', '$phone'); ";
		
		echo execute($sql);

	
// else{
// echo error();
// }
	
}
// else if($resource=="feedback"){
// 	$prop_id=$_GET['prop_id'];
// 	$apt_id=$_GET['apt_id'];
// 	$message=$_GET['message'];
// 	execute("insert into feedback(`propid`,`aptid`,`message`) values('$propid','$apt_id','$message')");

// }
else if($resource=="login"&&isset($_GET['username'])&&isset($_GET['password']))
{	
	// display tenant statement provided tenant id
	
	$result= generete_data("SELECT * FROM `accesslevels` WHERE (`username` = '$username' AND `password` = '$password')");
	if($result['success']){
		$token=base64_encode(json_encode(array("username"=>$username,"password"=>"$password")));
		echo json_encode(array("success"=>true,"token"=>base64_encode($token)));
		//array("success"=>true,""=>));
	}else{
	    	echo json_encode(array("success"=>false,"message"=>"Wrong Username Or Password"));
	}
}

else{
		$result=array("success"=>false,"message"=>"Access Denied");
	echo json_encode($result);
}



function login(){

}
