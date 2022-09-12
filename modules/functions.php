<?php
// //die("dd");
// ini_set('display_errors',1);
// ini_set('display_startup_errors',1);
// error_reporting(-1);
@session_start();
@include '../includes/database.php';
@header("Access-Control-Allow-Origin: *");
function loanPaid($propid,$startdate,$enddate){
    $mysqli = getMysqliConnection();
    $sql="SELECT sum(payments.amount) as amount FROM `loan_list` inner  join payments on payments.loan_id=loan_list.id  WHERE `borrower_id`=$propid and (payments.date_created between '$startdate' and '$enddate')";
  //die($sql);
    $query =$mysqli->query($sql) or die(mysqli_error($mysqli));
   // SELECT * FROM `loan_list` inner  join payments on payments.loan_id=loan_list.id  WHERE `borrower_id`=338
   //die( print_r($query->fetch_array()));
    return round($query->fetch_assoc()['amount'],2);
}
function getTenantApt($tenant_id){
    $mysqli = getMysqliConnection();
    $date=date("Y-m-d");
    $sql="select * from tenants where id=$tenant_id ";
    //die($sql);
    $query =$mysqli->query($sql) or die(mysqli_error($mysqli));
    return $query;


}
function gettenants_temp(){
    $mysqli = getMysqliConnection();
    $date=date("Y-m-d");
    $sql="select * from tenants_temp join properties on tenants_temp.propid=properties.propertyid";
    //die($sql);
    $query =$mysqli->query($sql) or die(mysqli_error($mysqli));
    $data=[];
    while($row=mysqli_fetch_assoc($query)){
        $data[]=$row;
    }
    return json_encode($data);
}

function getfeedbacks(){
    $mysqli = getMysqliConnection();
    $date=date("Y-m-d");
    $sql="select * from feedback join properties on feedback.propid=properties.propertyid";
    //die($sql);
    $query =$mysqli->query($sql) or die(mysqli_error($mysqli));
    $data=[];
    while($row=mysqli_fetch_assoc($query)){
        $data[]=$row;
    }
    return json_encode($data);
}

function getTenantfromApt($prop,$apt_tag) {
   
    $apt_tag=ltrim($apt_tag,'0');
    $mysqli = getMysqliConnection();
    $query =$mysqli->query("SELECT Id FROM tenants WHERE Apartment_tag='$apt_tag' AND vacated=0 and property_id='$prop'") or  die(mysqli_error($mysqli));;
    return $query->fetch_assoc()['Id'];
}

function getPropByName($name){
    $mysqli = getMysqliConnection();
    $date=date("Y-m-d");
    $sql="select propertyid as prop from properties where address='$name' ";
    //die($sql);
    $query =$mysqli->query($sql) or die(mysqli_error($mysqli));
    return $query->fetch_assoc()['prop'];

}
function getPrepayment($prop_id){
//    die('ddk');
    $mysqli = getMysqliConnection();
    $date=date("Y-m-d");
    $exits =$mysqli->query("select * from prepayments p  join floorplan f on(p.aptid=f.apt_id) where p.propid=$prop_id   and MONTH(p.date)=MONTH('$date') AND YEAR(p.date)=YEAR('$date') ") or die(mysqli_error($mysqli));
  //  die($sql);
  if(mysqli_num_rows($exits)<1){
    //$query =$mysqli->query($sql) or die(mysqli_error($mysqli));

    return "Reported Successifully";
  }else{
    $data=[];
    while($row=mysqli_fetch_assoc($exits)){
        $data[]=$row;
        //die(print_r($row));
    }
    
    return json_encode($data);
  }

}
function reportPrepayment($prop_id,$apt_id){
    $mysqli = getMysqliConnection();
    $date=date("Y-m-d");
    $sql="insert into prepayments (`propid`,`aptid`,`date`) values
    ($prop_id,'$apt_id','$date') ";
    $exits =$mysqli->query("select * from prepayments where propid=$prop_id and aptid='$apt_id'  and date='$date'") or die(mysqli_error($mysqli));
  //  die($sql);
  if(mysqli_num_rows($exits)<1){
    $query =$mysqli->query($sql) or die(mysqli_error($mysqli));

    return "Reported Successifully";
  }else{
    return "Already Reported";
  }

}
function tobepaid($propid,$amount){
    $mysqli = getMysqliConnection();
    $date=date("Y-m-d");
    $sql="select * from landlord_peddingpay where propid=$propid and pay_date='$date'";
    //die($sql);
    $query =$mysqli->query($sql) or die(mysqli_error($mysqli));
    $number=mysqli_num_rows($query);
    if($number<1){
        $query =$mysqli->query("INSERT INTO `landlord_peddingpay` (`propid`, `amount`, `pay_date`) VALUES ( '$propid', '$amount', '$date');");
    }
    ///"SELECT sum(payments.amount) as amount FROM `loan_list` inner  join payments on payments.loan_id=loan_list.id  WHERE `borrower_id`=$propid and (payments.date_created between '$startdate' and '$enddate')";
  //die($sql);
   // $query =$mysqli->query($sql) or die(mysqli_error($mysqli));
   // SELECT * FROM `loan_list` inner  join payments on payments.loan_id=loan_list.id  WHERE `borrower_id`=338
   //die( print_r($query->fetch_array()));
   // return round($query->fetch_assoc()['amount'],2);
}
function total_accumilated(){
    $mysqli = getMysqliConnection();
    $sql="select * from landlord_peddingpay where status='pedding' and amount>0";

    $query =$mysqli->query($sql) or die(mysqli_error($mysqli));
    $number=mysqli_num_rows($query);
    $sql="select sum(amount) as amount from landlord_peddingpay where status='pedding' and amount>0";
    $query =$mysqli->query($sql) or die(mysqli_error($mysqli));
    $amount=$query->fetch_assoc()['amount'];
    return json_encode(array("number"=>$number,"amount"=>$amount));
   
}



function invoiceAmount($propid,$startdate,$enddate){
    $mysqli = getMysqliConnection();
    // $date = date('d');
    // $startdate = date("Y-m-d", strtotime($startdate));
    // $enddate = date("Y-m-d", strtotime($enddate));
   $sql="SELECT ifnull(sum(amount),0) as amount FROM `invoices` WHERE `property_id`=$propid and `invoicedate` between '$startdate' and '$enddate'";
  // die($sql);
    $query =$mysqli->query($sql) or die(mysqli_error($mysqli));
   //die( print_r($query->fetch_array()));
    return $query->fetch_assoc()['amount'];

}
function getSiteRoot() {
    if($_SERVER['REMOTE_ADDR']!="127.0.0.1"){
        
    $parent = $_SERVER["DOCUMENT_ROOT"] ;//. '/property-rivercourt';
    }
    else{
        $parent = $_SERVER["DOCUMENT_ROOT"] . '/property-rivercourt';
    }
    return $parent;
}
function landlord_tobepaid(){
    $mysqli = getMysqliConnection();
    $date = date('d');
    $query =$mysqli->query("SELECT * FROM properties WHERE pay_day='$date'") or die(mysqli_error($mysqli));
   return mysqli_num_rows($query);
}
function payout_list(){
    $mysqli = getMysqliConnection();
    $date = date('d');

    //$list=$mysqli->query(" select ifnull((SELECT sum(monthlyincome) FROM `floorplan` f WHERE f.`propertyid`=p.propertyid and isoccupied=1),0)as amount ,p.propertyid,p.* from properties p where pay_day=DAY(now())");
    $list=$mysqli->query("select *,propertyid from properties  where pay_day=DAY(now())");
 
    // $total=$mysqli->query("select sum(l.amount) as total from (select ifnull((SELECT sum(monthlyincome) FROM `floorplan` f WHERE f.`propertyid`=p.propertyid and isoccupied=1),0)as amount ,p.propertyid from properties p where pay_day=DAY(now())) l ");
   $lists=array();
while($ln=$list->fetch_assoc()){
    $lists[]=$ln;
}
   return json_encode($lists);
   
    //$total=$mysqli->query("SELECT sum(monthlyincome) FROM `floorplan` WHERE `propertyid`=339 and isoccupied=0");
    //$
   // die("select  sum(prop.debit)as debit,sum(prop.credit) as credit,sum(prop.bal) as bal from (select property_id,x.idno,ifnull(sum(x.credit),0) as debit,ifnull(sum(x.debit),0) as credit,(ifnull(sum(x.credit),0)-ifnull(sum(x.debit),0)) as bal from (SELECT property_id,invoices.idno,invoices.amount as credit,(SELECT sum(amount) as debit FROM recptrans WHERE invoicenopaid=invoices.invoiceno AND revsd=0  ) as debit FROM invoices  where   invoices.revsd=0 AND month(invoicedate)=  MONTH(now())  )x group by x.idno) prop");
    //$total=$mysqli->query("select sum(prop.debit)as debit,sum(prop.credit) as credit,sum(prop.bal) as bal from (select property_id,x.idno,ifnull(sum(x.credit),0) as debit,ifnull(sum(x.debit),0) as credit,(ifnull(sum(x.credit),0)-ifnull(sum(x.debit),0)) as bal from (SELECT property_id,invoices.idno,invoices.amount as credit,(SELECT sum(amount) as debit FROM recptrans WHERE invoicenopaid=invoices.invoiceno AND revsd=0 ) as debit FROM invoices where invoices.revsd=0 AND month(invoicedate)= MONTH(now()) and property_id in(SELECT property_id from properties where pay_day=DAY(now())) )x group by x.idno) prop");
   // die(print_r($total->fetch_array()));
    //$query =$mysqli->query("SELECT * FROM properties WHERE pay_day='$date'") or die(mysqli_error($mysqli));
   
   // return  mysqli_num_rows($query);
}
function payout_list_cumilated(){
    
    $mysqli = getMysqliConnection();
    $date = date('d');

    //$list=$mysqli->query(" select ifnull((SELECT sum(monthlyincome) FROM `floorplan` f WHERE f.`propertyid`=p.propertyid and isoccupied=1),0)as amount ,p.propertyid,p.* from properties p where pay_day=DAY(now())");
    $list=$mysqli->query("select *,p.propertyid from landlord_peddingpay lp join properties p on lp.propid=p.propertyid ");
 
    // $total=$mysqli->query("select sum(l.amount) as total from (select ifnull((SELECT sum(monthlyincome) FROM `floorplan` f WHERE f.`propertyid`=p.propertyid and isoccupied=1),0)as amount ,p.propertyid from properties p where pay_day=DAY(now())) l ");
   $lists=array();
while($ln=$list->fetch_assoc()){
    $lists[]=$ln;
}
   return json_encode($lists);
   
    //$total=$mysqli->query("SELECT sum(monthlyincome) FROM `floorplan` WHERE `propertyid`=339 and isoccupied=0");
    //$
   // die("select  sum(prop.debit)as debit,sum(prop.credit) as credit,sum(prop.bal) as bal from (select property_id,x.idno,ifnull(sum(x.credit),0) as debit,ifnull(sum(x.debit),0) as credit,(ifnull(sum(x.credit),0)-ifnull(sum(x.debit),0)) as bal from (SELECT property_id,invoices.idno,invoices.amount as credit,(SELECT sum(amount) as debit FROM recptrans WHERE invoicenopaid=invoices.invoiceno AND revsd=0  ) as debit FROM invoices  where   invoices.revsd=0 AND month(invoicedate)=  MONTH(now())  )x group by x.idno) prop");
    //$total=$mysqli->query("select sum(prop.debit)as debit,sum(prop.credit) as credit,sum(prop.bal) as bal from (select property_id,x.idno,ifnull(sum(x.credit),0) as debit,ifnull(sum(x.debit),0) as credit,(ifnull(sum(x.credit),0)-ifnull(sum(x.debit),0)) as bal from (SELECT property_id,invoices.idno,invoices.amount as credit,(SELECT sum(amount) as debit FROM recptrans WHERE invoicenopaid=invoices.invoiceno AND revsd=0 ) as debit FROM invoices where invoices.revsd=0 AND month(invoicedate)= MONTH(now()) and property_id in(SELECT property_id from properties where pay_day=DAY(now())) )x group by x.idno) prop");
   // die(print_r($total->fetch_array()));
    //$query =$mysqli->query("SELECT * FROM properties WHERE pay_day='$date'") or die(mysqli_error($mysqli));
   
   // return  mysqli_num_rows($query);
}
function total_payout(){
    $mysqli = getMysqliConnection();
    $date = date('d');
    $list=$mysqli->query(" select ifnull((SELECT sum(monthlyincome) FROM `floorplan` f WHERE f.`propertyid`=p.propertyid and isoccupied=1),0)as amount ,p.propertyid from properties p where pay_day=DAY(now())");
    $total=$mysqli->query("select sum(l.amount) as total from (select ifnull((SELECT sum(monthlyincome) FROM `floorplan` f WHERE f.`propertyid`=p.propertyid and isoccupied=1),0)as amount ,p.propertyid from properties p where pay_day=DAY(now())) l ");
   return ($total->fetch_assoc()['total']);
   
    //$total=$mysqli->query("SELECT sum(monthlyincome) FROM `floorplan` WHERE `propertyid`=339 and isoccupied=0");
    //$
   // die("select  sum(prop.debit)as debit,sum(prop.credit) as credit,sum(prop.bal) as bal from (select property_id,x.idno,ifnull(sum(x.credit),0) as debit,ifnull(sum(x.debit),0) as credit,(ifnull(sum(x.credit),0)-ifnull(sum(x.debit),0)) as bal from (SELECT property_id,invoices.idno,invoices.amount as credit,(SELECT sum(amount) as debit FROM recptrans WHERE invoicenopaid=invoices.invoiceno AND revsd=0  ) as debit FROM invoices  where   invoices.revsd=0 AND month(invoicedate)=  MONTH(now())  )x group by x.idno) prop");
    //$total=$mysqli->query("select sum(prop.debit)as debit,sum(prop.credit) as credit,sum(prop.bal) as bal from (select property_id,x.idno,ifnull(sum(x.credit),0) as debit,ifnull(sum(x.debit),0) as credit,(ifnull(sum(x.credit),0)-ifnull(sum(x.debit),0)) as bal from (SELECT property_id,invoices.idno,invoices.amount as credit,(SELECT sum(amount) as debit FROM recptrans WHERE invoicenopaid=invoices.invoiceno AND revsd=0 ) as debit FROM invoices where invoices.revsd=0 AND month(invoicedate)= MONTH(now()) and property_id in(SELECT property_id from properties where pay_day=DAY(now())) )x group by x.idno) prop");
   // die(print_r($total->fetch_array()));
    //$query =$mysqli->query("SELECT * FROM properties WHERE pay_day='$date'") or die(mysqli_error($mysqli));
   
   // return  mysqli_num_rows($query);
}
function getSettings() {
    $mysqli = getMysqliConnection();
    $settings = array();
    $settingstable = getSettingsTable();
    $query = $mysqli->query("SELECT * FROM `$settingstable` WHERE id='1'") or die(mysqli_error($mysqli));
    //die($query);
    while ($row = mysqli_fetch_array($query)) {
        $settings['id'] = $row['id'];
        $settings['company_name'] = $row['company_name'];
        $settings['siteroot'] = $row['siteroot'];
        $settings['tagline'] = $row['tagline'];
        $settings['address'] = $row['address'];
        $settings['from_email'] = $row['from_email'];
        $settings['cc_email'] = $row['cc_email'];
        $settings['accounts_opening'] = $row['accounts_opening'];
        $settings['vat'] = $row['vat'];
        $settings['pin'] = $row['pin'];
    }

    return $settings;
}

//save settings
function saveSettings($settings, $mode) {
    $mysqli = getMysqliConnection();
    $settingstable = getSettingsTable();
    $id = $settings['id'];
    $company = $settings['company_name'];
    $tagline = $settings['tagline'];
    $settings['siteroot'];
    $address = $settings['address'];
    $accountsopening = date("Y-m-d", strtotime($settings['accounts_opening']));
    $fromemail = $settings['from_email'];
    $ccemail = $settings['cc_email'];
    $vat = $settings['vat'];
    $pin = $settings['pin'];
    if ($mode == 'update') {
        //die(print_r($settings));
        $result = $mysqli->query("UPDATE $settingstable  SET `company_name`='$company',`address`='$address',`from_email`='$fromemail',`cc_email`='$ccemail',`accounts_opening` ='$accountsopening',`tagline`='$tagline',`vat`='$vat',`pin`='$pin' WHERE id='$id'") or die($mysqli->error);
        if ($result) {
            return 'success';
        } else {
            return 'failed to update settings';
        }
    }


    return $settings;
}

//get settings
$settings = getSettings();
$_SESSION['clientname'] = $settings['company_name'];

function getAccountsOpeningDate() {
    $settings = getSettings();
    return $settings['accounts_opening'];
}

function getAdminEmail() {
    $settings = getSettings();
    return array('from' => $settings['from_email'], 'cc' => $settings['cc_email']);
}

function getAbsoluteUrl() {
    $settings = getSettings();
    return $settings['siteroot'];
}

function getIP() {
    if($_SERVER['REMOTE_ADDR']!="127.0.0.1"){
        return "https://" . $_SERVER["HTTP_HOST"] ;

    }else{
        return "http://" . $_SERVER["HTTP_HOST"] . "/property-rivercourt";

    }
    
}

function checkIfLoggedInProperty() {
    $homeurl = getIP() . "/home.php?error=Property Not Selected!";
    if (!$_SESSION['propertyid']) {
        header('Location: ' . $homeurl);
    }
}

//tables
function getChargeItemsTable() {
    return "chargeitems";
}

function getSettingsTable() {
    return "settings";
}

function getInvoiceTable() {
    return "invoices";
}

function getReceiptsTable() {
    return "recptrans";
}

function getPropertiesTable() {
    return "properties";
}

function delaypenaltiesTable() {
    return 'delaypenalties';
}

function invoiceitemsTable() {
    return 'invoiceitems';
}

function gettenantsTable() {
    return 'tenants';
}

function getSupplierListTable() {
    return 'supplierexpenselist';
}

function getPaymentsListTable() {
    return 'paytrans';
}

function getUsersTable() {
    $tablename = "accesslevels";
    return $tablename;
}

function getAccountsTable() {
    return 'bkaccounts';
}

function getFinancialYearTable() {
    return 'financial_years';
}

function getClosePeriodsTable() {
    return 'close_periods';
}

function getAccountTypesTable() {
    return 'acct_types';
}

function getAccountCategoriesTable() {
    return 'accounttype_categories';
}

function getJournalsTable() {
    return 'journal_entries';
}

function getBankTransactionsTable() {
    return 'bank_trans';
}

//save gl
function saveGL($gl, $mode) {
    $mysqli = getMysqliConnection();
    $glaccountstable = getAccountsTable();
    $accountype = $gl['idaccount_type'];
    $accountname = $gl['account_name'];
    $category = $gl['idaccounttype_categories'];
    $vat = $gl['vat'];
    @$is_bank = $gl['is_bank'];
    $glcode = incrementnumber("glcode");
    $username = $gl['username'];
    $gl['property_id'] == null ? $property_id = 0 : $property_id = $gl['property_id']; //if property_id==null
    @$gl['is_office'] == null ? $is_office = 0 : $is_office = $gl['is_office']; //if is office account
    @$gl['is_tenant'] == null ? $is_tenant = 0 : $is_tenant = $gl['is_tenant']; //if is house account
    @$gl['is_agent'] == null ? $is_agent = 0 : $is_agent = $gl['is_agent']; //if is agent account
    @$gl['is_landlord'] == null ? $is_landlord = 0 : $is_landlord = $gl['is_landlord']; //if is landlord account
    @$gl['is_commission'] == null ? $is_commission = 0 : $is_commission = $gl['is_commission']; //if is landlord account
    @$apt_id = $gl['apt_id'];
    $incometypes = @$gl['idincometypes'];
    $status = 1;
    $balance = $gl['balance'];
    if ($mode == 'insert') {
        //die(print_r($settings));
        $sql = "INSERT INTO `$glaccountstable` (acname,idacct_types,idaccounttype_categories,idincometypes,glcode,is_bank,is_office,is_tenant,is_agent,is_landlord,is_commission,apt_id,bal,us,property_id,status,has_vat)VALUES
('$accountname','$accountype','$category','$incometypes','$glcode','$is_bank','$is_office','$is_tenant','$is_agent','$is_landlord','$is_commission','$apt_id', '$balance','$username','$property_id','$status','$vat')";
        $result = $mysqli->query($sql) or die($mysqli->error);
        if ($result) {
            return TRUE;
        } else {
            return FALSE;
        }
    }
}

//delete gl
function deleteGL($id) {
    $mysqli = getMysqliConnection();
    $fytable = getAccountsTable();
    $result = $mysqli->query("DELETE FROM {$fytable} WHERE `acno`='$id' ") or die($mysqli->error);
    if ($result) {
        return TRUE;
    } else {
        return FALSE;
    }
    $mysqli->close();
}

//get gl for property
function getLandlordGls($propertyid) {

    $mysqli = getMysqliConnection();
    $accountdetails = array();
    $accountstable = getAccountsTable();
    $res = $mysqli->query("SELECT * FROM {$accountstable} WHERE `idaccounttype_categories`='8' AND `is_agent`=1 AND `property_id`='$propertyid'") or die($mysqli->error);

    while ($row = $res->fetch_assoc()) {
        array_push($accountdetails, $row);
    }
    $mysqli->close();
    return $accountdetails;
}

//get penalty gl

function getPenaltyGl() {
    $mysqli = getMysqliConnection();
    $accountdetails = array();
    $accountstable = getAccountsTable();
    $res = $mysqli->query("SELECT * FROM {$accountstable} WHERE `idacct_types`=1 AND `idaccounttype_categories`=3 AND `is_agent`=1 AND `acname` like 'Penalty' ORDER BY `glcode`") or die($mysqli->error);

    while ($row = $res->fetch_assoc()) {

        $accountdetails['acno'] = $row['acno'];
        $accountdetails['acname'] = $row['acname'];
        $accountdetails['glcode'] = $row['glcode'];
    }

    $mysqli->close();
    return $accountdetails;
}

function getBanksGls() {
    $mysqli = getMysqliConnection();
    $accountdetails = array();
    $accountstable = getAccountsTable();
    $res = $mysqli->query("SELECT * FROM {$accountstable} WHERE `idaccounttype_categories`='0' AND `is_bank`=1 AND `property_id`='0'") or die($mysqli->error);

    while ($row = $res->fetch_assoc()) {
        array_push($accountdetails, $row);
    }
    $mysqli->close();
    return $accountdetails;
}

//deposit penalty
function getDepositPenaltyGls() {

    $mysqli = getMysqliConnection();
    $accountdetails = array();
    $accountstable = getAccountsTable();
    $res = $mysqli->query("SELECT * FROM {$accountstable} WHERE `idaccounttype_categories`='3' AND `idacct_types`='1' AND `is_agent`=1 AND acname like '%Deposit Penalty%' ") or die($mysqli->error);

    while ($row = $res->fetch_assoc()) {
        array_push($accountdetails, $row);
    }
    $mysqli->close();
    return $accountdetails;
}

//get penalty amounts between dates
function getDepositPenaltyAmounts($startdate, $enddate) {
    $mysqli = getMysqliConnection();

    $accountstable = getJournalsTable();
    $res = $mysqli->query("SELECT  SUM(total_credit) AS total_credit FROM {$accountstable} WHERE DATE_FORMAT(`booking_date`,'%Y-%m-%d') between '$startdate' AND '$enddate' AND `transaction_type` like '%DPENALTY%' ") or die($mysqli->error);

    while ($row = $res->fetch_assoc()) {
        $totalcredit = $row["total_credit"];
    }
    $mysqli->close();
    return $totalcredit;
}

function getAgentIncomeGls() {

    $mysqli = getMysqliConnection();
    $accountdetails = array();
    $accountstable = getAccountsTable();
    $res = $mysqli->query("SELECT * FROM {$accountstable} WHERE `idaccounttype_categories`=3 AND `is_agent`=1") or die($mysqli->error);

    while ($row = $res->fetch_assoc()) {
        array_push($accountdetails, $row);
    }
    $mysqli->close();
    return $accountdetails;
}

function getAgentBankGls() {

    $mysqli = getMysqliConnection();
    $accountdetails = array();
    $accountstable = getAccountsTable();
    $res = $mysqli->query("SELECT * FROM {$accountstable} WHERE  `idaccounttype_categories`='1'  AND `idacct_types`='5' AND  `is_bank`=1") or die($mysqli->error);

    while ($row = $res->fetch_assoc()) {
        array_push($accountdetails, $row);
    }
    $mysqli->close();
    return $accountdetails;
}

function getGLCodeForAccount($params) {
    $mysqli = getMysqliConnection();
    $accountdetails = array();
    $accountstable = getAccountsTable();

    switch ($params["gl"]) {

        //invoice side
        case $params["gl"] == "Commissions":
            $incometype = getIncomeType("Commission");
            $incometypeid = $incometype['idincometypes'];
            $propid = @$params['property_id'];

            $res = $mysqli->query("SELECT * FROM {$accountstable} WHERE `idincometypes`='$incometypeid' AND  `property_id`='$propid' AND `is_agent`=1") or die($mysqli->error);
            break;

        case $params["gl"] == "HouseGL":
            $aptid = $params['apt_id'];
            $res = $mysqli->query("SELECT * FROM {$accountstable} WHERE `apt_id`='$aptid'") or die($mysqli->error);
            break;
//landlord account for agent
        case $params["gl"] == "AgentLandlord":
            $propid = $params['property_id'];
            $res = $mysqli->query("SELECT * FROM {$accountstable} WHERE `idaccounttype_categories`='8' AND `is_agent`=1 AND `property_id`='$propid' ") or die($mysqli->error);
            break;
        case $params["gl"] == "AgentBank":
            $propid = $params['property_id'];
            $res = $mysqli->query("SELECT * FROM {$accountstable} WHERE `idaccounttype_categories`='1' AND `is_agent`=1 AND `idacct_types`='3' ") or die($mysqli->error);
            break;
        case $params["gl"] == "AgentExpense":
            $propid = $params['property_id'];
            //expense acct of category agent expense
            $res = $mysqli->query("SELECT * FROM {$accountstable} WHERE `idaccounttype_categories`='2' AND `is_agent`=1 AND `idacct_types`='2' ") or die($mysqli->error);
            break;
        case $params["gl"] == "AgentLiability":
            //liability
            $res = $mysqli->query("SELECT * FROM {$accountstable} WHERE `idaccounttype_categories`='8' AND `is_agent`=1 AND `idacct_types`='4' AND is_agent='1' AND property_id=0 ") or die($mysqli->error);
            break;
        case $params["gl"] == "LandlordAgent":
            $propid = $params['property_id'];
            //landlord asset is agent
            $res = $mysqli->query("SELECT * FROM {$accountstable} WHERE `idaccounttype_categories`='9' AND `is_landlord`=1 AND `property_id`='$propid' ") or die($mysqli->error);
            break;
        case $params["gl"] == "LandlordRent":
            $propid = $params['property_id'];
            //landlord income
            $res = $mysqli->query("SELECT * FROM {$accountstable} WHERE `idaccounttype_categories`='5' AND `is_landlord`=1 AND `property_id`='$propid' ") or die($mysqli->error);
            break;

        case $params["gl"] == "LandlordBank":
            $propid = $params['property_id'];
            //landlord income
            $res = $mysqli->query("SELECT * FROM {$accountstable} WHERE `idaccounttype_categories`='6' AND `is_landlord`=1 AND `idacct_types`='3' AND `property_id`='$propid' ") or die($mysqli->error);
            break;

        case $params["gl"] == "LandlordCommission":
            $propid = $params['property_id'];
            //landlord income
            $res = $mysqli->query("SELECT * FROM {$accountstable} WHERE `idaccounttype_categories`='4' AND `is_landlord`=1 AND `idacct_types`='2' AND `is_commission`=1 AND `property_id`='$propid' ") or die($mysqli->error);
            break;
        case $params["gl"] == "LandlordExpense":

            //landlord income

            break;
    }

    //receipt side
    //agent to landlord transfer
    //agent penalty

    while ($row = $res->fetch_assoc()) {
        $accountdetails['acno'] = $row['acno'];
        $accountdetails['glcode'] = $row['glcode'];
        $accountdetails['idacct_types'] = $row['idacct_types'];
        $accountdetails['property_id'] = $row['property_id'];
        $accountdetails['acname'] = $row['acname'];
        $accountdetails['idaccounttype_categories'] = $row['idaccounttype_categories'];
        $accountdetails['is_landlord'] = $row['is_landlord'];
        $accountdetails['is_agent'] = $row['is_agent'];
        $accountdetails['bal'] = $row['bal'];
        $accountdetails['status'] = $row['status'];
    }

    $mysqli->close();
    return $accountdetails;
}

//update GL balance
//landlord expense
function getLandlordExpenseAccounts($params) {
    $mysqli = getMysqliConnection();
    $accountstable = getAccountsTable();
    $propid = $params['property_id'];
    $expenseaccts = array();
    $accountdetails = array();
    $res = $mysqli->query("SELECT * FROM {$accountstable} WHERE `idaccounttype_categories`='4' AND `is_landlord`=1  AND `idacct_types`='2' AND `is_commission`=0 AND `property_id`='$propid' ") or die($mysqli->error);
    while ($row = $res->fetch_assoc()) {
        $accountdetails['acno'] = $row['acno'];
        $accountdetails['glcode'] = $row['glcode'];
        $accountdetails['idacct_types'] = $row['idacct_types'];
        $accountdetails['property_id'] = $row['property_id'];
        $accountdetails['acname'] = $row['acname'];
        $accountdetails['idaccounttype_categories'] = $row['idaccounttype_categories'];
        $accountdetails['is_landlord'] = $row['is_landlord'];
        $accountdetails['is_agent'] = $row['is_agent'];
        $accountdetails['bal'] = $row['bal'];
        $accountdetails['status'] = $row['status'];
        $accountdetails['has_vat'] = $row['has_vat'];
        array_push($expenseaccts, $accountdetails);
    }

    return $expenseaccts;
}

//agent expense
function getAgentExpenseAccount() {
    $mysqli = getMysqliConnection();
    $accountstable = getAccountsTable();
    $expenseaccts = array();
    $accountdetails = array();
    $res = $mysqli->query("SELECT * FROM {$accountstable} WHERE `idaccounttype_categories`='2' AND `is_agent`=1 AND `idacct_types`='2' ") or die($mysqli->error);
    while ($row = $res->fetch_assoc()) {
        $accountdetails['acno'] = $row['acno'];
        $accountdetails['glcode'] = $row['glcode'];
        $accountdetails['idacct_types'] = $row['idacct_types'];
        $accountdetails['property_id'] = $row['property_id'];
        $accountdetails['acname'] = $row['acname'];
        $accountdetails['idaccounttype_categories'] = $row['idaccounttype_categories'];
        $accountdetails['is_landlord'] = $row['is_landlord'];
        $accountdetails['is_agent'] = $row['is_agent'];
        $accountdetails['bal'] = $row['bal'];
        $accountdetails['status'] = $row['status'];
        array_push($expenseaccts, $accountdetails);
    }

    return $expenseaccts;
}

function updateGLBalance($glcode, $amount) {
    
    $mysqli = getMysqliConnection();
    $glstable = getAccountsTable();
    $res = $mysqli->query("SELECT * FROM {$glstable} WHERE `glcode` = '$glcode' ") or die($mysqli->error);

    while ($row = $res->fetch_assoc()) {
        $balance = $row['bal'];
    }
    $newbal = $balance + $amount;
    //echo("UPDATE {$glstable} SET `bal` = '$newbal' WHERE `glcode`='$glcode'<br/>");
    $resultset = $mysqli->query("UPDATE {$glstable} SET `bal` = '$newbal' WHERE `glcode`='$glcode' ") or die($mysqli->error);
    
    $mysqli->close();
    if ($resultset) {
        return TRUE;
    } else {
        return FALSE;
    }
}

//get income type
function getIncomeType($code) {
    $mysqli = getMysqliConnection();
    $accountdetails = array();
    $incomestable = "incometypes";
    $res = $mysqli->query("SELECT * FROM {$incomestable} WHERE `code` ='$code' ") or die($mysqli->error);

    while ($row = $res->fetch_assoc()) {
        $accountdetails['idincometypes'] = $row['idincometypes'];
    }
    $mysqli->close();
    return $accountdetails;
}

//journals
function createJournalEntry($params) {
    $mysqli = getMysqliConnection();
    $jentrytable = getJournalsTable();
    $jvno = incrementnumber('jvno');
    $glcode = $params['glcode'];
    $debit = @$params['debit'];
    $credit = @$params['credit'];
    $ttype = $params['ttype'];
    $propid = @$params['property_id'];
    $docref = @$params['document_ref'];
    $desc = @$params['desc'];
    $idclose_period = $params['idclose_period'];
    $sql = "INSERT INTO `$jentrytable` (`glcode`, `total_debit`, `total_credit`, `transaction_type`, `journal_ref`,`document_ref`, `property_id`, `description`, `currency`, `idclose_periods`) VALUES
('$glcode','$debit','$credit','$ttype','$jvno','$docref','$propid','$desc','KES','$idclose_period')";
    $result = $mysqli->query($sql) or die($mysqli->error);
    if ($result) {
        //update gl acct balance
        if ($debit) {
            $update = updateGLBalance($glcode, $debit);
        } else {
            $update = updateGLBalance($glcode, -$credit);
        }
        return $jvno;
    } else {
        return 'failed to save journal';
    }
}

//reverse journal
function reverseJournal($params) {
    $mysqli = getMysqliConnection();
    $docref = $params['document_ref'];
    $ttype = strtoupper($params['ttype']);
    $journalstable = getJournalsTable();
    //set JE as reversed update balance of GL account
    $resultset = $mysqli->query("SELECT * FROM {$journalstable} WHERE `transaction_type`='$ttype' AND  `document_ref`='$docref'") or die($mysqli->error);
    while ($row = $resultset->fetch_assoc()) {
        $journal_ref = $row['journal_ref'];
        $debit = @$row['total_debit'];
        $credit = @$row['total_credit'];
        $glcode = $row['glcode'];
        $res = $mysqli->query("UPDATE {$journalstable} SET `revsd`=1 WHERE `journal_ref`='$journal_ref' ") or die($mysqli->error);
        if ($debit > 0) {
            $update = updateGLBalance($glcode, -$debit);
        } else {
            $update = updateGLBalance($glcode, $credit);
        }
    }
    $mysqli->close();
}

//get journal from glcode
function getGLJournals($glcode, $filter = 0, $period = 0) {
    $mysqli = getMysqliConnection();
    $journaldetails = array();
    $journalstable = getJournalsTable();
    if ($filter > 0) {
        $res = $mysqli->query("SELECT * FROM {$journalstable} WHERE `glcode` = '$glcode' AND `debited`=0 AND `revsd`=0 AND `$filter` =1 ORDER BY idclose_periods ") or die($mysqli->error);
    } elseif ($period > 0 && !$filter) {
        $res = $mysqli->query("SELECT * FROM {$journalstable} WHERE `glcode` = '$glcode' AND `debited`=0 AND `revsd`=0  AND `idclose_periods`='$period' ") or die($mysqli->error);
    } else {
        $res = $mysqli->query("SELECT * FROM {$journalstable} WHERE `glcode` = '$glcode' AND `debited`=0 AND `revsd`=0  ORDER BY idclose_periods  ") or die($mysqli->error);
    }
    while ($row = $res->fetch_assoc()) {
        array_push($journaldetails, $row);
    }


    $mysqli->close();
    return $journaldetails;
}

function getGLJournalsForDates($glcode, $startdate, $enddate) {
    $mysqli = getMysqliConnection();
    $journaldetails = array();
    $journalstable = getJournalsTable();
    $startdate = date("Y-m-d H:i:s", strtotime($startdate));
    $enddate = date("Y-m-d H:i:s", strtotime($enddate));
    $res = $mysqli->query("SELECT * FROM {$journalstable} WHERE `glcode` = '$glcode' AND `debited`=0 AND `revsd`=0  AND `booking_date` between '$startdate' AND '$enddate' ") or die($mysqli->error);

    while ($row = $res->fetch_assoc()) {
        array_push($journaldetails, $row);
    }


    $mysqli->close();
    return $journaldetails;
}

function getTotalForJournalPeriod($glcode, $idcloseperiod) {
    $mysqli = getMysqliConnection();
    $journaldetails = array();
    $journalrefs = "";
    $sum = array();
    $journalstable = getJournalsTable();
    $res = $mysqli->query("SELECT * FROM {$journalstable} WHERE `glcode` = '$glcode' AND `idclose_periods`='$idcloseperiod' AND `debited`=0 AND `revsd`=0 ") or die($mysqli->error);
    while ($row = $res->fetch_assoc()) {
        if ($row['total_credit'] > 0) {
            $amount = $row['total_credit'] - $row['total_debit'];

            $journalrefs = $journalrefs . '*' . $row['journal_ref'];
            array_push($sum, $amount);
        } else {
            $amount = $row['total_debit'] - $row['total_credit'];
            $journalrefs = $journalrefs . '*' . $row['journal_ref'];
            array_push($sum, $amount);
        }
    }
    if (array_sum($sum) > 0) {
        $journaldetails['closeperiod'] = $idcloseperiod;
        $journaldetails['closeperioddetails'] = getClosePeriod($idcloseperiod);
        $journaldetails['sum'] = array_sum($sum);
        $journaldetails['journal_refs'] = $journalrefs;


        return $journaldetails;
    }
}

//update journals
function updateJournals($journalrefsarray) {
    $mysqli = getMysqliConnection();
    $journalstable = getJournalsTable();
    if (is_array($journalrefsarray)) {
        foreach ($journalrefsarray as $value) {
            $res = $mysqli->query("UPDATE  {$journalstable} SET `debited` = 1 WHERE `journal_ref`='$value' ") or die($mysqli->error);
        }
    } else {
        $res = $mysqli->query("UPDATE  {$journalstable} SET `debited` = 1 WHERE `journal_ref`='$journalrefsarray' ") or die($mysqli->error);
    }
    $mysqli->close();
}

//save financial year
function saveFY($fy, $mode) {
    $mysqli = getMysqliConnection();
    $glaccountstable = getFinancialYearTable();
    $start_date = date("Y-m-d", strtotime($fy['start_date']));
    $end_date = date("Y-m-d", strtotime($fy['end_date']));

    $status = $fy['is_active'];

    if ($mode == 'insert') {
        //die(print_r($settings));
        $sql = "INSERT INTO `$glaccountstable` (start_date,end_date,status) VALUES
('$start_date','$end_date','$status')";
        $result = $mysqli->query($sql) or die($mysqli->error);
        if ($result) {
            return 'success';
        } else {
            return 'failed to save financial year';
        }
    }
}

//delete FY
function deleteFY($id) {
    $mysqli = getMysqliConnection();
    $fytable = getFinancialYearTable();
    $result = $mysqli->query("UPDATE {$fytable} SET `status`=0 WHERE `idfinancial_year` ='$id' ") or die($mysqli->error);
    if ($result) {
        return TRUE;
    } else {
        return FALSE;
    }
    $mysqli->close();
}

function getFinancialYears() {
    $mysqli = getMysqliConnection();
    $accountdetails = array();
    $accountstable = getFinancialYearTable();
    $res = $mysqli->query("SELECT * FROM $accountstable WHERE `status`=1") or die($mysqli->error);

    while ($row = $res->fetch_assoc()) {
        array_push($accountdetails, $row);
    }
    $mysqli->close();
    return $accountdetails;
}

//save financial year
function saveClosePeriod($cp, $mode) {
    $mysqli = getMysqliConnection();
    $glaccountstable = getClosePeriodsTable();
    $idyear = $cp['idfinancial_year'];
    $start_date = date("Y-m-d", strtotime($cp['start_date']));
    $end_date = date("Y-m-d", strtotime($cp['end_date']));
    $status = $cp['is_active'];

    if ($mode == 'insert') {
        //die(print_r($settings));
        $sql = "INSERT INTO `$glaccountstable` (idfinancial_year,start_date,end_date,is_active) VALUES
('$idyear','$start_date','$end_date','$status')";
        $result = $mysqli->query($sql) or die($mysqli->error);
        if ($result) {
            return 'success';
        } else {
            return 'failed to save close period';
        }
    }
}
function getPeriodByDate1($date) {
    
   
    $mysqli = getMysqliConnection();
    $closeperiodtable = getClosePeriodsTable();
    //get close period in which month falls
$date1 = DateTime::createFromFormat("d/m/Y", $date);
  // die($date); //$date;
//    //get the current month (tested-ok)
//$date1=DateTime::createFromFormat("Y-m-d", $date);
    $thisday = $date1->format("d");
    $thismonth = $date1->format("m");
    $thisyear = $date1->format("Y");
    $currentdate = $date1->format("Y-m-d");

   // $res = $mysqli->query
   // return "SELECT * FROM $closeperiodtable WHERE `start_date` <= '$currentdate' AND `end_date` >= '$currentdate' AND is_active=1 ";// or die($mysqli->error);
//echo($res);
    // $rows = null;
    // while ($row = $res->fetch_assoc()) {
    //     $startdate = new DateTime($row['start_date']);
    //     $enddate = new DateTime($row['end_date']);
    //     $startdatemonth = $startdate->format("m");
    //     $enddatemonth = $enddate->format("m");
    //     $enddateday = $enddate->format("d");
    //     $startdateyear = $startdate->format("Y");
    //     if ($thismonth <= $enddatemonth && $thismonth >= $startdatemonth && $thisyear <= $startdateyear) {
    //         //if($enddatemonth>$thismonth=>$startdatemonth && $thisyear==$startdateyear){
    //         $rows = $row;
    //     }
    //     $mysqli->close();
    //     if ($rows) {
    //         return $rows;
    //     } else {
    //         return false;
    //     }
    // }
}

//get period by date
function getPeriodByDate($date) {
   
    $mysqli = getMysqliConnection();
    $closeperiodtable = getClosePeriodsTable();
    //get close period in which month falls
    $date1 = DateTime::createFromFormat("d/m/Y", $date);
//    //get the current month (tested-ok)
    $thisday = $date1->format("d");
    $thismonth = $date1->format("m");
    $thisyear = $date1->format("Y");
    $currentdate = $date1->format("Y-m-d");

    $res = $mysqli->query("SELECT * FROM $closeperiodtable WHERE `start_date` <= '$currentdate' AND `end_date` >= '$currentdate' AND is_active=1 ") or die($mysqli->error);
//    echo($res);
    $rows = null;
    while ($row = $res->fetch_assoc()) {
        $startdate = new DateTime($row['start_date']);
        $enddate = new DateTime($row['end_date']);
        $startdatemonth = $startdate->format("m");
        $enddatemonth = $enddate->format("m");
        $enddateday = $enddate->format("d");
        $startdateyear = $startdate->format("Y");
        if ($thismonth <= $enddatemonth && $thismonth >= $startdatemonth && $thisyear <= $startdateyear) {
            //if($enddatemonth>$thismonth=>$startdatemonth && $thisyear==$startdateyear){
            $rows = $row;
        }
        $mysqli->close();
        if ($rows) {
            return $rows;
        } else {
            return false;
        }
    }
}

//get period by id
function getClosePeriod($id) {
    $mysqli = getMysqliConnection();
    $closeperiod = array();
    $closeperiodtable = getClosePeriodsTable();
    $res = $mysqli->query("SELECT * FROM {$closeperiodtable} WHERE `idclose_periods`='$id'") or die($mysqli->error);
    while ($row = $res->fetch_assoc()) {
        $closeperiod['financial_year'] = $row['idfinancial_year'];
        $closeperiod['start_date'] = $row['start_date'];
        $closeperiod['end_date'] = $row['end_date'];
        $closeperiod['is-active'] = $row['is_active'];
    }
    return $closeperiod;
}

//get close periods 
function getClosePeriods($fy) {
    $mysqli = getMysqliConnection();
    $accountdetails = array();
    $accountstable = getClosePeriodsTable();
    $res = $mysqli->query("SELECT * FROM {$accountstable} WHERE `idfinancial_year`='$fy'") or die($mysqli->error);

    while ($row = $res->fetch_assoc()) {
        array_push($accountdetails, $row);
    }
    $mysqli->close();
    return $accountdetails;
}

function changePeriodStatus($id, $status) {
    $mysqli = getMysqliConnection();
    $accountstable = getClosePeriodsTable();
    $res = $mysqli->query("UPDATE {$accountstable} SET `is_active`='$status' WHERE `idclose_periods`='$id'") or die($mysqli->error);
    if ($res) {
        return TRUE;
    } else {
        return FALSE;
    }
    $mysqli->close();
}

//delete periods
function deleteClosePeriod($id) {
    $mysqli = getMysqliConnection();
    $accountstable = getClosePeriodsTable();
    $res = $mysqli->query("DELETE FROM  {$accountstable}  WHERE `idclose_periods`='$id'") or die($mysqli->error);
    if ($res) {
        return TRUE;
    } else {
        return FALSE;
    }
    $mysqli->close();
}

//tenant email
function getTenantDetails($id) {
    $mysqli = getMysqliConnection();
    $tenantstable = gettenantsTable();
    $detailsarray = array();
    $result = $mysqli->query("SELECT tenant_name,tenantphone,tenantemail,kins_name,kinstel,tenantphoto,tenantpin,workplace FROM $tenantstable WHERE tenants.Id='$id'") or die($mysqli->error);
    while ($row = $result->fetch_assoc()) {
        $detailsarray['name'] = $row['tenant_name'];
        $detailsarray['phone'] = $row['tenantphone'];
        $detailsarray['kinsname'] = $row['kins_name'];
        $detailsarray['kinstel'] = $row['kinstel'];
        $detailsarray['email'] = $row['tenantemail'];
    }
    return $detailsarray;
}

//mysqli connection 
function getMysqliConnection() {
    require_once getSiteRoot() . '/includes/config.php';
    $file = @include_once '../includes/config.php';
    if (!file_exists($file)) {
        $file = @include_once '../../includes/config.php';
    }



    $mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME, 3306);
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQLi: (" . $mysqli->connect_errno . ") ";
    } else {
        return $mysqli;
    }
}
function tenantseditdetails($id) {
    $db = new MySQLDatabase();
    $db->open_connection();
    $sql = $db->query("SELECT * FROM tenants WHERE Id ='$id'") or die(mysql_error());
    $results = array();
    while ($row = mysql_fetch_assoc($sql)) {
        $results[] = $row;
    }
    header('Content-type: application/json');
    $json = json_encode($results);
    echo $json;

    $db->close_connection();
}
function populatejsArray() {
    $db = new MySQLDatabase();
    $db->open_connection();
    $sql = $db->query("SELECT property_name FROM properties") or die(mysql_error());
    $results = array();
    while ($row = mysql_fetch_array($sql)) {
        $results[] = $row['property_name'];
    }
    $json = json_encode($results);
    echo $json;

    $db->close_connection();
}

function populatemohalla() {
    $db = new MySQLDatabase();
    $db->open_connection();
    $sql = $db->query("SELECT mohalla FROM mohalla") or die(mysql_error());
    $results = array();
    while ($row = mysql_fetch_array($sql)) {
        $results[] = $row['mohalla'];
    }
    $json = json_encode($results);
    echo $json;

    $db->close_connection();
}

//populate legal form on select
function populateLegal($propertyid) {
    $db = new MySQLDatabase();
    $db->open_connection();
    $propertyid = $propertyid;
    $sql = $db->query("SELECT * FROM legaldocs WHERE propertyid='$propertyid'") or die(mysql_error());
    $data = array();
    while ($row = mysql_fetch_array($sql)) {

        $data['documents'] = array($row['doc']);
        $data['documentno'] = array($row['docno']);
        $data['issuedate'] = array($row['issuedate']);
        $data['issuer'] = array($row['issuer']);
        $data ['descr'] = array($row['descr']);
        $data['path'] = array($row['path']);
    }
    echo json_encode($data);

    $db->close_connection();
}

//populate agents
function populateagents() {
    $db = new MySQLDatabase();
    $db->open_connection();
    $sql = $db->query("SELECT agentname FROM agents") or die(mysql_error());
    $results = array();
    while ($row = mysql_fetch_array($sql)) {
        $results[] = $row['agentname'];
    }
    $json = json_encode($results);
    echo $json;

    $db->close_connection();
}

//upload legal details

function uploadlegal() {
    $db = new MySQLDatabase();
    $db->open_connection();
    if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {

        $propertyid = trim(mysql_real_escape_string($_POST["propertyid"]));
        $legaldocstype = trim(mysql_real_escape_string($_POST["selected"]));
        $docno = trim(mysql_real_escape_string($_POST["docno"]));
        $issuedate = trim(mysql_real_escape_string($_POST["issuedate"]));
        $issueofficer = trim(mysql_real_escape_string($_POST["issueofficer"]));
        $desc1 = trim(mysql_real_escape_string($_POST["desc1"]));
        $available = '';

//file upload (no mean task because of serialized form)

        $path = "../propertydocuments/legal/";

        $valid_formats = array("jpg", "png", "gif", "bmp", "pdf", "jpeg", "doc", "docx", "PNG", "jpeg", "JPEG", "JPG");
        if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {
            $name = $_FILES['legaldoc']['name'];
            $size = $_FILES['legaldoc']['size'];

            if (strlen($name)) {
                list($txt, $ext) = explode(".", $name);
                if (in_array($ext, $valid_formats)) {
                    if ($size < (2048 * 2048)) {
                        $actual_image_name = time() . substr(str_replace(" ", "_", $txt), 5) . "." . $ext;
                        $tmp = $_FILES['legaldoc']['tmp_name'];
                        if (move_uploaded_file($tmp, $path . $actual_image_name)) {
                            echo "successfully moved uploaded file";
                            $available = 1;
                        } else
                            echo "failed" . $path . $actual_image_name;
                    } else
                        echo "Image file size max 2 MB!";
                } else
                    echo "Invalid file format..";
            } else
                echo "Please select image..!";
        }


//file upload ends here

        if (empty($legaldocstype) || empty($issuedate)) {

            echo 'Database update failed!';
        } else {
            $count = $db->query("SELECT * FROM legaldocs WHERE document_id = '$legaldocstype' AND propertyid='$propertyid'");
            $numrows = mysql_num_rows($count);

            if ($numrows == 1) {
                $sql = "UPDATE `legaldocs` SET `docno`='$docno',`issuedate`='$issuedate',`issuer`='$issueofficer',`descr`='$desc1',`path`='$path$actual_image_name',`docavailable`='$available' WHERE `document_id`='$legaldocstype' AND `propertyid`='$propertyid'";

// $sql="UPDATE legaldocs SET propertyid='$propertyid',docno ='$docno' ,issuedate = '$issuedate',issuer='$issueofficer',desc='$desc1',path='$legaldoc' WHERE doc='$legaldocstype' ";
            } else {
                $sqlstmnt = $db->query("SELECT document_name FROM propertydocuments WHERE document_id='$legaldocstype'");
                while ($row1 = mysql_fetch_array($sqlstmnt)) {
                    $docname = $row1["document_name"];
                }


                $sql = "INSERT INTO legaldocs (document_id,propertyid,doc,docno,issuedate,issuer,descr,path,docavailable)VALUES
('$legaldocstype','$propertyid','$docname','$docno','$issuedate','$issueofficer','$desc1','$path$actual_image_name','$available')";
            }
            if (!$db->query($sql)) {
                echo 'Database Insert failed !';
            } else {
                $db->close_connection();
//echo " Successfully uploaded details";
                echo 'Document Saved!';
                echo '<script type="text/javascript">
alert("saved!");
window.location = "updatedata.php"</script>';
            }
        }
    } else {
        echo "data not received from form!";
    }
}

//upload property docs
function uploaddocs() {

    $db = new MySQLDatabase();
    $db->open_connection();


    if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {

        $propertyid1 = trim(mysql_real_escape_string($_POST["propertyid1"]));
        $propertydocstype = trim(mysql_real_escape_string($_POST["selected1"]));
        $docno1 = trim(mysql_real_escape_string($_POST["docno1"]));
        $issuedate1 = trim(mysql_real_escape_string($_POST["issuedate1"]));
        $issueofficer1 = trim(mysql_real_escape_string($_POST["issueofficer1"]));
        $desc1 = trim(mysql_real_escape_string($_POST["descr"]));
        $available = '';

//file upload (no mean task because of serialized form-hadto convert to post and reload page with javascript)

        $path = "../propertydocuments/property/";

        $valid_formats = array("jpg", "JPG", "png", "PNG", "gif", "GIF", "bmp", "pdf", "jpeg", "JPEG", "doc", "docx");
        if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {
            $name = $_FILES['propertydoc']['name'];
            $size = $_FILES['propertydoc']['size'];

            if (strlen($name)) {
                list($txt, $ext) = explode(".", $name);
                if (in_array($ext, $valid_formats)) {
                    if ($size < (2048 * 2048)) {
                        $actual_image_name = time() . substr(str_replace(" ", "_", $txt), 5) . "." . $ext;
                        $tmp = $_FILES['propertydoc']['tmp_name'];
                        if (move_uploaded_file($tmp, $path . $actual_image_name)) {
                            echo "successfully moved uploaded file";
                            $available = 1;
                        } else
                            echo "failed" . $path . $actual_image_name;
                    } else
                        echo "Image file size max 2 MB!";
                } else
                    echo "Invalid file format..";
            } else
                echo "Please select image..!";
        }


//file upload ends here

        if (empty($propertydocstype) || empty($issuedate1)) {
            echo 'Database update failed!';
        } else {

            $count = $db->query("SELECT * FROM propertydocs WHERE document_id = '$propertydocstype' AND propertyid='$propertyid1'");
            $numrows = mysql_num_rows($count);

            if ($numrows == 1) {
                $sql = "UPDATE `propertydocs` SET `propdocno`='$docno1',`propdocissuedate`='$issuedate1',`propdocissuer`='$issueofficer1',`propdescr`='$desc1',`propdocpath`='$path$actual_image_name',`docavailable`='$available' WHERE `document_id`='$propertydocstype' AND `propertyid`='$propertyid1'";
            } else {
                $sqlstmnt = $db->query("SELECT document_name FROM propertydocuments WHERE document_id=$propertydocstype");
                while ($row = mysql_fetch_array($sqlstmnt)) {
                    $docname = $row['document_name'];
                }

                $sql = "INSERT INTO propertydocs (document_id,propertyid,propdoc,propdocno,propdocissuedate,propdocissuer,propdescr,propdocpath,docavailable)VALUES
('$propertydocstype','$propertyid1','$docname','$docno1','$issuedate1','$issueofficer1','$desc1','$path$actual_image_name','$available')";
            }
            if (!$db->query($sql)) {
                echo 'Database Insert failed !';
            } else {
                $db->close_connection();
//echo " Successfully uploaded details";
                echo 'Document Saved!';
                echo '<script type="text/javascript">alert("saved!");window.location = "updatedata.php"</script>';
            }
        }
    } else {
        echo "data not received from form!";
    }
}

//add property
function addproperty1() {

    $category = '';
    $db = new MySQLDatabase();
    $db->open_connection();


    $proptype = mysql_real_escape_string($_POST["proptype"]);
    if (empty($_POST["category2"]) && empty($_POST["category3"])) {
        $category = mysql_real_escape_string($_POST["category1"]);
    } elseif (empty($_POST["category1"]) && empty($_POST["category2"])) {
        $category = mysql_real_escape_string($_POST["category3"]);
    } elseif (empty($_POST["category1"]) && empty($_POST["category3"])) {
        $category = mysql_real_escape_string($_POST["category2"]);
    }


    $propname = mysql_real_escape_string($_POST["pname"]);
    $propname = str_replace(' ', '_', $propname);
    $buyown = mysql_real_escape_string($_POST["boname"]);
    $plotno = mysql_real_escape_string($_POST["plotno"]);
    $titleno = mysql_real_escape_string($_POST["titleno"]);
    $acres = mysql_real_escape_string($_POST["acres"]);
    $sqmetres = mysql_real_escape_string($_POST["sqmetres"]);
    $sqft = mysql_real_escape_string($_POST["sqft"]);
    $floors = mysql_real_escape_string($_POST["floors"]);
    $mohalla = mysql_real_escape_string($_POST["mohalla"]);
    $occupants = mysql_real_escape_string($_POST["occupants"]);
    $structstatus = mysql_real_escape_string($_POST["structstatus"]);
    $condition = mysql_real_escape_string($_POST["condition"]);
    $desc = mysql_real_escape_string($_POST["desc"]);
    $address = mysql_real_escape_string($_POST["address"]);
    $propurl = mysql_real_escape_string($_POST["propurl"]);
    $water_rate = mysql_real_escape_string($_POST["watercharge"]);
    $agentcomm = mysql_real_escape_string($_POST["agentcommission"]);
    $vat = mysql_real_escape_string($_POST["vat"]);
    $payday = mysql_real_escape_string($_POST["pay_day"]);

    $agentid = 1;


//print_r($_POST);

    if ($payday==""||$proptype == '' || $propname == '' || $buyown == '' || $plotno == '' || $titleno == '' || $acres == '' || $mohalla == '' || $occupants == '' || $structstatus == '' || $condition == '' || $address == '' || $propurl == '' || $water_rate == '') {
        echo "Enter all Required Fields";
        print_r($_POST);
    } else {

        function PrepSQL($value) {
// Stripslashes
            if (get_magic_quotes_gpc()) {
                $value = stripslashes($value);
            }

// Quote
            $value = "'" . mysql_real_escape_string($value) . "'";

            return($value);
        }

        $sql = "INSERT INTO properties (pay_day,property_name,plotno,property_type,address,mapurl,category,categoryremarks,status,statusremarks,owner,mohalla,occupants,propcondition,conditiondescr,numfloors,area,areasq,areasqft,titledeed,agent_commission,water_rate,has_vat,agentid) VALUES (" .
                 PrepSQL($payday) . ", " .       
                PrepSQL($propname) . ", " .
                PrepSQL($plotno) . ", " .
                PrepSQL($proptype) . ", " .
                PrepSQL($address) . ", " .
                PrepSQL($propurl) . ", " .
                PrepSQL($category) . ", " .
                PrepSQL('<br/>') . ", " .
                PrepSQL($structstatus) . ", " .
                PrepSQL('<br/>') . ", " .
                PrepSQL($buyown) . ", " .
                PrepSQL($mohalla) . ", " .
                PrepSQL($occupants) . ", " .
                PrepSQL($condition) . ", " .
                PrepSQL($desc) . ", " .
                PrepSQL($floors) . ", " .
                PrepSQL($acres) . ", " .
                PrepSQL($sqmetres) . ", " .
                PrepSQL($sqft) . ", " .
                PrepSQL($titleno) . ", " .
                PrepSQL($agentcomm) . ", " .
                PrepSQL($water_rate) . ", " .
                PrepSQL($vat) . ", " .
                PrepSQL($agentid) . ")";



//die($sql);
        $result = $db->query($sql) or die(mysql_error());
        $lastid = mysql_insert_id();
        if (!$result) {
            echo 'Database update failed! '; /* this also exits the script */
        } else {
            echo " Successfully uploaded details";
//add link to db
            $sql = "UPDATE `properties` SET `detailslink`='<a href=\"propertydetails.php?propid=$lastid\">Property Details</a>' WHERE  propertyid='$lastid' limit 1";
            $db->query($sql) or die(mysql_error());
//add floorplan
            $sql2 = $db->query("SELECT * FROM properties WHERE propertyid='$lastid'") or die(mysql_error());
            while ($row = mysql_fetch_array($sql2)) {
                $propertyid = $row['propertyid'];
                $propertyname = $row['property_name'];
                $numfloors = $row['numfloors'];
            }
            echo @addfloors($propertyid, $propertyname, $numfloors);
        }
        //create rent gl account for landlord on landlord side 
        $gl = array();
        $gl['username'] = $_SESSION['username'];
        $gl['account_name'] = $propertyname . '(Rent)';
        $rentincometype = getIncomeType("Rent");
        $gl['idincometypes'] = $rentincometype['idincometypes'];
        $gl['idaccounttype_categories'] = 5; //landlord income
        $gl['idaccount_type'] = 1; //income
        $gl['is_bank'] = 0;
        $gl['balance'] = 0;
        $gl['is_landlord'] = 1; //is landlord
        $gl['property_id'] = $lastid;
        $saved = saveGL($gl, 'insert');
        //create agent gl account for Agent of type Asset (we expect money from him)  
        $glnew = array();
        $glnew['username'] = $_SESSION['username'];
        $glnew['account_name'] = 'Agent Account' . '(' . $propertyname . ')';
        $glnew['idaccounttype_categories'] = 9; //landlord asset
        $glnew['idaccount_type'] = 3; //income
        $glnew['is_landlord'] = 1; //is landlord
        $glnew['balance'] = 0;
        $glnew['property_id'] = $lastid;
        $saved = saveGL($glnew, 'insert');
        //create main gl account for landlord  
        $gl1 = array();
        $gl1['username'] = $_SESSION['username'];
        $gl1['account_name'] = $propertyname . '(Main)';
        $gl1['idaccounttype_categories'] = 6; //landlord Bank
        $gl1['is_landlord'] = 1; //is landlord
        $gl1['idaccount_type'] = 3; //Asset Account
        $gl1['is_bank'] = 0;
        $gl1['balance'] = 0;
        $gl1['property_id'] = $lastid;
        $saved1 = saveGL($gl1, 'insert');

//if vat add another expense gl account
        if ($vat) {
            $vataccount = array();
            $vataccount['username'] = $_SESSION['username'];
            $vataccount['account_name'] = $propertyname . '(VAT)';
            $vataccount['idaccounttype_categories'] = 4; //landlord Expense
            $vataccount['idaccount_type'] = 2; //expense
            $vataccount['is_bank'] = 0;
            $vataccount['is_landlord'] = 1; //is landlord
            $vataccount['balance'] = 0;
            $vataccount['property_id'] = $lastid;
            $saved2 = saveGL($vataccount, 'insert');
        }

        //create landlord account on agent side of type liability
        $glacct = array();
        $glacct['username'] = $_SESSION['username'];
        $glacct['account_name'] = 'Landlord Account(' . $propertyname . ')';
        $glacct['idaccounttype_categories'] = 8; //Agent Liability
        $glacct['is_agent'] = 1;
        $glacct['idaccount_type'] = 4; //Liability
        $glacct['is_bank'] = 0;
        $glacct['balance'] = 0;
        $glacct['property_id'] = $lastid;
        $saved1 = saveGL($glacct, 'insert');
        //create commissions account on agent side of type Income
        $commsacct = array();
        $commsacct['username'] = $_SESSION['username'];
        $commsacct['account_name'] = 'Commissions Account(' . $propertyname . ')';
        $commsacct['idaccounttype_categories'] = 3; //Agent Income
        $incometype = getIncomeType("Commission");
        $commsacct['idincometypes'] = $incometype['idincometypes'];
        $commsacct['is_agent'] = 1;
        $commsacct['idaccount_type'] = 1; //Incomes
        $commsacct['is_bank'] = 0;
        $commsacct['balance'] = 0;
        $commsacct['property_id'] = $lastid;
        $saved = saveGL($commsacct, 'insert');
        //commissions account on landlord side
        $commsacctlandlord = array();
        $commsacctlandlord['username'] = $_SESSION['username'];
        $commsacctlandlord['account_name'] = 'Landlord Commissions Account(' . $propertyname . ')';
        $commsacctlandlord['idaccounttype_categories'] = 4; //Agent Income
        $commsacctlandlord['is_landlord'] = 1;
        $commsacctlandlord['is_commission'] = 1;
        $commsacctlandlord['idaccount_type'] = 2; //Expense
        $commsacctlandlord['is_bank'] = 0;
        $commsacctlandlord['balance'] = 0;
        $commsacctlandlord['property_id'] = $lastid;
        $savedcomm = saveGL($commsacctlandlord, 'insert');


        //penalty acct


        @$db->close_connection();
    }
}

function addfloors($propertyid, $propertyname, $numfloors) {
    $db = new MySQLDatabase();
    $db->open_connection();
    $tag = substr($propertyname, 0, 3); //apartment prefix
    for ($i = 1; $i <= $numfloors; $i++) {

        $sql = "Insert into floorplan (propertyid,property_name,floornumber,units,apt_tag,monthlyincome,marketvalue) values 
('$propertyid','$propertyname','$i','0','$tag$i','0','0');";
        $result = $db->query($sql) or die(mysql_error());
    }
    echo empty($result) ? "values not inserted" : ""; //"success!";
    $db->close_connection();
}

//get apartment details
function getApartmentDetails($aptid) {
    $rows = array();
    $mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME, 3306);
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") ";
    }
    $resultset = $mysqli->query("SELECT * FROM floorplan WHERE apt_id='$aptid'") or die($mysqli->error);

    while ($row = $resultset->fetch_assoc()) {
        array_push($rows, $row);
    }
    mysqli_close($mysqli);
//print_r($row);
    return $rows;
}

//get floorplan
function getfloorplan() {

    $propid = $_REQUEST['propid'];
    $db = new MySQLDatabase();
    $db->open_connection();


    echo '<table id="floorplan">
<tr>
<th>Propertyid</th>
<th>Floor</th>
<th>Units/floor</th>
<th>Apartment Tag</th>
<th>Monthly Unit Income(Ksh)</th>
<th>Monthly Total Income(Ksh)</th>
<th>Current Mkt Value(Ksh)</th>
</tr>';
//sql query to retreive tabular data for floorplan
    $sql = $db->query("SELECT * FROM floorplan WHERE propertyid='$propid'") or die(mysql_error());
    while ($row = mysql_fetch_array($sql)) {
        echo "<tr>";
        echo "<td>" . $row['propertyid'] . "</td>";
        echo "<td>" . $row['floornumber'] . "</td>";
        echo "<td>" . $row['units'] . "</td>";
        echo "<td>" . $row['apt_tag'] . "</td>";
        echo "<td>" . number_format($row['monthlyincome']) . "</td>";
        echo "<td>" . number_format($row['units'] * $row['monthlyincome']) . "</td>";
        echo "<td>" . number_format($row['marketvalue']) . "</td>";

        echo "</tr>";
    }
    $db->close_connection();
    echo '</table>';
}

function dropdown() {

    $sql = $db->query("SELECT * FROM properties") or die(mysql_error());
    while ($row = mysql_fetch_array($sql)) {
        echo "<option value=" . $row['propertyid'] . ">" . $row['property_name'] . "</option>";
    }
}

function availabledocs() {
    echo '<link rel="stylesheet" href="../css/update.css" />';
    $db = new MySQLDatabase();
    $db->open_connection();
    $id = $_REQUEST['propid'];

    echo'<U><b>Legal Documents available</b></U>';
    echo '<table id="floorplan">
<tr>
<th>Propertyid</th>
<th>Document</th>
<th>Document No</th>

</tr>';
//sql query to retreive tabular data for floorplan
    $sql = $db->query("SELECT * FROM legaldocs where propertyid=$id") or die(mysql_error());
    while ($row = mysql_fetch_array($sql)) {
        echo "<tr class=\"alt\">";
        echo "<td>" . $row['propertyid'] . "</td>";
        echo "<td>" . $row['doc'] . '&nbsp;&nbsp;<img src="../images/cursors/available.png"></img>' . "</td>";
        echo "<td>" . $row['docno'] . "</td>";
        echo "</tr>";
    }
    echo '</table>';

//propertydocs available
    echo'<U><b>Property Documents available</b></U>';
    echo '<table id="floorplan">
<tr>
<th>Propertyid</th>
<th>Document</th>
<th>Document No</th>

</tr>';

    $sql1 = $db->query("SELECT * FROM propertydocs where propertyid=$id") or die(mysql_error());
    while ($row = mysql_fetch_array($sql1)) {
        echo "<tr class=\"alt\">";
        echo "<td>" . $row['propertyid'] . "</td>";
        echo "<td>" . $row['propdoc'] . '&nbsp;&nbsp;<img src="../images/cursors/available.png"></img>' . "</td>";
        echo "<td>" . $row['propdocno'] . "</td>";
        echo "</tr>";
    }
    $db->close_connection();
    echo '</table>';
}

function availabledocslegal() {

    $db = new MySQLDatabase();
    $db->open_connection();
    $id = $_REQUEST['propid'];

    $result = array();

    $sql = $db->query("SELECT * FROM legaldocs where propertyid=$id") or die(mysql_error());
    while ($row = mysql_fetch_array($sql)) {

        $result[] = $row['doc'] . '------AVAILABLE';
    }

    $json = array(array('field' => 'document1', 'value' => @$result[0]),
        array('field' => 'document2', 'value' => @$result[1]),
        array('field' => 'document3', 'value' => @$result[2]),
        array('field' => 'document4', 'value' => @$result[3]),
        array('field' => 'document5', 'value' => @$result[4]),
        array('field' => 'document6', 'value' => @$result[5]),
        array('field' => 'document7', 'value' => @$result[6]),
        array('field' => 'document8', 'value' => @$result[7]),
        array('field' => 'document9', 'value' => @$result[8]),
        array('field' => 'document10', 'value' => @$result[9]),);

    echo json_encode($json);
    $db->close_connection();
}

function availabledocsproperty() {

    $db = new MySQLDatabase();
    $db->open_connection();
    $id = $_REQUEST['propid'];

    $result = array();

    $sql = $db->query("SELECT * FROM propertydocs where propertyid=$id") or die(mysql_error());
    while ($row = mysql_fetch_array($sql)) {

        $result[] = $row['propdoc'] . '------AVAILABLE';
    }

    $json = array(array('field' => 'document1', 'value' => @$result[0]),
        array('field' => 'document2', 'value' => @$result[1]),
        array('field' => 'document3', 'value' => @$result[2]),
        array('field' => 'document4', 'value' => @$result[3]),
        array('field' => 'document5', 'value' => @$result[4]),
        array('field' => 'document6', 'value' => @$result[5]),
        array('field' => 'document7', 'value' => @$result[6]),
        array('field' => 'document8', 'value' => @$result[7]),
    );

    echo json_encode($json);
    $db->close_connection();
}

//upload photos

function uploadpictorials($propertyid, $photocategory) {
    $db = new MySQLDatabase();
    $db->open_connection();
//// Php photo uploader

    $uploadpath = '../images/gallery/';      // directory to store the uploaded files
    $max_size = 20480;          // maximum file size, in KiloBytes
    $alwidth = 19600;            // maximum allowed width, in pixels
    $alheight = 12000;           // maximum allowed height, in pixels
    $allowtype = array('bmp', 'gif', 'GIF', 'jpg', 'JPG', 'jpeg', 'JPEG', 'png', 'PNG', 'pdf');        // allowed extensions

    if (isset($_FILES['fileup']) && strlen($_FILES['fileup']['name']) > 1) {
        $uploadpath = $uploadpath . basename(time() . "_" . $_FILES['fileup']['name']);       // gets the file name
        $sepext = explode('.', strtolower($_FILES['fileup']['name']));
        $type = end($sepext);       // gets extension
        list($width, $height) = getimagesize($_FILES['fileup']['tmp_name']);     // gets image width and height
        $err = '';         // to store the errors
// Checks if the file has allowed type, size, width and height (for images)
        if (!in_array($type, $allowtype))
            $err .= 'The file: <b>' . $_FILES['fileup']['name'] . '</b> is NOT  the allowed file type.';
        if ($_FILES['fileup']['size'] > $max_size * 1000)
            $err .= '<br/>Maximum file size must be: ' . $max_size . ' KB.';
        if (isset($width) && isset($height) && ($width >= $alwidth || $height >= $alheight))
            $err .= '<br/>The maximum Width x Height must be: ' . $alwidth . ' x ' . $alheight;

// If no errors, upload the image, else, output the errors
        if ($err == '') {
            if (move_uploaded_file($_FILES['fileup']['tmp_name'], $uploadpath)) {

                $insert = $db->query("insert into pictorials(propertyid,path,priority,photocategory)values('" . $propertyid . "','" . $uploadpath . "','0','$photocategory')") or die(mysql_error());
                $query = $db->query("SELECT * FROM pictorials WHERE propertyid='$propertyid'");
                $numrows = mysql_num_rows($query);
                if ($numrows > 1) {
                    $sql = "UPDATE `pictorials` SET `priority`='1' WHERE  propertyid='$propertyid' limit 1";
                    $db->query($sql);
                }
                echo ' <script type="text/javascript">
alert("Photo:' . basename($_FILES['fileup']['name']) . 'successfully uploaded");
window.location = "updatedata.php#tabs-4" </script>';
            } else
                echo '<script type="text/javascript">
alert("Unable to upload the photo");
window.location = "updatedata.php#tabs-4" </script>';
        } else
            echo '<script type="text/javascript">
alert("' . $err . '");
window.location = "updatedata.php#tabs-4"</script>';
    }
    $db->close_connection();
}

//function insert/update photos ends here

function lookupphotos($propid, $photocat) {
    $db = new MySQLDatabase();
    $db->open_connection();
    $images = array();
    $propid = $propid;
    if (empty($propid)) {
        echo '<center><h3>select property first!</h3></center>';
        exit();
    } else
    if ($photocat == 'ALL') {
        $sql = $db->query("SELECT * FROM pictorials WHERE propertyid='$propid'") or die(mysql_error());
    } else {
        $sql = $db->query("SELECT * FROM pictorials WHERE propertyid='$propid' AND photocategory like '$photocat'") or die(mysql_error());
    }
    $numrows = mysql_num_rows($sql);
    if ($numrows != 0) {
        while ($row = mysql_fetch_array($sql)) {
            $id = $row['id'];
            $path = $row['path'];
            array_push($images, '<a href="#" class="photos" title="' . $path . '" id="' . $id . '"><img id="photos" src="' . $path . '" title="' . $id . '" width="100" height="100"/></a>');
        }
    } else {
        $images = array("0" => "<center><h3>no images</h3></center>");
    }
    return $images;

    $db->close_connection();
}

function deletephotos($photoid) {
    $db = new MySQLDatabase();
    $db->open_connection();

    $propertyid = $propertyid;
    $photoid = $photoid;
    if (empty($photoid) || $photoid === 'undefined') {
        echo '<script type="text/javascript">
alert("No photo to delete!");
window.location = "updatedata.php#tabs-4"</script>';
    }

    $sql = "SELECT * FROM pictorials WHERE id='$photoid'";
    $query = $db->query($sql) or die(mysql_error());
    while ($row = mysql_fetch_array($query)) {
        $resultpath = $row['path'];
    }

    if (file_exists($resultpath)) {

        if (unlink($resultpath))
//it's better to use the ID rather than the name of the file to delete it from db
            $db->query("DELETE FROM pictorials WHERE id='$photoid'") or die(mysql_error());
        echo '<script type="text/javascript">
alert("photo deleted");
window.location = "updatedata.php#tabs-4"</script>';
    }
    $db->close_connection();
}

//move photo
function movephotos($photoid, $target) {
    $db = new MySQLDatabase();
    $db->open_connection();
    $db->query("UPDATE `pictorials` SET `photocategory`='$target' WHERE id='$photoid'") or die(mysql_error());
    $db->close_connection();
}

//page content on return properties

function paginate() {
    $db = new MySQLDatabase();
    $db->open_connection();

    $sql = "SELECT properties.property_name, properties.category,properties.tm,pictorials.path FROM properties LEFT JOIN pictorials ON properties.propertyid=pictorials.propertyid WHERE pictorials.priority='1' ";

    $query = $db->query($sql) or die(mysql_error());
    $nr = mysql_num_rows($query);
    while ($row = mysql_fetch_array($query)) {
        $resultname = $row['property_name'];
        $resultcategory = $row['category'];
        $resultpath = $row['path'];
    }
    $delstring = $resultname . '#' . $resultcategory . '#' . $resultpath . '#' . $nr;
    return $delstring;
    $db->close_connection();
}

function loadpropertydetails($id) {

    $photos = array();
    $db = new MySQLDatabase();
    $db->open_connection();
    $id = $id;
    $sql = "SELECT * FROM properties WHERE propertyid='$id' ";
    $sql2 = "SELECT * FROM pictorials WHERE propertyid='$id'";
    $query = $db->query($sql) or die(mysql_error()); //$nr = mysql_num_rows( $query); 
    while ($row = mysql_fetch_array($query)) {
        $resultname = $row['property_name'];
        $resultplotno = $row['plotno'];
        $resulttype = $row['property_type'];
        $category = $row['category'];
        $owner = $row['owner'];
        $occupants = $row['occupants'];
        $resultmohalla = $row['mohalla'];
        $propcondition = $row['propcondition'];
        $resultaddress = $row['address'];
        $resultmap = $row['mapurl'];
        $resultareasq = $row['areasq'];
        $resultitle = $row['titledeed'];
    }
    $query2 = $db->query($sql2) or die(mysql_error());  //$nr2 = mysql_num_rows( $query2); 
    while ($row2 = mysql_fetch_array($query2)) {
        array_push($photos, $row2['path']); /* push paths into photos array */
    }
    $propertydetails = array("property_name" => "$resultname", "plotno" => "$resultplotno", "title" => "$resultitle", "propcondition" => "$propcondition", "proptype" => "$resulttype", "category" => $category, "owner" => $owner, "occupants" => $occupants, "mohalla" => "$resultmohalla", "area" => $resultareasq, "address" => $resultaddress, "map" => $resultmap, "photo1" => @$photos[0], "photo2" => @$photos[1], "photo3" => @$photos[2], "photo4" => @$photos[3], "photo5" => @$photos[4]);  //push results to array queue
    return $propertydetails;
}

//retreive floor plan details for a property
function floorplan($id) {
    $db = new MySQLDatabase();
    $db->open_connection();
    $floordetail = array();
    $allfloordetails = array();
    $sql = "SELECT * FROM floorplan WHERE propertyid='$id' ORDER BY apt_id ASC";
    $query = $db->query($sql) or die(mysql_error());
    while ($row = mysql_fetch_array($query)) {
        $resultapt = $row['apt_id'];
        $resultname = $row['property_name'];
        $resultfloornum = $row['floornumber'];
        $resultag = $row['apt_tag'];
        $monthlyinc = $row['monthlyincome'];
        $totalinc = $row['yearlyincome'];
        $marketvalue = $row['marketvalue'];
        $elecmeter = $row['elec_meter'];
        $watermeter = $row['water_meter'];
        $metereading = $row['current_water_reading'];
        $floordetails["apt_id"] = $resultapt;
        $floordetails["property_name"] = $resultname;
        $floordetails["floornumber"] = $resultfloornum;
        if ($row['isoccupied'] != 0) {
            $floordetails["tenant_name"] = findtenantbyapt($resultapt);
        } else {
            $floordetails["tenant_name"] = "VACANT";
        }
        $floordetails["apt-tag"] = $resultag;
        $floordetails["monthlyincome"] = $monthlyinc;
        $floordetails["totalincome"] = $totalinc;
        $floordetails["marketvalue"] = $marketvalue;
        $floordetails["yearlyincome"] = $totalinc;
        $floordetails["elecmeter"] = $elecmeter;
        $floordetails["watermeter"] = $watermeter;
        $floordetails["metereading"] = $metereading;
        $floordetails["isoccupied"] = $row['isoccupied'];
        $floordetails["receipt_due"] = $row['receipt_due'];
        array_push($allfloordetails, $floordetails);
    }
    $db->close_connection();
    return $allfloordetails;
}

//chargeitems
//retreive floor plan details
function getChargeItems($propid) {
    
    $db = new MySQLDatabase();
    $db->open_connection();
    $chargedetail = array();
    $allchargedetails = array();
    $sql = "SELECT * FROM chargeitems WHERE propertyid='$propid' ORDER BY accname ASC";
    $query = $db->query($sql) or die(mysql_error());
    while ($row = $db->fetch_array($query)) {
        $accname = $row['accname'];
        $amount = $row['amount'];
        $id = $row['id'];
        $chargedetail['id'] = $id;
        $chargedetail['accname'] = $accname;
        $chargedetail['amount'] = $amount;
        $chargedetail['has_vat'] = $row['has_vat'];
        $chargedetail['commission'] = $row['charged_commission'];
        $chargedetail['is_deposit'] = $row['is_deposit'];
        array_push($allchargedetails, $chargedetail);
    }
    $db->close_connection();
    return $allchargedetails;
}

//chargeable item xristics

function getChargeItem($id) {
    $chargeitemstable = getChargeItemsTable();
    $chargeitemdetails = array();
    $mysqli = getMysqliConnection();
    $result = $mysqli->query("SELECT * FROM {$chargeitemstable} WHERE id='$id'") or die($mysqli->error);
    while ($row = $result->fetch_assoc()) {
        $chargeitemdetails['id'] = $row['id'];
        $chargeitemdetails['accname'] = $row['accname'];
        $chargeitemdetails['has_vat'] = $row['has_vat'];
        $chargeitemdetails['charged_commission'] = $row['charged_commission'];
        $chargeitemdetails['is_deposit'] = $row['is_deposit'];
        $chargeitemdetails['amount'] = $row['amount'];
    }

    return $chargeitemdetails;
}

//get charge item from name
function getChargeItemByName($name, $propid) {
    $chargeitemstable = getChargeItemsTable();
    $chargeitemdetails = array();
    $mysqli = getMysqliConnection();
    $result = $mysqli->query("SELECT Distinct * FROM {$chargeitemstable} WHERE `accname`='$name' AND `propertyid`='$propid' GROUP BY `id`") or die($mysqli->error);
    while ($row = $result->fetch_assoc()) {
        $chargeitemdetails['id'] = $row['id'];
        $chargeitemdetails['accname'] = $row['accname'];
        $chargeitemdetails['has_vat'] = $row['has_vat'];
        $chargeitemdetails['charged_commission'] = $row['charged_commission'];
        $chargeitemdetails['is_deposit'] = $row['is_deposit'];
        $chargeitemdetails['amount'] = $row['amount'];
    }

    return $chargeitemdetails;
}

//add apartment to building
function addapartments($rows) {
    $db = new MySQLDatabase();
    $db->open_connection();
    $propertyid = $rows['propertyid'];
    $floornumber = $rows['floornumber'];
    $apt_tag = $rows['apt_tag'];
    $monthlyincome = $rows['monthlyincome'];
    $yearlyincome = 12 * $monthlyincome;
    $marketvalue = $rows['marketvalue'];
    $elecmeter = $rows['elecmeter'];
    $watermeter = $rows['watermeter'];
    $metereading = $rows['metereading'];
    $receipt_due = $rows['receipt_due'];
    $sql0 = "SELECT property_name FROM properties WHERE propertyid='$propertyid'";
    $query0 = $db->query($sql0);
    while ($row0 = mysql_fetch_array($query0)) {
        $propertyname = $row0['property_name'];
    }
    $house_account = incrementnumber('house_account');

    $sql = "INSERT INTO floorplan (house_account,propertyid,property_name,floornumber,units,apt_tag,monthlyincome,yearlyincome,marketvalue,elec_meter,water_meter,current_water_reading,receipt_due)VALUES
('$house_account','$propertyid','$propertyname','$floornumber','0','$apt_tag','$monthlyincome','$yearlyincome','$marketvalue','$elecmeter','$watermeter','$metereading','$receipt_due')";

    $result = $db->query($sql) or die($db->error());
    $lastid = mysql_insert_id();
    if (!$result) {
        return FALSE;
    } else {
        //create apartment  gl account
        $gl = array();
        $gl['username'] = $_SESSION['username'];
        $gl['account_name'] = $propertyname . '(' . $apt_tag . ')';
        $gl['idaccounttype_categories'] = 7; //Agent Asset
        $gl['idaccount_type'] = 3; //income
        $gl['is_bank'] = 0;
        $gl['balance'] = 0;
        $gl['is_tenant'] = 1;
        $gl['apt_id'] = $lastid;
        $gl['property_id'] = $propertyid;
        $saved = saveGL($gl, 'insert');
        $db->close_connection();
        return TRUE;
    }
}

//edit the floorplan details
function editfloorplan($rows) {
    $db = new MySQLDatabase();
    $db->open_connection();
    $aptid = $rows['apt_id'];
    $propertyid = $rows['propertyid'];
    $floornumber = $rows['floornumber'];
    $apt_tag = $rows['apt_tag'];
    $monthlyincome = $rows['monthlyincome'];
    $yearlyincome = 12 * $monthlyincome;
    $marketvalue = $rows['marketvalue'];
    $elecmeter = $rows['elecmeter'];
    $watermeter = $rows['watermeter'];
    $metereading = $rows['metereading'];
    $receipt_due = $rows["receipt_due"];
//print_r($rows);
    $sql = "UPDATE `floorplan` SET `propertyid`='$propertyid',`floornumber`='$floornumber',`units`='0',`apt_tag`='$apt_tag',`monthlyincome`='$monthlyincome',`yearlyincome`='$yearlyincome',`marketvalue`='$marketvalue',`elec_meter`='$elecmeter',`water_meter`='$watermeter',`current_water_reading`='$metereading',`receipt_due`='$receipt_due' WHERE  apt_id='$aptid' limit 1";
    $result = $db->query($sql) or die($db->error());

    if (!$result) {
        return FALSE;
    } return TRUE;
    $db->close_connection();
}

//get apartments of a property
function getPropertyApartments($propertyid) {
    $mysqli = getMysqliConnection();
    $floorplantable = 'floorplan';
    $apartmentdetails = array();
    $allapartments = array();
    $resultset = $mysqli->query("SELECT apt_id,apt_tag FROM $floorplantable WHERE propertyid='$propertyid'") or die($mysqli->error);
    while ($row = $resultset->fetch_assoc()) {
        $apartmentdetails['apt_id'] = $row['apt_id'];
        $apartmentdetails['apt_tag'] = $row['apt_tag'];
        array_push($allapartments, $apartmentdetails);
    }
    return $allapartments;
}

//delete house
function deletefloorplan($rows) {
    $db = new MySQLDatabase();
    $db->open_connection();
    $aptid = $rows['apt_id'];
    $sql = "DELETE FROM floorplan WHERE apt_id='$aptid' ";

    if (!$db->query($sql)) {
        return FALSE;
    } return TRUE;
    $db->close_connection();
}

//load deposits
function getDeposits($aptid) {
    $db = new MySQLDatabase();
    $db->open_connection();
    $apartmentdeposits = array();
    $sql = $db->query("SELECT * FROM deposits WHERE apt_id='$aptid'") or die(mysql_error());

    while ($row = mysql_fetch_array($sql)) {
        array_push($apartmentdeposits, $row);
    }
    return $apartmentdeposits;
    $db->close_connection();
}

//save deposit
function saveDeposits($aptid, $deposit, $value) {
    $db = new MySQLDatabase();
    $db->open_connection();
    $apartmentdeposits = array();
    $sql = $db->query("INSERT into deposits (dep_description,apt_id,amount) VALUES ('$deposit','$aptid','$value')") or die(mysql_error());
    if ($sql) {
        return TRUE;
    } else {
        return FALSE;
    }
    $db->close_connection();
}

//delete deposit
function deleteDeposits($dep_id) {
    $db = new MySQLDatabase();
    $db->open_connection();
    $sql = $db->query("DELETE FROM deposits WHERE dep_id='$dep_id' ") or die(mysql_error());
    if ($sql) {
        return TRUE;
    } else {
        return FALSE;
    }
    $db->close_connection();
}

//save charge items
function saveChargeItem($propid, $itemid, $item, $amount, $vat, $commission, $isdeposit) {
    $db = new MySQLDatabase();
    $db->open_connection();
    $chargeitemstable = getChargeItemsTable();
    $result = $db->query("SELECT * FROM $chargeitemstable WHERE id='$itemid'") or die($db->error());
    if ($db->num_rows($result) >= 1) {
        $sql = $db->query("UPDATE $chargeitemstable SET accname='$item',amount='$amount',has_vat='$vat',charged_commission='$commission',is_deposit='$isdeposit' WHERE id='$itemid'") or die($db->error());
    }
    $sql = $db->query("INSERT into $chargeitemstable (propertyid,accname,amount,has_vat,charged_commission,is_deposit) VALUES ('$propid','$item','$amount','$vat','$commission','$isdeposit')") or die(mysql_error());
    if ($sql) {
        return TRUE;
    } else {
        return FALSE;
    }
    $db->close_connection();
}

//delete deposit
function deleteChargeItem($itemid) {
    $db = new MySQLDatabase();
    $db->open_connection();
    $chargeitemstable = getChargeItemsTable();

    $sql = $db->query("DELETE FROM $chargeitemstable WHERE id='$itemid' ") or die($db->error());
    if ($sql) {
        return TRUE;
    } else {
        return FALSE;
    }
    $db->close_connection();
}

function populateproperties() {
    $db = new MySQLDatabase();
    $db->open_connection();
    $sql = $db->query("SELECT propertyid,property_name FROM properties ORDER BY property_name ASC") or die(mysql_error());

    while ($row = mysql_fetch_array($sql)) {
        $respropid = $row['propertyid'];
        echo "<option value='$respropid' >" . htmlspecialchars($row['property_name']) . "</option>";
    }

    $db->close_connection();
}

function populateapartments($propertyid = 31) {
    $db = new MySQLDatabase();
    $db->open_connection();
    $sql = $db->query("SELECT apt_id,apt_tag FROM floorplan WHERE propertyid='$propertyid'") or die(mysql_error());

    while ($row = mysql_fetch_array($sql)) {
        $aptid = $row['apt_id'];
        echo "<option value='$aptid' >" . htmlspecialchars($row['apt_tag']) . "</option>";
    }

    $db->close_connection();
}

function populateapartmentselect($propertyid) {
    $db = new MySQLDatabase();
    $db->open_connection();
    $sql = $db->query("SELECT apt_id,apt_tag FROM floorplan WHERE propertyid='$propertyid'") or die(mysql_error());
    echo '<select id="unitname" name="unitname"  style="width:305px;" class="input">';
    echo '<option selected="selected" value="all">---</option>';
    while ($row = mysql_fetch_array($sql)) {
        $aptid = $row['apt_id'];
        echo "<option value='$aptid' >" . htmlspecialchars($row['apt_tag']) . "</option>";
    }
    echo '</select>';
    $db->close_connection();
}

function populatecommercialproperties() {
    include 'includes/database.php';
    $db = new MySQLDatabase();
    $db->open_connection();
    $sql = $db->query("SELECT propertyid,property_name FROM properties WHERE propertyid >1") or die(mysql_error());

    while ($row = mysql_fetch_array($sql)) {
        $respropid = $row['propertyid'];
        echo "<option value='$respropid' >" . htmlspecialchars($row['property_name']) . "</option>";
    }

    $db->close_connection();
}

function populatelegaldocs() {
    $db = new MySQLDatabase();
    $db->open_connection();
    $sql = $db->query("SELECT document_id,document_name FROM propertydocuments WHERE doc_type='legaldocument'") or die(mysql_error());
    while ($row = mysql_fetch_array($sql)) {
        $resdocid = $row['document_id'];
        echo "<option value='$resdocid' >" . htmlspecialchars($row['document_name']) . "</option>";
    }

    $db->close_connection();
}

function populatepropdocs() {
    $db = new MySQLDatabase();
    $db->open_connection();
    $sql = $db->query("SELECT document_id,document_name FROM propertydocuments WHERE doc_type='propertydocument'") or die(mysql_error());
    while ($row = mysql_fetch_array($sql)) {
        $resdocid = $row['document_id'];
        echo "<option value='$resdocid' >" . htmlspecialchars($row['document_name']) . "</option>";
    }


    $db->close_connection();
}

//for populating tenants on relocation
function populatetenants() {
    $db = new MySQLDatabase();
    $db->open_connection();
//    if ($_SESSION['usergroup'] == 1) {
//        $propid = '%';
//    } else {
    $propid = $_SESSION['propertyid'];
    // }
    $sql = $db->query("SELECT id,tenant_name FROM tenants WHERE property_id like '$propid' ORDER BY tenant_name ASC") or die(mysql_error());
    while ($row = mysql_fetch_array($sql)) {
        $restenantid = $row['id'];
        echo "<option value='$restenantid' >" . htmlspecialchars($row['tenant_name']) . "</option>";
    }
    $db->close_connection();
}

function getlegdocuments($propid) {
    $db = new MySQLDatabase();
    $db->open_connection();
    $myarray = array();
    $sql = $db->query("SELECT document_id,doc FROM legaldocs WHERE propertyid='$propid'");
    while ($row = mysql_fetch_array($sql)) {
        $checked = '<input type="checkbox" class="propdoc" value="' . $row['document_id'] . '">' . $row['doc'] . '&nbsp;&nbsp;';
        array_push($myarray, $checked);
    }
    foreach ($myarray as $array) {
        echo $array;
    }

    $db->close_connection();
}

function getpropdocuments($propid) {
    $db = new MySQLDatabase();
    $db->open_connection();
    $proparray = array();
    $sql = $db->query("SELECT document_id,propdoc FROM propertydocs WHERE propertyid='$propid'");
    while ($row = mysql_fetch_array($sql)) {
        $checked = '<input type="checkbox" class="propdoc" value="' . $row['document_id'] . '">' . $row['propdoc'] . '&nbsp;&nbsp;';
        array_push($proparray, $checked);
    }
    foreach ($proparray as $array) {
        echo $array;
    }
//return $proparray;
    $db->close_connection();
}

function prepare_legal_documents($propid, $stringdocs) {
    $db = new MySQLDatabase();
    $db->open_connection();

    $arraydocs = preg_split("/[\s$]+/", $stringdocs);
    $query1 = '';
    for ($i = 1; $i < (count($arraydocs) - 1); $i++) {
        $query1.="OR document_id = '$arraydocs[$i]'";
    }
    if (count($arraydocs) > 1) {
        $q = "SELECT * FROM `legaldocs` WHERE propertyid='$propid' AND (document_id = '$arraydocs[0]'  $query1 )";
    } else {
        $q = "SELECT *FROM `legaldocs`WHERE propertyid = '$propid' AND document_id = '$arraydocs[0]' ";
    } //ORDER BY PRIORITY/DOCUMENT_TYPE ETC
    $query = $db->query($q) or die(mysql_error());
    while ($row = mysql_fetch_assoc($query)) {
        $id = $row['id'];
        $path = $row['path'];
        $doc = $row['doc'];
        $array = array("id" => $id, "path" => $path, "document" => $doc);
    }
    if (empty($array)) {
        exit();
    }
    return $array;
    $db->close_connection();
}

function prepare_property_documents($propid, $stringdocs) {
    $db = new MySQLDatabase();
    $db->open_connection();


    $arraydocs1 = preg_split("/[\s$]+/", $stringdocs);
    $query2 = '';
    for ($i = 1; $i < (count($arraydocs1) - 1); $i++) {
        $query2.=" OR document_id = '$arraydocs1[$i]'";
    }

    if (count($arraydocs1) > 1) {
        $q = "SELECT * FROM `propertydocs` WHERE propertyid = '$propid' AND (document_id = '$arraydocs1[0]'$query2 )";
    } else {
        $q = "SELECT *FROM `propertydocs` WHERE propertyid = '$propid' AND document_id = '$arraydocs1[0]' ";
    } //ORDER BY PRIORITY/DOCUMENT_TYPE ETC
//echo $q;
    $queryy = $db->query($q) or die(mysql_error());

    while ($row = mysql_fetch_assoc($queryy)) {
        $id = $row['propdocid'];
        $path = $row['propdocpath'];
        $doc = $row['propdoc'];
        $array1 = array("id" => $id, "path" => $path, "document" => $doc);
    }
    if (empty($array1)) {
        exit();
    }
    return $array1;
    $db->close_connection();
}

function propertyname($propid) {
    $db = new MySQLDatabase();
    $db->open_connection();
    $q = "SELECT property_name FROM properties WHERE propertyid like '$propid'";
    $query = $db->query($q) or die(mysql_error());
    $db->close_connection();
    while ($row = mysql_fetch_assoc($query)) {
        $array = $row['property_name'];
    }
    return $array;
}

function propertyinfo($propid) {
    $db = new MySQLDatabase();
    $db->open_connection();
    $res = mysql_query("SELECT * FROM properties WHERE propertyid = '$propid' ") or die (mysql_error());
	$response_array=array();
	 $db->close_connection();
    while ($row = mysql_fetch_assoc($res)){
		//$aptid = $row['apt_id'];
		 $response_array[] =$row; 
      
        
    }
    header('Content-type: application/json');
    //$response_array['address']=$address; $response_array['email']=$email; $response_array['phone']=$phone;  $response_array['city']=$city;      
    return json_encode($response_array); 
}

function update_prop($propid,$url,$plot_no,$propname,$titledeed_no){
   $db=new MySQLDatabase();        $tablename="properties";
    $db->open_connection();
	

    $res = mysql_query("UPDATE $tablename SET `property_name`='$propname',mapurl='$url',titledeed='$titledeed_no',plotno =' $plot_no' WHERE propertyid ='$propid'")or die ($mysql_error());
    if($res){
	echo '1'; 
	}else{
	echo '2'; 	
	}	
    $db->close_connection();  
}


function intPart($float) {
    if ($float < -0.0000001)
        return ceil($float - 0.0000001);
    else
        return floor($float + 0.0000001);
}

//calculate hirji dates from gregorian calender
function Hijri2Greg($day, $month, $year, $string = false) {
    $day = (int) $day;
    $month = (int) $month;
    $year = (int) $year;

    $jd = intPart((11 * $year + 3) / 30) + 354 * $year + 30 * $month - intPart(($month - 1) / 2) + $day + 1948440 - 385;

    if ($jd > 2299160) {
        $l = $jd + 68569;
        $n = intPart((4 * $l) / 146097);
        $l = $l - intPart((146097 * $n + 3) / 4);
        $i = intPart((4000 * ($l + 1)) / 1461001);
        $l = $l - intPart((1461 * $i) / 4) + 31;
        $j = intPart((80 * $l) / 2447);
        $day = $l - intPart((2447 * $j) / 80);
        $l = intPart($j / 11);
        $month = $j + 2 - 12 * $l;
        $year = 100 * ($n - 49) + $i + $l;
    } else {
        $j = $jd + 1402;
        $k = intPart(($j - 1) / 1461);
        $l = $j - 1461 * $k;
        $n = intPart(($l - 1) / 365) - intPart($l / 1461);
        $i = $l - 365 * $n + 30;
        $j = intPart((80 * $i) / 2447);
        $day = $i - intPart((2447 * $j) / 80);
        $i = intPart($j / 11);
        $month = $j + 2 - 12 * $i;
        $year = 4 * $k + $n + $i - 4716;
    }

    $data = array();
    $date['year'] = $year;
    $date['month'] = $month;
    $date['day'] = $day;

    if (!$string)
        return $date;
    else
        return "{$year}-{$month}-{$day}";
}

function Greg2Hijri($day, $month, $year, $string = false) {
    $day = (int) $day;
    $month = (int) $month;
    $year = (int) $year;

    if (($year > 1582) or ( ($year == 1582) and ( $month > 10)) or ( ($year == 1582) and ( $month == 10) and ( $day > 14))) {
        $jd = intPart((1461 * ($year + 4800 + intPart(($month - 14) / 12))) / 4) + intPart((367 * ($month - 2 - 12 * (intPart(($month - 14) / 12)))) / 12) -
                intPart((3 * (intPart(($year + 4900 + intPart(($month - 14) / 12) ) / 100) ) ) / 4) + $day - 32075;
    } else {
        $jd = 367 * $year - intPart((7 * ($year + 5001 + intPart(($month - 9) / 7))) / 4) + intPart((275 * $month) / 9) + $day + 1729777;
    }

    $l = $jd - 1948440 + 10632;
    $n = intPart(($l - 1) / 10631);
    $l = $l - 10631 * $n + 354;
    $j = (intPart((10985 - $l) / 5316)) * (intPart((50 * $l) / 17719)) + (intPart($l / 5670)) * (intPart((43 * $l) / 15238));
    $l = $l - (intPart((30 - $j) / 15)) * (intPart((17719 * $j) / 50)) - (intPart($j / 16)) * (intPart((15238 * $j) / 43)) + 29;

    $month = intPart((24 * $l) / 709);
    $day = $l - intPart((709 * $month) / 24);
    $year = 30 * $n + $j - 30;

    $date = array();
    $date['year'] = $year;
    $date['month'] = $month;
    $date['day'] = $day;

    if (!$string)
        return $date;
    else
        return "{$year}-{$month}-{$day}";
}

//hirji calendar

function gettenants() {
//    if ($_SESSION['usergroup'] == 1) {
//        $propid = '%';
//    } else {

    $propid = $_SESSION['propertyid'];
    // }
    $db = new MySQLDatabase();
    $db->open_connection();
//$path='<a href="">Path</a>';
    $aColumns = array('Id', 'tenant_name', 'tenantphone', 'tenantemail', 'workplace', 'idno', 'Apartment_tag', 'property_name', 'fromdate', 'todate', 'leasedoc', 'property_id','kins_name','kinstel','kinsemail'); //Change Column Headings

    /* Indexed column (used for fast and accurate table cardinality) */
    $sIndexColumn = "Id"; //CHANGE THE INDEX COLUMN

    /* DB table to use */
    $sTable = "tenants";  //CHANGE THE TABLE



    /*
     * Paging
     */
    $sLimit = "";
    if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
        $sLimit = "LIMIT " . mysql_real_escape_string($_GET['iDisplayStart']) . ", " .
                mysql_real_escape_string($_GET['iDisplayLength']);
    }


    /*
     * Ordering
     */
    $sOrder = "";
    if (isset($_GET['iSortCol_0'])) {
        $sOrder = "ORDER BY  ";
        for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
            if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
                $sOrder .= "`" . $aColumns[intval($_GET['iSortCol_' . $i])] . "` " .
                        mysql_real_escape_string($_GET['sSortDir_' . $i]) . ", ";
            }
        }

        $sOrder = substr_replace($sOrder, "", -2);
        if ($sOrder == "ORDER BY") {
            $sOrder = "";
        }
    }


    /*
     * Filtering
     * NOTE this does not match the built-in DataTables filtering which does it
     * word by word on any field. It's possible to do here, but concerned about efficiency
     * on very large tables, and MySQL's regex functionality is very limited
     */
    $sWhere = "WHERE vacated='0' AND property_id like '$propid' ";  // account ID ADDED TO FILTER OUT RECORDS ACCORDING TO THE CLIENT ID OF THE USER LOGGED IN
    $oWhere = $sWhere;
    if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {
        $sWhere .= "AND ( ";
        for ($i = 0; $i < count($aColumns); $i++) {
            $sWhere .= "`" . $aColumns[$i] . "` LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' OR ";
        }
        $sWhere = substr_replace($sWhere, "", -3);
        $sWhere .= ')';
    }

    /* Individual column filtering */
    for ($i = 0; $i < count($aColumns); $i++) {
        if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '') {
            if ($sWhere == "") {
                $sWhere = "WHERE ";
            } else {
                $sWhere .= " AND ";
            }
            $sWhere .= "`" . $aColumns[$i] . "` LIKE '%" . mysql_real_escape_string($_GET['sSearch_' . $i]) . "%' ";
        }
    }


    /*
     * SQL queries
     * Get data to display
     */
    $sQuery = "
SELECT SQL_CALC_FOUND_ROWS `" . str_replace(" , ", " ", implode("`, `", $aColumns)) . "`
FROM   $sTable
$sWhere
ORDER BY apartmentid ASC 
$sLimit
";
    $rResult = $db->query($sQuery) or die(mysql_error());

    /* Data set length after filtering */
    $sQuery = "
SELECT FOUND_ROWS()
";
    $rResultFilterTotal = $db->query($sQuery) or die(mysql_error());
    $aResultFilterTotal = mysql_fetch_array($rResultFilterTotal);
    $iFilteredTotal = $aResultFilterTotal[0];

    /* Total data set length */
    $sQuery = "
SELECT COUNT(`" . $sIndexColumn . "`)
FROM   $sTable $oWhere
";
    $rResultTotal = $db->query($sQuery) or die(mysql_error());
    $aResultTotal = mysql_fetch_array($rResultTotal);
    $iTotal = $aResultTotal[0];


    /*
     * Output
     */
    $output = array(
        "iTotalRecords" => $iTotal,
        "iTotalDisplayRecords" => $iFilteredTotal,
        "aaData" => array()
    );

    while ($aRow = mysql_fetch_array($rResult)) {
        $row = array();
        for ($i = 0; $i < count($aColumns); $i++) {
            if ($aColumns[$i] == "version") {
                /* Special output formatting for 'version' column */
                $row[] = ($aRow[$aColumns[$i]] == "0") ? '-' : $aRow[$aColumns[$i]];
            } else if ($aColumns[$i] != ' ') {
                /* General output */
                $row[] = $aRow[$aColumns[$i]];
            }
        }
        $output['aaData'][] = $row;
    }

    echo json_encode($output);
    $db->close_connection();
}

function update_tenant_info() {
    $db = new MySQLDatabase();
    $db->open_connection();

    $editedValue = mysql_real_escape_string($_POST['value']);
    $id = mysql_real_escape_string($_POST['id']);
    $colID = mysql_real_escape_string($_POST['columnId']);
//$user_id= mysql_real_escape_string( $_SESSION['user_id']  );
    $tablename = 'tenants';

    $bal = mysql_real_escape_string($editedValue);

    if ($colID == 1) {
        $status = $db->query("update $tablename set tenant_name='$bal' where Id='$id'") or print "Database Error: " . $db->error();
        if ($status == 1) {
            echo "OK,Succesfuly updated name.";
        } else
            echo "Failed to update Name, try again";
    }
    elseif ($colID == 2) {

        $status = $db->query("update $tablename set tenantphone='$bal' where Id='$id'") or print "Database Error: " . $db->error();
        if ($status == 1) {
            echo "OK,Succesfuly updated Phone. ";
        } else
            echo "Failed to update Phone, try again";
    }
    elseif ($colID == 3) {

        $status = $db->query("update $tablename set tenantemail='$bal' where Id='$id'") or print "Database Error: " . $db->error();
        if ($status == 1) {
            echo "OK,Succesfuly updated email.";
        } else
            echo "Failed to update email, try again";
    }
    elseif ($colID == 4) {

        $status = $db->query("update $tablename set workplace='$bal' where Id='$id'") or print "Database Error: " . $db->error();
        if ($status == 1) {
            echo "OK,Succesfuly updated workplace.";
        } else
            echo "Failed to update workplace no, try again";
    }
    elseif ($colID == 5) {

        $status = $db->query("update $tablename set idno='$bal' where Id='$id'") or print "Database Error: " . $db->error();
        if ($status == 1) {
            echo "OK,Succesfuly updated Id.";
        } else
            echo "Failed to update Id no, try again";
    }
    elseif ($colID == 8) {

        $status = $db->query("update $tablename set fromdate='$bal' where Id='$id'") or print "Database Error: " . $db->error();
        if ($status == 1) {
            echo "OK,Succesfuly updated lease Start date.";
        } else
            echo "Failed to update , try again";
    }
    elseif ($colID == 9) {

        $status = $db->query("update $tablename set todate='$bal' where Id='$id'") or print "Database Error: " . $db->error();
        if ($status == 1) {
            echo "OK,Succesfuly updated Lease end date.";
        } else
            echo "Failed to update, try again";
    }
    elseif ($colID == 10) {

        $status = $db->query("update $tablename set duedate='$bal' where Id='$id'") or print "Database Error: " . $db->error();
        if ($status == 1) {
            echo "OK,Succesfuly updated Billing date.";
        } else
            echo "Failed to update, try again";
    }
elseif ($colID == 12) {

        $status = $db->query("update $tablename set kins_name='$bal' where Id='$id'") or print "Database Error: " . $db->error();
        if ($status == 1) {
            echo "OK,Succesfuly updated kin name.";
        } else
            echo "Failed to update, try again";
    }
	elseif ($colID == 13) {

        $status = $db->query("update $tablename set kinstel='$bal' where Id='$id'") or print "Database Error: " . $db->error();
        if ($status == 1) {
            echo "OK,Succesfuly updated kin telephone.";
        } else
            echo "Failed to update, try again";
    }
    else {
        echo "Server Error!";
    }

    $db->close_connection();
}

function vacate_apartment() {
    $db = new MySQLDatabase();
    $db->open_connection();
    $id = mysql_real_escape_string($_POST['id']);
    $date = date("d/m/y");
//$user_id= mysql_real_escape_string( $_SESSION['user_id']  );
    $tablename = 'tenants';
    $tablename2 = 'floorplan';
    $tablename3 = 'occupancy';
    $status = $db->query("update $tablename set vacated='1' where Id='$id'") or print "Database Error: " . $db->error();
    $status2 = $db->query("update $tablename2 set isoccupied='0',tenant_id='0' where tenant_id='$id' AND isoccupied='1'") or print "Database Error: " . $db->error(); //update floorplan to show empty house
    $status3 = $db->query("update $tablename3 set end_date='$date' where tenantId='$id'") or print "Database Error: " . $db->error();
    if ($status == 1 && $status2 == 1) {
        echo "OK,Succesfuly Vacated tenant.";
    } else
        echo "Failed to Vacated tenant, try again";

    $db->close_connection();
}

function vacant_apartments($propid) {
    $db = new MySQLDatabase();
    $db->open_connection();
    $sql = $db->query("SELECT apt_id,apt_tag FROM floorplan WHERE propertyid='$propid' AND isoccupied='0'") or die($db->error()); //vacant houses/apartments
    echo '<select id=\'aptname\' name=\'aptname\'  style="width:300px;">';
    echo '<option selected="selected" value="">---</option>';
    while ($row = mysql_fetch_array($sql)) {
        $aptid = $row['apt_id'];
        echo "<option value='$aptid' >" . htmlspecialchars($row['apt_tag']) . "</option>";
    }
    echo ' </select> ';
    $db->close_connection();
}

function vacant_apartments_list($propid) {
    $db = new MySQLDatabase();
    $db->open_connection();
    $sql = $db->query("SELECT apt_id,apt_tag,monthlyincome FROM floorplan WHERE propertyid='$propid' AND isoccupied='0'") or die($db->error()); //vacant houses/apartments

    while ($row = mysql_fetch_array($sql)) {
        $rows[] = $row;
    }
    return $rows;
    $db->close_connection();
}
function property_tenants($propid) {
    $db = new MySQLDatabase();
    $db->open_connection();
    $sql = $db->query("SELECT  Id,tenant_name FROM tenants WHERE  property_id='$propid' AND vacated='0'") or die($db->error()); //vacant houses/apartments
  //  echo '<select id=\'tenantname\' name=\'tenantname\'  style="width:300px;">';
    echo '<option selected="selected" value="">---</option>';
    while ($row = mysql_fetch_array($sql)) {
        $Id = $row['Id'];
        echo "<option value='$Id' >" . htmlspecialchars($row['tenant_name']) . "</option>";
    }
   // echo ' </select> ';
    $db->close_connection();
}
function addtenant($aptid, $aptname, $propertyid, $propertyname, $name, $phone, $email, $pin, $work, $idno, $photo, $leasestart, $leaseend, $leasedoc, $agentname, $physcaddress, $postaddress, $kinsname, $kinstel, $kinsemail, $regdate) {
    $db = new MySQLDatabase();
    $db->open_connection();
    $docpath = "../leasedoc/";
    $table1 = 'tenants';
    $table2 = 'occupancy';
    $leasestart = date('Y-m-d',strtotime($leasestart));
    $leaseend = date('Y-m-d',strtotime($leaseend));
    $photopath = '<img src="../images/tenantphotos/' . $photo . '" width="50" height="50"/>';
    if (isset($agentname)) {
        $queryresult = $db->query("SELECT agentid FROM agents WHERE agentname like '$agentname'"); //set agentid 
        while ($row = mysql_fetch_array($queryresult)) {
            $agentid = $row['agentid'];
        }
    }
    $sql = "INSERT INTO $table1 (apartmentid,Apartment_tag,property_id,property_name,tenant_name,tenantphone,tenantemail,tenantphoto,tenantpin,workplace,idno,fromdate,todate,leasedoc,agentid,physcaladdress,postaladdress,kins_name,kinstel,kinsemail,regdate)VALUES
('$aptid','$aptname','$propertyid','$propertyname','$name','$phone','$email','$photopath','$pin','$work','$idno','$leasestart','$leaseend','<a href=\"$docpath$leasedoc\" target=\"_blank\">lease Document</a>','$agentid','$physcaddress','$postaddress','$kinsname','$kinstel','$kinsemail','$regdate')";



    if (!$db->query($sql)) {
        die($db->error());
        return FALSE;
    } else {
        $lastid = mysql_insert_id();
        $sql2 = $db->query("INSERT INTO $table2 (tenantId,propertyid,apt_id,start_date,end_date,comments)VALUES
('$lastid','$propertyid','$aptid','$leasestart','0','comment to be added')");
        $status2 = $db->query("update floorplan set isoccupied='1',tenant_id='$lastid' where apt_id='$aptid'") or print "Database Error: " . $db->error();
        return "Tenant Added";
        $db->close_connection();
    }
}
function update_tenant($aptname, $propertyid, $propertyname, $name, $phone, $email, $pin, $work, $idno, $photo, $leasestart, $leaseend, $leasedoc, $agentname, $physcaddress, $postaddress, $kinsname, $kinstel, $kinsemail, $regdate,$bankcct) {
    $db = new MySQLDatabase();
    $db->open_connection();
    $docpath = "../leasedoc/";
    $table1 = 'tenants';
    $table2 = 'occupancy';
    $leasestart = date('Y-m-d',strtotime($leasestart));
    $leaseend = date('Y-m-d',strtotime($leaseend));
    $photopath = '<img src="../images/tenantphotos/' . $photo . '" width="50" height="50"/>';
    
    if (isset($agentname)) {
        $queryresult = $db->query("SELECT agentid FROM agents WHERE agentname like '$agentname'"); //set agentid 
        while ($row = mysql_fetch_array($queryresult)) {
            $agentid = $row['agentid'];
        }
    }
    $sql = "UPDATE $table1 SET  tenantphone = '$phone' ,tenantemail = '$email' ,tenantphoto = '$photopath',tenantpin ='$pin',workplace = '$work',idno ='$idno',
        fromdate = '$leasestart' ,todate = '$leaseend' ,leasedoc = '<a href=\"$docpath$leasedoc\" target=\"_blank\">lease Document</a>' ,agentid ='$agentid',physcaladdress = '$physcaddress' ,postaladdress = '$postaddress'
            ,kins_name ='$kinsname',kinstel = '$kinstel' ,kinsemail = '$kinsemail',regdate = '$regdate', bank_id='$bankcct' WHERE Id = '$name'
";
//die($sql);


    if (!$db->query($sql)) {
        die($db->error());
        return FALSE;
    } else {
                return "Tenant Updated";
        $db->close_connection();
    }
}


function getExpiredLeases() {
    $mysqli = getMysqliConnection();


    $sql = $mysqli->query("SELECT * from tenants  WHERE vacated='0' AND todate < CURDATE() AND  property_name NOT LIKE '%vacant%' order by property_name ASC ") or die($mysqli->error); //vacant houses/apartments

    while ($row = mysqli_fetch_array($sql)) {
        $rows[] = $row;
    }
    return $rows;
}

function get_agent_id_from_username($username) {
    include_once './includes/config.php';
    $mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME, 3306);
    $result = array();
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") ";
    }
    $res = $mysqli->query("SELECT agent_id FROM accesslevels WHERE username like '$username' ") or die($mysqli->error);
    while ($row = $res->fetch_assoc()) {
        $id = $row['agent_id'];
    }
    mysqli_close($mysqli);
    return @$id;
}
function user_group(){
    return $_SESSION['usergroup'];
}
function get_agent_property($agentid) {
    include './includes/config.php';
    $mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME, 3306);
    $result = array();
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") ";
    }
    if(user_group()=="4" || user_group()=="2"   ){
        $res = $mysqli->query("SELECT agentproperty.* FROM agentproperty JOIN properties on agentproperty.property_id=properties.propertyid WHERE  properties.active=1 ORDER BY agentproperty.propertyname ") or die($mysqli->error);
    
    }else{
        $res = $mysqli->query("SELECT agentproperty.* FROM agentproperty JOIN properties on agentproperty.property_id=properties.propertyid WHERE agentproperty.agent_id='$agentid' and properties.active=1 ORDER BY agentproperty.propertyname ") or die($mysqli->error);
  
    }
    while ($row = $res->fetch_assoc()) {
        array_push($result, $row['property_id'] . '#' . $row['propertyname'] . '#' . $row['commission']);
    }
    return array_unique($result);
    mysqli_close($mysqli);
}

function getallproperties($true, $parameter, $asc_desc, $user) {
    $db = new MySQLDatabase();
    $db->open_connection();
    date_default_timezone_set('Africa/Nairobi');
    $date = date("d/m/y");
    $time = date('h:i A');
    $tablename = 'properties';
    if ($parameter != "none") {
        $q = "SELECT * FROM $tablename ORDER BY $parameter $asc_desc";
    } else {
        $q = "SELECT * FROM $tablename ";
    }
    $query = $db->query($q) or die($db->error());

    echo '<table class="treport1" style="width:900px"><tr><td colspan="14">
<h3><center>PROPERTY LIST REPORT-' . $_SESSION['clientname'] . '</center></h3></td></tr>
<tr>
<th><center><u>Property Name</u></center></th>
<th><center><u>Plot No</u></center></th>
<th><center><u>Type</u></center></th>
<th><center><u>Address</u></center></th>
<th><center><u>Category</u></center></th>
<th><center><u>Status</u></center></th>
<th><center><u>Owner</u></center></th>
<th><center><u>Estate</u></center></th>
<th><center><u>Occupants</u></center></th>
<th><center><u>Condition</u></center></th>
<th><center><u>Area(acres)</u></center></th>
<th><center><u>Area(SqFt)</u></center></th>
<th><center><u>TitleDeed</u></center></th>
<th><center><u>Tenants</u></center></th>

</tr>';

    while ($row = mysql_fetch_array($query)) {
        $propertyid = $row['propertyid'];
        $resultname = str_replace("_", " ", $row['property_name']);
        $resultplotno = $row['plotno'];
        $resulttype = str_replace("_", " ", $row['property_type']);
        $resultaddress = ucwords(strtolower($row['address']));
        $category = $row['category'];
        $status = $row['status'];
        $owner = $row['owner'];
        $occupants = $row['occupants'];
        $resultmohalla = $row['mohalla'];

        $propcondition = $row['propcondition'];
        $resultacres = $row['area'];
        $resultareasq = $row['areasq'];
        $resultitle = $row['titledeed'];

//echo '<div id="valuerow">'.$array ["apartmentid"].'<span id="values"></span>'.$array ["apartmenttag"].'<span id="values"></span>'.$array ["propertyname"].'<span id="values"></span>'.str_repeat('&nbsp;',15).$array ["tenantname"].'</div><br/>';
        echo'<tr><td>' . $resultname . '</td><td>' . $resultplotno . '</td><td>' . $resulttype . '</td><td><center>' . $resultaddress . '</center></td><td>' . $category . '</td><td>' . $status . '</td><td>' . $owner . '</td><td>' . $resultmohalla . '</td><td>' . $occupants . '</td><td>' . $propcondition . '</td><td>' . $resultacres . '</td><td>' . $resultareasq . '</td><td>' . $resultitle . '</td><td><a href="defaultreports.php?report=tenantlist&propertyid=' . $propertyid . '&id=tenant_name&sort=DESC">Tenants</a></td></tr>';
    }
    echo '</tr></table>';
    echo '<hr/>';
    echo '<i>Printed by:</i> ' . $user . '&nbsp;&nbsp;&nbsp;&nbsp;' . date("d-m-y") . '&nbsp;' . $time;
    $db->close_connection();
}

//get a properties tenants
function getPropertyTenants($propertyid) {
    $db = new MySQLDatabase();
    $db->open_connection();
    date_default_timezone_set('Africa/Nairobi');
    $tablename = 'tenants';
    $alltenants = array();
    $q = "SELECT * FROM $tablename WHERE property_id like '$propertyid'";
    $resultset = $db->query($q) or die($db->error());
    while ($row = $db->fetch_array($resultset)) {
        $apartmentid = $row['apartmentid'];
        $apartmenttag = $row['Apartment_tag'];
        $propertyname = str_replace("_", " ", $row['property_name']);
        $tenantname = $row['tenant_name'];
        $tenantphone = $row['tenantphone'];
        $tenantemail = $row['tenantemail'];
        $tenantphoto = $row['tenantphoto'];
        $tenantpin = $row['tenantpin'];
        $id = $row['idno'];
        $tenantid = $row['Id'];
        $address = $row['postaladdress'];
        $kinsname = $row['kins_name'];
        $kinstel = $row['kinstel'];
        $leasestart = $row['fromdate'];
        $leaseend = $row['todate'];
        $array = array("tenantid" => $tenantid, "apartmentid" => $apartmentid, "apartmenttag" => $apartmenttag, "propertyname" => $propertyname, "tenantname" => $tenantname, "tenantphone" => $tenantphone, "tenantemail" => $tenantemail, "tenantphoto" => $tenantphoto, "tenantpin" => $tenantpin, "id" => $id, "leasestart" => $leasestart, "leaseend" => $leaseend);
        array_push($alltenants, $array);
        $db->close_connection();
        return $alltenants;
    }
}

//return all tenants
function getallSystemtenants($propertyid, $parameter, $asc_desc, $user = "") {
    $db = new MySQLDatabase();
    $db->open_connection();
    $alltenants = array();
    date_default_timezone_set('Africa/Nairobi');
    $date = date("d/m/y");
    $time = date('h:i A');
    $tablename = 'tenants';
    if ($propertyid != "all") {
        $q = "SELECT * FROM $tablename WHERE property_id like '$propertyid' AND vacated='0' ORDER BY $parameter $asc_desc";
    } else {
        $q = "SELECT * FROM $tablename WHERE vacated='0'  ORDER BY $parameter $asc_desc";
    }
    $query = $db->query($q) or die($db->error());
    while ($row = mysql_fetch_array($query)) {
        array_push($alltenants, $row);
    }
    $db->close_connection();
    return $alltenants;
}

function getalltenants($propertyid, $parameter, $asc_desc, $user, $options) {
    $db = new MySQLDatabase();
    $db->open_connection();
    date_default_timezone_set('Africa/Nairobi');
    $date = date("d/m/y");
    $time = date('h:i A');
    $tablename = 'tenants';
    if ($propertyid != "all") {

        $q = "SELECT * FROM $tablename WHERE property_id like '$propertyid' AND vacated='0' ORDER BY $parameter $asc_desc";
    } else {
        if ($options["tenant_name"] && $options["houseno"]) {
            $tenantname = $options["tenant_name"];
            $houseno = $options["houseno"];
            $q = "SELECT * FROM $tablename WHERE tenant_name like '$tenantname' AND Apartment_tag like '$houseno'  ORDER BY $parameter $asc_desc";
        } elseif ($options["tenant_name"] && $options["houseno"] == "") {
            $tenantname = $options["tenant_name"];

            $q = "SELECT * FROM $tablename WHERE tenant_name like '$tenantname'  ORDER BY $parameter $asc_desc";
        } else if ($options["houseno"] && $options["tenant_name"] == "") {

            $houseno = $options["houseno"];
            $q = "SELECT * FROM $tablename WHERE  Apartment_tag like '$houseno'  ORDER BY $parameter $asc_desc";
        } else {
            $q = "SELECT * FROM $tablename ORDER BY $parameter $asc_desc";
        }
    }
    $query = $db->query($q) or die($db->error());
    $db->close_connection();
    echo '<table class="treport1 exportlist" style="width:900px"><br>
<tr><td colspan="15">
<h3><center>TENANT LIST REPORT - ' . $_SESSION['clientname'] . '</center></h3></td></tr>
<tr>
<th><center><u>S/no</u></center></th>
<th><center><u>Apartment tag</u></center></th>
<th><center><u>Property Name</u></center></th>
<th><center>|</center></th>
<th><center><u>Tenant Photo</u></center></th>
<th><center><u>Tenant Name</u></center></th>
<th><center><u>Tenant Phone</u></center></th>
<th><center><u>Tenant Email</u></center></th>
<th><center><u>KRA Pin</u></center></th>
<th><center><u>ID No</u></center></th>
<th><center><u>Address</u></center></th>
<th><center><u>Kins Name</u></center></th>
<th><center><u>Kins Tel</u></center></th>
<th><center><u>Lease Starts</u></center></th>
<th><center><u>Lease Ends</u></center></th>

</tr>';

    while ($row = mysql_fetch_assoc($query)) {
        $apartmentid = $row['apartmentid'];
        $apartmenttag = $row['Apartment_tag'];
        $propertyname = str_replace("_", " ", $row['property_name']);
        $tenantname = $row['tenant_name'];
        $tenantphone = $row['tenantphone'];
        $tenantemail = $row['tenantemail'];
        $tenantphoto = $row['tenantphoto'];
        $tenantpin = $row['tenantpin'];
        $id = $row['idno'];
        $address = $row['postaladdress'];
        $kinsname = $row['kins_name'];
        $kinstel = $row['kinstel'];
        $leasestart = $row['fromdate'];
        $leaseend = $row['todate'];
        $array = array("apartmentid" => "$apartmentid", "apartmenttag" => "$apartmenttag", "propertyname" => "$propertyname", "tenantname" => "$tenantname", "tenantphone" => "$tenantphone", "tenantemail" => "$tenantemail", "tenantphoto" => "$tenantphoto", "tenantpin" => "$tenantpin", "id" => "$id", "leasestart" => "$leasestart", "leaseend" => "$leaseend");

//echo '<div id="valuerow">'.$array ["apartmentid"].'<span id="values"></span>'.$array ["apartmenttag"].'<span id="values"></span>'.$array ["propertyname"].'<span id="values"></span>'.str_repeat('&nbsp;',15).$array ["tenantname"].'</div><br/>';
        echo'<tr><td>' . $array ["apartmentid"] . '</td><td>' . $array ["apartmenttag"] . '</td><td>' . $array ["propertyname"] . '</td><td>|</td><td><center>' . $array ["tenantphoto"] . '</center></td><td>' . $array ["tenantname"] . '</td><td>' . $array ["tenantphone"] . '</td><td>' . $array ["tenantemail"] . '</td><td>' . $array ["tenantpin"] . '</td>' . '<td>' . $array ["id"] . '</td><td>' . $address . '</td><td>' . $kinsname . '</td><td>' . $kinstel . '</td><td>' . $array ["leasestart"] . '</td><td>' . $array ["leaseend"] . '</td></tr>';
    }
    echo '</table>';
    echo '<hr/>';
    echo '<i>Printed by:</i> ' . $user . '&nbsp;&nbsp;&nbsp;&nbsp;' . date("d-m-y") . '&nbsp;' . $time;
  //  echo '<button class="sexybutton sexymedium sexyyellow excel_tb" id="tbtoexcel"><span><span><span class="cancel">Export To Excel</span></span></span></button>';
	echo '<a href="#" id="tbtoexcel" class="export" style="float:right">Export Table data into Excel</a>  ';
}

function searchparameterstenant() {
    $db = new MySQLDatabase();
    $db->open_connection();

    $res = $db->query('select * from tenants') or die($db->error());

    $apartmentid = mysql_field_name($res, 1);
    $apartmenttag = mysql_field_name($res, 2);
    $tenantname = mysql_field_name($res, 5);
    $tenantpin = mysql_field_name($res, 9);
    $ejamaat = mysql_field_name($res, 10);
    $idno = mysql_field_name($res, 11);
    $leasestart = mysql_field_name($res, 12);
    $leaseend = mysql_field_name($res, 13);
    $vacated = mysql_field_name($res, 16);

    echo "<option value='$apartmentid' >" . $apartmentid . "</option>";
    echo "<option value='$apartmenttag' >" . $apartmenttag . "</option>";
    echo "<option value='$tenantname' >" . $tenantname . "</option>";
    echo "<option value='$tenantpin' >" . $tenantpin . "</option>";
    echo "<option value='$ejamaat' >" . $ejamaat . "</option>";
    echo "<option value='$idno' >" . $idno . "</option>";
    echo "<option value='$leasestart' >" . $leasestart . "</option>";
    echo "<option value='$leaseend' >" . $leaseend . "</option>";
    echo "<option value='$vacated' >" . $vacated . "</option>";


    $db->close_connection();
}

function searchparametersproperty() {
    $db = new MySQLDatabase();
    $db->open_connection();

    $res = $db->query('select * from properties') or die($db->error());
    $propertyid = mysql_field_name($res, 0);
    $propertyname = mysql_field_name($res, 1);
    $plotno = mysql_field_name($res, 2);
    $type = mysql_field_name($res, 3);
    $category = mysql_field_name($res, 6);
    $status = mysql_field_name($res, 8);
    $mohalla = mysql_field_name($res, 10);
    $occupants = mysql_field_name($res, 11);
    $condition = mysql_field_name($res, 12);
    $area = mysql_field_name($res, 13);
    echo "<option value='none'>" . 'NONE' . "</option>";
    echo "<option value='$propertyid' >" . $propertyid . "</option>";
    echo "<option value='$propertyname' >" . $propertyname . "</option>";
    echo "<option value='$plotno' >" . $plotno . "</option>";
    echo "<option value='$type' >" . $type . "</option>";
    echo "<option value='$category' >" . $category . "</option>";
    echo "<option value='$status' >" . $status . "</option>";
    echo "<option value='$mohalla' >" . $mohalla . "</option>";
    echo "<option value='$occupants' >" . $occupants . "</option>";
    echo "<option value='$condition' >" . $condition . "</option>";
    echo "<option value='$area' >" . $area . "</option>";



    $db->close_connection();
}

function relocatetenant($tenantid, $propid, $propname, $aptid, $apttag, $leasestart, $leaseend, $leasedoc) {
    $db = new MySQLDatabase();
    $db->open_connection();
    date_default_timezone_set('Africa/Nairobi');
    $date = date("d/m/y");
    $query0 = $db->query("select apt_id from floorplan where tenant_id='$tenantid' AND isoccupied='1'") or print "Database Error: " . $db->error();
    while ($row = mysql_fetch_array($query0)) {
        $lastaptid = $row['apt_id'];
    }

    $db->query("UPDATE occupancy  set end_date='$date' WHERE apt_id='$lastaptid'") or die($db->error());
    $db->query("update floorplan set isoccupied='0',tenant_id='0' WHERE apt_id='$lastaptid'") or print "Database Error: " . $db->error();

    $db->query("INSERT INTO occupancy (tenantId,propertyid,apt_id,start_date,end_date,comments)VALUES
('$tenantid','$propid','$aptid','$leasestart','0','comment to be added')") or die($db->error());

    $db->query("update floorplan set isoccupied='1',tenant_id='$tenantid' WHERE apt_id='$aptid'") or print "Database Error: " . $db->error();
   
    $db->query("update tenants set apartmentid='$aptid',Apartment_tag='$apttag',property_id='$propid',property_name='$propname',fromdate='$leasestart',todate='$leaseend',leasedoc='$leasedoc' where Id='$tenantid'") or print "Database Error: " . $db->error();

    echo 'sucess';
    $db->close_connection();
}

//agent registration
function registeragent($agentname, $agentpassword, $agentphone, $group, $agentaddress) {
    $db = new MySQLDatabase();
    $db->open_connection();
    $tablename = "agents";
    $query = "INSERT into $tablename (`agentname`,`contacts`,`address`) VALUES ('$agentname','$agentphone','$agentaddress') ";
    if (!$db->query($query)) {
        return false;
    } else {
        $lastid = mysql_insert_id();
        $db->query("INSERT into accesslevels (`agent_id`,`username`,`password`,`group`,`status`) VALUES ('$lastid','$agentname','$agentpassword','$group','ACTIVE')") or die($db->error());
        return true;
    }

    $db->close_connection();
}

function registeruser($username, $usergroup, $password) {
    $db = new MySQLDatabase();
    $db->open_connection();
    $tablename = "accesslevels";
    $query = "INSERT into $tablename(`username`,`password`,`group`,`status`) VALUES ('$username','$password','$usergroup','ACTIVE') ";
    if (!$db->query($query)) {
        echo $db->error();
    } else {
        echo 'success';
    }

    $db->close_connection();
}

function assignproperty($agentid, $propid, $propname, $commission = 0) {
    $db = new MySQLDatabase();
    $db->open_connection();
    $tablename = "agentproperty";
    $result = $db->query("SELECT agent_id FROM $tablename WHERE agent_id='$agentid' AND property_id='$propid'") or die($db->error());
    if (mysql_numrows($result) > 0) {
        $query = $db->query("SELECT agentname from agents WHERE agentid='$agentid'") or die($db->error());
        while ($row = mysql_fetch_array($query)) {
            $agent_name = $row['agentname'];
        }
        header('Content-Type: application/json');
        $response_array['status'] = 'Property already assigned to ' . $agent_name;
        echo json_encode($response_array);
    } else {
        $query1 = "INSERT into $tablename(`agent_id`,`property_id`,`propertyname`,`commission`) VALUES ('$agentid','$propid','$propname','$commission') ";
        if (!$db->query($query1)) {
            die($db->error());
        } else {
            header('Content-Type: application/json');
            $response_array['status'] = 'Successfully assigned <u>' . $propname . '</u> to agent';
            echo json_encode($response_array);
        }
    }
    $db->close_connection();
}

//assign all properties to an agent
function assignProperties($agentid) {
    $db = new MySQLDatabase();
    $db->open_connection();
    $tablename = "agentproperty";
    $commission = 0;
    $properties = getProperties();
    foreach ($properties as $property) {
        $propid = $property['property_id'];
        $propname = $property['property_name'];
        $result = $db->query("SELECT agent_id FROM $tablename WHERE agent_id='$agentid' AND property_id='$propid'") or die($db->error());
        //if existing update
        if (mysql_numrows($result) > 0) {
            $query = $db->query("UPDATE $tablename SET agent_id='$agentid' WHERE property_id='$propid' AND agent_id='$agentid'") or die($db->error());
        }
        //else insert
        else {
            $query1 = "INSERT into $tablename(`agent_id`,`property_id`,`propertyname`,`commission`) VALUES ('$agentid','$propid','$propname','$commission') ";
            if (!$db->query($query1)) {
                die($db->error());
            }
        }
    }

    header('Content-Type: application/json');
    $response_array['status'] = 'Successfully assigned <u>Properties</u> to agent';
    echo json_encode($response_array);
    $db->close_connection();
}

function populateallagents() {
    include 'includes/config.php';
    $mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME, 3306);
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") ";
    }
    $res = $mysqli->query("SELECT agent_id,username FROM accesslevels ") or die($mysqli->error);

    while ($row = $res->fetch_assoc()) {
        $resagentid = $row['agent_id'];
        echo "<option value='$resagentid' >" . htmlspecialchars($row['username']) . "</option>";
    }
    mysqli_close($mysqli);
}

//get property using id
function findpropertybyid($id) {
    include_once '../includes/config.php';
    $mysqli = getMysqliConnection();
    $res = $mysqli->query("SELECT property_name FROM properties WHERE propertyid like '$id' ") or die($mysqli->error);

    while ($row = $res->fetch_assoc()) {
        $pname = $row['property_name'];
    }if (@!$pname) {
        $pname = 'Office';
    }
    return $pname;
}

//fetchall properties
function getProperties() {
    $mysqli = getMysqliConnection();
    $propertiestable = getPropertiesTable();
    $allpropertydetails = array();
    $propdetails = array();
    $res = $mysqli->query("SELECT propertyid,property_name,has_vat FROM {$propertiestable} WHERE active=1 ORDER BY property_name ASC");
    while ($row = $res->fetch_assoc()) {
        $propdetails['property_id'] = $row['propertyid'];
        $propdetails['property_name'] = $row['property_name'];
        $propdetails['owner'] = $row['owner'];
        $propdetails['has_vat'] = $row['has_vat'];
        array_push($allpropertydetails, $propdetails);
    }
    return $allpropertydetails;
}

function getallagentProperties($agentid) {
    $mysqli = getMysqliConnection();
    $propertiestable = getPropertiesTable();
    $allagentpropertydetails = array();
    $propdetails = array();
    $res = $mysqli->query("SELECT propertyid,property_name,has_vat FROM {$propertiestable} JOIN agentproperty ON agentproperty.property_id = $propertiestable.propertyid WHERE active=1 AND agentproperty.agent_id = '$agentid' ORDER BY property_name ASC");
    while ($row = $res->fetch_assoc()) {
        $propdetails['property_id'] = $row['propertyid'];
        $propdetails['property_name'] = $row['property_name'];
        $propdetails['owner'] = $row['owner'];
        $propdetails['has_vat'] = $row['has_vat'];
        array_push($allagentpropertydetails, $propdetails);
    }
    return $allagentpropertydetails;
}
function findtenantbyid($id) {
    include_once '../includes/config.php';
    $mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME, 3306);
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") ";
    }
    $res = $mysqli->query("SELECT tenant_name FROM tenants WHERE Id='$id' ") or die($mysqli->error);

    while ($row = $res->fetch_assoc()) {
        $name = $row['tenant_name'];
    }
    return @$name;
    mysqli_close($mysqli);
}
// function findtenantaptbyid($id) {
//     include_once '../includes/config.php';
//     $mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME, 3306);
//     if ($mysqli->connect_errno) {
//         echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") ";
//     }
//     $res = $mysqli->query("SELECT tenant_name FROM tenants WHERE Id='$id' ") or die($mysqli->error);

//     while ($row = $res->fetch_assoc()) {
//         $name = $row['tenant_name'];
//     }
//     return @$name;
//     mysqli_close($mysqli);
// }

function findtenantbyapt($aptid) {
    include_once '../includes/config.php';
    $mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME, 3306);
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") ";
    }
    $res = $mysqli->query("SELECT tenant_name FROM tenants WHERE apartmentid='$aptid' AND vacated=0") or die($mysqli->error);

    while ($row = $res->fetch_assoc()) {
        $name = $row['tenant_name'];
    }
    return @$name;
    mysqli_close($mysqli);
}

//al tenant details from apt_id
function findtenantDetailsbyapt($aptid) {
    include_once '../includes/config.php';
    $mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME, 3306);
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") ";
    }
    $res = $mysqli->query("SELECT * FROM tenants WHERE apartmentid='$aptid' AND vacated=0") or die($mysqli->error);

    while ($row = $res->fetch_assoc()) {
        $details = $row;
    }
    return @$details;
    mysqli_close($mysqli);
}

function findtenantbypropertyid($propid) {
    $db = new MySQLDatabase();
    $db->open_connection();
    $tablename = "tenants";
    $tablename1 = "floorplan";
//update tenants based on floorplan details
    $floorplan = $db->query("SELECT $tablename1.apt_id,apt_tag from $tablename1 where propertyid='$propid' and tenant_id=0 ") or die($db->error());
    while ($roww = mysql_fetch_array($floorplan)) {
        $aptid = $roww['apt_id'];
        $apttag = $roww['apt_tag'];
        $db->query("UPDATE $tablename SET apartmentid='$aptid' WHERE Apartment_tag='$apttag' and property_id='$propid' and vacated=0  "); //update tenants
    }
//die("SELECT $tablename.id,$tablename.idno,$tablename.tenant_name,$tablename1.apt_tag,$tablename.apartmentid,$tablename1.current_water_reading,$tablename1.current_water_reading,$tablename1.monthlyincome FROM $tablename LEFT JOIN $tablename1 ON $tablename.apartmentid=$tablename1.apt_id WHERE $tablename.property_id like '$propid' AND $tablename.vacated='0' ORDER BY $tablename1.apt_id ASC ");
    $sql = $db->query("SELECT $tablename.id,$tablename.idno,$tablename.tenant_name,$tablename1.apt_tag,$tablename.apartmentid,$tablename1.current_water_reading,$tablename1.current_water_reading,$tablename1.monthlyincome FROM $tablename LEFT JOIN $tablename1 ON $tablename.apartmentid=$tablename1.apt_id WHERE $tablename.property_id like '$propid' AND $tablename.vacated='0' ORDER BY $tablename1.apt_id ASC ") or die($db->error());

    while ($row = mysql_fetch_array($sql)) {
        $tenantid = $row['id'];
        $houseno = $row['apt_tag'];
        $monthrent = $row['monthlyincome'];
        $unitsused = $row['current_water_reading'];
        $aptid = $row['apartmentid'];
        echo "<option value='$tenantid' id='" . $row['monthlyincome'] . "' title='$unitsused'class='$aptid' >" . htmlspecialchars($row['tenant_name']) . '&nbsp;&nbsp[' . $houseno . ']&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Rent-Ksh ' . number_format($monthrent) . "/-</option>";
    }
    $db->close_connection();
}

//unit functions
function getunithistory($propertyid, $aptid, $sort) {
//die("SELECT tenants.tenant_name,tenants.tenantphone,tenants.tenantemail,tenants.tenantphoto,tenants.idno,tenants.fromdate,tenants.todate,floorplan.property_name,floorplan.apt_tag,floorplan.monthlyincome,floorplan.yearlyincome FROM tenants LEFT JOIN occupancy ON occupancy.tenantId = tenants.Id  LEFT JOIN floorplan ON tenants.apartmentid=floorplan.apt_id WHERE occupancy.apt_id='$aptid' ORDER BY tenants.fromdate $sort");
    $res = mysqli_connect_db()->query("SELECT tenants.tenant_name,tenants.tenantphone,tenants.tenantemail,tenants.tenantphoto,tenants.idno,IF(tenants.fromdate='0000-00-00',occupancy.start_date,tenants.fromdate)as fromdate,IF(tenants.todate ='0000-00-00',occupancy.end_date,tenants.todate) as todate,floorplan.property_name,floorplan.apt_tag,floorplan.monthlyincome,floorplan.yearlyincome FROM tenants LEFT JOIN occupancy ON occupancy.tenantId = tenants.Id  LEFT JOIN floorplan ON tenants.apartmentid=floorplan.apt_id WHERE occupancy.apt_id='$aptid' ORDER BY tenants.fromdate $sort") or die(mysqli_connect_db()->error);
    
    echo '<table class="treport" >
<tr><td colspan="11"><center><span style="font-size:16px;font-weight:normal;float:left;"> <b>UNIT HISTORY REPORT</b></span><span style="font-size:18px;font-weight:bold">' . $_SESSION['clientname'] . '</span><span style="font-size:18px;font-weight:normal; float:right;">' . findpropertybyid($propertyid) . '</span><center>
<br/><span style="font-size:16px;font-weight:normal;"><center>' . str_repeat('&nbsp;', 25) . 'Unit history as of <b> ' . @date('d-m-Y') . '</b></center></span></td></tr>';
    echo '<tr>
<th><center><u>Property Name</u></center></th>
<th><center><u>Apartment/room tag</u></center></th>
<th><center><u>Monthly Income</u></center></th>
<th><center><u>Yearly Income</u></center></th>
<th><center><u>Photo</center></u></center></th>
<th><center><u>Tenant Name</u></center></th>
<th><center><u>Phone No</u></center></th>
<th><center><u>Email</center></u></th>
<th><center><u>ID NO</center></u></th>
<th><center><u>lease start</center></u></th>
<th><center><u>lease end</u></center></th>
</tr>';
    while ($row = $res->fetch_assoc()) {
        echo '<tr><td>' . $row['property_name'] . '</td>' . '<td>' . $row['apt_tag'] . '</td>' . '<td>' . $row['monthlyincome'] . '</td>' . '<td>' . $row['yearlyincome'] . '</td>' . '<td>' . $row['tenantphoto'] . '</td>' . '<td>' . $row['tenant_name'] . '</td>' . '<td>' . $row['tenantphone'] . '</td>' . '<td>' . $row['tenantemail'] . '</td>' . '<td>' . $row['idno'] . '</td>' . '<td>' . $row['fromdate'] . '</td>' . '<td>' . $row['todate'] . '</td></tr>';
    }
    echo '</table>';
    mysqli_close(mysqli_connect_db());
}

//mysqli connect
function mysqli_connect_db() {
    include_once '../includes/config.php';
    $mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME, 3306);
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") ";
    }
    return $mysqli;
}

/* * *accounting processess 
 * functions here
 * 
 * 
 * 
 * *** */

function getincomeaccount($propertyid) {
    $db = new MySQLDatabase();
    $db->open_connection();
    $sql = $db->query("SELECT id,accname FROM chargeitems WHERE propertyid='$propertyid'") or die($db->error());
    while ($row = mysql_fetch_array($sql)) {
        $id = $row['id'];
        echo "<option value='$id' >" . htmlspecialchars($row['accname']) . "</option>";
    }
    $db->close_connection();
}

function set_charge_items($propertyid) {
    $db = new MySQLDatabase();
    $db->open_connection();
    $chargeitems = array();
    $total12 = 0;
    $sql = $db->query("SELECT id,accname,amount FROM chargeitems WHERE propertyid='$propertyid'") or die($db->error());
    echo '<table>';
    while ($row = mysql_fetch_array($sql)) {
        $id = $row['id'];
        $amount = $row['amount'];
        if ($amount) { //if charge item is associated with an amount
            echo '<tr><td><img src="../images/cursors/available.png"></td><td>' . htmlspecialchars($row['accname']) . '</td><td><input type="text" id="chargeitem' . $id . '" class="chargeitem" value="' . $amount . '" title="' . $row['accname'] . '"/></td></tr>';
         $total12 = $total12 + $row['amount'];
            
        } else {
            echo '<tr><td><img src="../images/cursors/available.png"></td><td>' . htmlspecialchars($row['accname']) . '</td><td><input type="text" id="chargeitem' . $id . '" class="chargeitem" title="' . $row['accname'] . '"/></td></tr>';
        }
       
        array_push($chargeitems, $id . '&');
    } $chargeitems_string = implode($chargeitems);
    echo '<input type="hidden" id="chargeitemsarray" value="' . $chargeitems_string . '"/>';
    echo '</table>';
    //echo'<tr><td><label for="amount">Amount </label></td>
//<td><input id="amount" type="text" name="amount" value="'.$total12.'"  style="width:350px;">
//</td></tr>';
    $db->close_connection();
}

//get account type by id
function getAccountType($id) {
    $accountstable = getAccountTypesTable();
    $mysqli = getMysqliConnection();
    $accountdetails = array();
    $resultset = $mysqli->query("SELECT * FROM `$accountstable` WHERE `idacct_types`=$id") or die($mysqli->error);
    while ($row = $resultset->fetch_assoc()) {
        $accountdetails['desc'] = $row['desc'];
        $accountdetails['code'] = $row['Code'];
    }
    return $accountdetails;
}

//get account types
function getAccountTypes() {
    $accounttypestable = getAccountTypesTable();
    $mysqli = getMysqliConnection();
    $accountdetails = array();
    $alltypes = array();
    $resultset = $mysqli->query("SELECT * FROM `$accounttypestable`") or die($mysqli->error);
    while ($row = $resultset->fetch_assoc()) {
        $accountdetails['id'] = $row['idacct_types'];
        $accountdetails['desc'] = $row['desc'];
        $accountdetails['code'] = $row['Code'];
        array_push($alltypes, $accountdetails);
    }
    return $alltypes;
}

//account categories
function getAccountCategories() {
    $accountcategories = getAccountCategoriesTable();
    $mysqli = getMysqliConnection();
    $accountdetails = array();
    $alltypes = array();
    $resultset = $mysqli->query("SELECT * FROM `$accountcategories`") or die($mysqli->error);
    while ($row = $resultset->fetch_assoc()) {
        $accountdetails['id'] = $row['idaccounttype_categories'];
        $accountdetails['code'] = $row['code'];
        $accountdetails['alias'] = $row['alias'];
        $accountdetails['is_bank'] = $row['is_bank'];
        array_push($alltypes, $accountdetails);
    }
    return $alltypes;
}

function getAccountCategory($id) {

    $mysqli = getMysqliConnection();
    $accountdetails = array();
    $accountstable = getAccountCategoriesTable();
    $res = $mysqli->query("SELECT * FROM {$accountstable} WHERE `idaccounttype_categories`='$id'") or die($mysqli->error);

    while ($row = $res->fetch_assoc()) {
        array_push($accountdetails, $row);
    }
    $mysqli->close();
    return $accountdetails;
}

function getbankaccount() {
    $db = new MySQLDatabase();
    $db->open_connection();
    $sql = $db->query("SELECT acno,acname FROM bkaccounts WHERE idacct_types like '1'") or die($db->error());
    while ($row = mysql_fetch_array($sql)) {
        $id = $row['acno'];
        echo "<option value='$id' >" . htmlspecialchars($row['acname']) . "</option>";
    }
    $db->close_connection();
}

function getexpenseaccount() {
    $db = new MySQLDatabase();
    $db->open_connection();
    $sql = $db->query("SELECT acno,acname FROM bkaccounts WHERE idacct_types=2") or die($db->error());
    while ($row = mysql_fetch_array($sql)) {
        $id = $row['acno'];
        echo "<option value='$id' >" . htmlspecialchars($row['acname']) . "</option>";
    }
    $db->close_connection();
}

function getcashaccount() {
    $db = new MySQLDatabase();
    $db->open_connection();
    $sql = $db->query("SELECT acno,acname FROM bkaccounts WHERE idacct_types=1") or die($db->error());
    while ($row = mysql_fetch_array($sql)) {
        $id = $row['acno'];
        echo "<option value='$id' >" . htmlspecialchars($row['acname']) . "</option>";
    }
    $db->close_connection();
}

function getpaymentmode() {
    $db = new MySQLDatabase();
    $db->open_connection();
    $sql = $db->query("SELECT id,paymode FROM paymodes") or die($db->error());
    while ($row = mysql_fetch_array($sql)) {
        $id = $row['id'];
        echo "<option value='$id' >" . htmlspecialchars($row['paymode']) . "</option>";
    }
    $db->close_connection();
}

//create invoice
//chargenames:array,charges:array
function create_invoice($id, $entrydate, $incomeacct, $amount, $billing, $user, $propid, $remarks, $chargenames, $charges, $counter, $currentreading, $aptid, $fperiod, $items,$invoicebbf) {
   
   
   //print_r($_REQUEST);
    $db = new MySQLDatabase();
    $db->open_connection();
    $tablename = "invoices";
    $tablename2 = 'invoiceitems';
    // $entrydate=date('Y-m-d',$entrydate);
    $myDateTime = DateTime::createFromFormat('d/m/Y', trim($entrydate));
    $entrydate = $myDateTime->format('Y-m-d');
   $currentdate = date('Y-m-d h:i:s');
    if ($billing == "0") {
        $result2 = incrementnumber("invno");
    } else {
        $result2 = incrementnumber("invno");
        // $result2 = incrementnumber("credno");
    }
    //invoice amount is now gotten from sum of charges
    $invoiceamount = array_sum($charges);
    
    $query = "INSERT into $tablename(`invoiceno`,`invoicedate`,`amount`,`idno`,`incomeaccount`,`us`,`invoicecredit`,`property_id`,`remarks`,`idclose_periods`,`ts`,`bbf`) VALUES ('$result2','$entrydate','$invoiceamount','$id','$incomeacct','$user','$billing','$propid','$remarks','$fperiod','$currentdate','$invoicebbf') ";
    $resultquery = $db->query("SELECT current_water_reading FROM floorplan WHERE apt_id='$aptid'") or die($db->error());
    while ($row = mysql_fetch_array($resultquery)) {
        $lastreading = $row['current_water_reading'];
    }
    $queryresult = $db->query($query) or die($db->error());
    if (!$queryresult) {
        die("could not create invoice");
    } else {
        //loop through chargeable items and separate based on characteristics
//        $commissionnotcharged=0;
//        $chargeitems=  explode(",",$items);
//        foreach ($chargeitems as $value) {
//            $item=getChargeItem($value);
//            //if item is n0t charge commission add to total
//          if($item['charged_commission']==0){
//              
//          }
//           // print_r($item);
//        }
        //if theres a water reading
        if ($currentreading) {
            $db->query("UPDATE floorplan SET last_water_reading='$lastreading',current_water_reading='$currentreading' WHERE apt_id='$aptid' ") or die($db->error());
        }
        $priority = 'Z';
        $commissionnotcharged = 0;
        //die();
        for ($i = 0; $i <= ($counter - 1); $i++) {
            $chargename = $chargenames[$i];
            $priority = setChargeItemPriority($chargename);
            $chargeitemdetails = getChargeItemByName($chargename, $propid);
            //if the charge item doesnt attract commission,reduce the credit amount below by the commission previously charged on the item
            if ($chargeitemdetails['charged_commission'] == 0) {
                $commissionnotcharged = $commissionnotcharged + $charges[$i];
            }
            if ($chargeitemdetails['is_deposit'] == 1) {
                //create an invoice and return
                header('Content-Type: application/json');
                $response_array['status'] = 'Invoice/Credit Note ' . $result2 . ' created!';
                $response_array['invoiceno'] = $result2;
                $query1 = "INSERT into $tablename2 (`invoiceno`,`item_name`,`amount`,`priority`) VALUES ('$result2','$chargename','$charges[$i]','$priority')";
                $db->query($query1) or die($db->error());
                echo json_encode($response_array);
                return;
            }


            $query1 = "INSERT into $tablename2 (`invoiceno`,`item_name`,`amount`,`priority`) VALUES ('$result2','$chargename','$charges[$i]','$priority')";
            $db->query($query1) or die($db->error());
        }
        //empty counter,charge items,charges
        unset($counter);
        unset($chargenames);
        unset($charges);

        //get relevant ledger accounts
        // debit entry for apartment gl
        $glaccount = getGLCodeForAccount(array('gl' => 'HouseGL', 'apt_id' => $aptid));
        $glcode = $glaccount['glcode'];
        $entry = createJournalEntry(array('glcode' => $glcode, 'document_ref' => $result2, 'debit' => $invoiceamount, 'ttype' => 'INV', 'property_id' => $propid, 'desc' => $remarks, 'idclose_period' => $fperiod));

//credit commission income acct for agent 
        $glaccount1 = getGLCodeForAccount(array('gl' => 'Commissions', 'property_id' => $propid));
        $glcode1 = $glaccount1['glcode'];
        $commission = getPropertyCommissionRate($propid);
        $creditamount = ((($commission * $invoiceamount) / 100) - (($commission * $commissionnotcharged) / 100));
        $commissionentry = createJournalEntry(array('glcode' => $glcode1, 'document_ref' => $result2, 'credit' => round($creditamount, 2), 'ttype' => 'INV', 'property_id' => $propid, 'desc' => $remarks, 'idclose_period' => $fperiod));
        //credit entry for landlord account on agent side
        $glaccountal = getGLCodeForAccount(array('gl' => 'AgentLandlord', 'property_id' => $propid));
        $glcode1 = $glaccountal['glcode'];
        $entry = createJournalEntry(array('glcode' => $glcode1, 'document_ref' => $result2, 'credit' => ($invoiceamount - $creditamount), 'ttype' => 'INV', 'property_id' => $propid, 'desc' => $remarks, 'idclose_period' => $fperiod));
//create debit entry for Agent on Landlord Side
        $landlordagent = getGLCodeForAccount(array('gl' => 'LandlordAgent', 'property_id' => $propid));
        $landlordagentgl = $landlordagent['glcode'];
        $entry = createJournalEntry(array('glcode' => $landlordagentgl, 'document_ref' => $result2, 'debit' => ($invoiceamount - $creditamount), 'ttype' => 'INV', 'property_id' => $propid, 'desc' => $remarks, 'idclose_period' => $fperiod));

        //create credit entry for rent on  landlord (whole amount)
        $landlordrent = getGLCodeForAccount(array('gl' => 'LandlordRent', 'property_id' => $propid));
        $landlordrentgl = $landlordrent['glcode'];
        $entry1 = createJournalEntry(array('glcode' => $landlordrentgl, 'document_ref' => $result2, 'credit' => $invoiceamount, 'ttype' => 'INV', 'property_id' => $propid, 'desc' => $remarks, 'idclose_period' => $fperiod));

        //create debit entry for commission account on landlord side (expense)
        $landlordcomm = getGLCodeForAccount(array('gl' => 'LandlordCommission', 'property_id' => $propid));
        $landlordrentgl = $landlordcomm['glcode'];
        $entry1 = createJournalEntry(array('glcode' => $landlordrentgl, 'document_ref' => $result2, 'debit' => $creditamount, 'ttype' => 'INV', 'property_id' => $propid, 'desc' => $remarks, 'idclose_period' => $fperiod));

        header('Content-Type: application/json');
        $response_array['status'] = 'Invoice/Credit Note ' . $result2 . ' created!';
        $response_array['invoiceno'] = $result2;
        echo json_encode($response_array);
    }

    $db->close_connection();
}

function create_crdtnote($id, $entrydate, $incomeacct, $amount, $billing, $user, $propid, $remarks, $chargenames, $charges, $counter, $currentreading, $aptid, $fperiod, $items,$crdtinvce) {
    $db = new MySQLDatabase();
    $db->open_connection();
    $tablename = "invoices";
    $tablename2 = 'invoiceitems';
    // $entrydate=date('Y-m-d',$entrydate);
    $myDateTime = DateTime::createFromFormat('d/m/Y', trim($entrydate));
    $entrydate = $myDateTime->format('Y-m-d');
    $currentdate = date('Y-m-d h:i:s');
    
    if ($billing == "0") {
        $result2 = incrementnumber("invno");
    } else {
        $result2 = incrementnumber("invno");
        // $result2 = incrementnumber("credno");
    }
    //invoice amount is now gotten from sum of charges
    $invoiceamount = array_sum($charges);
    $query = "INSERT into $tablename(`invoiceno`,`invoicedate`,`amount`,paidamount,`idno`,`incomeaccount`,`us`,`invoicecredit`,`property_id`,`remarks`,`idclose_periods`,`ts`) VALUES ('$result2','$entrydate','$invoiceamount','$invoiceamount','$id','$incomeacct','$user','$billing','$propid','$remarks','$fperiod','$currentdate') ";

    $resultquery = $db->query("SELECT current_water_reading FROM floorplan WHERE apt_id='$aptid'") or die($db->error());
    while ($row = mysql_fetch_array($resultquery)) {
        $lastreading = $row['current_water_reading'];
    }
    $queryresult = $db->query($query) or die($db->error());
    if (!$queryresult) {
        die("could not create Credit Note");
    } else {
        $db->query("UPDATE $tablename SET paidamount = paidamount + $invoiceamount WHERE invoiceno ='$crdtinvce' ");
        //loop through chargeable items and separate based on characteristics
//        $commissionnotcharged=0;
//        $chargeitems=  explode(",",$items);
//        foreach ($chargeitems as $value) {
//            $item=getChargeItem($value);
//            //if item is n0t charge commission add to total
//          if($item['charged_commission']==0){
//              
//          }
//           // print_r($item);
//        }
        //if theres a water reading
        if ($currentreading) {
           // $db->query("UPDATE floorplan SET last_water_reading='$lastreading',current_water_reading='$currentreading' WHERE apt_id='$aptid' ") or die($db->error());
        }
        $priority = 'Z';
        $commissionnotcharged = 0;
        //die();
        for ($i = 0; $i <= ($counter - 1); $i++) {
            $chargename = $chargenames[$i];
            $priority = setChargeItemPriority($chargename);
            $chargeitemdetails = getChargeItemByName($chargename, $propid);
            //if the charge item doesnt attract commission,reduce the credit amount below by the commission previously charged on the item
            if ($chargeitemdetails['charged_commission'] == 0) {
                $commissionnotcharged = $commissionnotcharged + $charges[$i];
            }
            if ($chargeitemdetails['is_deposit'] == 1) {
                //create an invoice and return
                header('Content-Type: application/json');
                $response_array['status'] = 'Invoice/Credit Note ' . $result2 . ' created!';
                $response_array['invoiceno'] = $result2;
                $query1 = "INSERT into $tablename2 (`invoiceno`,`item_name`,`amount`,`priority`) VALUES ('$result2','$chargename','$charges[$i]','$priority')";
                $db->query($query1) or die($db->error());
                echo json_encode($response_array);
                return;
            }


            $query1 = "INSERT into $tablename2 (`invoiceno`,`item_name`,`amount`,`priority`) VALUES ('$result2','$chargename','$charges[$i]','$priority')";
            $db->query($query1) or die($db->error());
        }
        //empty counter,charge items,charges
        unset($counter);
        unset($chargenames);
        unset($charges);

        //get relevant ledger accounts
        // debit entry for apartment gl
        $glaccount = getGLCodeForAccount(array('gl' => 'HouseGL', 'apt_id' => $aptid));
        $glcode = $glaccount['glcode'];
        $entry = createJournalEntry(array('glcode' => $glcode, 'document_ref' => $result2, 'debit' => $invoiceamount, 'ttype' => 'INV', 'property_id' => $propid, 'desc' => $remarks, 'idclose_period' => $fperiod));

//credit commission income acct for agent 
        $glaccount1 = getGLCodeForAccount(array('gl' => 'Commissions', 'property_id' => $propid));
        $glcode1 = $glaccount1['glcode'];
        $commission = getPropertyCommissionRate($propid);
        $creditamount = ((($commission * $invoiceamount) / 100) - (($commission * $commissionnotcharged) / 100));
        $commissionentry = createJournalEntry(array('glcode' => $glcode1, 'document_ref' => $result2, 'credit' => round($creditamount, 2), 'ttype' => 'INV', 'property_id' => $propid, 'desc' => $remarks, 'idclose_period' => $fperiod));
        //credit entry for landlord account on agent side
        $glaccountal = getGLCodeForAccount(array('gl' => 'AgentLandlord', 'property_id' => $propid));
        $glcode1 = $glaccountal['glcode'];
        $entry = createJournalEntry(array('glcode' => $glcode1, 'document_ref' => $result2, 'credit' => ($invoiceamount - $creditamount), 'ttype' => 'INV', 'property_id' => $propid, 'desc' => $remarks, 'idclose_period' => $fperiod));
//create debit entry for Agent on Landlord Side
        $landlordagent = getGLCodeForAccount(array('gl' => 'LandlordAgent', 'property_id' => $propid));
        $landlordagentgl = $landlordagent['glcode'];
        $entry = createJournalEntry(array('glcode' => $landlordagentgl, 'document_ref' => $result2, 'debit' => ($invoiceamount - $creditamount), 'ttype' => 'INV', 'property_id' => $propid, 'desc' => $remarks, 'idclose_period' => $fperiod));

        //create credit entry for rent on  landlord (whole amount)
        $landlordrent = getGLCodeForAccount(array('gl' => 'LandlordRent', 'property_id' => $propid));
        $landlordrentgl = $landlordrent['glcode'];
        $entry1 = createJournalEntry(array('glcode' => $landlordrentgl, 'document_ref' => $result2, 'credit' => $invoiceamount, 'ttype' => 'INV', 'property_id' => $propid, 'desc' => $remarks, 'idclose_period' => $fperiod));

        //create debit entry for commission account on landlord side (expense)
        $landlordcomm = getGLCodeForAccount(array('gl' => 'LandlordCommission', 'property_id' => $propid));
        $landlordrentgl = $landlordcomm['glcode'];
        $entry1 = createJournalEntry(array('glcode' => $landlordrentgl, 'document_ref' => $result2, 'debit' => $creditamount, 'ttype' => 'INV', 'property_id' => $propid, 'desc' => $remarks, 'idclose_period' => $fperiod));

        header('Content-Type: application/json');
        $response_array['status'] = 'Credit Note ' . $result2 . ' created!';
        $response_array['invoiceno'] = $result2;
        echo json_encode($response_array);
    }

    $db->close_connection();
}
function setChargeItemPriority($itemname) {
    $priority;
    switch (strtoupper($itemname)) {
        case 'RENT':
            $priority = 'A';
            break;

        case 'WATER':
            $priority = 'B';
            break;

        case 'GARBAGE':
            $priority = 'C';
            break;

        default:
            $priority = 'Z';
            break;
    }
    return $priority;
}

//penalty invoice (tested-ok)

function createPenaltyInvoice($invoiceitemsarray) {
    $db = new MySQLDatabase();
    $db->open_connection();
    $tablename = "invoices";
    $tablename2 = 'invoiceitems';
    $entrydate = $invoiceitemsarray['date'];
    $invoiceamount = $invoiceitemsarray['amount'];
    $tenantid = $invoiceitemsarray['tenant_id'];
    $incomeacct = 0; //$invoiceitemsarray['incomeaccount'];
    $penalty = 1;
    $user = $_SESSION['username'];
    $propid = $invoiceitemsarray['property_id'];
    $remarks = $invoiceitemsarray['remarks'];
    $recpno = $invoiceitemsarray['recpno'];
    $billing = $invoiceitemsarray['billing'];
    $currentdate = date('Y-m-d h:i:s');
    if ($billing == "0") {
        $incrementedinvoice = incrementnumber("invno");
    } else {
        $incrementedinvoice = incrementnumber("credno");
    }
    $query = "INSERT into $tablename(`invoiceno`,`invoicedate`,`amount`,`recpno`,`idno`,`incomeaccount`,`us`,`invoicecredit`,`is_penalty`,`property_id`,`remarks`,`ts`) VALUES ('$incrementedinvoice','$entrydate','$invoiceamount','$recpno','$tenantid','$incomeacct','$user','$billing','$penalty','$propid','$remarks','$currentdate') ";
    $queryresult = $db->query($query) or die($db->error());
    $chargename = $invoiceitemsarray['chargename'];
    $chargeamount = $invoiceitemsarray['chargeamount'];
    $query1 = "INSERT into $tablename2(`invoiceno`,`item_name`,`amount`) VALUES ('$incrementedinvoice','$chargename','$chargeamount')";
    $db->query($query1) or die($db->error());
}

function create_batch_invoice($entrydate, $incomeacct, $amount, $billing, $user, $propid, $remarks, $fperiod) {
    $db = new MySQLDatabase();
    $db->open_connection();
    $tenantdetails = array();
    $individualdetails = array();
    $tablename = "invoices";
    $tablename2 = "tenants";

    $query = $db->query("SELECT Id,apartmentid FROM $tablename2 WHERE property_id='$propid' AND vacated='0'") or die($db->error());
    while ($row2 = mysql_fetch_array($query)) {
        $individualdetails['idno'] = $row2['Id'];
        $individualdetails['apt_id'] = $row2['apartmentid'];
        array_push($tenantdetails, $individualdetails);
    }

    //get chargeables for this property
    $chargeables = getChargeItems($propid);
    $charges = array();
    foreach ($chargeables as $chargeable) {


        if ($chargeable['amount'] > 0) {
            $charges[$chargeable['accname']] = $chargeable['amount'];
        }
    }

    foreach ($tenantdetails as $value) {
        //get chargeables for each tenant

        $vat = getVAT("housevat");
        $hasvat = checkPropertyVAT($propid);
        if ($hasvat) {
            $rent = getApartmentChargeables($value['apt_id']);
            $vatamount = (($vat / 100) * $rent);
            $vatrent = $vatamount + $rent;
            $chargeitems = array("RENT" => $rent, "VAT" => $vatamount);
        } else {
            $rent = getApartmentChargeables($value['apt_id']);
            $chargeitems = array("RENT" => $rent);
        }
        //i have all chargeable items plus their amounts at this point
        $allitems = array_merge($chargeitems, $charges);
        $charges1 = array_values($allitems);
        $chargenames = array_keys($allitems);
        $amount = array_sum(array_values($allitems));

        //so create invoice
        $invoices = create_invoice($value['idno'], $entrydate, $incomeacct, $amount, $billing, $user, $propid, $remarks, $chargenames, $charges1, sizeof($charges1), $currentreading = 0, $value['apt_id'], $fperiod);
    }
    unset($tenantdetails);
    unset($individualdetails);
    unset($charges);
    unset($allitems);
    unset($chargenames);
    unset($charges1);
//     if ($invoices) {
//            return true;
//        } else {
//            return FALSE;
//        }
//    $db->close_connection();
}

//get chargeables for each apartment/house
function getApartmentChargeables($aptid) {
    $mysqli = getMysqliConnection();
    $floorplantable = 'floorplan';
    $res = $mysqli->query("SELECT propertyid,monthlyincome FROM $floorplantable WHERE apt_id='$aptid' limit 1") or die($mysqli->error);
    while ($row = $res->fetch_assoc()) {
      
        $rent = $row['monthlyincome'];
        
    }
    return $rent;
    //get rent
}

//invoice details
//@param int $invoiceno
//@return array $invoicedetail
function getInvoiceDetails($invoiceno) {
    $mysqli = getMysqliConnection();
    $tablename = getInvoiceTable();
    $invoicedetail = array();
  //  echo $invoiceno;
    $res = $mysqli->query("SELECT  * FROM $tablename WHERE invoiceno='$invoiceno'") or die($mysqli->error);

    while ($row = $res->fetch_assoc()) {
        $invoicedetail['date'] = $row['invoicedate'];
        $invoicedetail['amount'] = $row['amount'];
        $invoicedetail['paidamount'] = $row['paidamount'];
        $invoicedetail['clientid'] = $row['idno'];
        $invoicedetail['revsd'] = $row['revsd'];
        $invoicedetail['invoicecreditnote'] = $row['invoicecredit'];
        $invoicedetail['is_penalty'] = $row['is_penalty'];
        $invoicedetail['remarks'] = $row['remarks'];
    }
    return $invoicedetail;
}

function getinvoicebyid($id) {
    $db = new MySQLDatabase();
    $db->open_connection();
    $tablename = "invoices";
    echo '<table class="treport">
<tr>
<th><center><u>Invoice No</u></center></th>
<th><center><u>Invoice Date</u></center></th>
<th><center><u>Amount</u></center></th>
<th><center><u>Idno</u></center></th>
<th><center>Action</center></th></tr>';

    $sql = $db->query("SELECT * FROM $tablename WHERE invoiceno like '$id'") or die($db->error());
    while ($row = mysql_fetch_array($sql)) {
        $invid = $row['tno'];
        $invoiceno = $row['invoiceno'];
        $invoicedate = $row['invoicedate'];
        $amount = $row['amount'];
        $idno = $row['idno'];
        echo "<tr><td>$invoiceno</td><td>" . $invoicedate . "</td><td>" . $amount . "</td><td>" . $idno . "</td><td><a href='#' id='delinv' title='$invid'><img src='../images/close.png'>Delete Invoice</a></td>";
    }
    echo '</tr></table>';
    $db->close_connection();
}

function getUnreversedinvoicebyid($id) {
    $db = new MySQLDatabase();
    $db->open_connection();
    $tablename = "invoices";
    echo '<table class="treport">
<tr>
<th><center><u>Invoice No</u></center></th>
<th><center><u>Invoice Date</u></center></th>
<th><center><u>Amount</u></center></th>
<th><center><u>Idno</u></center></th>
<th><center>Action</center></th></tr>';

    $sql = $db->query("SELECT * FROM $tablename WHERE invoiceno like '$id' AND revsd=0") or die($db->error());
    while ($row = mysql_fetch_array($sql)) {
        $invid = $row['tno'];
        $invoiceno = $row['invoiceno'];
        $invoicedate = $row['invoicedate'];
        $amount = $row['amount'];
        $idno = $row['idno'];
        echo "<tr><td>$invoiceno</td><td>" . $invoicedate . "</td><td>" . $amount . "</td><td>" . $idno . "</td><td><a href='#' id='delinv' title='$invid'><img src='../images/close.png'>Delete Invoice</a></td>";
    }
    echo '</tr></table>';
    $db->close_connection();
}

function reverseinvoice($id, $user) {
    $db = new MySQLDatabase();
    $db->open_connection();
    $tablename = "invoices";
     $tablename2 = "invoiceitems";
     $currentdate = date('Y-m-d h:i:s');
    $sql = $db->query("SELECT * FROM $tablename WHERE `tno` like '$id'") or die($db->error());
    while ($row = $db->fetch_array($sql)) {
        $invoiceno = $row['invoiceno'];
        //if invoice is already reversed
        if (strpos($invoiceno, "R") !== FALSE) {
            return 0;
        } else {
            $invoicedate = $row['invoicedate'];
            $amount = $row['amount'];
            $idno = $row['idno'];
            $incomeacct = $row['incomeaccount'];
            $billing = $row['invoicecredit'];
            $propid = $row['property_id'];
            $idclose_periods = $row['idclose_periods'];
        }
    }
    $sql2 = $db->query("SELECT * FROM $tablename2 WHERE `invoiceno` = '$invoiceno'") or die($db->error());
    while ($row2 = $db->fetch_array($sql2)) {
        $item_invoiceno = $row2['invoiceno'];
            $item_item_name = $row2['item_name'];
            $item_priority = $row2['priority'];
            $item_amnt = $row2['amount'];
           
      $query2 = $db->query("INSERT into $tablename2(`invoiceno`,`item_name`,`priority`,`amount`) VALUES ('R$item_invoiceno','$item_item_name','$item_priority','-$item_amnt')") or die($db->error());
  
    }
    $query = $db->query("INSERT into $tablename(`invoiceno`,`invoicedate`,`amount`,`idno`,`incomeaccount`,`us`,`revsd`,`invoicecredit`,`property_id`,`idclose_periods`,`ts`) VALUES ('R$invoiceno','$invoicedate','-$amount','$idno','$incomeacct','$user','1','$billing','$propid','$idclose_periods','$currentdate')") or die($db->error());
    $update = $db->query("UPDATE $tablename SET `revsd`='1' WHERE  `invoiceno` ='$invoiceno'") or die($db->error());
//delete receipt
    deleteReceiptFromInvoice($invoiceno);
//reverse Journals
    reverseJournal(array('document_ref' => $invoiceno, 'ttype' => 'INV'));
    $db->close_connection();
}

//print an invoice
function printhillsinvoice($invoiceno, $propid, $user) {
    $db = new MySQLDatabase();
    date_default_timezone_set('Africa/Nairobi');
    $date = date("d/m/y");
    $time = date('h:i A');
    $db->open_connection();
    $tablename = "invoices";
    $tablename1 = "chargeitems";
    $tablename2 = "invoiceitems";
    $tableitems = [];
    $chargeablesamount = array();
    $chargeablenames = array();
    //die("SELECT * FROM $tablename WHERE `invoiceno` like '$invoiceno'");
    $sql = $db->query("SELECT * FROM $tablename WHERE `invoiceno` like '$invoiceno'") or die($db->error());
    if (mysql_numrows($sql) > 0) {
        while ($row = mysql_fetch_array($sql)) {
            $invoiceno = $row['invoiceno'];
            $invoicedate = $row['invoicedate'];
            if($row['invoicecredit']=='1'){$amount = -1*$row['amount'];}
            else{$amount = $row['amount'];}
            $idno = $row['idno'];
            $incomeacct = $row['incomeaccount'];
            $billing = $row['invoicecredit'];
            $remarks = $row['remarks'];
            $bbf = $row['bbf'];
        }
        //get apartment No
        $apartmentid = getApartmentFromTenant($idno);
        $aptdetails = getApartmentDetails($apartmentid);
       // die($billing);
       if ($billing == '0'){ $value = "Invoice";}
       else{ $value = "Credit Note";} //type of billing
       
        $sql1 = $db->query("SELECT accname FROM $tablename1 WHERE `id` like '$incomeacct' ORDER BY `priority` ASC") or die($db->error());
        while ($row1 = mysql_fetch_array($sql1)) {
            $accname = $row1['accname'];
        }
        $sql2 = $db->query("SELECT tenants.property_name,tenants.apartmentid,tenants.tenant_name,floorplan.last_water_reading,floorplan.current_water_reading,banks.bank_name,banks.acct_no,banks.acct_name FROM tenants  JOIN floorplan ON tenants.apartmentid=floorplan.apt_id LEFT JOIN banks ON banks.id = tenants.bank_id  WHERE ( tenants.Id='$idno' AND tenants.vacated like '0')") or die($db->error());
        while ($row2 = mysql_fetch_array($sql2)) {
            $propertyname = $row2['property_name'];
            $tname = $row2['tenant_name'];
            $lastwater = $row2['last_water_reading'];
            $currentwater = $row2['current_water_reading'];
            $bankname = $row2['bank_name'];
             $acct_no = $row2['acct_no'];
             $acct_name = $row2['acct_name'];
        }
        //get sum of chargeable items
        $sql3 = $db->query("SELECT item_name,amount FROM $tablename2 WHERE invoiceno='$invoiceno' ORDER BY priority ASC") or die($db->error());
        while ($row3 = mysql_fetch_array($sql3)) {
            $item_name = $row3['item_name'];
            $item_amount = $row3['amount'];
            //total amount
            array_push($chargeablesamount, $item_amount);
            array_push($chargeablenames, $item_name);
            //if the charge item !=0
            if ($item_amount != 0) {
                array_push($tableitems, '<tr><td></td><td style="color:black;" colspan="2"><u>' . $item_name . '</u></td><td>Ksh:' . number_format($item_amount, 2) . '</td></tr>');
            }
        }
        //get outstanding balance/prepayment
        if($bbf =='0'){
        $balance = getCorrectBalance($idno, $invoiceno,$invoicedate);
        } else{
          $balance = $bbf;  
        }
    
        $chargestotal = array_sum($chargeablesamount);
        $totaldue = $balance + $chargestotal;
        $settings = getSettings();
        echo '<br><br><br><br><br><br><br><br>';
        ?>



        <center><table class="tftable printable" style="width:90%" border="1">
            <tr><td colspan="5">
<img src="../images/cursors/logo1.png" style="height:137px;width:auto;">
</td></tr>
               
                <tr><td><b><?php echo $value;  ?> NO</b> </td><td><?php echo '<span id="invoiceno">&nbsp;' . $invoiceno . '</span>' ?></td><td></td><td><?php echo 'Date ' . date("d-m-Y", strtotime($invoicedate)) . '</b>' ?></td></tr>
                <tr><td>TO</td><td colspan="3"><?php echo ucwords(@$tname) . '(' . $aptdetails[0]['apt_tag'] . ')' ?> <b>OF </b><?php echo ucwords(str_replace('_', " ", $propertyname)) ?></td></tr>
                <?php
                foreach ($chargeablenames as $item) {

                    if (strtoupper($item) == 'WATER') {
                        ?>
                        <tr><td>WATER CONSUMPTION</td><td colspan="3">
                        <?php echo '<span style="border:2px solid black;color:black">Last Reading:&nbsp;&nbsp;' . $lastwater . '&nbsp;Current Reading:&nbsp;&nbsp;' . $currentwater . '&nbsp;Consumption:&nbsp;' . ($currentwater - $lastwater) . 'units&nbsp;&nbsp;Rate:(ksh)' . get_water_rate($propid) . '&nbsp;&nbsp;&nbsp;</span>'; ?>
                            </td></tr>
                        <?php
                    }
                }
                ?>

                <tr><td><b>Qty</b></td><td colspan="2"><b>Particulars</b></td><td><b>Amount</b></td></tr>
                <?php
                 
                foreach ($tableitems as $key => $value1) {

                    echo trim($key, 0) . trim($value1, 0); //remove trailing zeros
                }
                ?>
                <tr><td></td><td class="blackfont" colspan="2">BALANCE BROUGHT FORWARD:</td><td><?php echo 'Ksh: ' . number_format($balance, 2); ?></td></tr>
                <tr><td></td><td ></td><td><b>TOTAL CHARGEABLE</b></td><td><b><?php echo number_format($chargestotal, 2) ?></b></td></tr>
                <tr><td></td><td></td><td><b>TOTAL DUE</b></td><td><b><?php echo number_format($totaldue, 2) ?></b></td></tr>
                <tr><td colspan="4"><?php echo 'Being ' . $value . ' for: </b>' . @$remarks ?></td></tr>
              <!--  Bank Details:<?php //echo $bankname; ?> <br> Acct No:<?php //echo $acct_no ?> <br> Acct Name:<?php //echo $acct_name ?>-->
                <tr><td colspan="4"><span class="linkright"><?php echo $user . '&nbsp;&nbsp;' . $date . '&nbsp;&nbsp;' . $time ?></span></td></tr>
                            <tr><td colspan="4">
<!-- <img src="../images/cursors/hills_footer.png" style="height:90px;width:1100px;"> -->
</td></tr>
            </table>

        </center>
        <?php
//        echo '<tr><td colspan="3">All acounts are due on or before the 5th day of the month,failure to settle by the said date attracts a penalty of 10% after every 7 days |&nbsp;&nbsp;&nbsp; <i>' . $user . '&nbsp;&nbsp;' . $date . '&nbsp;&nbsp;' . $time . '</i></td></tr>';
//        echo '</table></center>';
        ?>
    <?php
    } else {
        return false;
    }
}




//print an invoice
function printinvoice($invoiceno, $propid, $user) {
    $db = new MySQLDatabase();
    date_default_timezone_set('Africa/Nairobi');
    $date = date("d/m/y");
    $time = date('h:i A');
    $db->open_connection();
    $tablename = "invoices";
    $tablename1 = "chargeitems";
    $tablename2 = "invoiceitems";
    $tableitems = [];
    $chargeablesamount = array();
    $chargeablenames = array();
    //die("SELECT * FROM $tablename WHERE `invoiceno` like '$invoiceno'");
    $sql = $db->query("SELECT * FROM $tablename WHERE `invoiceno` like '$invoiceno'") or die($db->error());
    if (mysql_numrows($sql) > 0) {
        while ($row = mysql_fetch_array($sql)) {
            $invoiceno = $row['invoiceno'];
            $invoicedate = $row['invoicedate'];
            $amount = $row['amount'];
            $idno = $row['idno'];
            $incomeacct = $row['incomeaccount'];
            $billing = $row['invoicecredit'];
            $remarks = $row['remarks'];
        }
        //get apartment No
        $apartmentid = getApartmentFromTenant($idno);
        $aptdetails = getApartmentDetails($apartmentid);
       // die($billing);
       if ($billing == '0'){ $value = "Invoice";}
       else{ $value = "Credit Note";} //type of billing
       
        $sql1 = $db->query("SELECT accname FROM $tablename1 WHERE `id` like '$incomeacct' ORDER BY `priority` ASC") or die($db->error());
        while ($row1 = mysql_fetch_array($sql1)) {
            $accname = $row1['accname'];
        }
        $sql2 = $db->query("SELECT tenants.property_name,tenants.apartmentid,tenants.tenant_name,floorplan.last_water_reading,floorplan.current_water_reading FROM tenants  JOIN floorplan ON tenants.apartmentid=floorplan.apt_id WHERE ( tenants.Id='$idno' AND tenants.vacated like '0')") or die($db->error());
        while ($row2 = mysql_fetch_array($sql2)) {
            $propertyname = $row2['property_name'];
            $tname = $row2['tenant_name'];
            $lastwater = $row2['last_water_reading'];
            $currentwater = $row2['current_water_reading'];
        }
        //get sum of chargeable items
        $sql3 = $db->query("SELECT item_name,amount FROM $tablename2 WHERE invoiceno='$invoiceno' ORDER BY priority ASC") or die($db->error());
        while ($row3 = mysql_fetch_array($sql3)) {
            $item_name = $row3['item_name'];
            $item_amount = $row3['amount'];
            //total amount
            array_push($chargeablesamount, $item_amount);
            array_push($chargeablenames, $item_name);
            //if the charge item !=0
            if ($item_amount != 0) {
                array_push($tableitems, '<tr><td></td><td style="color:black;" colspan="2"><u>' . $item_name . '</u></td><td>Ksh:' . number_format($item_amount, 2) . '</td></tr>');
            }
        }
        //get outstanding balance/prepayment
        $balance = getCorrectBalance($idno, $invoiceno);
        $chargestotal = array_sum($chargeablesamount);
        $totaldue = $balance + $chargestotal;
        $settings = getSettings();
        echo '<br><br><br><br><br><br><br><br>';
        ?>



        <center><table class="tftable printable" style="width:90%" border="1">
                <tr><th><img src="../images/cursors/logo.png"/></th><th colspan="3"><center><?php echo $settings['company_name'] ?></center><br><?php echo $settings['tagline'] ?></th></tr>
                <tr><td><b><?php echo $value;  ?> NO</b> </td><td><?php echo '<span id="invoiceno">&nbsp;' . $invoiceno . '</span>' ?></td><td></td><td><?php echo 'Date ' . date("d-m-Y", strtotime($invoicedate)) . '</b>' ?></td></tr>
                <tr><td>TO</td><td colspan="3"><?php echo ucwords(@$tname) . '(' . $aptdetails[0]['apt_tag'] . ')' ?> <b>OF </b><?php echo ucwords(str_replace('_', " ", $propertyname)) ?></td></tr>
                <?php
                foreach ($chargeablenames as $item) {

                    if (strtoupper($item) == 'WATER') {
                        ?>
                        <tr><td>WATER CONSUMPTION</td><td colspan="3">
                        <?php echo '<span style="border:2px solid black;color:black">Last Reading:&nbsp;&nbsp;' . $lastwater . '&nbsp;Current Reading:&nbsp;&nbsp;' . $currentwater . '&nbsp;Consumption:&nbsp;' . ($currentwater - $lastwater) . 'units&nbsp;&nbsp;Rate:(ksh)' . get_water_rate($propid) . '&nbsp;&nbsp;&nbsp;</span>'; ?>
                            </td></tr>
                        <?php
                    }
                }
                ?>
                <tr><td><b>Qty</b></td><td colspan="2"><b>Particulars</b></td><td><b>Amount</b></td></tr>
                <?php
                foreach ($tableitems as $key => $value1) {

                    echo trim($key, 0) . trim($value1, 0); //remove trailing zeros
                }
                ?>
                <tr><td></td><td class="blackfont" colspan="2">BALANCE BROUGHT FORWARD:</td><td><?php echo 'Ksh: ' . number_format($balance, 2); ?></td></tr>
                <tr><td></td><td ></td><td><b>TOTAL CHARGEABLE</b></td><td><b><?php echo number_format($chargestotal, 2) ?></b></td></tr>
                <tr><td></td><td></td><td><b>TOTAL DUE</b></td><td><b><?php echo number_format($totaldue, 2) ?></b></td></tr>
                <tr><td colspan="4"><?php echo 'Being ' . $value . ' for: </b>' . @$remarks ?></td></tr>
                <tr><td colspan="4">PIN NO:<?php echo $settings['pin'] ?>  VAT NO:<?php echo $settings['vat'] ?><span class="linkright"><?php echo $user . '&nbsp;&nbsp;' . $date . '&nbsp;&nbsp;' . $time ?></span></td></tr>
            </table>

        </center>
        <?php
//        echo '<tr><td colspan="3">All acounts are due on or before the 5th day of the month,failure to settle by the said date attracts a penalty of 10% after every 7 days |&nbsp;&nbsp;&nbsp; <i>' . $user . '&nbsp;&nbsp;' . $date . '&nbsp;&nbsp;' . $time . '</i></td></tr>';
//        echo '</table></center>';
        ?>
    <?php
    } else {
        return false;
    }
}

function printinvoiceToScreen($invoiceno, $propid, $user) {
    echo printinvoice($invoiceno, $propid, $user);
}

//get outstanding balance for customer
//@ param integer CustomerId
function getBalance($idno, $latestinvoice = '0') {
    $tablename = getInvoiceTable();
    $balances = array();
    $mysqli = getMysqliConnection();
    //get balance for a tenants previous invoices that have not been reversed 
    $res = $mysqli->query("SELECT (amount-paidamount) as balance  FROM {$tablename} WHERE idno='$idno' AND revsd=0 AND invoiceno !='$latestinvoice' ") or die($mysqli->error);
    while ($row = $res->fetch_assoc()) {
        array_push($balances, $row['balance']);
    }
    return array_sum($balances);
}

/* * **************new 5/04/2016 ************* */

function getrecpbyNo($recpno) {
    $db = new MySQLDatabase();
    $db->open_connection();
    $tablename = "recptrans";
    $recpdetails = array();

    $sql = $db->query("SELECT * FROM $tablename WHERE recpno like '$recpno'") or die(mysql_error());
    while ($row = mysql_fetch_array($sql)) {
        $recpdetails = $row;
    }
    return $recpdetails;
    $db->close_connection();
}

//balances except for the last invoice
function getCorrectBalance($idno, $latestinvoice = '0',$invoicedate, $receiptno = "") {

    $tablename = getInvoiceTable();
    $mysqli = getMysqliConnection();
    $totalinvoicesamount = array();
    $totalreceivedamount = array();
    $receiptdetails = getrecpbyNo($receiptno);
 
    $propid=$_REQUEST["propid"];
    //do not show balance if receipt date < invoice date
    $date = date("Y-m")."-30";
     $propid = $_SESSION['propertyid'];
    
    if ($receiptno) {
        $rdate = $receiptdetails["rdate"];

        // die ("SELECT invoiceno,amount  FROM {$tablename} WHERE idno='$idno' AND revsd=0 AND invoiceno !='$latestinvoice' AND invoicedate <= '$rdate' ");
       
        //SELECT invoices.invoiceno,invoices.invoicedate,invoices.amount as credit,invoices.remarks as narration FROM invoices WHERE invoices.idno='$tenantid' AND invoices.revsd=0 AND property_id='$propid' AND invoicedate between '$startdate' AND '$enddate'
        $res = $mysqli->query("SELECT invoiceno,IF(invoicecredit=0,amount,amount*-1) as amount ,invoicedate  FROM {$tablename} WHERE idno='$idno' AND revsd=0 AND property_id='$propid' AND invoiceno !='$latestinvoice' AND invoicedate <='$rdate'  ") or die($mysqli->error); //AND `invoicedate` <= $date
       // $res=$mysqli->query("SELECT invoices.invoiceno,invoices.invoicedate,invoices.amount as credit,invoices.remarks as narration FROM invoices WHERE invoices.idno='$idno' AND invoices.revsd=0 AND property_id='$propid' AND invoicedate between '$startdate' AND '$enddate'");
    }
    //get balance for a tenants previous invoices that have not been fully paid and not reversed 
    //we are not using paidamount here to check whether invoice is paid/not since an invoice may have been paid fully but in installments,hence getInvoiceReceipts
    else {

        $res = $mysqli->query("SELECT invoiceno,IF(invoicecredit=0,amount,amount*-1) as amount,invoicedate  FROM {$tablename} WHERE idno='$idno' AND property_id='$propid' AND revsd=0 AND invoiceno !='$latestinvoice'  AND `invoicedate` <= '$invoicedate' ") or die($mysqli->error);//AND `invoicedate` <= $date
    }
   // die("SELECT invoiceno,IF(invoicecredit=0,amount,amount*-1) as amount,invoicedate  FROM {$tablename} WHERE idno='$idno' AND property_id='$propid' AND revsd=0 AND invoiceno !='$latestinvoice'  AND `invoicedate` <= '$invoicedate' ");
    while ($row = $res->fetch_assoc()) {
        $invoiceno = $row['invoiceno'];
        //total invoiceamount
        $invoiceamount = $row['amount'];
        $invoicedate=$row["invoicedate"];
        //total received amounts
       $receivedamount = getInvoiceReceipts($invoiceno);
        // $receivedamount = getTenantRecptrans($idno);
        array_push($totalreceivedamount, $receivedamount);
        array_push($totalinvoicesamount, $invoiceamount);
    }
   $date=  DateTime::createFromFormat("Y-m-d",$invoicedate);
    //if(is_object($date)){
    
//    if($date->format("m") > date("m")){
//        return 0;
//    }
//    }
//    else{

    return (array_sum($totalinvoicesamount) - array_sum($totalreceivedamount));
    //}
    
}

function getCorrectBalance2($idno, $latestinvoice = '0', $receiptno = "") {

    $tablename = getInvoiceTable();
    $mysqli = getMysqliConnection();
    $totalinvoicesamount = array();
    $totalreceivedamount = array();
    $receiptdetails = getrecpbyNo($receiptno);
 
    $propid=$_REQUEST["propid"];
    //do not show balance if receipt date < invoice date
    $date = date("Y-m")."-30";
    
    
    if ($receiptno) {
        $rdate = $receiptdetails["rdate"];

        // die ("SELECT invoiceno,amount  FROM {$tablename} WHERE idno='$idno' AND revsd=0 AND invoiceno !='$latestinvoice' AND invoicedate <= '$rdate' ");
       
        //SELECT invoices.invoiceno,invoices.invoicedate,invoices.amount as credit,invoices.remarks as narration FROM invoices WHERE invoices.idno='$tenantid' AND invoices.revsd=0 AND property_id='$propid' AND invoicedate between '$startdate' AND '$enddate'
        $res = $mysqli->query("SELECT invoiceno,amount,invoicedate  FROM {$tablename} WHERE idno='$idno' AND revsd=0 AND property_id='$propid' AND invoiceno !='$latestinvoice' AND invoicedate <='$rdate'  ") or die($mysqli->error); //AND `invoicedate` <= $date
       // $res=$mysqli->query("SELECT invoices.invoiceno,invoices.invoicedate,invoices.amount as credit,invoices.remarks as narration FROM invoices WHERE invoices.idno='$idno' AND invoices.revsd=0 AND property_id='$propid' AND invoicedate between '$startdate' AND '$enddate'");
    }
    //get balance for a tenants previous invoices that have not been fully paid and not reversed 
    //we are not using paidamount here to check whether invoice is paid/not since an invoice may have been paid fully but in installments,hence getInvoiceReceipts
    else {

        $res = $mysqli->query("SELECT invoiceno,amount,invoicedate  FROM {$tablename} WHERE idno='$idno' AND property_id='$propid' AND revsd=0 AND invoiceno !='$latestinvoice'  ") or die($mysqli->error);//AND `invoicedate` <= $date
    }
    while ($row = $res->fetch_assoc()) {
        $invoiceno = $row['invoiceno'];
        //total invoiceamount
        $invoiceamount = $row['amount'];
        $invoicedate=$row["invoicedate"];
        //total received amounts
        $receivedamount = getInvoiceReceipts($invoiceno);
        array_push($totalreceivedamount, $receivedamount);
        array_push($totalinvoicesamount, $invoiceamount);
    }
   $date=  DateTime::createFromFormat("Y-m-d",$invoicedate);
    //if(is_object($date)){
    
//    if($date->format("m") > date("m")){
//        return 0;
//    }
//    }
//    else{

    return (array_sum($totalinvoicesamount) - array_sum($totalreceivedamount));
    //}
    
}




//get charge items for invoice
function getInvoiceChargeItemsValue($invoiceno) {
    $chargeitemstable = invoiceitemsTable();
    $chargeablesamount = array();
    $mysqli = getMysqliConnection();
    //get sum of chargeable items
    $res = $mysqli->query("SELECT item_name,amount FROM $chargeitemstable WHERE invoiceno='$invoiceno' ORDER BY priority ASC") or die($mysqli->error + "ERROR2");
    $total = 0;
    while ($row3 = $res->fetch_assoc()) {
        $item_name = $row3['item_name'];
        $item_amount = $row3['amount'];
        //total amount
        array_push($chargeablesamount, $item_amount);
    }
    return array_sum($chargeablesamount);
}

function getInvoiceItemsAndValue($invoiceno) {
    $chargeitemstable = invoiceitemsTable();

    $items = array();
    $mysqli = getMysqliConnection();
    //get sum of chargeable items
    $res = $mysqli->query("SELECT item_name,amount FROM $chargeitemstable WHERE invoiceno='$invoiceno' ORDER BY priority ASC") or die($mysqli->error + "ERROR 1");
    //gett total amount paid for this invoice

    $result = $mysqli->query("SELECT SUM(amount) AS paidamount FROM recptrans WHERE invoicenopaid='$invoiceno' ") or die($mysqli->error + "ERROR 1");
    while ($row = $result->fetch_assoc()) {
        @$paidamount = @$row['paidamount'];
    }
    $total = 0;
    while ($row3 = $res->fetch_assoc()) {
        $item['name'] = $row3['item_name'];
        $item['amount'] = $row3['amount'];
        $item['paidamount'] = $paidamount;
        //total amount
        array_push($items, $item);
    }
    return $items;
}

//array sum
function myarraysum($array) {
    $total = 0;
    foreach ($array as $value) {
        $total = $total + $value;
    }
    return $total;
}

//getlatest invoice
function getLatestInvoiceTenant($id) {
    
}

function getInvoiceBalance($idno, $latestinvoice = '0') {
    $tablename = getInvoiceTable();
    $balances = array();
    $mysqli = getMysqliConnection();
    //get balance for a tenant where invoice has not been reversed 
    $res = $mysqli->query("SELECT (amount-paidamount) as balance  FROM {$tablename} WHERE idno='$idno' AND revsd=0 AND invoiceno='$latestinvoice' ") or die($mysqli->error);
    while ($row = $res->fetch_assoc()) {
        array_push($balances, $row['balance']);
    }
    return array_sum($balances);
}

//all invoices
function getinvoicelist($startdate, $enddate, $accid, $accname, $propid, $user, $allpropertiesflag = 0) {
    date_default_timezone_set('Africa/Nairobi');
    $time = date('h:i A');
    $db = new MySQLDatabase();
    $db->open_connection();
    $tablename = "invoices";
    $tablename2 = "tenants";
    $tablename3 = "vatamounts";
    if ($accid == "0") {
        $accid = '%';
    } else {
        $accid = $accid;
    }
    $q2 = $db->query("SELECT amount from $tablename3 WHERE vatid='1'") or die($db->error());
    while ($row1 = mysql_fetch_array($q2)) {
        $tenantVAT = $row1['amount'];
    }

    //if invoice list is for all properties
    if ($allpropertiesflag) {
        $allproperties = getProperties();
        $property = getSettings();
        echo '<table class="treport" ><thead>
<tr><td colspan="9"><center><span style="font-size:15px;font-weight:normal;float:left;"> <b>INVOICE LIST -' . $accname . '</b></span><span style="font-size:18px;font-weight:bold">' . $property['company_name'] . '</span><span style="font-size:18px;font-weight:normal; float:right;"></span><center>
<br/><span style="font-size:16px;font-weight:normal;"><center>' . str_repeat('&nbsp;', 25) . 'Invoice List From <b> ' . date("d-m-Y", strtotime($startdate)) . '</b>  To  <b>' . date("d-m-Y", strtotime($enddate)) . '</b></center></span></td></tr>';
        echo'<tr>
<u><th>S/no</th><th>Property</th> <th>Invoice/Credit No</th><th>Date</th> <th>House No</th><th>Tenant/Other Name</th> <th>Amount</th><th>Vat(' . round($tenantVAT * 100) . '%)</th><th>Total Amount</th></u></tr></thead>';
        echo '<tbody>';
        foreach ($allproperties as $property) {
            $propid = $property['property_id'];

            $query = $db->query("SELECT $tablename.invoiceno,$tablename.invoicedate,IF(invoicecredit ='0',$tablename.amount,$tablename.amount*-1) as amount,$tablename2.Id,$tablename2.tenant_name,$tablename2.Apartment_tag,$tablename2.property_name FROM $tablename LEFT JOIN $tablename2 ON $tablename.idno=$tablename2.Id WHERE $tablename.invoicedate BETWEEN '$startdate' AND '$enddate' AND $tablename.property_id='$propid' ORDER BY $tablename.invoiceno ASC ") or die($db->error()); //find a way to sort by propertyid

            $i = 1;
            while ($row = $db->fetch_array($query)) {
if($row['amount'] < 0){echo '<tr><td>' . $i . '</td><td>' . findpropertybyid($propid) . '</td><td><a href="defaultreports.php?report=printinvoice&invoiceno=' . $row['invoiceno'] . '" target="blank">' . $row['invoiceno'] . '</a></td><td>' . date('F j,Y', strtotime($row['invoicedate'])) . '</td><td>' . $row['Apartment_tag'] . '</td><td>' . $row['tenant_name'] . '</td><td style="background-color:#FF0000">' . number_format($row['amount'], 2) . '</td><td>' . number_format($tenantVAT * $row['amount'], 2) . '</td><td>' . number_format($row['amount'] + ($tenantVAT * $row['amount']), 2) . '</td></tr>'; }else{
                echo '<tr><td>' . $i . '</td><td>' . findpropertybyid($propid) . '</td><td><a href="defaultreports.php?report=printinvoice&invoiceno=' . $row['invoiceno'] . '" target="blank">' . $row['invoiceno'] . '</a></td><td>' . date('F j,Y', strtotime($row['invoicedate'])) . '</td><td>' . $row['Apartment_tag'] . '</td><td>' . $row['tenant_name'] . '</td><td>' . number_format($row['amount'], 2) . '</td><td>' . number_format($tenantVAT * $row['amount'], 2) . '</td><td>' . number_format($row['amount'] + ($tenantVAT * $row['amount']), 2) . '</td></tr>';}
                $sumamount[] = $row['amount'];
                $sumvatamount[] = ($tenantVAT * $row['amount']);
                $sumtotalamount[] = ($row['amount'] + ($tenantVAT * $row['amount']));
                $i++;
            }
        }
        echo '</tbody>';
        echo '<tfoot><tr><td><b>GRAND TOTAL</b></td><td></td><td></td><td></td><td></td><td></td><td><b>' . number_format(@array_sum($sumamount), 2) . '</b></td><td><b>' . number_format(@array_sum($sumvatamount), 2) . '</b></td><td><b>' . number_format(@array_sum($sumtotalamount), 2) . '</b></td></tr>';
        echo '</tfoot></table>';
        echo '<hr/>';
        echo '<i>Printed by:</i> ' . $user . '&nbsp;&nbsp;&nbsp;&nbsp;' . date("d-m-y") . '&nbsp;' . $time;
    } else {
        $query = $db->query("SELECT $tablename.invoiceno,$tablename.invoicedate,IF(invoicecredit = '0',$tablename.amount,$tablename.amount*-1) as amount,$tablename2.Id,$tablename2.tenant_name,$tablename2.Apartment_tag,$tablename2.property_name,$tablename.revsd FROM $tablename LEFT JOIN $tablename2 ON $tablename.idno=$tablename2.Id WHERE $tablename.invoicedate BETWEEN '$startdate' AND '$enddate' AND $tablename.property_id='$propid' ORDER BY $tablename.invoiceno ASC ") or die(mysql_error()); //find a way to sort by propertyid
        $property = getSettings();
        echo '<table class="treport" ><thead>
<tr><td colspan="8"><center><span style="font-size:15px;font-weight:normal;float:left;"> <b>INVOICE LIST -' . $accname . '</b></span><span style="font-size:18px;font-weight:bold">' . $property['company_name'] . '</span><span style="font-size:18px;font-weight:normal; float:right;">' . findpropertybyid($propid) . '</span><center>
<br/><span style="font-size:16px;font-weight:normal;"><center>' . str_repeat('&nbsp;', 25) . 'Invoice List From <b> ' . date("d-m-Y", strtotime($startdate)) . '</b>  To  <b>' . date("d-m-Y", strtotime($enddate)) . '</b></center></span></td></tr>';
        echo'<tr>
<u><th>S/no</th> <th>Invoice/Credit No</th><th>Date</th> <th>House No</th><th>Tenant/Other Name</th> <th>Amount</th><th>Vat(' . round($tenantVAT * 100) . '%)</th><th>Total Amount</th></u></tr></thead>';
        echo '<tbody>';
        $i = 1;
        while ($row = $db->fetch_array($query)) {
if($row['revsd'] == 1){ echo '<tr><td>' . $i++ . '</td><td><a href="defaultreports.php?report=printhillsinvoice&invoiceno=' . $row['invoiceno'] . '" target="blank">' . $row['invoiceno'] . '</a></td><td>' . date('F j,Y', strtotime($row['invoicedate'])) . '</td><td>' . $row['Apartment_tag'] . '</td><td>' . $row['tenant_name'] . '</td><td style="background-color:#FF0000">' . number_format($row['amount'], 2) . '</td><td>' . number_format($tenantVAT * $row['amount'], 2) . '</td><td>' . number_format($row['amount'] + ($tenantVAT * $row['amount']), 2) . '</td></tr>';} else{
            echo '<tr><td>' . $i++ . '</td><td><a href="defaultreports.php?report=printhillsinvoice&invoiceno=' . $row['invoiceno'] . '" target="blank">' . $row['invoiceno'] . '</a></td><td>' . date('F j,Y', strtotime($row['invoicedate'])) . '</td><td>' . $row['Apartment_tag'] . '</td><td>' . $row['tenant_name'] . '</td><td>' . number_format($row['amount'], 2) . '</td><td>' . number_format($tenantVAT * $row['amount'], 2) . '</td><td>' . number_format($row['amount'] + ($tenantVAT * $row['amount']), 2) . '</td></tr>';}
            $sumamount[] = $row['amount'];
            $sumvatamount[] = ($tenantVAT * $row['amount']);
            $sumtotalamount[] = ($row['amount'] + ($tenantVAT * $row['amount']));
        }
        echo '</tbody>';
        echo '<tfoot><tr><td><b>GRAND TOTAL</b></td><td></td><td></td><td></td><td></td><td><b>' . number_format(@array_sum($sumamount), 2) . '</b></td><td><b>' . number_format(@array_sum($sumvatamount), 2) . '</b></td><td><b>' . number_format(@array_sum($sumtotalamount), 2) . '</b></td></tr>';
        echo '</tfoot></table>';
        echo '<hr/>';
        echo '<i>Printed by:</i> ' . $user . '&nbsp;&nbsp;&nbsp;&nbsp;' . date("d-m-y") . '&nbsp;' . $time;
    }
}

function getinvoicelistChargeables($startdate, $enddate, $accid, $accname, $propid, $user) {
    date_default_timezone_set('Africa/Nairobi');
    $time = date('h:i A');
    $invoicedetail = array();
    $allinvoicedetails = array();

    $mysqli = getMysqliConnection();
    $invoicetable = "invoices";
    $tablename2 = "tenants";
    $tablename3 = "vatamounts";
    if ($accid == "0") {
        $accid = '%';
    } else {
        $accid = $accid;
    }
    $q2 = $mysqli->query("SELECT amount from $tablename3 WHERE vatid='1'") or die($mysqli->error);
    while ($row1 = mysql_fetch_array($q2)) {
        $tenantVAT = $row1['amount'];
    }
    //get invoices unreversed invoices for a property excluding the penalty invoices
    $resultset = $mysqli->query("SELECT $invoicetable.invoiceno,$invoicetable.idno,$invoicetable.invoicedate,$invoicetable.amount,$tablename2.Id,$tablename2.tenant_name,$tablename2.Apartment_tag,$tablename2.property_name FROM $invoicetable LEFT JOIN $tablename2 ON $invoicetable.idno=$tablename2.Id WHERE $invoicetable.invoicedate BETWEEN '$startdate' AND '$enddate' AND $invoicetable.is_penalty=0 AND $invoicetable.revsd=0 AND $invoicetable.property_id='$propid' ORDER BY $tablename2.Apartment_tag ASC ") or die($mysqli->error); //find a way to sort by propertyid
    while ($row = $resultset->fetch_assoc()) {
        $invoicedetail['invoiceno'] = $row['invoiceno'];
        $invoicedetail['apartment'] = $row['Apartment_tag'];
        $invoicedetail['name'] = $row['tenant_name'];
        $invoicedetail['id'] = $row['idno'];
        $invoicedetail['chargeables'] = getInvoiceItemsAndValue($row['invoiceno']);
        array_push($allinvoicedetails, $invoicedetail);
    }
    return $allinvoicedetails;
}

function getreceiptlistChargeables($startdate, $enddate, $accid, $accname, $propid, $user) {
    date_default_timezone_set('Africa/Nairobi');
    $time = date('h:i A');
    $invoicedetail = array();
    $allinvoicedetails = array();

    $mysqli = getMysqliConnection();
    $recptable = "recptrans";
    $tablename2 = "tenants";
    $tablename3 = "vatamounts";
    if ($accid == "0") {
        $accid = '%';
    } else {
        $accid = $accid;
    }
    $q2 = $mysqli->query("SELECT amount from $tablename3 WHERE vatid='1'") or die($mysqli->error);
    while ($row1 = mysql_fetch_array($q2)) {
        $tenantVAT = $row1['amount'];
    }
    //get invoices unreversed invoices for a property excluding the penalty invoices
    $resultset = $mysqli->query("SELECT $recptable.recpno,$recptable.idno,$recptable.invoicenopaid,$recptable.rdate,$recptable.amount,$tablename2.Id,$tablename2.tenant_name,$tablename2.Apartment_tag,$tablename2.property_name FROM $recptable LEFT JOIN $tablename2 ON $recptable.idno=$tablename2.Id WHERE $recptable.rdate BETWEEN '$startdate' AND '$enddate' AND $recptable.revsd=0 AND $recptable.property_id='$propid' ORDER BY $tablename2.Apartment_tag ASC ") or die($mysqli->error); //find a way to sort by propertyid
    while ($row = $resultset->fetch_assoc()) {
        $invoicedetail['invoiceno'] = $row['invoicenopaid'];
        $invoicedetail['apartment'] = $row['Apartment_tag'];
        $invoicedetail['name'] = $row['tenant_name'];
        $invoicedetail['id'] = $row['idno'];
        $invoicedetail['chargeables'] = getInvoiceItemsAndValue($row['invoicenopaid']);

        array_push($allinvoicedetails, $invoicedetail);
    }
    return $allinvoicedetails;
}

//get receipts for tenant
function getreceiptlistTenant($startdate, $enddate, $accid, $accname, $propid, $tenantid) {
    date_default_timezone_set('Africa/Nairobi');
    $time = date('h:i A');
    $invoicedetail = array();
    $allinvoicedetails = array();

    $mysqli = getMysqliConnection();
    $recptable = "recptrans";
    $tablename2 = "tenants";
    $tablename3 = "vatamounts";
    if ($accid == "0") {
        $accid = '%';
    } else {
        $accid = $accid;
    }
//    $q2 = $mysqli->query("SELECT amount from $tablename3 WHERE vatid=1") or die($mysqli->error);
//    while ($row1 = mysql_fetch_array($q2)) {
//        $tenantVAT = $row1['amount'];
//    }
    //get invoices unreversed invoices for a property excluding the penalty invoices


    $resultset = $mysqli->query("SELECT $recptable.recpno,$recptable.idno,$recptable.invoicenopaid,$recptable.rdate,$recptable.amount,$tablename2.Id,$tablename2.tenant_name,$tablename2.Apartment_tag,$tablename2.property_name FROM $recptable LEFT JOIN $tablename2 ON $recptable.idno=$tablename2.Id WHERE $recptable.rdate BETWEEN '$startdate' AND '$enddate' AND $recptable.revsd=0 AND $recptable.property_id='$propid' AND $recptable.idno='$tenantid' ORDER BY $tablename2.Apartment_tag ASC ") or die($mysqli->error); //find a way to sort by propertyid
    //die("SELECT $recptable.recpno,$recptable.idno,$recptable.invoicenopaid,$recptable.rdate,$recptable.amount,$tablename2.Id,$tablename2.tenant_name,$tablename2.Apartment_tag,$tablename2.property_name FROM $recptable LEFT JOIN $tablename2 ON $recptable.idno=$tablename2.Id WHERE $recptable.rdate BETWEEN '$startdate' AND '$enddate' AND $recptable.revsd=0 AND $recptable.property_id='$propid' AND $recptable.idno='$tenantid' ORDER BY $tablename2.Apartment_tag ASC" );
    while ($row = $resultset->fetch_assoc()) {
        $invoicedetail["receiptpaidamount"]=$row["amount"];
         $invoicedetail['recpno'] = $row['recpno'];
        $invoicedetail['invoiceno'] = $row['invoicenopaid'];
        $invoicedetail['apartment'] = $row['Apartment_tag'];
        $invoicedetail['name'] = $row['tenant_name'];
        $invoicedetail['id'] = $row['idno'];
        //echo $row['invoicenopaid']."<br>";
        $invoicedetail['chargeables'] = getInvoiceItemsAndValue($row['invoicenopaid']);

        array_push($allinvoicedetails, $invoicedetail);
    }
    return $allinvoicedetails;
}

function incrementnumber($column) {
    $mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME, 3306);
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") ";
    }
    $tablename = "refnos";
    $q = $mysqli->query("SELECT $column from $tablename") or die($mysqli->error);

    while ($row = mysqli_fetch_array($q)) {
        $result = $row[$column];
    }
    
    $result = $result + 1;
    $q1 = $mysqli->query("UPDATE $tablename set `$column`='$result'") or die($mysqli->error);
    $q2 = $mysqli->query("SELECT $column from $tablename") or die($mysqli->error);
    while ($row1 = mysqli_fetch_array($q2)) {
        $result = $row1[$column];
    }
    return $result;
}

function addclient($name, $address, $email, $city, $clientphone, $usr) {
    $mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME, 3306);
    $tablename = "clientlist";
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") ";
    }
    $res = $mysqli->query("INSERT into $tablename(`clientname`,`address`,`email`,`city`,`phone`,`usr`) VALUES ('$name','$address','$email','$city','$clientphone','$usr')") or die($mysqli->error);
    header('Content-Type: application/json');
    $response_array['status'] = 'Client <u>' . $name . '</u> created!';
    echo json_encode($response_array);
    mysqli_close($mysqli);
}

function editclient($id, $name, $address, $email, $city, $clientphone) {
    $mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME, 3306);
    $tablename = "clientlist";
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") ";
    }
    $res = $mysqli->query("UPDATE $tablename SET `clientname`='$name',`address`='$address',`email`='$email',`city`='$city',`phone`='$clientphone' WHERE id='$id'") or die($mysqli->error);
    header('Content-Type: application/json');
    $response_array['status'] = 'Client <u>' . $name . '</u> saved!';
    echo json_encode($response_array);
    mysqli_close($mysqli);
}

function populateclients() {
    $db = new MySQLDatabase();
    $tablename = "clientlist";
    $db->open_connection();
    $sql = $db->query("SELECT id FROM $tablename") or die(mysql_error());
    $results = array();
    while ($row = mysql_fetch_array($sql)) {
        $results[] = $row['id'];
    }
    $json = json_encode($results);
    echo $json;

    $db->close_connection();
}

function fetchallclients() {
    $mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME, 3306);
    $tablename = "clientlist";
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") ";
    }
    $res = $mysqli->query("SELECT  id,clientname FROM $tablename ORDER BY clientname ASC") or die($mysqli->error);
    while ($row = $res->fetch_assoc()) {
        echo "<option value=" . $row['id'] . ">" . $row['clientname'] . "</option>";
    }
    mysqli_close($mysqli);
}

function fetchclientdetails($id) {
    $mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME, 3306);
    $tablename = "clientlist";
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") ";
    }
    $res = $mysqli->query("SELECT  * FROM $tablename WHERE id='$id'") or die($mysqli->error);

    while ($row = $res->fetch_assoc()) {
        $address = $row['address'];
        $email = $row['email'];
        $city = $row['city'];
        $phone = $row['phone'];
    }
    header('Content-Type: application/json');
    $response_array['address'] = $address;
    $response_array['email'] = $email;
    $response_array['phone'] = $phone;
    $response_array['city'] = $city;
    echo json_encode($response_array);
    mysqli_close($mysqli);
}

//suppliers
function fetchsupplierdetails($id) {
    $mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME, 3306);
    $tablename = getSupplierListTable();
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") ";
    }
    $res = $mysqli->query("SELECT  * FROM $tablename WHERE sup_id='$id'") or die($mysqli->error);

    while ($row = $res->fetch_assoc()) {
        $items = $row['sup_items'];
        $address = $row['address'];
        $email = $row['email'];
        $city = $row['city'];
        $phone = $row['phone'];
    }
    header('Content-Type: application/json');
    $response_array['items'] = $items;
    $response_array['address'] = $address;
    $response_array['email'] = $email;
    $response_array['phone'] = $phone;
    $response_array['city'] = $city;
    echo json_encode($response_array);
    mysqli_close($mysqli);
}

function fetchallsuppliers($propid) {
    $mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME, 3306);
    $tablename = getSupplierListTable();
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") ";
    }
    $res = $mysqli->query("SELECT sup_id,suppliername FROM $tablename WHERE property_id='$propid' ORDER BY suppliername ASC") or die($mysqli->error);
    while ($row = $res->fetch_assoc()) {
        echo "<option value=" . $row['sup_id'] . ">" . $row['suppliername'] . "</option>";
    }
    mysqli_close($mysqli);
}

function addsupplier($name, $items, $address, $email, $city, $clientphone, $usr, $propid) {
    $mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME, 3306);
    $tablename = getSupplierListTable();
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") ";
    }
    $res = $mysqli->query("INSERT into $tablename(`suppliername`,`sup_items`,`address`,`email`,`city`,`phone`,`usr`,`property_id`) VALUES ('$name','$items','$address','$email','$city','$clientphone','$usr','$propid')") or die($mysqli->error);
    header('Content-Type: application/json');
    $response_array['status'] = 'Supplier <u>' . $name . '</u> created!';
    echo json_encode($response_array);
    mysqli_close($mysqli);
}

//edit supplier
function editsupplier($id, $name, $items, $address, $email, $city, $clientphone) {
    $mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME, 3306);
    $tablename = getSupplierListTable();
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") ";
    }
    $res = $mysqli->query("UPDATE $tablename SET `suppliername`='$name',`sup_items`='$items',`address`='$address',`email`='$email',`city`='$city',`phone`='$clientphone' WHERE sup_id='$id'") or die($mysqli->error);
    header('Content-Type: application/json');
    $response_array['status'] = 'Supplier <u>' . $name . '</u> saved!';
    echo json_encode($response_array);
    mysqli_close($mysqli);
}

//convert number towords
function convert_number_to_words($number) {

    $hyphen = '-';
    $conjunction = ' and ';
    $separator = ', ';
    $negative = 'negative ';
    $decimal = ' point ';
    $dictionary = array(
        0 => 'zero',
        1 => 'one',
        2 => 'two',
        3 => 'three',
        4 => 'four',
        5 => 'five',
        6 => 'six',
        7 => 'seven',
        8 => 'eight',
        9 => 'nine',
        10 => 'ten',
        11 => 'eleven',
        12 => 'twelve',
        13 => 'thirteen',
        14 => 'fourteen',
        15 => 'fifteen',
        16 => 'sixteen',
        17 => 'seventeen',
        18 => 'eighteen',
        19 => 'nineteen',
        20 => 'twenty',
        30 => 'thirty',
        40 => 'fourty',
        50 => 'fifty',
        60 => 'sixty',
        70 => 'seventy',
        80 => 'eighty',
        90 => 'ninety',
        100 => 'hundred',
        1000 => 'thousand',
        1000000 => 'million',
        1000000000 => 'billion',
        1000000000000 => 'trillion',
        1000000000000000 => 'quadrillion',
        1000000000000000000 => 'quintillion'
    );

    if (!is_numeric($number)) {
        return false;
    }

    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
        // overflow
        trigger_error(
                'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX, E_USER_WARNING
        );
        return false;
    }

    if ($number < 0) {
        return $negative . convert_number_to_words(abs($number));
    }

    $string = $fraction = null;

    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }

    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens = ((int) ($number / 10)) * 10;
            $units = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }
            break;
        case $number < 1000:
            $hundreds = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . convert_number_to_words($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= convert_number_to_words($remainder);
            }
            break;
    }

    if (null !== $fraction && is_numeric($fraction)) {
        $string .= $decimal;
        $words = array();
        foreach (str_split((string) $fraction) as $number) {
            $words[] = $dictionary[$number];
        }
        $string .= implode(' ', $words);
    }

    return $string;
}
function storeData($sql){
    $mysqli = getMysqliConnection();
  
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") ";
    }
    $res = $mysqli->query($sql) or die($mysqli->error);
    return $res;
    
}
function queryResults($table){
    $mysqli = getMysqliConnection();
  
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") ";
    }
    $res = $mysqli->query("SELECT * From $table ") or die($mysqli->error);
    $rowcount = mysqli_num_rows($res);
    $data=array();
    while($row=mysqli_fetch_assoc($res)){
        $data[]=$row;
    }
    return  json_decode (json_encode (array("count"=>$rowcount,"data"=>$data)), FALSE);

}
function ApproveInvoice($id){
    $mysqli = getMysqliConnection();

    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") ";
    }
    $res = $mysqli->query("Update  invoice_dummy set status=1 where id=$id") or die($mysqli->error);

}
function ApproveReceipt($id){
    $mysqli = getMysqliConnection();
  
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") ";
    }
    $res = $mysqli->query("Update  recept_dummy set status=1 where id=$id") or die($mysqli->error);
   
}
function getTempInvoicesById($id)
{
    $mysqli = getMysqliConnection();

    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") ";
    }
    $res = $mysqli->query("SELECT * From invoice_dummy where status=0 and id=$id") or die($mysqli->error);
    $rowcount = mysqli_num_rows($res);
    $row = mysqli_fetch_assoc($res);

    return  json_decode(json_encode(array("count" => $rowcount, "data" => $row['invoice'])), FALSE);
}
function getTempInvoices()
{
    $mysqli = getMysqliConnection();

    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") ";
    }
    $res = $mysqli->query("SELECT * From invoice_dummy where status=0") or die($mysqli->error);
    $rowcount = mysqli_num_rows($res);
    $data = array();
    while ($row = mysqli_fetch_assoc($res)) {
        $data[] = $row;
    }
    return  json_decode(json_encode(array("count" => $rowcount, "data" => $data)), FALSE);
}
function getTempReceiptsById($id){
    $mysqli = getMysqliConnection();
  
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") ";
    }
    $res = $mysqli->query("SELECT * From recept_dummy where status=0 and id=$id") or die($mysqli->error);
    $rowcount = mysqli_num_rows($res);
    $row=mysqli_fetch_assoc($res);
    
    return  json_decode (json_encode (array("count"=>$rowcount,"data"=>$row['receipt'])), FALSE);

}
function getTempReceipts(){
    $mysqli = getMysqliConnection();
  
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") ";
    }
    $res = $mysqli->query("SELECT * From recept_dummy where status=0") or die($mysqli->error);
    $rowcount = mysqli_num_rows($res);
    $data=array();
    while($row=mysqli_fetch_assoc($res)){
        $data[]=$row;
    }
    return  json_decode (json_encode (array("count"=>$rowcount,"data"=>$data)), FALSE);

}
function  create_temp_receipt($data){
    $mysqli = getMysqliConnection();
    // $tablename = "invoices";
    $data1=json_encode($data);
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") ";
    }
    //die($data1);
    $stmt = $mysqli->prepare("Insert into recept_dummy (`receipt`) values(?)");
    $stmt->bind_param("s", $receipt);
        // set parameters and execute
        $receipt = $data1;
       
     
     if( $stmt->execute()){
         return "success";
     }
     else{
         return "failed";
     }
    
    return ;
}
function  create_temp_invoice($data)
{
    $mysqli = getMysqliConnection();
    // $tablename = "invoices";
    $data1 = json_encode($data);
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") ";
    }
    //die($data1);
    $stmt = $mysqli->prepare("Insert into invoice_dummy (`invoice`) values(?)");
    $stmt->bind_param("s", $receipt);
    // set parameters and execute
    $receipt = $data1;


    if ($stmt->execute()) {
        return "success";
    } else {
        return "failed";
    }

    return;
}
//receipts
function fetchinvoicedetailsPlain($tenantid) {
    $mysqli = getMysqliConnection();
    $tablename = "invoices";
    $tablename1 = "tenants";
    $invoicenos = array();
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") ";
    }
    $res = $mysqli->query("SELECT $tablename.*,$tablename1.tenant_name FROM $tablename  LEFT JOIN $tablename1 ON $tablename.idno=$tablename1.Id WHERE  $tablename.revsd=0 AND $tablename.idno like '$tenantid' AND ($tablename.amount - $tablename.paidamount)>0 order by $tablename.invoicedate") or die($mysqli->error);
    $rowcount = mysqli_num_rows($res);
    $data=array();
    while($row=$res->fetch_assoc()){
        $data[]=$row; 
    }
    return $data;
}
function fetchinvoicedetails($tenantid) {
    $mysqli = getMysqliConnection();
    $tablename = "invoices";
    $tablename1 = "tenants";
    $invoicenos = array();
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") ";
    }
    $res = $mysqli->query("SELECT $tablename.*,$tablename1.tenant_name FROM $tablename  LEFT JOIN $tablename1 ON $tablename.idno=$tablename1.Id WHERE  $tablename.revsd=0 AND $tablename.idno like '$tenantid' AND ($tablename.amount - $tablename.paidamount)>0 order by $tablename.invoicedate") or die($mysqli->error);
    $rowcount = mysqli_num_rows($res);
    echo '<table class="treport" class="receipting"><thead>
<tr>
 <th><center><u>No</u></center></th>
<th><center><u>Invoice No</u></center></th>
<th><center><u>Invoice Date</u></center></th>
<th><center><u>Invoiced Amount</u></center></th>
<th><center>Paid Amount</center></th>
<th><center>Balance</center></th>
<th><center>Part/Over pay</center></th>
<th><center>Full Payment</center></th> </tr><thead>
<tbody>';
    $i = 1;
    while ($row = $res->fetch_assoc()) {
        $id = $row['tno'];
        $amount = $row['amount'];
        $pdamount = $row['paidamount'];
        $name = $row['tenant_name'];
        $invoiceno = $row['invoiceno'];
        $invoicedate = $row['invoicedate'];
        $bal = ($amount - $pdamount);
        $topay = '<input type="text" id="payamount' . $id . '" title="' . $invoiceno . '" style="width:100px; height:15px;"/>';
        $checkpay = '<input type="checkbox" id="clientcheck' . $id . '" id="client" class="' . $bal . '" title="' . $id . '" style="text-align:central;">';
        echo '<tr><td>' . $i++ . '</td><td id="invoicenotd">' . $invoiceno . '</td><td id="invoicedatetd">' . $invoicedate . '</td><td><input id="clientinvoicedamount' . $id . '" value="' . $amount . '" style="width:100px; height:15px;" readonly/>' . '</td><td id="paidamount' . $id . '">' . $pdamount . '</td><td id="balancetd">' . ($amount - $pdamount) . '</td><td id="topay">' . $topay . '</td><td id="checkpay">' . $checkpay . '</td></tr>';
        array_push($invoicenos, $id . '&');
    }
    $invoiceno_string = implode($invoicenos);
    echo '<input type="hidden" id="invoicenos" value="' . $invoiceno_string . '"/>';
    echo '</tbody>';
    echo '<tfoot><tr><td></td><td></td><td></td><td></td><td><br>&nbsp;&nbsp;&nbsp;<input type="submit" id="btnreceipt"  class="text ui-widget-content ui-corner-aEll" value="RECEIVE PAYMENT" style="width:200px;font-weight:bold;"/></td><td></td><td></td><td></td></tr></tfoot>';
    echo '</table>';
    mysqli_close($mysqli);
}

function fetchpendinginvoicedetails($tenantid) {
    $mysqli = getMysqliConnection();
    $tablename = "invoices";
    $tablename1 = "tenants";
    $invoicenos = array();
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") ";
    }
    $res = $mysqli->query("SELECT $tablename.*,$tablename1.tenant_name FROM $tablename  LEFT JOIN $tablename1 ON $tablename.idno=$tablename1.Id WHERE  $tablename.revsd=0 AND $tablename.idno like '$tenantid' AND ($tablename.amount - $tablename.paidamount)>0") or die($mysqli->error);
    $rowcount = mysqli_num_rows($res);
    echo '<table class="treport" class="receipting"><thead>
<tr>
 <th><center><u>No</u></center></th>
<th><center><u>Invoice No</u></center></th>
<th><center><u>Invoice Date</u></center></th>
<th><center><u>Invoiced Amount</u></center></th>
<th><center>Paid Amount</center></th>
<th><center>Balance</center></th>

<tbody>';
    $i = 1;
    while ($row = $res->fetch_assoc()) {
        $id = $row['tno'];
        $amount = $row['amount'];
        $pdamount = $row['paidamount'];
        $name = $row['tenant_name'];
        $invoiceno = $row['invoiceno'];
        $invoicedate = $row['invoicedate'];
        $bal = ($amount - $pdamount);
        $topay = '<input type="text" id="payamount' . $id . '" title="' . $invoiceno . '" style="width:100px; height:15px;"/>';
        $checkpay = '<input type="checkbox" id="clientcheck' . $id . '" id="client" class="' . $bal . '" title="' . $id . '" style="text-align:central;">';
        echo '<tr><td>' . $i++ . '</td><td id="invoicenotd">' . $invoiceno . '</td><td id="invoicedatetd">' . $invoicedate . '</td><td><input id="clientinvoicedamount' . $id . '" value="' . $amount . '" style="width:100px; height:15px;" readonly/>' . '</td><td id="paidamount' . $id . '">' . $pdamount . '</td><td id="balancetd">' . ($amount - $pdamount) . '</td></tr>';
        array_push($invoicenos, $id . '&');
    }
    $invoiceno_string = implode($invoicenos);
    echo '<input type="hidden" id="invoicenos" value="' . $invoiceno_string . '"/>';
    echo '</tbody>';

    echo '</table>';
    mysqli_close($mysqli);
}

function update_invoice($invoiceno, $recpamount, $idno, $receiptdate, $paymode, $cashaccount, $bankaccount, $chequedate, $chequeno, $chequedetails, $remarks, $paidby, $user, $counter, $propid, $penalty, $penaltygl, $fperiod, $bankdeposit,$reference) {
    $db = new MySQLDatabase();
    $db->open_connection();
    $tablename = "invoices";
    $resultset = $db->query("SELECT paidamount FROM $tablename WHERE invoiceno='$invoiceno'");
    while ($row = $db->fetch_array($resultset)) {
        $paid = $row['paidamount'];
    }
    $paidamount = $recpamount + $paid;
    $db->query("UPDATE $tablename SET `paidamount`='$paidamount' WHERE invoiceno='$invoiceno'") or die(mysql_error());
    create_receipt($invoiceno, $idno, $receiptdate, $paymode, $recpamount, $cashaccount, $bankaccount, $chequedate, $chequeno, $chequedetails, $remarks, $paidby, $user, $propid, $penalty, $penaltygl, $fperiod, $bankdeposit,$reference);

}

//create a receipt
function create_receipt($invoiceno, $idno, $receiptdate, $paymode, $recpamount, $cashaccount, $bankaccount, $chequedate, $chequeno, $chequedetails, $remarks, $paidby, $user, $propid, $penalty, $penaltygl, $fperiod, $bankdeposit = 0,$reference) {
    $db = new MySQLDatabase();
    date_default_timezone_set('Africa/nairobi');
    $db->open_connection();
    if (!empty($chequedate)) {
        $chqdate = strtotime($chequedate);
        $chequedate = date('Y-m-d', $chqdate);
    }
    $myDateTime = DateTime::createFromFormat('d/m/Y', trim($receiptdate));
    $receiptdate = $myDateTime->format('Y-m-d');
    $currentdate = date('Y-m-d h:i:s');
    $tablename = "recptrans";
    $recpno = incrementnumber("recpno");

    $invoiceitems = getInvoiceItemsAndValue($invoiceno);
    $aptid = getApartmentFromTenant($idno);

    //if item is deposit
    foreach ($invoiceitems as $item) {
        $invoiceitemdetails = getChargeItemByName($item['name'], $propid);
        if ($invoiceitemdetails['is_deposit'] == 1) {
            $query = "INSERT into $tablename(`rdate`,`amount`,`pmode`,`recpno`,`chqdet`,`chqno`,`chequedate`,`rmks`,`idno`,`invoicenopaid`,`cashacc`,`bankacc`,`paidby`,`us`,`isdeposited`,`property_id`,`idclose_periods`,`is_deposit`,`reference`,`ts`) VALUES ('$receiptdate','$recpamount','$paymode','$recpno','$chequedetails','$chequeno','$chequedate','$remarks','$idno','$invoiceno','$cashaccount','$bankaccount','$paidby','$user','0','$propid','$fperiod','D','$reference','$currentdate')";
            $db->query($query) or die($db->error());
            // credit entry for apartment gl
            $glaccount = getGLCodeForAccount(array('gl' => 'HouseGL', 'apt_id' => $aptid));
            $glcode1 = $glaccount['glcode'];
            $entry = createJournalEntry(array('glcode' => $glcode1, 'document_ref' => $recpno, 'credit' => $recpamount, 'ttype' => 'RECP', 'property_id' => $propid, 'desc' => $remarks, 'idclose_period' => $fperiod));

            //debit entry for agent bank
            $glaccountal = getGLCodeForAccount(array('gl' => 'AgentBank'));
            $glcode2 = $glaccountal['glcode'];
            $entry = createJournalEntry(array('glcode' => $glcode2, 'document_ref' => $recpno, 'debit' => $recpamount, 'ttype' => 'RECP', 'property_id' => $propid, 'desc' => $remarks, 'idclose_period' => $fperiod));
            //debit entry for deposit
            $glaccountal1 = getGLCodeForAccount(array('gl' => 'AgentLiability'));
            $glcode3 = $glaccountal1['glcode'];
            $entry = createJournalEntry(array('glcode' => $glcode3, 'document_ref' => $recpno, 'credit' => $recpamount, 'ttype' => 'RECP', 'property_id' => $propid, 'desc' => $remarks, 'idclose_period' => $fperiod));

            //bank amount
            if ($paymode == 0) {
                $data = array("recpno" => $recpno, "amount" => $recpamount, "date" => date("Y-m-d H:i:s", strtotime($receiptdate)), "bank_type" => "UC", "is_credit" => 1, "is_debit" => 0, "narration" => $remarks);
                saveUndepositedCash($data);
            }
            //bank deposits
            else if ($paymode == 3) {
                $bank = getBankDetails($bankdeposit);
                $data = array("recpno" => $recpno, "amount" => $recpamount, "date" => date("Y-m-d H:i:s", strtotime($receiptdate)), "bank_type" => $bank["bank_code"], "is_credit" => 1, "is_debit" => 0, "narration" => $remarks);
                saveUndepositedCash($data);
            }


            header('Content-Type: application/json');
            $response_array['status'] = $recpno;
            echo json_encode($response_array);
            return;
        }
    }
    $query = "INSERT into $tablename(`rdate`,`amount`,`pmode`,`recpno`,`chqdet`,`chqno`,`chequedate`,`rmks`,`idno`,`invoicenopaid`,`cashacc`,`bankacc`,`paidby`,`us`,`isdeposited`,`property_id`,`idclose_periods`,`reference`,`ts`) VALUES ('$receiptdate','$recpamount','$paymode','$recpno','$chequedetails','$chequeno','$chequedate','$remarks','$idno','$invoiceno','$cashaccount','$bankaccount','$paidby','$user','0','$propid','$fperiod','$reference','$currentdate')";

    if (!$db->query($query)) {
        echo $db->error();
    } else {

        // credit entry for apartment gl
        $glaccount = getGLCodeForAccount(array('gl' => 'HouseGL', 'apt_id' => $aptid));
        $glcode4 = $glaccount['glcode'];
        $entry = createJournalEntry(array('glcode' => $glcode4, 'document_ref' => $recpno, 'credit' => $recpamount, 'ttype' => 'RECP', 'property_id' => $propid, 'desc' => $remarks, 'idclose_period' => $fperiod));

        //debit entry for agent bank
        //if not penalty calculate commission
        if (!$penalty) {
            $commission = getPropertyCommissionRate($propid);
            $creditamount = round((($commission * $recpamount) / 100), 2);
            $glaccountal = getGLCodeForAccount(array('gl' => 'AgentBank'));
            $glcode5 = $glaccountal['glcode'];
            $entry = createJournalEntry(array('glcode' => $glcode5, 'document_ref' => $recpno, 'debit' => $recpamount, 'ttype' => 'RECP', 'property_id' => $propid, 'desc' => $remarks, 'idclose_period' => $fperiod));
            //debit entry for bank
        }



        header('Content-Type: application/json');
        //check if invoice is penalty and penalty option is checked,if not calculate a penalty
        $invoicedetail = getInvoiceDetails($invoiceno);
        if ($invoicedetail['is_penalty'] == 0 && $penalty) {
            //create penalties if any
            $penaltyamount = calculatePenalty(array('tenantid' => $idno, 'rdate' => $receiptdate, 'amount' => $invoicedetail['amount'], 'recpno' => $recpno, 'property_id' => $propid, 'penalty_gl' => $penaltygl, 'idclose_period' => $fperiod));
        }
        //process cash deposits
        if ($paymode == 0) {
            $data = array("recpno" => $recpno, "amount" => $recpamount, "date" => date("Y-m-d H:i:s", strtotime($receiptdate)), "bank_type" => "UC", "is_credit" => 1, "is_debit" => 0, "narration" => $remarks);
            saveUndepositedCash($data);
        }
        //bank deposits
        else if ($paymode == 3) {
            $bank = getBankDetails($bankdeposit);
            $data = array("recpno" => $recpno, "amount" => $recpamount, "date" => date("Y-m-d H:i:s", strtotime($receiptdate)), "bank_type" => $bank["bank_code"], "is_credit" => 1, "is_debit" => 0, "narration" => $remarks);
            saveUndepositedCash($data);
        }
        $response_array['status'] = $recpno;
        echo json_encode($response_array);
    }
}

//other receipt
function create_other_receipt($customer, $receiptdate, $paymode, $recpamount, $cashaccount, $bankaccount, $chequedate, $chequeno, $chequedetails, $remarks, $paidby, $user, $propid, $penalty, $penaltygl, $fperiod, $bank, $response = 1,$reference) {
   
    $db = new MySQLDatabase();
    date_default_timezone_set('Africa/nairobi');
    $db->open_connection();
    if (!empty($chequedate)) {
        $chqdate = strtotime($chequedate);
        $chequedate = date('Y-m-d', $chqdate);
    }
    $myDateTime = DateTime::createFromFormat('d/m/Y', trim($receiptdate));
    $receiptdate = $myDateTime->format('Y-m-d');

    $tablename = "otherrecptrans";
    $recpno = incrementnumber("recpno");

    $aptid = getApartmentFromTenant($idno);

  
    //if item is deposit
    $glaccountal = getGLCodeForAccount(array('gl' => 'AgentBank'));
    $glcode2 = $glaccountal['glcode'];
    $entry = createJournalEntry(array('glcode' => $glcode2, 'document_ref' => $recpno, 'debit' => $recpamount, 'ttype' => 'RECP', 'property_id' => $propid, 'desc' => $remarks, 'idclose_period' => $fperiod));
    $query = "INSERT into $tablename(`rdate`,`amount`,`pmode`,`recpno`,`chqdet`,`chqno`,`chequedate`,`rmks`,`customer`,`invoicenopaid`,`cashacc`,`bankacc`,`paidby`,`us`,`isdeposited`,`property_id`,`idclose_periods`,`bank`,`reference`) VALUES ('$receiptdate','$recpamount','$paymode','$recpno','$chequedetails','$chequeno','$chequedate','$remarks','$customer','0','$cashaccount','$bankaccount','$paidby','$user','0','$propid','$fperiod','$bank','$reference')";

    if (!$db->query($query)) {
        echo $db->error();
    } else {

        // credit entry for apartment gl
        $glaccount = getGLCodeForAccount(array('gl' => 'HouseGL', 'apt_id' => $aptid));
        $glcode4 = $glaccount['glcode'];
        $entry = createJournalEntry(array('glcode' => $glcode4, 'document_ref' => $recpno, 'credit' => $recpamount, 'ttype' => 'RECP', 'property_id' => $propid, 'desc' => $remarks, 'idclose_period' => $fperiod));

        //debit entry for agent bank
        //if not penalty calculate commission
        if (!$penalty) {
            $commission = getPropertyCommissionRate($propid);
            $creditamount = round((($commission * $recpamount) / 100), 2);
            $glaccountal = getGLCodeForAccount(array('gl' => 'AgentBank'));
            $glcode5 = $glaccountal['glcode'];
            $entry = createJournalEntry(array('glcode' => $glcode5, 'document_ref' => $recpno, 'debit' => $recpamount, 'ttype' => 'RECP', 'property_id' => $propid, 'desc' => $remarks, 'idclose_period' => $fperiod));
            //debit entry for bank
        }

        if ($response > 0) {

            header('Content-Type: application/json');
            //check if invoice is penalty and penalty option is checked,if not calculate a penalty
            $invoicedetail = getInvoiceDetails($invoiceno);
            if ($invoicedetail['is_penalty'] == 0 && $penalty) {
                //create penalties if any
                $penaltyamount = calculatePenalty(array('tenantid' => $idno, 'rdate' => $receiptdate, 'amount' => $invoicedetail['amount'], 'recpno' => $recpno, 'property_id' => $propid, 'penalty_gl' => $penaltygl, 'idclose_period' => $fperiod));
            }
            $response_array['status'] = $recpno;
            echo json_encode($response_array);
        } else {
            return $response_array;
        }
    }
}

//get banks
function getBanks($expenseacct = "") {
    $mysqli = getMysqliConnection();
    $accountdetails = array();
    $accountstable = "banks";
    if ($expenseacct == 1) {
        $res = $mysqli->query("SELECT * FROM {$accountstable} WHERE `expense_acct`=1 ORDER BY bank_name") or die($mysqli->error);
    } else if ($expenseacct == "b") {
        $res = $mysqli->query("SELECT * FROM {$accountstable} WHERE `expense_acct`=0 ORDER BY bank_name") or die($mysqli->error);
    } else {
        $res = $mysqli->query("SELECT * FROM {$accountstable} ORDER BY bank_name") or die($mysqli->error);
    }
    while ($row = $res->fetch_assoc()) {
        array_push($accountdetails, $row);
    }
    $mysqli->close();
    return $accountdetails;
}

//get bank balance
function getBankDetails($id) {
    $mysqli = getMysqliConnection();
    $accountdetails = array();
    $accountstable = "banks";

    $res = $mysqli->query("SELECT * FROM {$accountstable} WHERE `id`='$id' ") or die($mysqli->error);

    while ($row = $res->fetch_assoc()) {
        $result = $row;
    }
    $mysqli->close();
    return $result;
}

//send cash to undeposited 
function saveUndepositedCash($data) {
    $bankstable = "banks";
    $mysqli = getMysqliConnection();
    $tablename = getBankTransactionsTable();
    $recpno = $data['recpno'];
    $recpamount = $data['amount'];
    $date = $data["date"];
    $banktype = $data["bank_type"];
    $iscredit = $data["is_credit"];
    $isdebit = $data["is_debit"];
    $narration = $data["narration"];
    $user = $_SESSION['username'];


    $query = "INSERT into $tablename(`recpno`,`amount`,`date`,`bank_type`,`is_credit`,`is_debit`,`narration`,`user`) "
            . "VALUES ('$recpno','$recpamount','$date','$banktype','$iscredit','$isdebit','$narration','$user')";

    if (!$mysqli->query($query)) {
        echo $mysqli->error;
    } else {
        //update bank balance 
        $result = $mysqli->query("SELECT `total_balance` FROM $bankstable WHERE `bank_code`='$banktype' ") or die($mysqli->error);
        while ($row = $result->fetch_assoc()) {
            $amount = $row['total_balance'];
        }
        $date = date("Y-m-d H:i:s", strtotime($data["date"]));
        $totalbalance = $amount + $recpamount;
        //die("UPDATE $bankstable SET   `total_balance` ='$totalbalance',`balance_as_at`='$date'  WHERE `bank_code`='$banktype'" );
        $balance = $mysqli->query("UPDATE $bankstable SET  `total_balance` ='$totalbalance',`balance_as_at`='$date'  WHERE `bank_code`='$banktype' ") or die($mysqli->error);
    }

    return $balance;
}

//get bank transactions

function getBankTransactions($startdate, $enddate, $id = "") {

    $mysqli = getMysqliConnection();
    $tablename = getBankTransactionsTable();
    $array = array();
    $result = $mysqli->query("SELECT $tablename.*,banks.bank_name FROM $tablename join banks ON $tablename.bank_type=banks.bank_code WHERE  DATE_FORMAT(`date`,'%Y-%m-%d') BETWEEN '$startdate' AND '$enddate' AND banks.id='$id' ") or die($mysqli->error);
    while ($row = $result->fetch_assoc()) {
        array_push($array, $row);
    }
    $mysqli->close();
    return $array;
}

//pay landlord
function payLandlord($params) {
    $propid = $params['property_id'];
    $amount = $params['amount'];
    $fperiod = $params['idclose_periods'];
    $journals = $params['journal_refs'];
   
   // die("dd");
    //debit entry for landlord account on agent side
    $glaccountal = getGLCodeForAccount(array('gl' => 'AgentLandlord', 'property_id' => $propid));
    $glcode1 = $glaccountal['glcode'];
    $remarks = 'Landlord Settlement on account' . $glcode1;
    $payno = incrementnumber("payno");
    $journal = createJournalEntry(array('glcode' => $glcode1, 'document_ref' => $payno, 'debit' => $amount, 'ttype' => 'PAY', 'property_id' => $propid, 'desc' => $remarks, 'idclose_period' => $fperiod));
    updateJournals($journal);
    //credit entry for agent bank with commission amount
    $glaccountal = getGLCodeForAccount(array('gl' => 'AgentBank', 'property_id' => $propid));
    $glcode = $glaccountal['glcode'];
    $entry = createJournalEntry(array('glcode' => $glcode, 'document_ref' => $payno, 'credit' => $amount, 'ttype' => 'PAY', 'property_id' => $propid, 'desc' => $remarks, 'idclose_period' => $fperiod));

    //Debit entry for landlord bank with amount
    $glaccountlb = getGLCodeForAccount(array('gl' => 'LandlordBank', 'property_id' => $propid));
    $glcodelb = $glaccountlb['glcode'];
    $entry = createJournalEntry(array('glcode' => $glcodelb, 'document_ref' => $payno, 'debit' => $amount, 'ttype' => 'PAY', 'property_id' => $propid, 'desc' => $remarks, 'idclose_period' => $fperiod));
    //credit entry for Agent account on Landlord side with amount
    $landlordagent = getGLCodeForAccount(array('gl' => 'LandlordAgent', 'property_id' => $propid));
//       $commission=  getPropertyCommissionRate($propid);
//       $creditamount=((100/(100-$commission))*$amount);
    $landlordagentgl = $landlordagent['glcode'];
    $entry = createJournalEntry(array('glcode' => $landlordagentgl, 'document_ref' => $payno, 'credit' => $amount, 'ttype' => 'PAY', 'property_id' => $propid, 'desc' => $remarks, 'idclose_period' => $fperiod));
    if ($entry) {
        //update journals
        updateJournals($journals);
        return TRUE;
    } else {
        return FALSE;
    }
}

//get floor count

function getFloorCount($propid) {
    $db = new MySQLDatabase();
    $result = $db->query("SELECT floornumber from floorplan WHERE propertyid='$propid' order by floornumber ASC LIMIT 1 ") or die($db->error());
    while ($row = $db->fetch_array($result)) {
        $aptid = $row['floornumber'];
    }
    return $aptid;
}

function UpdateFloorCount($propid) {
    $db = new MySQLDatabase();
    $result = $db->query("SELECT floornumber from floorplan WHERE propertyid='$propid' order by floornumber DESC LIMIT 1 ") or die($db->error());
    while ($row = @$db->fetch_array($result)) {
        $floorcount = $row['floornumber'];
        $result = @$db->query("UPDATE properties SET numfloors ='$floorcount'  WHERE propertyid='$propid' ") or die($db->error());
    }
}

//get tenant apt from id
function getApartmentFromTenant($tenantid) {
    $aptid = 0;
    $tenantstable = getTenantTable();
    $db = new MySQLDatabase();
    $result = $db->query("SELECT apartmentid FROM {$tenantstable} WHERE Id='$tenantid' AND vacated=0 ") or die($db->error());
    while ($row = $db->fetch_array($result)) {
        $aptid = $row['apartmentid'];
    }
    return $aptid;
}

function getTenantDetailsFromRow($tenantid) {
    $tenantstable = getTenantTable();
    $db = new MySQLDatabase();
    $result = $db->query("SELECT * FROM {$tenantstable} WHERE Id='$tenantid' AND vacated=0 ") or die($db->error());
     $row = $db->fetch_array($result);  
    return $row;
}

function getTenantDetailsFromId($tenantid) {
    $tenantstable = getTenantTable();
    $db = new MySQLDatabase();
    $result = $db->query("SELECT * FROM {$tenantstable} WHERE idno='$tenantid' AND vacated=0 ") or die($db->error());
     $row = $db->fetch_array($result);  
    return $row;
}

//tenant table
function getTenantTable() {
    return 'tenants';
}

/* calculate penalty
 * @param array()
 * invoked after receiving of receipt
 */

function calculatePenalty($penaltydetails) {
    $datetime = getDateAndTime();
    $date = new DateTime($datetime["todaysdate"]);
    //get the current month (tested-ok)
    $thismonth = $date->format("m");

    //get month receipt was made (tested-ok)
    $rdate = new DateTime($penaltydetails['rdate']);
    $rmonth = $rdate->format("m");

//get apartment id for tenant (tested-ok)
    $aptid = getApartmentFromTenant($penaltydetails['tenantid']);
//returns array(array()); (tested-ok)
    $aptdetails = getApartmentDetails($aptid); //get apartment details
    //check if date is in  this month
    if ($rmonth == $thismonth) {
        //get the exact receipt day (tested-ok)
        $receiptday = $rdate->format("d");

        $remainingdays = 0;
        //if the receipt date is greater than 8th day/specified day of the month (tested -ok)
        if ($receiptday > $aptdetails[0]['receipt_due']) { //get no of days
            $days = $receiptday - $aptdetails[0]['receipt_due'];
            //tested (ok)
            $rate = getPenaltyRate($days);


            //get remaining days
            //$remainingdays=$receiptday-$days;
        }
    }
    //if the receipt date is past current month
    else if ($rmonth > $thismonth) {

        //get the exact receipt day (tested-ok)
        $receiptday = $rdate->format("d");

        $remainingdays = 0;
        //if the receipt date is greater than 5th day of the month (tested -ok)

        $interval = $rdate->diff($date, true);
        $days = $interval->d;
        //tested (ok)
        $rate = getPenaltyRate($days);
    } else {
        //terminate creation of penalty invoice
        exit();
    }
    //get penalty regimes (tested -ok)

    $amount = ($rate * $penaltydetails['amount']) / 100;

    //dynamic amount 
    $remamount = $amount;
    //static amount
    //$remamount=1000;

    if ($remamount > 0) {
        //create new invoice here
        createPenaltyInvoice(array('tenant_id' => $penaltydetails['tenantid'], 'date' => $datetime["todaysdate"], 'amount' => $remamount, 'billing' => '0', 'property_id' => $penaltydetails['property_id'], 'remarks' => "Penalty For Late Payment on Recpno " . $penaltydetails['recpno'], 'chargename' => 'Penalty', 'chargeamount' => $remamount, 'recpno' => $penaltydetails['recpno']));
        //create credit entry for penalty
        $entry = createJournalEntry(array('glcode' => $penaltydetails['penalty_gl'], 'document_ref' => "PENALTY" . $penaltydetails['tenantid'], 'credit' => $remamount, 'ttype' => 'INVOICE', 'property_id' => $penaltydetails['property_id'], 'desc' => "Penalty For Late Payment on Recpno " . $penaltydetails['recpno'], 'idclose_period' => $penaltydetails['idclose_period']));
    }
}

//check delay btn today and receipt date
//if day>1,fetch penalty
//use propertyid and tenantid to generate penalty invoice,appending "penalty for delayed payment on INVOICENO"
//check if penalty exists
function checkPenalty($days) {
    $penaltiestable = delaypenaltiesTable();
    $db = new MySQLDatabase();
    //select where days fall in between a lower and upper limit
    $resultset = $db->query("SELECT * FROM {$penaltiestable} WHERE lower_limit_days <='$days' AND upper_limit_days >='$days' ") or die($db->error());
    $numrows = $db->num_rows($resultset);
    if ($numrows) {
        return $numrows;
    } else {
        return NULL;
    }
    $db->close_connection();
}

//check property  VAT
function checkPropertyVAT($propid) {
    $mysqli = getMysqliConnection();
    $result = $mysqli->query("SELECT has_vat FROM properties WHERE `propertyid`='$propid'") or die($mysqli->error);
    while ($row = $result->fetch_assoc()) {
        $hasvat = $row['has_vat'];
    }
    return $hasvat;
}

//get vat amounts
function getVAT($vatname) {
    $mysqli = getMysqliConnection();
    $result = $mysqli->query("SELECT amount from vatamounts  WHERE `alias` like '$vatname' ") or die($mysqli->error);
    while ($row = $result->fetch_assoc()) {
        $amount = $row['amount'];
    }
    return $amount;
}

//get penalty rate
function getPenaltyRate($days) {
    include_once './includes/database.php'; //format amount in words
    $penaltiestable = delaypenaltiesTable();
    $db = new MySQLDatabase();
    //select where days fall in between a lower and upper limit
    $resultset = $db->query("SELECT * FROM {$penaltiestable} WHERE lower_limit_days <='$days' AND upper_limit_days >='$days' ") or die($db->error());
    while ($row = $db->fetch_array($resultset)) {
        $penaltyrate = $row['penaltyrate'];
    }
    return 10; //$penaltyrate;
}

function printreceipt2($receiptno, $user) {
    include '../includes/numberformatter.php'; //format amount in words
    $db = new MySQLDatabase();
    date_default_timezone_set('Africa/Nairobi');
    $date = date("d/m/y");
    $time = date('h:i A');
    $db->open_connection();
    $tablename = "recptrans";
    $tablename2 = "tenants";
    $tablename3 = "invoiceitems";
    $invoicetable=  getInvoiceTable();
    $tableitems = [];
    $chargeablesamount=array();
  
    $sql = $db->query("SELECT * FROM $tablename WHERE `recpno` like '$receiptno'") or die($db->error());
    if ($db->num_rows($sql) > 0) {
        while ($row = $db->fetch_array($sql)) {
            $recpno = $row['recpno'];
            $recpdate = $row['rdate'];
            $amount = $row['amount'];
            $chequedate=$row['chequedate'];
            $chequeno=$row['chqno'];
            $cheqdet=$row['chqdet'];
            $idno = $row['idno'];
            $remarks = $row['rmks'];
            $pmode = $row['pmode'];
            $mode=getPayMode($pmode);
            $invoiceno = $row['invoicenopaid'];
            $reference=$row['reference'];
        }
        $sql2 = $db->query("SELECT property_name,tenant_name,Apartment_tag FROM $tablename2 WHERE ( `Id`='$idno' AND `vacated` like '0')") or die($db->error());
        while ($row2 =$db->fetch_array($sql2)) {
            $propertyname = $row2['property_name'];
            $tname = $row2['tenant_name'];
            $tag = $row2['Apartment_tag'];
        }
        $sql3 =$db->query("SELECT item_name,amount FROM $tablename3 WHERE invoiceno='$invoiceno' ORDER BY `priority` ASC") or die($db->error());
        while ($row3 = $db->fetch_array($sql3)) {
            $item_name = $row3['item_name'];
            $item_amount = $row3['amount'];
            array_push($chargeablesamount,$item_amount);
            array_push($tableitems, '<tr><td></td><td style="color:black;" colspan="2">' . $item_name . '</u></td><td>Ksh:' . number_format($item_amount, 2) . '</td></tr>');
        }
         //get outstanding balance/pre<tr><td></td><td style="color:black;" colspan="2">'payment
        
        $balance=  getCorrectBalance($idno,$latestinvoice='0');
        $settings=getSettings();
        
        $penaltysql=$db->query("SELECT amount FROM $invoicetable WHERE recpno='$recpno' AND `is_penalty`=1 ") or die($db->error());
        while ($row= $db->fetch_array($penaltysql)) {
            $penaltyamount = $row['amount'];
              }
             //echo '<br>';
              ?>
<style>
    .tftable td{font-size:18px;}
    
</style>
<table class="tftable printable " width="90%" style="font-family:Arial,sans-serif;letter-spacing:2px;" >
       <br><br>
        <!--<tr style="border-top:1px solid black"><td colspan="4" ></td></tr>--> 
        <tr height="1%"><td ><b><?php echo strtoupper($settings["company_name"])?></b></td></tr>
         <tr height="1%"><td ><?php echo strtoupper($settings["tagline"])?></td></tr>
         <tr height="1%"><td ><b><br></b></center></td></tr>
          <tr height="2%"><td style="height:1px;"><b>RECPNO:</b>&nbsp;<?php echo $recpno ?>&nbsp;&nbsp;<b>Date:</b><?php echo date("d-m-Y",strtotime($recpdate ))?></td></tr>
                
                        <tr height="1%"><td><b>Received From:</b>&nbsp;<?php echo ucwords(@$tname) .'('.@$tag.')'?> <b>OF </b><br><?php echo  ucwords(str_replace('_', " ", $propertyname))  ?></td></tr>
                        <tr height="2%"><td style="height:1px;"><b>Amount:</b><?php echo number_format($amount, 2).'&nbsp (Ksh&nbsp;'.convert_number_to_words($amount).' only)'?></b></td></tr>
                            <tr><td><b>Payment Mode&nbsp;&nbsp;</b><?php echo strtoupper($mode[0]['paymode']);?></b></td></tr>
                        <?php if(strtoupper($mode[0]['paymode'])=="CHEQUE"){?>
                        <tr height="2%"><td style="height:1px;"><b>CHEQUE NO<?php echo  $chequeno.' OF '.$cheqdet?></b>&nbsp;<b>Chq.Date&nbsp;</b><?php echo date("d-m-Y",strtotime($chequedate));?></td></tr>
                 <?php } else if(strtoupper($mode[0]['paymode'])=="BANK DEPOSIT"){                                   ?>
           
                        <tr height="2%"><td style="height:1px;"><b><?php echo getReceiptBank($recpno,$recpdate)?></b></td></tr>
              <?php }?>
                        <tr height="2%"><td style="height:1px;"><b>REFERENCE:</b><?=strtoupper($reference);?></b></td></tr>
        <tr height="1%"><td><b>Remarks</b>&nbsp;<?php echo $remarks?></td></tr>
        <?php  
        //for peta
        // echo '<tr><td colspan="4"><hr/></td></tr>'
        echo '<tr><td></td><td colspan="3">';
        
      // foreach ($tableitems as $key => $value1) {
           
          // echo $key . $value1;
        
      //  }
        echo '<td></tr>';
        if($penaltyamount){
           echo '<tr height="5%"><td></td><td><u>Penalty For Late Payment</u></td><td>Ksh:' . number_format($penaltyamount, 2) . '</td></tr>';
      
        }
        
         if($balance){  ?>
<tr height="5%"><td class="blackfont"><b>BAL C/FORWARD:</b>&nbsp;&nbsp;<?php echo 'Ksh: '.number_format($balance,2);?></td></tr>
<?php }
?>
<tr height="5%"><td ><?php echo $user.'&nbsp;&nbsp;'.$date.'&nbsp;&nbsp;'.$time?><span><br>PIN NO:<?php echo $settings['pin']  ?>  VAT NO:<?php echo $settings['vat']  ?></span></td></tr>
<tr height="1%"><td ><center><b><br></b></center></td></tr>
</table>
<?php
       /* echo '<center><table class="printable" style="width:800px;"><div id="printheader"><tr><td colspan="3" style="width:800px"><span id="copy">Copy</span><center><span id="invoice">RECEIPT</span></center></td></tr>

</div>';
        echo '<tr><td colpan="2"><span id="invoiceno">RECEIPT NO&nbsp;' . $recpno . '</span></td><td></td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date ' .date("d-m-Y",strtotime($recpdate)) . '</td></tr>';
        echo '<tr><td colspan="3"><br/><td></tr>';
        echo '<tr><td colspan="2"><b>Received from&nbsp;</b><u>' . strtoupper(@$tname) . '</u>&nbsp;of&nbsp;<b>' . ucwords(str_replace('_', " ", @$propertyname)) . ' (' . @$tag . ') ' . '</b>
    <br>the sum of Kshs <u><b>' . convert_number_to_words($amount) . ' Only<br/>being payment for: </b>' . $remarks . '</u> </b></td><td><h4>AMOUNT: Kshs ' . number_format($amount, 2) . '</h4></td></tr>';
        echo '<tr><td colspan="3"><hr/></td></tr><tr><td colspan="3">';
        
        foreach ($tableitems as $key => $value1) {
           
            echo $key . $value1;
        
        }
        if($penaltyamount){
           echo '<tr><td><u>Penalty For Late Payment</u></td><td>Ksh:' . number_format($penaltyamount, 2) . '</td></tr>';
      
        }
        if($balance){
        echo '<tr><td class="blackfont">BALANCE CARRIED FORWARD:</td><td colspan="1"> Ksh: '.number_format($balance,2).'</td></tr>';
        }
        echo '<tr><td colspan="3">' . $user . '&nbsp;&nbsp;' . $date . '&nbsp;&nbsp;' . $time . '</td></tr>';
        
          echo '</table>';*/
        
    } else {
        return false;
    }
}

function printreceipt($receiptno, $user) {
    include '../includes/numberformatter.php'; //format amount in words
    $db = new MySQLDatabase();
    date_default_timezone_set('Africa/Nairobi');
    $date = date("d/m/y");
    $time = date('h:i A');
    $db->open_connection();
    $tablename = "recptrans";
    $tablename2 = "tenants";
    $tablename3 = "invoiceitems";
     $tableinv = "invoices";
    $invoicetable=  getInvoiceTable();
    $tableitems = [];
    $chargeablesamount=array();
    $carfrwd = 0;
    
    $sql = $db->query("SELECT * FROM $tablename WHERE `recpno` like '$receiptno'") or die($db->error());
    if ($db->num_rows($sql) > 0) {
        while ($row = $db->fetch_array($sql)) {
            $recpno = $row['recpno'];
            $recpdate = $row['rdate'];
            $amount = $row['amount'];
            $chequedate=$row['chequedate'];
            $chequeno=$row['chqno'];
            $cheqdet=$row['chqdet'];
            $idno = $row['idno'];
            $remarks = $row['rmks'];
            $pmode = $row['pmode'];
            $mode=getPayMode($pmode);
            $invoiceno = $row['invoicenopaid'];
            $reference=$row['reference'];
        }
        $sql2 = $db->query("SELECT property_name,tenant_name,Apartment_tag FROM $tablename2 WHERE ( `Id`='$idno' AND `vacated` like '0')") or die($db->error());
        while ($row2 =$db->fetch_array($sql2)) {
            $propertyname = $row2['property_name'];
            $tname = $row2['tenant_name'];
            $tag = $row2['Apartment_tag'];
        }
        $sql4 = $db->query("SELECT invoicedate FROM $tableinv WHERE invoiceno = '$invoiceno'") or die($db->error());
        while ($row4 =$db->fetch_array($sql4)) {
            $invoicedate = $row4['invoicedate'];
           
        }
        $sql3 =$db->query("SELECT item_name,amount FROM $tablename3 WHERE invoiceno='$invoiceno' ORDER BY `priority` ASC") or die($db->error());
        while ($row3 = $db->fetch_array($sql3)) {
            $item_name = $row3['item_name'];
            $item_amount = $row3['amount'];
            array_push($chargeablesamount,$item_amount);
            array_push($tableitems, '<tr><td></td><td style="color:black;" colspan="2">' . $item_name . '</u></td><td>Ksh:' . number_format($item_amount, 2) . '</td></tr>');
        }
         //get outstanding balance/pre<tr><td></td><td style="color:black;" colspan="2">'payment
        
        $balance=  getCorrectBalance($idno,$latestinvoice='0',$invoicedate);
        $settings=getSettings();
        
        $penaltysql=$db->query("SELECT amount FROM $invoicetable WHERE recpno='$recpno' AND `is_penalty`=1 ") or die($db->error());
        while ($row= $db->fetch_array($penaltysql)) {
            $penaltyamount = $row['amount'];
              }
             //echo '<br>';
              ?>
<style>
    .tftable td{font-size:16px;}
    .tdborder  {
border-collapse: collapse;
  }
    .tdborder td {border: 1px solid black;}
   
</style>
<table class="tftable printable " width="90%" style="font-family:Arial,sans-serif;letter-spacing:2px;" >
       <br><br>
        <!--<tr style="border-top:1px solid black"><td colspan="4" ></td></tr>--> 
       <tr height="1%"><td colspan="4"><img src="../images/cursors/LHEAD.png"/></td></tr>
       <tr height="1%"><td align="right" colspan="4"><b>RECEIPT</b></td></tr>
           <tr height="2%"><td colspan="4" style="height:1px;" align="right"><u>Date:</u>&nbsp;<?php echo date("d-m-Y",strtotime($recpdate ))?></td></tr>
           <tr height="2%"><td colspan="4" style="height:1px;" align="right"><u>Receipt No:</u>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $recpno ?>&nbsp;&nbsp;</td></tr>
      
                        <tr height="1%"><td colspan="4"><b>Received From:</b>&nbsp;<?php echo ucwords(@$tname) .'('.@$tag.')'?> <b>OF </b><br><?php echo  ucwords(str_replace('_', " ", $propertyname))  ?></td></tr>
                        <tr height="2%"><td colspan="4" align="right" style="height:1px;"><b>Amount: <?php echo number_format($amount, 2).'&nbsp <br> (Ksh&nbsp;'.convert_number_to_words($amount).' only)'?></b></td></tr>
                        <tr height="1%"><td colspan="4"><b>Remarks</b>&nbsp;<?php echo $remarks?></td></tr> 
                        <tr height="1%"><td colspan="4"><table class="tdborder" width="90%" align="center">
                        <tr height="1%" border="1"  ><td style="border: 1px solid black;">Date</td><td style="border: 1px solid black;">Description</td><td style="border: 1px solid black;">Amount</td><td style="border: 1px solid black;">Balance</td></tr> 
                         <tr height="1%" border="1" ><td style="border: 1px solid black;"><?php echo date("d-m-Y",strtotime($recpdate ))?></td><td style="border: 1px solid black;">B/F</td><td style="border: 1px solid black;">-</td><td style="border: 1px solid black;"><?php echo '0'; ?></td></tr> 
                        <tr height="1%"  ><td></td><td>Rent</td><td><?php echo number_format($amount, 2);?></td><td><?php echo number_format($balance,2);?></td></tr>
                                </table></td></tr>
                        <tr ><td colspan="4"><b>Payment Mode&nbsp;&nbsp;</b><?php echo strtoupper($mode[0]['paymode']);?></b></td></tr>
                        <?php if(strtoupper($mode[0]['paymode'])=="CHEQUE"){?>
                        <tr height="2%"><td colspan="4" style="height:1px;"><b>CHEQUE NO<?php echo  $chequeno.' OF '.$cheqdet?></b>&nbsp;<b>Chq.Date&nbsp;</b><?php echo date("d-m-Y",strtotime($chequedate));?></td></tr>
                 <?php } else if(strtoupper($mode[0]['paymode'])=="BANK DEPOSIT"){                                   ?>
           
                        <tr height="2%"><td colspan="4" style="height:1px;"><b><?php echo getReceiptBank($recpno,$recpdate)?></b></td></tr>
              <?php }?>
                        <tr height="2%"><td colspan="4" style="height:1px;"><b>REFERENCE:</b><?=strtoupper($reference);?></b></td></tr>
        
        <?php  
        //for peta
        // echo '<tr><td colspan="4"><hr/></td></tr>'
        echo '<tr><td></td><td colspan="3">';
        
      // foreach ($tableitems as $key => $value1) {
           
          // echo $key . $value1;
        
      //  }
        echo '<td></tr>';
        if($penaltyamount){
           echo '<tr height="5%"><td></td><td colspan="3"><u>Penalty For Late Payment</u></td><td>Ksh:' . number_format($penaltyamount, 2) . '</td></tr>';
      
        }
        
         if($balance){  ?>
                        <tr height="5%"><td colspan="4" style="text-align:center" class="blackfont">BAL C/FORWARD:&nbsp;&nbsp;<?php echo 'Ksh: '.number_format($balance,2);?></td></tr>
<?php }
?>
<tr height="5%"><td colspan="4" align="right" style="font-size:10px" ><?php echo $user.'&nbsp;&nbsp;'.$date.'&nbsp;&nbsp;'.$time?></td></tr>
<tr height="1%"><td colspan="4" ><center><b><br></b></center></td></tr>
<tr height="1%"><td  colspan="4" ><img src="../images/cursors/Footer.png"/></td></tr>
</table>
<?php
       /* echo '<center><table class="printable" style="width:800px;"><div id="printheader"><tr><td colspan="3" style="width:800px"><span id="copy">Copy</span><center><span id="invoice">RECEIPT</span></center></td></tr>

</div>';
        echo '<tr><td colpan="2"><span id="invoiceno">RECEIPT NO&nbsp;' . $recpno . '</span></td><td></td><td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Date ' .date("d-m-Y",strtotime($recpdate)) . '</td></tr>';
        echo '<tr><td colspan="3"><br/><td></tr>';
        echo '<tr><td colspan="2"><b>Received from&nbsp;</b><u>' . strtoupper(@$tname) . '</u>&nbsp;of&nbsp;<b>' . ucwords(str_replace('_', " ", @$propertyname)) . ' (' . @$tag . ') ' . '</b>
    <br>the sum of Kshs <u><b>' . convert_number_to_words($amount) . ' Only<br/>being payment for: </b>' . $remarks . '</u> </b></td><td><h4>AMOUNT: Kshs ' . number_format($amount, 2) . '</h4></td></tr>';
        echo '<tr><td colspan="3"><hr/></td></tr><tr><td colspan="3">';
        
        foreach ($tableitems as $key => $value1) {
           
            echo $key . $value1;
        
        }
        if($penaltyamount){
           echo '<tr><td><u>Penalty For Late Payment</u></td><td>Ksh:' . number_format($penaltyamount, 2) . '</td></tr>';
      
        }
        if($balance){
        echo '<tr><td class="blackfont">BALANCE CARRIED FORWARD:</td><td colspan="1"> Ksh: '.number_format($balance,2).'</td></tr>';
        }
        echo '<tr><td colspan="3">' . $user . '&nbsp;&nbsp;' . $date . '&nbsp;&nbsp;' . $time . '</td></tr>';
        
          echo '</table>';*/
        
    } else {
        return false;
    }
}


function printreceiptother($receiptno, $user) {
    include '../includes/numberformatter.php'; //format amount in words
    $db = new MySQLDatabase();
    date_default_timezone_set('Africa/Nairobi');
    $date = date("d/m/y");
    $time = date('h:i A');
    $db->open_connection();
    $tablename = "otherrecptrans";
    $tablename2 = "tenants";
    $tablename3 = "invoiceitems";
    $invoicetable=  getInvoiceTable();
    $tableitems = [];
    $chargeablesamount=array();
    $sql = $db->query("SELECT * FROM $tablename WHERE `recpno` like '$receiptno'") or die($db->error());
    if ($db->num_rows($sql) > 0) {
        while ($row = $db->fetch_array($sql)) {
            $recpno = $row['recpno'];
            $recpdate = $row['rdate'];
            $amount = $row['amount'];
            $chequedate=$row['chequedate'];
            $chequeno=$row['chqno'];
            $cheqdet=$row['chqdet'];
            $customer = $row['customer'];
            $remarks = $row['rmks'];
            $pmode = $row['pmode'];
            $mode=getPayMode($pmode);
            $invoiceno = $row['invoicenopaid'];
        }

   
        
       
        $settings=getSettings();
        
        $penaltysql=$db->query("SELECT amount FROM $invoicetable WHERE recpno='$recpno' AND `is_penalty`=1 ") or die($db->error());
    
             echo '<br><br><br>';
              ?>

<center><table class="tftable printable "  style="font-weight:bold;width:90% !important" border="1">
       <tr><th><img src="../images/cursors/logo.png"/></th><th colspan="3"><center><?php echo $settings['company_name'] ?></center><br><?php echo $settings['tagline'] ?></th></tr>
                <tr height="2%"><td style="height:1px;"><?php echo str_repeat(5,'&nbsp;')?>RECPNO</td><td style="height:5px;"><?php echo $recpno ?></td><td></td><td>Date:<?php echo date("d-m-Y",strtotime($recpdate ))?></td></tr>
                
                        <tr height="1%"><td><?php echo str_repeat(5,'&nbsp;')?>Received From</td><td colspan="3"><?php echo ucwords(@$customer)?> </td></tr>
                        <tr height="2%"><td style="height:1px;">Amount</td><td colspan="2"><b><?php echo number_format($amount, 2).'&nbsp (Ksh&nbsp;'.convert_number_to_words($amount).' only)'?></b></td><td><b><?php echo strtoupper($mode[0]['paymode']);?></b></td></tr>
                        <?php if(strtoupper($mode[0]['paymode'])=="CHEQUE"){?>
                        <tr height="2%"><td style="height:1px;"></td><td colspan="2"><b>CHEQUE NO<?php echo  $chequeno.' OF '.$cheqdet?></b></td><td>Chq.Date&nbsp;<?php echo date("d-m-Y",strtotime($chequedate));?></td></tr>
                 <?php }else if(strtoupper($mode[0]['paymode'])=="BANK DEPOSIT"){
               
                     ?>
                        <tr height="2%"><td style="height:1px;"></td><td></td><td colspan="2"><b> <?php echo getReceiptBank($recpno,$recpdate)?></b></td></tr>
              <?php }?>
        <tr height="1%"><td><?php echo str_repeat(5,'&nbsp;')?>Remarks</td><td colspan="3"><b><?php echo $remarks?></b></td></tr>
        <?php  
        //for peta
//         echo '<tr><td colspan="4"><hr/></td></tr>'
//        . '<tr><td></td><td colspan="3">';
//        
//        foreach ($tableitems as $key => $value1) {
//           
//            echo $key . $value1;
//        
//        }
//        echo '<td></tr>';
    ?>
<tr height="5%"><td colspan="4"><?php echo $user.'&nbsp;&nbsp;'.$date.'&nbsp;&nbsp;'.$time?><span class="linkright">PIN NO:<?php echo $settings['pin']  ?>  VAT NO:<?php echo $settings['vat']  ?></span></td></tr>
</table>

</center>
<?php

        
    } else {
        return false;
    }
}
function getrecpbyid($id,$type="") {
    $db = new MySQLDatabase();
    $db->open_connection();
	if($type=="other"){
		 $tablename = "otherrecptrans";
	}else{
    $tablename = "recptrans";
	}
    echo '<table class="treport" ><thead>
<tr>
<th><center><u>Receipt No</u></center></th>
<th><center><u>Receipt Date</u></center></th>
<th><center><u>Amount</u></center></th>
<th><center><u>Idno</u></center></th>
<th><center>Action</center></th></tr><thead>
<tbody>';

    $sql = $db->query("SELECT * FROM $tablename WHERE recpno ='$id'") or die(mysql_error());
    while ($row = mysql_fetch_array($sql)) {
        $recpid = $row['tno'];
        $recpno = $row['recpno'];
        $recpdate = $row['rdate'];
        $amount = $row['amount'];
        $idno = $row['idno'];
		
        echo "<tr><td>$recpno</td><td>" . $recpdate . "</td><td>" . $amount . "</td><td>" . $idno . "</td><td><a href='#' id='delreceipt' title='$recpid' tabindex='$type'><img src='../images/close.png'>Reverse Receipt</a></td>";
    }
    echo '</tr></tbody>'
    . '</table></center>';
    $db->close_connection();
}


function getBillbyNo($no) {
    $db = new MySQLDatabase();
    $db->open_connection();
	
		 $tablename = "bills";

    $sql = $db->query("SELECT * FROM $tablename WHERE bill_no = '$no'") or die(mysql_error());
    while ($row = mysql_fetch_array($sql)) {
        $data['bl_id']= $row['pay_id'];
        $data['bill_no'] = $row['bill_no'];
        $data['bill_amount'] = $row['bill_amnt'];
      	
    }
    $db->close_connection();
    if(is_array($data)){
    return json_encode($data);
    }else {
        return NULL;
    }
}


function reverseBillbyNo($no) {
    $db = new MySQLDatabase();
    $db->open_connection();
	
		 $tablename = "bills";

    $sql = $db->query("UPDATE $tablename SET reversed=1 WHERE bill_no = '$no'") or die(mysql_error());
    $sql2 = $db->query("UPDATE paytrans SET revsd=1 WHERE billnopaid = '$no'") or die(mysql_error());
   
    $db->close_connection();
   return TRUE;
}

function reverseExpensebyNo($payno) {
   $db = getMysqliConnection();
	$tablename = "paytrans";
        
         $res = $db->query("SELECT * FROM $tablename WHERE `payno` = $payno ") or die($db->error);
    while ($row = $res->fetch_assoc()) {
        $billno = $row['billnopaid'];
    }
    if($billno){
    $db->query("UPDATE bills SET reversed=1 WHERE bill_no = '$billno'") or die(mysql_error());
     $db->query("UPDATE paytrans SET revsd=1 WHERE payno = '$payno'") or die(mysql_error());
   
  //  $db->close_connection();
   return "Voucher ".$payno." has been reversed";
    }
    else{
        return "No such voucher";
    }
}
//get bank receipt
function getReceiptBank($recpno, $date) {

    $mysqli = getMysqliConnection();

    $banktrans = getBankTransactionsTable();
    $bankstable = "banks";
    $res = $mysqli->query("SELECT * FROM $banktrans WHERE `recpno` like '$recpno' AND `is_credit`=1 AND DATE_FORMAT(`date`,'%Y-%m-%d')='$date'") or die($mysqli->error);
    while ($row = $res->fetch_assoc()) {
        $bankcode = $row['bank_type'];
    }

    $result = $mysqli->query("SELECT * FROM $bankstable WHERE `bank_code` = '$bankcode' ") or die($mysqli->error);
    $bankname = "";
    while ($row = $result->fetch_assoc()) {
        $bankname = $row['bank_name'];
    }
    return $bankname;
}

function reversereceipt($id,$username,$type) {
    $db = new MySQLDatabase();
    $db->open_connection();
	if($type=="other"){
		 $tablename = "otherrecptrans";
	}
	else{
    $tablename = "recptrans";
	}
	$currentdate = date('Y-m-d h:i:s');
    $invoicetable = getInvoiceTable();
    $sql = $db->query("SELECT * FROM $tablename WHERE `tno` like '$id'") or die(mysql_error());
    while ($row = mysql_fetch_array($sql)) {
        $recpno = $row['recpno'];
        $invoiceno = $row['invoicenopaid'];
        $rdate = $row['rdate'];
        $pmode = $row['pmode'];
        $chequedet = $row['chqdet'];
        $chqno = $row['chqno'];
        $chequedate = $row['chequedate'];
        $amount = $row['amount'];
        $idno = $row['idno'];
		 $isdeposit = $row['is_deposit'];
        $rmks = $row['rmks'];
        $invoicenopaid = $row['invoicenopaid'];
        $cashacct = $row['cashacc'];
        $bankacct = $row['bankacc'];
        $paidby = $row['paidby'];
        $idclose_periods = $row['idclose_periods'];
        $user = $row['us'];
    }

    $query = "INSERT into $tablename(`rdate`,`amount`,`pmode`,`recpno`,`chqdet`,`chqno`,`chequedate`,`rmks`,`idno`,`invoicenopaid`,`cashacc`,`bankacc`,`paidby`,`us`,`isdeposited`,`is_deposit`,`revsd`,`idclose_periods`,`ts`) VALUES ('$rdate','-$amount','$pmode','R$recpno','$chequedet','$chqno','$chequedate','$rmks','$idno','$invoicenopaid','$cashacct','$bankacct','$paidby','$user','0','$isdeposit','1','$idclose_periods', '$currentdate')";

    if (!$db->query($query)) {
        echo mysql_error();
    } else {
        $update = $db->query("UPDATE $tablename SET `revsd`=1 WHERE  `recpno` like '$recpno'") or die($db->error());
        $update1 = $db->query("UPDATE $invoicetable SET `paidamount`= paidamount-$amount  WHERE  `invoiceno` like '$invoiceno'") or die($db->error());
        $db->query("DELETE FROM $invoicetable WHERE `recpno`='$recpno' AND is_penalty=1") or die($db->error());

        header('Content-Type: application/json');
        $response_array['status'] = 'Transaction Reversed';
        echo json_encode($response_array);
    }
}

//delete receipt (deletes a receipt and its reversed equivalent
function deleteReceiptFromInvoice($invoiceno) {
    $mysqli = getMysqliConnection();
    $receipttable = getReceiptsTable();
    $mysqli->query("UPDATE {$receipttable} SET `is_deleted`=1 WHERE `invoicenopaid` like '$invoiceno' ") or die($mysqli->error);
    $mysqli->close();
}

function getreceiptlist($startdate, $enddate, $propid, $user, $allpropertiesflag = 0, $tenant = 0) {

    date_default_timezone_set('Africa/Nairobi');
    $time = date('h:i A');
    $db = new MySQLDatabase();
    $db->open_connection();
    $tablename = "recptrans";
    $tablename2 = "tenants";
    $tablename3 = "vatamounts";
    $q2 = $db->query("SELECT amount from $tablename3 WHERE vatid='1'") or die(mysql_error());
    $sumamount = array();
    $sumcommmamount = array();
    $sumtotalamount = array();
    while ($row1 = mysql_fetch_array($q2)) {
        $tenantVAT = $row1['amount'];
    }

    if ($allpropertiesflag) {
        $allproperties = getProperties();
        echo '<table class="treport1 width70 exportlist" ><thead>
<tr><td colspan="15"><center><span style="font-size:15px;font-weight:normal;float:left;"> <b>RECEIPT LIST</b></span><span style="font-size:18px;font-weight:bold">' . $_SESSION['clientname'] . '</span><span style="font-size:18px;font-weight:normal; float:right;"></span><center>
<br/><span style="font-size:16px;font-weight:normal;"><centre>' . str_repeat('&nbsp;', 25) . 'Receipt List From <b> ' . date("d-m-Y", strtotime($startdate)) . '</b>  To  <b>' . date("d-m-Y", strtotime($enddate)) . '</b></center></span></td></tr>';
        echo'<tr>
<u><td>T/AC</td><td>L/AC</td><td>Property</td> <td>Invoice No</td><td>Receipt No</td><td>Paymode</td><td>Bank</td><td>Receipt Date</td><td>Transaction Date</td><td>Reference</td> <td>House No</td><td>Tenant/Other Name</td><td>Narration</td> <td>Amount</td><td>Commission</td><td>Net Amount</td></u></tr>';
        echo '</thead><tbody>';
        //foreach ($allproperties as $property) {

        if ($tenant !== " " && $tenant > 0) {
            $query = $db->query("SELECT $tablename.recpno,$tablename.pmode,$tablename.property_id,$tablename.invoicenopaid,$tablename.rdate,$tablename.reference,$tablename.revsd,$tablename.ts,$tablename.rmks,$tablename.amount,$tablename2.Id,$tablename2.tenant_name,$tablename2.Apartment_tag,$tablename2.property_name FROM $tablename LEFT JOIN $tablename2 ON $tablename.idno=$tablename2.Id WHERE (DATE_FORMAT($tablename.ts,'%Y-%m-%d') BETWEEN '$startdate' AND '$enddate')  AND  recptrans.idno='$tenant'   ORDER BY `recptrans`.`recpno` ASC  ") or die($db->error());     //AND `is_deposit`='R'
        } else {

            $query = $db->query("SELECT $tablename.recpno,$tablename.pmode,$tablename.property_id,$tablename.invoicenopaid,$tablename.rdate,$tablename.reference,$tablename.revsd,$tablename.ts,$tablename.rmks,$tablename.amount,$tablename2.Id,$tablename2.tenant_name,$tablename2.Apartment_tag,$tablename2.property_name FROM $tablename LEFT JOIN $tablename2 ON $tablename.idno=$tablename2.Id WHERE (DATE_FORMAT($tablename.ts,'%Y-%m-%d') BETWEEN '$startdate' AND '$enddate')  ORDER BY `recptrans`.`recpno` ASC  ") or die($db->error());    //AND `is_deposit`='R'
        }
        $i = 1;
        $recpnos = array();
        while ($row = $db->fetch_array($query)) {
            array_push($recpnos, $row['recpno']);
            $propid = $row['property_id'];

            $commission = getPropertyCommissionRate($propid);
            //get invoice items 
            $commissionnotcharged = 0;
            $invoice = getInvoiceDetails($row['invoicenopaid']);
            $pmode = $row['pmode'];
            $paymode = getPayMode($pmode);
            $invoicevalue = $invoice['amount'];
            $invoiceitems = getInvoiceItemsAndValue($row['invoicenopaid']);

            foreach ($invoiceitems as $item) {
                $itemdetails = getChargeItemByName($item['name'], $propid);

                if ($itemdetails['commission_charged'] == 0 || $invoice['is_penalty'] == 1) {
                    //get the item value as a % of the total amount 
                    if ($itemdetails['amount'])
                        $commissionnotcharged = $commissionnotcharged + (($itemdetails['amount'] / $invoicevalue) * ($commission / 100) * $invoicevalue);
                    // echo $commissionnotcharged;
                }
            }
            if ($invoice['is_penalty']) {
                $commamount = 0;
            } else {
                $commamount = (($commission / 100) * $row['amount']) - $commissionnotcharged;
            }

            $netamount = $row['amount'] - $commamount;
            if ($row["revsd"]) {
                $link = $row['recpno'];
            } else {
                $recpno = $row['recpno'];
                $link = '<a class="whitetext"  href="defaultreports.php?report=printreceipt&receiptno=' . $recpno . '" target="blank"><span style="color:blue">' . $recpno . '</span></a>';
            }

            echo '<tr><td>' . $row['Id'] . '</td><td>' . $propid . $invoice['amount'] . '</td><td>' . findpropertybyid($propid) . '</td><td>'.$row['invoicenopaid'].'</td><td>' . $link . '</td><td>' . $paymode[0]['paymode'] . '</td><td>' . getReceiptBank($row['recpno'], $row['rdate']) . '</td><td>' . date("d-m-Y", strtotime($row['rdate'])) . '</td><td>' . date("d-m-Y", strtotime($row['ts'])) . '</td><td>'.$row['reference'].'</td><td>' . $row['Apartment_tag'] . '</td><td>' . $row['tenant_name'] . '</td><td>' . $row['rmks'] . '</td><td>' . number_format($row['amount'], 2) . '</td><td>' . number_format($commamount, 2) . '</td><td>' . number_format($netamount, 2) . '</td></tr>';
            array_push($sumamount, (float) $row['amount']);
            array_push($sumcommmamount, (float) ($commamount));
            array_push($sumtotalamount, (float) ($netamount));
        }

        //  }

        echo '</tbody>';
        echo '<tfoot><tr><td><b>GRAND TOTAL</b></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td><b>' . number_format(@array_sum($sumamount), 2) . '</b></td><td><b>' . number_format(@array_sum($sumcommmamount), 2) . '</b></td><td><b>' . number_format(@array_sum($sumtotalamount), 2) . '</b></td></tr></tfoot>';
        echo '</table>';
        echo '<hr/>';
        echo '<i>Printed by:</i> ' . $user . '&nbsp;&nbsp;&nbsp;&nbsp;' . date("d-m-y") . '&nbsp;' . $time;
    } else {
        //get receipts not deleted
        if ($tenant !== "" && $tenant > 0) {

            $query = $db->query("SELECT $tablename.recpno,$tablename.pmode,$tablename.reference,$tablename.invoicenopaid,$tablename.rdate,$tablename.revsd,$tablename.ts,$tablename.rmks,$tablename.amount,$tablename2.Id,$tablename2.tenant_name,$tablename2.Apartment_tag,$tablename2.property_name FROM $tablename LEFT JOIN $tablename2 ON $tablename.idno=$tablename2.Id WHERE ($tablename.rdate BETWEEN '$startdate' AND '$enddate') AND $tablename.is_deleted=0 AND $tablename2.property_id='$propid'   AND recptrans.idno='$tenant' ORDER BY $tablename.recpno ASC ") or die($db->error());
        } else {
            $query = $db->query("SELECT $tablename.recpno,$tablename.pmode,$tablename.reference,$tablename.invoicenopaid,$tablename.rdate,$tablename.ts,$tablename.revsd,$tablename.rmks,$tablename.amount,$tablename2.Id,$tablename2.tenant_name,$tablename2.Apartment_tag,$tablename2.property_name FROM $tablename LEFT JOIN $tablename2 ON $tablename.idno=$tablename2.Id WHERE ($tablename.rdate BETWEEN '$startdate' AND '$enddate') AND $tablename.is_deleted=0 AND $tablename2.property_id='$propid'  ORDER BY $tablename.recpno ASC ") or die($db->error());
        }
        echo '<table class="treport1 width70" ><thead>
<tr><td colspan="14"><center><span style="font-size:15px;font-weight:normal;float:left;"> <b>RECEIPT LIST</b></span><span style="font-size:18px;font-weight:bold">' . $_SESSION['clientname'] . '</span><span style="font-size:18px;font-weight:normal; float:right;"></span><center>
<br/><span style="font-size:16px;font-weight:normal;"><centreceiptlier>' . str_repeat('&nbsp;', 25) . 'Receipt List From <b> ' . date("d-m-Y", strtotime($startdate)) . '</b>  To  <b>' . date("d-m-Y", strtotime($enddate)) . '</b></center></span></td></tr>';
        echo'<tr>
<u><th>T/AC</th><th>L/AC</th><th>Property</th> <td>Invoice No</td><th>Receipt No</th><th>Paymode</th><th>Bank</th><th>Receipt Date</th><th>Transaction Date</th><th>Reference</th>  <th>House No</th><th>Tenant/Other Name</th><th>Narration</th> <th>Amount</th><th>Commission</th><th>Net Amount</th></u></tr>';
        echo '</thead><tbody>';
        $i = 1;
        $commission = getPropertyCommissionRate($propid);
        while ($row = $db->fetch_array($query)) {

            $pmode = $row['pmode'];
            $paymode = getPayMode($pmode);
            $commissionnotcharged = 0;
            $invoice = getInvoiceDetails($row['invoicenopaid']);

            $invoicevalue = @$invoice['amount'];
            // echo $invoicevalue."<br>";
            $invoiceitems = getInvoiceItemsAndValue($row['invoicenopaid']);

            foreach ($invoiceitems as $item) {
                $itemdetails = getChargeItemByName($item['name'], $propid);
                if ($itemdetails['commission_charged'] == 0 || $invoice['is_penalty'] == 1) {
                    //get the item value as a % of the total amount 
                    if ($itemdetails['amount'])
                        $commissionnotcharged = $commissionnotcharged + (($itemdetails['amount'] / $invoicevalue) * ($commission / 100) * $invoicevalue);
                    // echo $commissionnotcharged;
                }
            }
            if ($invoice['is_penalty']) {
                $commamount = 0;
            } else {
                $commamount = (($commission / 100) * $row['amount']) - $commissionnotcharged;
            }
            $netamount = $row['amount'] - $commamount;
            if ($row["revsd"] == 1) {
                $link = $row['recpno'];
            } else {
                $recpno = $row['recpno'];
                $link = '<a class="whitetext"  href="defaultreports.php?report=printreceipt&receiptno=' . $recpno . '" target="blank"><span style="color:blue">' . $recpno . '</span></a>';
            }
            echo '<tr><td>' . $row['Id'] . '</td><td>' . $propid . '</td><td>' . findpropertybyid($propid) . '</td><td>'.$row['invoicenopaid'].'</td><td>' . $link . '</td><td>' . $paymode[0]['paymode'] . '</td><td>' . getReceiptBank($row['recpno'], $row['rdate']) . '<td>' . date("d-m-Y", strtotime($row['rdate'])) . '</td><td>' . date("d-m-Y", strtotime($row['ts'])) . '</td><td>'.$row['reference'].'</td><td>' . $row['Apartment_tag'] . '</td><td>' . $row['tenant_name'] . '</td><td>' . $row['rmks'] . '</td><td>' . number_format($row['amount'], 2) . '</td><td>' . number_format($commamount, 2) . '</td><td>' . number_format($netamount, 2) . '</td></tr>';
            array_push($sumamount, (float) $row['amount']);
            array_push($sumcommmamount, (float) ($commamount));
            array_push($sumtotalamount, (float) ($netamount));
        }

        echo '</tbody>';
        echo '<tfoot><tr><td><b>GRAND TOTAL</b></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td><b>' . number_format(@array_sum($sumamount), 2) . '</b></td><td><b>' . number_format(@array_sum($sumcommmamount), 2) . '</b></td><td><b>' . number_format(@array_sum($sumtotalamount), 2) . '</b></td></tr></tfoot>';
        echo '</table>';
        echo '<hr/>';
        echo '<i>Printed by:</i> ' . $user . '&nbsp;&nbsp;&nbsp;&nbsp;' . date("d-m-y") . '&nbsp;' . $time;
    }
}

function getreceiptlistother($startdate, $enddate, $propid, $user, $allpropertiesflag = 0) {

    date_default_timezone_set('Africa/Nairobi');
    $time = date('h:i A');
    $db = new MySQLDatabase();
    $db->open_connection();
    $tablename = "otherrecptrans";
    $tablename2 = "tenants";
    $tablename3 = "vatamounts";
    $q2 = $db->query("SELECT amount from $tablename3 WHERE vatid='1'") or die(mysql_error());
    $sumamount = array();
    $sumcommmamount = array();
    $sumtotalamount = array();
    while ($row1 = mysql_fetch_array($q2)) {
        $tenantVAT = $row1['amount'];
    }



    echo '<table class="treport1 width70 exportlist" ><thead>
<tr><td colspan="7"><center><span style="font-size:15px;font-weight:normal;float:left;"> <b>RECEIPT LIST</b></span><span style="font-size:18px;font-weight:bold">' . $_SESSION['clientname'] . '</span><span style="font-size:18px;font-weight:normal; float:right;"></span><center>
<br/><span style="font-size:16px;font-weight:normal;"><centre>' . str_repeat('&nbsp;', 25) . 'Customer Receipt List From <b> ' . date("d-m-Y", strtotime($startdate)) . '</b>  To  <b>' . date("d-m-Y", strtotime($enddate)) . '</b></center></span></td></tr>';
    echo'<tr>
<u> <td>Receipt No</td><td>Paymode</td><td>Receipt Date</td> <td>CustomerName</td><td>Narration</td><td>Reference</td> <td>Amount</td>></u></tr>';
    echo '</thead><tbody>';
    $query = $db->query("SELECT $tablename.*  FROM $tablename WHERE DATE_FORMAT($tablename.ts,'%Y-%m-%d') BETWEEN '$startdate' AND '$enddate' AND $tablename.is_deleted=0 AND `is_deposit`='R' ORDER BY rdate DESC ") or die($db->error());
    while ($row = $db->fetch_array($query)) {
        //loop here
        array_push($sumamount, (float) $row['amount']);

        $pmode = $row['pmode'];
        $paymode = getPayMode($pmode);
        ?>
        <tr><td><a class='whitetext'  href="defaultreports.php?report=printreceiptcustomer&receiptno=<?= $row['recpno'] ?>" target='blank'><span style='color:blue'><?= $row['recpno'] ?></span></a></td><td><?= $paymode[0]['paymode'] ?></td><td><?= date("d-m-Y", strtotime($row['rdate'])) ?></td><td><?= $row["customer"] ?></td><td><?= $row['rmks'] ?></td><td><?= $row['reference'] ?></td><td><?= number_format($row['amount'], 2) ?></td></tr>
    <?php
    }
    echo '</tbody>';
    echo '<tfoot><tr><td><b>GRAND TOTAL</b></td><td></td><td></td><td></td><td></td><td></td><td><b>' . number_format(@array_sum($sumamount), 2) . '</b></td></tr></tfoot>';
    echo '</table>';
    echo '<hr/>';
    echo '<i>Printed by:</i> ' . $user . '&nbsp;&nbsp;&nbsp;&nbsp;' . date("d-m-y") . '&nbsp;' . $time;
}

function getTenantDeposits($startdate, $enddate, $propid, $user, $allpropertiesflag = 0) {
    date_default_timezone_set('Africa/Nairobi');
    $time = date('h:i A');
    $db = new MySQLDatabase();
    $db->open_connection();
    $tablename = "recptrans";
    $tablename2 = "tenants";
    $tablename3 = "vatamounts";
    // die($startdate." ".$enddate);
    $myDateTimestart = DateTime::createFromFormat('d-m-Y',trim($startdate));
$startdate =$myDateTimestart->format('Y-m-d');
    $myDateTime = DateTime::createFromFormat('d-m-Y',trim($enddate));
$enddate = $myDateTime->format('Y-m-d');

    $q2 = $db->query("SELECT amount from $tablename3 WHERE vatid='1'") or die(mysql_error());
    $sumamount = array();
    $sumcommmamount = array();
    $sumtotalamount = array();
    while ($row1 = mysql_fetch_array($q2)) {
        $tenantVAT = $row1['amount'];
    }

    if ($allpropertiesflag) {
        $allproperties = getProperties();
        echo '<table class="treport1 width70" ><thead>
<tr><td colspan="11"><center><span style="font-size:15px;font-weight:normal;float:left;"> <b>DEPOSITS LIST</b></span><span style="font-size:18px;font-weight:bold">' . $_SESSION['clientname'] . '</span><span style="font-size:18px;font-weight:normal; float:right;"></span><center>
<br/><span style="font-size:16px;font-weight:normal;"><centre>' . str_repeat('&nbsp;', 25) . 'Deposits List From <b> ' . date("d-m-Y", strtotime($startdate)) . '</b>  To  <b>' . date("d-m-Y", strtotime($enddate)) . '</b></center></span></td></tr>';
        echo'<tr>
<u><th>T/AC</th><th>L/AC</th><th>Property</th> <th>Receipt No</th><th>Paymode</th><th>Reference</th><th>Receipt Date</th> <th>House No</th><th>Tenant/Other Name</th><th>Narration</th> <th>Amount</th><th>Net Amount</th></u></tr>';
        echo '</thead><tbody>';
        foreach ($allproperties as $property) {
            $propid = $property['property_id'];
            $commission = getPropertyCommissionRate($propid);
//die("SELECT $tablename.recpno,$tablename.pmode,$tablename.invoicenopaid,$tablename.rdate,$tablename.rmks,$tablename.amount,$tablename2.Id,$tablename2.tenant_name,$tablename2.Apartment_tag,$tablename2.property_name FROM $tablename LEFT JOIN $tablename2 ON $tablename.idno=$tablename2.Id WHERE ($tablename.rdate BETWEEN '$startdate' AND '$enddate')  AND $tablename2.property_id='$propid' AND `is_deposit`='D' ORDER BY $tablename2.Apartment_tag ASC ");
            $query = $db->query("SELECT $tablename.recpno,$tablename.pmode,$tablename.invoicenopaid,$tablename.reference,$tablename.rdate,$tablename.rmks,$tablename.amount,$tablename2.Id,$tablename2.tenant_name,$tablename2.Apartment_tag,$tablename2.property_name FROM $tablename LEFT JOIN $tablename2 ON $tablename.idno=$tablename2.Id WHERE ($tablename.rdate BETWEEN '$startdate' AND '$enddate')  AND $tablename2.property_id='$propid' AND `is_deposit`='D' ORDER BY $tablename2.Apartment_tag ASC ") or die($db->error());
            $i = 1;
            while ($row = $db->fetch_array($query)) {
                //get invoice items 
                $commissionnotcharged = 0;
                $invoice = getInvoiceDetails($row['invoicenopaid']);
                $pmode = $row['pmode'];
                $paymode = getPayMode($pmode);
				//print_r($paymode);
                $invoicevalue = $invoice['amount'];
                $invoiceitems = getInvoiceItemsAndValue($row['invoicenopaid']);

                $netamount = $row['amount'];
                echo '<tr><td>' . $row['Id'] . '</td><td>' . $propid . $invoice['amount'] . '</td><td>' . findpropertybyid($propid) . '</td><td><a class="whitetext"  href="defaultreports.php?report=printreceipt&receiptno=' . $row['recpno'] . '" target="blank"><span style="color:blue">' . $row['recpno'] . '</span></a></td><td>' . $paymode[0]['paymode'] . '</td><td>'.$row['reference'].'</td><td>' . date("d-m-Y", strtotime($row['rdate'])) . '</td><td>' . $row['Apartment_tag'] . '</td><td>' . $row['tenant_name'] . '</td><td>' . $row['rmks'] . '</td><td>' . number_format($row['amount'], 2) . '</td><td>' . number_format($netamount, 2) . '</td></tr>';
                array_push($sumamount, (float) $row['amount']);

                array_push($sumtotalamount, (float) ($netamount));
            }
        }

        echo '</tbody>';
        echo '<tfoot><tr><td><b>GRAND TOTAL</b></td><td><td></td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td><b>' . number_format(@array_sum($sumamount), 2) . '</b></td></td><td><b>' . number_format(@array_sum($sumtotalamount), 2) . '</b></td></tr></tfoot>';
        echo '</table>';
        echo '<hr/>';
        echo '<i>Printed by:</i> ' . $user . '&nbsp;&nbsp;&nbsp;&nbsp;' . date("d-m-y") . '&nbsp;' . $time;
    } else {
        //get receipts not deleted
		
        $query = $db->query("SELECT $tablename.recpno,$tablename.pmode,$tablename.invoicenopaid,$tablename.rdate,$tablename.rmks,$tablename.amount,$tablename2.Id,$tablename2.tenant_name,$tablename2.Apartment_tag,$tablename2.property_name FROM $tablename LEFT JOIN $tablename2 ON $tablename.idno=$tablename2.Id WHERE ($tablename.rdate BETWEEN '$startdate' AND '$enddate') AND $tablename2.property_id='$propid' AND `is_deposit`='D' ORDER BY $tablename2.Apartment_tag ASC ") or die($db->error());
        echo '<table class="treport1 width70" ><thead>
<tr><td colspan="10"><center><span style="font-size:15px;font-weight:normal;float:left;"> <b>DEPOSITS LIST</b></span><span style="font-size:18px;font-weight:bold">' . $_SESSION['clientname'] . '</span><span style="font-size:18px;font-weight:normal; float:right;"></span><center>
<br/><span style="font-size:16px;font-weight:normal;"><centreceiptlier>' . str_repeat('&nbsp;', 25) . 'Deposits List From <b> ' . date("d-m-Y", strtotime($startdate)) . '</b>  To  <b>' . date("d-m-Y", strtotime($enddate)) . '</b></center></span></td></tr>';
        echo'<tr>
<u><th>T/AC</th><th>L/AC</th><th>Property</th> <th>Receipt No</th><th>Receipt Date</th> <th>House No</th><th>Tenant/Other Name</th><th>Narration</th> <th>Amount</th><th>Net Amount</th></u></tr>';
        echo '</thead><tbody>';
        $i = 1;

        while ($row = $db->fetch_array($query)) {

            $pmode = ['pmode'];
            $paymode = getPayMode($pmode);
            $netamount = $row['amount'];
            echo '<tr><td>' . $row['Id'] . '</td><td>' . $propid . '</td><td>' . findpropertybyid($propid) . '</td><td><a class="whitetext"  href="defaultreports.php?report=printreceipt&receiptno=' . $row['recpno'] . '" target="blank"><span style="color:blue">' . $row['recpno'] . '</span></a></td><td>' . $pmode[0]['paymode'] . '</td><td>' . date("d-m-Y", strtotime($row['rdate'])) . '</td><td>' . $row['Apartment_tag'] . '</td><td>' . $row['tenant_name'] . '</td><td>' . $row['rmks'] . '</td><td>' . number_format($row['amount'], 2) . '</td><td>' . number_format($netamount, 2) . '</td></tr>';
            array_push($sumamount, (float) $row['amount']);
            array_push($sumcommmamount, (float) ($commamount));
            array_push($sumtotalamount, (float) ($netamount));
        }

        echo '</tbody>';
        echo '<tfoot><tr><td><b>GRAND TOTAL</b></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td><b>' . number_format(@array_sum($sumamount), 2) . '</b></td><td><b>' . number_format(@array_sum($sumtotalamount), 2) . '</b></td></tr></tfoot>';
        echo '</table>';
        echo '<hr/>';
        echo '<i>Printed by:</i> ' . $user . '&nbsp;&nbsp;&nbsp;&nbsp;' . date("d-m-y") . '&nbsp;' . $time;
    }
}

//end of recptrans
//get water rate
function get_water_rate($propid) {
    include_once '../includes/config.php';
    $mysqli = getMysqliConnection();
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") ";
    }
    $res = $mysqli->query("SELECT water_rate FROM properties WHERE propertyid='$propid' ") or die($mysqli->error);

    while ($row = $res->fetch_assoc()) {
        $water_rate = $row['water_rate'];
    }
    return $water_rate;
    mysqli_close($mysqli);
}

//fetch tenant/property rent statement
function fetchstatement2($tenantid, $propid, $startdate, $enddate, $count, $allpropertiesflag) {
    //die('dsdsdsds');
    include_once '../includes/config.php';
   
    $mysqli = getMysqliConnection();
    date_default_timezone_set('Africa/Nairobi');
    $startdate = date("Y-m-d", strtotime($startdate));
    $enddate = date("Y-m-d", strtotime($enddate));
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
    if ($count == '0') {
        //$res = $mysqli->query("SELECT recptrans.recpno,recptrans.idno,recptrans.rdate,recptrans.rmks as narration,recptrans.is_deposit as transactiontype,recptrans.invoicenopaid FROM recptrans WHERE recptrans.idno='$tenantid' AND recptrans.revsd=0 AND property_id='$propid' AND rdate between '$startdate' AND '$enddate' ") or die($mysqli->error);
$querybf = $mysqli->query("SELECT SUM(amount) as bal,idno, tno FROM invoices WHERE invoices.idno='$tenantid' AND invoices.revsd=0 AND property_id='$propid' AND invoicedate < '$startdate' AND invoicecredit ='0'
    UNION SELECT SUM(amount*-1) as bal,idno, tno FROM invoices WHERE invoices.idno='$tenantid' AND invoices.revsd=0 AND property_id='$propid' AND invoicedate < '$startdate' AND invoicecredit ='1'
    UNION SELECT SUM(-1*recptrans.amount) as bal,idno, tno FROM recptrans WHERE recptrans.idno='$tenantid' AND property_id='$propid' AND recptrans.rdate < '$startdate' AND recptrans.revsd=0  GROUP BY idno") or  die($mysqli->error);

        $res = $mysqli->query("SELECT invoices.invoiceno,invoices.invoicedate,IF(invoicecredit ='1',invoices.amount,'0')as credit,IF(invoicecredit ='1','0',invoices.amount)  as debit,invoices.remarks as narration,IF(invoicecredit ='1','Credit Note','Invoice') as transaction_type FROM invoices WHERE invoices.idno='$tenantid' AND invoices.revsd=0 AND property_id='$propid' AND invoicedate between '$startdate' AND '$enddate'
                                UNION SELECT recptrans.recpno,rdate,amount as credit,'0'as debit,rmks,'Receipt' as transaction_type FROM recptrans WHERE recptrans.idno='$tenantid' AND recptrans.revsd=0 AND property_id='$propid' AND rdate between '$startdate' AND '$enddate' ORDER BY invoicedate") or die($mysqli->error);
        $entity = strtoupper(findtenantbyid($tenantid));
    } else {
     $querybf = $mysqli->query("SELECT SUM(amount) as bal,idno, tno FROM invoices WHERE  invoices.revsd=0 AND property_id='$propid' AND invoicedate < '$startdate'  AND invoicecredit ='0'
    UNION SELECT SUM(amount*-1) as bal,idno, tno FROM invoices WHERE  invoices.revsd=0 AND property_id='$propid' AND invoicedate < '$startdate' AND invoicecredit ='1'
    UNION SELECT SUM(-1*recptrans.amount) as bal,idno, tno FROM recptrans WHERE  property_id='$propid' AND recptrans.rdate < '$startdate' AND recptrans.revsd=0  GROUP BY idno") or  die($mysqli->error);

        $res = $mysqli->query("SELECT invoices.invoiceno,invoices.invoicedate,IF(invoicecredit ='1',invoices.amount,'0')as credit,IF(invoicecredit ='1','0',invoices.amount)  as debit,invoices.remarks as narration,IF(invoicecredit ='1','Credit Note','Invoice') as transaction_type FROM invoices WHERE  invoices.revsd=0 AND property_id='$propid' AND invoicedate between '$startdate' AND '$enddate'
                                UNION SELECT recptrans.recpno,rdate,amount as credit,'0'as debit,rmks,'Receipt' as transaction_type FROM recptrans WHERE  recptrans.revsd=0 AND property_id='$propid' AND rdate between '$startdate' AND '$enddate' ORDER BY invoicedate ") or die($mysqli->error);
   
        
     //   $res = $mysqli->query("SELECT invoices.invoiceno,invoices.idno,invoices.invoicedate,invoices.amount as credit,invoices.remarks as narration FROM invoices WHERE property_id='$propid' AND invoices.revsd=0 AND invoicedate between '$startdate' AND '$enddate'") or die($mysqli->error);
        //$res = $mysqli->query("SELECT recptrans.recpno,recptrans.idno,recptrans.rdate,recptrans.rmks as narration,recptrans.is_deposit as transactiontype,recptrans.invoicenopaid FROM recptrans WHERE recptrans.revsd=0 AND property_id='$propid' AND rdate between '$startdate' AND '$enddate' ") or die($mysqli->error);
        $entity = 'ALL';
    }
    echo '<table class="treport" ><thead>
<tr><td><img src="../images/cursors/logo1.png" style="height:100px;width:140px;"></td><td colspan="8" style="background-color:beige"><h3><center>STATEMENT FOR <u>' . $entity . '</u> OF <i>' . strtoupper(findpropertybyid($propid)) . '</i><br><br>&nbsp;FOR THE PERIOD&nbsp;' .date('d-m-Y',strtotime($startdate)). '&nbsp;TO&nbsp;' . date('d-m-Y',strtotime($enddate)) . '</center></h3></td></tr>
<tr>
<th><center><u>Date</u></center></th>
<th><center><u>Invoice/Receipt No</u></center></th>
<th><center><u>Transaction</u></center></th>
<th><center><u>Narration</u></center></th>
<th><center><u>Unit Amount</center></u></th>
<th><center><u>Debit</center></u></th>
<th><center><u>Credit</center></u></th>
<th><center><u>Balance</center></u></th></tr></thead><tbody>';
 //die($querybf1);
while ($row1 = $querybf->fetch_assoc()) {
 $bftot = $bftot + $row1['bal'];
} 
echo '<tr><td colspan="4" align="right"><b>B/F Amount</b></td><td></td><td></td><td align=right>'.number_format($bftot,2).'&nbsp;&nbsp;</td></tr>';
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
echo '<tr><td>'.date("d-m-Y", strtotime($row['invoicedate'])).'</td><td>' .$row['invoiceno'].'</td><td>' . $row['transaction_type'] . '</td><td>&nbsp;&nbsp;&nbsp;&nbsp;' . $row['narration'] . '</td><td>'.$rent.'</td><td align=right style="color:red">&nbsp;&nbsp;' . number_format($row['debit']) . '&nbsp;&nbsp;</td><td align=right style="color:green">&nbsp;&nbsp;' . number_format($row['credit']) . '&nbsp;&nbsp;</td><td align=right>' . number_format($gtot+$bftot) . '&nbsp;&nbsp;</td></tr>';

    }

    echo '</tbody>';

    echo '<tfoot><tr><td><b>GRAND TOTAL</b></td><td></td><td></td><td></td><td></td><td align=right><b>' . number_format($dtot, 2) . '&nbsp;&nbsp;</b></td><td align=right><b>' . number_format($ctot, 2) . '&nbsp;&nbsp;</b></td><td align=right><b>' . number_format(($dtot-$ctot)+$bftot, 2) . '&nbsp;&nbsp;</b></td></tr></tfoot>';
    echo '</table>';
    mysqli_close($mysqli);
}
// function fetchstatement($tenantid, $propid, $startdate, $enddate, $count, $allpropertiesflag) {
 
// $res = $mysqli->query("SELECT agentproperty.* FROM agentproperty JOIN properties on agentproperty.property_id=properties.propertyid WHERE  properties.active=1 ORDER BY agentproperty.propertyname ") or die($mysqli->error);
// } 
//fetch tenant/property rent statement
function fetchstatement($tenantid, $propid, $startdate, $enddate, $count, $allpropertiesflag) {
    include_once '../includes/config.php';
    $mysqli = getMysqliConnection();
    date_default_timezone_set('Africa/Nairobi');
    $startdate = date("Y-m-d", strtotime($startdate));
    $enddate = date("Y-m-d", strtotime($enddate));
    $invoicenos = array();
    $invoicedetails = array();
    $allinvoicedetails = array();
    $suminvoiceamount = array();
    $sumpaidamount = array();
    $sumbal = array();
   
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") ";
    }
    if ($count == '0') {
        //$res = $mysqli->query("SELECT recptrans.recpno,recptrans.idno,recptrans.rdate,recptrans.rmks as narration,recptrans.is_deposit as transactiontype,recptrans.invoicenopaid FROM recptrans WHERE recptrans.idno='$tenantid' AND recptrans.revsd=0 AND property_id='$propid' AND rdate between '$startdate' AND '$enddate' ") or die($mysqli->error);

        $res = $mysqli->query("SELECT invoices.invoiceno,invoices.invoicedate,invoices.amount as credit,invoices.remarks as narration FROM invoices WHERE invoices.idno='$tenantid' AND invoices.revsd=0 AND property_id='$propid' AND invoicedate between '$startdate' AND '$enddate' ") or die($mysqli->error);
        $entity = strtoupper(findtenantbyid($tenantid));
    } else {
        $res = $mysqli->query("SELECT invoices.invoiceno,invoices.idno,invoices.invoicedate,invoices.amount as credit,invoices.remarks as narration FROM invoices WHERE property_id='$propid' AND invoices.revsd=0 AND invoicedate between '$startdate' AND '$enddate'") or die($mysqli->error);
        //$res = $mysqli->query("SELECT recptrans.recpno,recptrans.idno,recptrans.rdate,recptrans.rmks as narration,recptrans.is_deposit as transactiontype,recptrans.invoicenopaid FROM recptrans WHERE recptrans.revsd=0 AND property_id='$propid' AND rdate between '$startdate' AND '$enddate' ") or die($mysqli->error);
        $entity = 'ALL';
    }
    echo '<table class="treport" ><thead>
<tr><td><img src="../images/cursors/logo1.png" style="height:100px;width:140px;"></td><td colspan="8" style="background-color:beige"><h3><center>STATEMENT FOR <u>' . $entity . '</u> OF <i>' . strtoupper(findpropertybyid($propid)) . '</i>&nbsp;FOR THE PERIOD&nbsp;' . $startdate . '&nbsp;TO&nbsp;' . $enddate . '</center></h3></td></tr>
<tr>
<th><center><u>Invoice/Receipt No</u></center></th>
<th><center><u>Date</u></center></th>
<th><center><u>Transaction</u></center></th>
<th><center><u>Customer/Tenant Name</u></center></th>
<th><center><u>House</u></center></th>
<th><center><u>Narration</u></center></th>
<th><center><u>Debit</center></u></th>
<th><center><u>Credit</center></u></th>
<th><center><u>Balance</center></u></th></tr></thead><tbody>';
    while ($row = $res->fetch_assoc()) {
//get all payments from recptrans for a given invoice 

        $invoicedetails['recpno'] = $row['invoiceno'];
        $invoicedetails['rdate'] = $row['invoicedate'];
        $tenantdetails = getTenantDetails($row['idno']);
        $invoicedetails['name'] = $tenantdetails['name'];
        $aptid = getApartmentFromTenant($row['idno']);
        $aptdetails = getApartmentDetails($aptid);
        $invoicedetails['aptname'] = $aptdetails[0]['apt_tag'];
        $invoicedetails['remarks'] = $row['narration'];
        $invoice = getInvoiceDetails($row['invoiceno']);
        if($invoice['invoicecreditnote'] == 1){ 
            $invamn = $invoice['amount']*-1;
        }else{
            $invamn = $invoice['amount'];
        }
        $invoicedetails['credit'] = $invamn;
        $invoicedetails['transaction_type'] = $row['transactiontype'];

        $invoicedetails['paidamount'] = getInvoiceReceipts($row['invoiceno']);
        array_push($allinvoicedetails, $invoicedetails);
    }

    foreach ($allinvoicedetails as $invoicedetail) {
        $paidamount = $invoicedetail['paidamount'];
        $bal = $invoicedetail['credit'] - $paidamount;
        array_push($suminvoiceamount, $invoicedetail['credit']);
        array_push($sumpaidamount, $paidamount);
        array_push($sumbal, $bal);
        echo '<tr><td>' . $invoicedetail['recpno'] . '</td><td>' . date('d-m-Y', strtotime($invoicedetail['rdate'])) . '</td><td>' . $invoicedetail['transaction_type'] . '</td><td>' . $invoicedetail['name'] . '</td><td>' . $invoicedetail['aptname'] . '</td><td>&nbsp;&nbsp;&nbsp;&nbsp;' . $invoicedetail['remarks'] . '</td><td style="color:red">&nbsp;&nbsp;' . number_format($invoicedetail['credit']) . '</td><td style="color:green">&nbsp;&nbsp;' . number_format($paidamount) . '</td><td>&nbsp;&nbsp;' . $bal . '</td></tr>';
    }
    echo '</tbody>';

    echo '<tfoot><tr><td><b>GRAND TOTAL</b></td><td></td><td></td><td></td><td></td><td></td><td><b>' . number_format(@array_sum($suminvoiceamount), 2) . '</b></td><td><b>' . number_format(@array_sum($sumpaidamount), 2) . '</b></td><td><b>' . number_format(@array_sum($sumbal), 2) . '</b></td></tr></tfoot>';
    echo '</table>';
    mysqli_close($mysqli);
}
// function is_admin(){

//  return $_SESSION['usergroup']==1?:true:false;
// }
// function is_cashier(){
//  return $_SESSION['usergroup']==4?:true:false;
// }
// function is_office_officer(){
//  return $_SESSION['usergroup']==2?:true:false;
// }
// function is_office_manager(){
//  return $_SESSION['usergroup']==5?:true:false;
// }
// function land_lord(){
//  return $_SESSION['usergroup']==2?:true:false;
// }
function fetchstatement13($tenantid, $propid, $startdate, $enddate, $count, $allpropertiesflag)
{
    include_once '../includes/config.php';
    $mysqli = getMysqliConnection();
    date_default_timezone_set('Africa/Nairobi');
    $startdate = date("Y-m-d", strtotime($startdate));
    $enddate = date("Y-m-d", strtotime($enddate));
    $invoicenos = array();
    $invoicedetails = array();
    $allinvoicedetails = array();
    $suminvoiceamount = array();
    $sumpaidamount = array();
    $sumbal = array();

    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") ";
    }
    if ($count == '0') {
        //$res = $mysqli->query("SELECT recptrans.recpno,recptrans.idno,recptrans.rdate,recptrans.rmks as narration,recptrans.is_deposit as transactiontype,recptrans.invoicenopaid FROM recptrans WHERE recptrans.idno='$tenantid' AND recptrans.revsd=0 AND property_id='$propid' AND rdate between '$startdate' AND '$enddate' ") or die($mysqli->error);

        $res = $mysqli->query("SELECT invoices.invoiceno,invoices.invoicedate,invoices.amount as credit,invoices.remarks as narration FROM invoices WHERE invoices.idno='$tenantid' AND invoices.revsd=0 AND property_id='$propid' AND invoicedate between '$startdate' AND '$enddate' ") or die($mysqli->error);
        $entity = strtoupper(findtenantbyid($tenantid));
    } else {
        $res = $mysqli->query("SELECT invoices.invoiceno,invoices.idno,invoices.invoicedate,invoices.amount as credit,invoices.remarks as narration FROM invoices WHERE property_id='$propid' AND invoices.revsd=0 AND invoicedate between '$startdate' AND '$enddate'") or die($mysqli->error);
        //$res = $mysqli->query("SELECT recptrans.recpno,recptrans.idno,recptrans.rdate,recptrans.rmks as narration,recptrans.is_deposit as transactiontype,recptrans.invoicenopaid FROM recptrans WHERE recptrans.revsd=0 AND property_id='$propid' AND rdate between '$startdate' AND '$enddate' ") or die($mysqli->error);
        $entity = 'ALL';
    }
    echo '<table class="treport" ><thead>
<tr><td><img src="../images/cursors/logo1.png" style="height:100px;width:140px;"></td><td colspan="8" style="background-color:beige"><h3><center>STATEMENT FOR <u>' . $entity . '</u> OF <i>' . strtoupper(findpropertybyid($propid)) . '</i>&nbsp;FOR THE PERIOD&nbsp;' . $startdate . '&nbsp;TO&nbsp;' . $enddate . '</center></h3></td></tr>
<tr>
<th><center><u>Invoice/Receipt No</u></center></th>
<th><center><u>Date</u></center></th>
<th><center><u>Transaction</u></center></th>
<th><center><u>Customer/Tenant Name</u></center></th>
<th><center><u>House</u></center></th>
<th><center><u>Narration</u></center></th>
<th><center><u>Debit</center></u></th>
<th><center><u>Credit</center></u></th>
<th><center><u>Balance</center></u></th></tr></thead><tbody>';
    while ($row = $res->fetch_assoc()) {
        //get all payments from recptrans for a given invoice 

        $invoicedetails['recpno'] = $row['invoiceno'];
        $invoicedetails['rdate'] = $row['invoicedate'];
        $tenantdetails = getTenantDetails($row['idno']);
        $invoicedetails['name'] = $tenantdetails['name'];
        $aptid = getApartmentFromTenant($row['idno']);
        $aptdetails = getApartmentDetails($aptid);
        $invoicedetails['aptname'] = $aptdetails[0]['apt_tag'];
        $invoicedetails['remarks'] = $row['narration'];
        $invoice = getInvoiceDetails($row['invoiceno']);
        if ($invoice['invoicecreditnote'] == 1) {
            $invamn = $invoice['amount'] * -1;
        } else {
            $invamn = $invoice['amount'];
        }
        $invoicedetails['credit'] = $invamn;
        $invoicedetails['transaction_type'] = $row['transactiontype'];

        $invoicedetails['paidamount'] = getInvoiceReceipts($row['invoiceno']);
        array_push($allinvoicedetails, $invoicedetails);
    }

    foreach ($allinvoicedetails as $invoicedetail) {
        $paidamount = $invoicedetail['paidamount'];
        $bal = $invoicedetail['credit'] - $paidamount;
        array_push($suminvoiceamount, $invoicedetail['credit']);
        array_push($sumpaidamount, $paidamount);
        array_push($sumbal, $bal);
        echo '<tr><td>' . $invoicedetail['recpno'] . '</td><td>' . date('d-m-Y', strtotime($invoicedetail['rdate'])) . '</td><td>' . $invoicedetail['transaction_type'] . '</td><td>' . $invoicedetail['name'] . '</td><td>' . $invoicedetail['aptname'] . '</td><td>&nbsp;&nbsp;&nbsp;&nbsp;' . $invoicedetail['remarks'] . '</td><td style="color:red">&nbsp;&nbsp;' . number_format($invoicedetail['credit']) . '</td><td style="color:green">&nbsp;&nbsp;' . number_format($paidamount) . '</td><td>&nbsp;&nbsp;' . $bal . '</td></tr>';
    }
    echo '</tbody>';

    echo '<tfoot><tr><td><b>GRAND TOTAL</b></td><td></td><td></td><td></td><td></td><td></td><td><b>' . number_format(@array_sum($suminvoiceamount), 2) . '</b></td><td><b>' . number_format(@array_sum($sumpaidamount), 2) . '</b></td><td><b>' . number_format(@array_sum($sumbal), 2) . '</b></td></tr></tfoot>';
    echo '</table>';
    mysqli_close($mysqli);
}
function fetchstatement3($tenantid, $propid, $startdate, $enddate, $count, $allpropertiesflag) {
   
    include_once '../includes/config.php';
    $mysqli = getMysqliConnection();
    date_default_timezone_set('Africa/Nairobi');
    $startdate = date("Y-m-d", strtotime($startdate));
    $enddate = date("Y-m-d", strtotime($enddate));
    $invoicenos = array();
    $invoicedetails = array();
    $allinvoicedetails = array();
    $suminvoiceamount = array();
    $sumpaidamount = array();
    $sumbal = array();
   
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") ";
    }
    if ($count == '0') {
        //$res = $mysqli->query("SELECT recptrans.recpno,recptrans.idno,recptrans.rdate,recptrans.rmks as narration,recptrans.is_deposit as transactiontype,recptrans.invoicenopaid FROM recptrans WHERE recptrans.idno='$tenantid' AND recptrans.revsd=0 AND property_id='$propid' AND rdate between '$startdate' AND '$enddate' ") or die($mysqli->error);

        $res = $mysqli->query("SELECT invoices.invoiceno,invoices.invoicedate,invoices.amount as credit,invoices.remarks as narration FROM invoices WHERE invoices.idno='$tenantid' AND invoices.revsd=0 AND property_id='$propid' AND invoicedate between '$startdate' AND '$enddate' ") or die($mysqli->error);
        $entity = strtoupper(findtenantbyid($tenantid));
    } else {
        $res = $mysqli->query("SELECT invoices.invoiceno,invoices.idno,invoices.invoicedate,invoices.amount as credit,invoices.remarks as narration FROM invoices WHERE property_id='$propid' AND invoices.revsd=0 AND invoicedate between '$startdate' AND '$enddate'") or die($mysqli->error);
        //$res = $mysqli->query("SELECT recptrans.recpno,recptrans.idno,recptrans.rdate,recptrans.rmks as narration,recptrans.is_deposit as transactiontype,recptrans.invoicenopaid FROM recptrans WHERE recptrans.revsd=0 AND property_id='$propid' AND rdate between '$startdate' AND '$enddate' ") or die($mysqli->error);
        $entity = 'ALL';
    }
    echo '<table class="treport" ><thead>
<tr><td><img src="../images/cursors/logo1.png" style="height:100px;width:140px;"></td><td colspan="9" style="background-color:beige"><h3><center>STATEMENT FOR <u>' . $entity . '</u> OF <i>' . strtoupper(findpropertybyid($propid)) . '</i>&nbsp;FOR THE PERIOD&nbsp;' . $startdate . '&nbsp;TO&nbsp;' . $enddate . '</center></h3></td></tr>
<tr>
<th><center><u>Invoice/Receipt No</u></center></th>
<th><center><u>Date</u></center></th>
<th><center><u>Transaction</u></center></th>
<th><center><u>Customer/Tenant Name</u></center></th>
<th><center><u>House</u></center></th>
<th><center><u>Narration</u></center></th>
<th><center><u>Debit</center></u></th>
<th><center><u>Credit</center></u></th>
<th><center><u>Balance</center></u></th>

</tr></thead><tbody>';
    while ($row = $res->fetch_assoc()) {
//get all payments from recptrans for a given invoice 

        $invoicedetails['recpno'] = $row['invoiceno'];
        $invoicedetails['rdate'] = $row['invoicedate'];
        $tenantdetails = getTenantDetails($row['idno']);
        $invoicedetails['name'] = $tenantdetails['name'];
        $aptid = getApartmentFromTenant($row['idno']);
        $aptdetails = getApartmentDetails($aptid);
        $invoicedetails['aptname'] = $aptdetails[0]['apt_tag'];
        $invoicedetails['remarks'] = $row['narration'];
        $invoice = getInvoiceDetails($row['invoiceno']);
        if($invoice['invoicecreditnote'] == 1){ 
            $invamn = $invoice['amount']*-1;
        }else{
            $invamn = $invoice['amount'];
        }
        $invoicedetails['credit'] = $invamn;
        $invoicedetails['transaction_type'] = $row['transactiontype'];
      //  die($row['invoiceno']);
        $invoicedetails['paidamount'] = getInvoiceReceipts($row['invoiceno']);
       // echo "<br>ss".getInvoiceReceipts($row['invoiceno'])."<br/>";
        array_push($allinvoicedetails, $invoicedetails);
     
    }
    //die(print_r($allinvoicedetails));
    foreach ($allinvoicedetails as $invoicedetail) {
        
        $paidamount = $invoicedetail['paidamount'];
        $bal = $invoicedetail['credit'] - $paidamount;
        array_push($suminvoiceamount, $invoicedetail['credit']);
        array_push($sumpaidamount, $paidamount);
        array_push($sumbal, $bal);
        $credit=$invoicedetail['credit'];
        if($bal<=0){
            $percent=100;
        }
            else{
                $percent= number_format($paid/$credit*100);
            }
        echo '<tr><td>' . $invoicedetail['recpno'] . '</td><td>' . date('d-m-Y', strtotime($invoicedetail['rdate'])) . '</td><td>' . $invoicedetail['transaction_type'] . '</td><td>' . $invoicedetail['name'] . '</td><td>' . $invoicedetail['aptname'] . '</td><td>&nbsp;&nbsp;&nbsp;&nbsp;' . $invoicedetail['remarks'] . '</td><td style="color:red">&nbsp;&nbsp;' . number_format($invoicedetail['credit']) . '</td><td style="color:green">&nbsp;&nbsp;' . number_format($paidamount) . '</td><td>&nbsp;&nbsp;' . $bal . '</td></tr>';
    }
    echo '</tbody>';

    echo '<tfoot><tr><td><b>GRAND TOTAL</b></td><td></td><td></td><td></td><td></td><td></td><td><b>' . number_format(@array_sum($suminvoiceamount), 2) . '</b></td><td><b>' . number_format(@array_sum($sumpaidamount), 2) . '</b></td><td><b>' . number_format(@array_sum($sumbal), 2) . '</b></td></tr></tfoot>';
    echo '</table>';
    mysqli_close($mysqli);
}
function fetchstatement4($tenantid, $propid, $startdate, $enddate, $count, $allpropertiesflag,$percentage) {
   
    include_once '../includes/config.php';
    $mysqli = getMysqliConnection();
    date_default_timezone_set('Africa/Nairobi');
    $startdate = date("Y-m-d", strtotime($startdate));
    $enddate = date("Y-m-d", strtotime($enddate));
    $invoicenos = array();
    $invoicedetails = array();
    $allinvoicedetails = array();
    $suminvoiceamount = array();
    $sumpaidamount = array();
    $sumbal = array();
   
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") ";
    }
    if ($count == '0') {
        //$res = $mysqli->query("SELECT recptrans.recpno,recptrans.idno,recptrans.rdate,recptrans.rmks as narration,recptrans.is_deposit as transactiontype,recptrans.invoicenopaid FROM recptrans WHERE recptrans.idno='$tenantid' AND recptrans.revsd=0 AND property_id='$propid' AND rdate between '$startdate' AND '$enddate' ") or die($mysqli->error);

        $res = $mysqli->query("SELECT invoices.invoiceno,invoices.invoicedate,invoices.amount as credit,invoices.remarks as narration FROM invoices WHERE invoices.idno='$tenantid' AND invoices.revsd=0 AND property_id='$propid' AND invoicedate between '$startdate' AND '$enddate' ") or die($mysqli->error);
        $entity = strtoupper(findtenantbyid($tenantid));
    } else {
        $res = $mysqli->query("SELECT invoices.invoiceno,invoices.idno,invoices.invoicedate,invoices.amount as credit,invoices.remarks as narration FROM invoices WHERE property_id='$propid' AND invoices.revsd=0 AND invoicedate between '$startdate' AND '$enddate'") or die($mysqli->error);
        //$res = $mysqli->query("SELECT recptrans.recpno,recptrans.idno,recptrans.rdate,recptrans.rmks as narration,recptrans.is_deposit as transactiontype,recptrans.invoicenopaid FROM recptrans WHERE recptrans.revsd=0 AND property_id='$propid' AND rdate between '$startdate' AND '$enddate' ") or die($mysqli->error);
        $entity = 'ALL';
    }
    echo '<table class="treport" ><thead>
<tr><td><img src="../images/cursors/logo1.png" style="height:100px;width:140px;"></td><td colspan="9" style="background-color:beige"><h3><center>STATEMENT FOR <u>' . $entity . '</u> OF <i>' . strtoupper(findpropertybyid($propid)) . '</i>&nbsp;FOR THE PERIOD&nbsp;' . $startdate . '&nbsp;TO&nbsp;' . $enddate . '</center></h3></td></tr>
<tr>
<th><center><u>Invoice/Receipt No</u></center></th>
<th><center><u>Date</u></center></th>
<th><center><u>Transaction</u></center></th>
<th><center><u>Customer/Tenant Name</u></center></th>
<th><center><u>House</u></center></th>
<th><center><u>Narration</u></center></th>
<th><center><u>Debit</center></u></th>
<th><center><u>Credit</center></u></th>
<th><center><u>Balance</center></u></th>
<th><center><u>Percent(%)</center></u></th>

</tr></thead><tbody>';
    while ($row = $res->fetch_assoc()) {
//get all payments from recptrans for a given invoice 

        $invoicedetails['recpno'] = $row['invoiceno'];
        $invoicedetails['rdate'] = $row['invoicedate'];
        $tenantdetails = getTenantDetails($row['idno']);
        $invoicedetails['name'] = $tenantdetails['name'];
        $aptid = getApartmentFromTenant($row['idno']);
        $aptdetails = getApartmentDetails($aptid);
        $invoicedetails['aptname'] = $aptdetails[0]['apt_tag'];
        $invoicedetails['remarks'] = $row['narration'];
        $invoice = getInvoiceDetails($row['invoiceno']);
        if($invoice['invoicecreditnote'] == 1){ 
            $invamn = $invoice['amount']*-1;
        }else{
            $invamn = $invoice['amount'];
        }
        $invoicedetails['credit'] = $invamn;
        $invoicedetails['transaction_type'] = $row['transactiontype'];

        $invoicedetails['paidamount'] = getInvoiceReceipts($row['invoiceno']);
        array_push($allinvoicedetails, $invoicedetails);
    }

    foreach ($allinvoicedetails as $invoicedetail) {
        $paidamount = $invoicedetail['paidamount'];
        $bal = $invoicedetail['credit'] - $paidamount;
        array_push($suminvoiceamount, $invoicedetail['credit']);
        array_push($sumpaidamount, $paidamount);
        array_push($sumbal, $bal);
        $credit=$invoicedetail['credit'];
        if($bal<=0){
            $percent=100;
        }
            else{
                $percent= number_format($paid/$credit*100);
            }
          

        echo '<tr><td>' . $invoicedetail['recpno'] . '</td><td>' . date('d-m-Y', strtotime($invoicedetail['rdate'])) . '</td><td>' . $invoicedetail['transaction_type'] . '</td><td>' . $invoicedetail['name'] . '</td><td>' . $invoicedetail['aptname'] . '</td><td>&nbsp;&nbsp;&nbsp;&nbsp;' . $invoicedetail['remarks'] . '</td><td style="color:red">&nbsp;&nbsp;' . number_format($invoicedetail['credit']) . '</td><td style="color:green">&nbsp;&nbsp;' . number_format($paidamount) . '</td><td>&nbsp;&nbsp;' . $bal . '</td><td>'.$percent.'</td></tr>';
    }
    echo '</tbody>';

    echo '<tfoot><tr><td><b>GRAND TOTAL</b></td><td></td><td></td><td></td><td></td><td></td><td><b>' . number_format(@array_sum($suminvoiceamount), 2) . '</b></td><td><b>' . number_format(@array_sum($sumpaidamount), 2) . '</b></td><td><b>' . number_format(@array_sum($sumbal), 2) . '</b></td></tr></tfoot>';
    echo '</table>';
    mysqli_close($mysqli);
}

function getAgentStatement($startdate, $enddate, $propid, $user, $allpropertiesflag = 1) {

    date_default_timezone_set('Africa/Nairobi');
    $time = date('h:i A');
  $mysqli = getMysqliConnection();
    $tablename = "recptrans";
    $tablename2 = "tenants";
    $tablename3 = "vatamounts";
    $sumpenaltyamount = array();
   // $startdate = date("Y-m-d", strtotime($startdate));
    $myDateTimee = DateTime::createFromFormat('d/m/Y', trim($startdate));
    $startdate = $myDateTimee->format('Y-m-d');
    $myDateTime = DateTime::createFromFormat('d/m/Y', trim($enddate));
    $enddate = $myDateTime->format('Y-m-d');
    
    // $enddate=date("Y-m-d",strtotime(trim($enddate));
    //penalties as income
    $penalties = fetchPaidPenalties($startdate, $enddate, $count = 0);
    foreach ($penalties as $penalty) {
        array_push($sumpenaltyamount, $penalty['paidamount']);
    }



    $q2 = $mysqli->query("SELECT amount from $tablename3 WHERE vatid=1") or die($mysqli->error);
    $sumamount = array();
    $sumcommmamount = array();
    $sumtotalamount = array();
    while ($row1 = mysqli_fetch_array($q2)) {
        $tenantVAT += $row1['amount'];
    }

    if ($allpropertiesflag) {
        
        $allproperties = getProperties();
        echo '<div class="dvData"><table class="treport1" style="width:900px !important"><thead>
<tr><td colspan="11"><center><span style="font-size:15px;font-weight:normal;float:left;"> <b>AGENT STATEMENT</b></span><span style="font-size:18px;font-weight:bold">' . $_SESSION['clientname'] . '</span><span style="font-size:18px;font-weight:normal; float:right;"></span><center>
<br/><span style="font-size:16px;font-weight:normal;"><centre>' . str_repeat('&nbsp;', 25) . 'AGENT STATEMENT From <b> ' . date("d-m-Y", strtotime($startdate)) . '</b>  To  <b>' . date("d-m-Y", strtotime($enddate)) . '</b></center></span></td></tr>';
        echo'<tr>
<u><th>T/AC</th><th>L/AC</th><th>Property</th><th>House</th> <td>Tenant/Client</td><th>Receipt/Payno No</th><th>Paymode</th><th>Receipt/Pay Date</th><th>Narration</th> <th>Amount</th><th>Commission</th></u></tr>';
        echo '</thead><tbody>';
        $receipts = array();
        
        foreach ($allproperties as $property) {
            
            $propid1 = $property['property_id'];

            $commission = getPropertyCommissionRate($propid1);
//echo "SELECT $tblename.recpno,$tablename.pmode,$tablename.invoicenopaid,$tablename.rdate,$tablename.rmks,$tablename.amount,$tablename2.Id,$tablename2.tenant_name,$tablename2.Apartment_tag,$tablename2.property_name FROM $tablename LEFT JOIN $tablename2 ON $tablename.idno=$tablename2.Id WHERE ($tablename.rdate BETWEEN '$startdate' AND '$enddate') AND $tablename.is_deleted=0 AND $tablename2.property_id='$propid1' AND `is_deposit`='R' ORDER BY $tablename2.Apartment_tag ASC <br>";
            $res = $mysqli->query("SELECT $tablename.recpno,$tablename.pmode,$tablename.invoicenopaid,$tablename.rdate,$tablename.rmks,$tablename.amount,$tablename2.Id,$tablename2.tenant_name,$tablename2.Apartment_tag,$tablename2.property_name FROM $tablename LEFT JOIN $tablename2 ON $tablename.idno=$tablename2.Id WHERE ($tablename.rdate BETWEEN '$startdate' AND '$enddate') AND $tablename.is_deleted=0 AND $tablename2.property_id='$propid1' ORDER BY $tablename2.Apartment_tag ASC ") or die($mysqli->error);// or die($db->error());
//print_r($res);
            $i = 1;
               while ($row = $res->fetch_assoc()) {
               // print_r($row)."dsd";
                 //get invoice items 
                $commissionnotcharged = 0;
                $invoice = getInvoiceDetails($row['invoicenopaid']);
                $pmode = $row['pmode'];
                $paymode = getPayMode($pmode);
                $invoicevalue = $invoice['amount'];
                $invoiceitems = getInvoiceItemsAndValue($row['invoicenopaid']);

                foreach ($invoiceitems as $item) {
                    $itemdetails = getChargeItemByName($item['name'], $propid1);
                    if ($itemdetails['commission_charged'] == 0 || $invoice['is_penalty'] == 1) {
                        //get the item value as a % of the total amount 
                        if ($itemdetails['amount'])
                     $commissionnotcharged = $commissionnotcharged + (($itemdetails['amount'] / $invoicevalue) * ($commission / 100) * $invoicevalue);
                        // echo $commissionnotcharged;
                    }
                }
                if ($invoice['is_penalty']) {
                    $commamount = 0;
                } else {
                    $commamount = (($commission / 100) * $row['amount']) - $commissionnotcharged;
                }

                $netamount = $row['amount'] - $commamount;

                //if receipt is not already in array
                // print($row['recpno']);
                if (!in_array($row['recpno'], $receipts)) {
                    
                    //add receipts to array
                    array_push($receipts, $row['recpno']);
                    echo '<tr><td>' . $row['Id'] . '</td><td>' . $propid1 . '</td><td>' . findpropertybyid($propid1) . '</td><td>' . $row['Apartment_tag'] . '</td><td>' . $row['tenant_name'] . '</td><td><a class="whitetext"  href="defaultreports.php?report=printreceipt&receiptno=' . $row['recpno'] . '" target="blank"><span style="color:blue">' . $row['recpno'] . '</span></a></td><td>' . $paymode[0]['paymode'] . '</td><td>' . date("d-m-Y", strtotime($row['rdate'])) . '<td>' . $row['rmks'] . '</td><td>' . number_format($row['amount'], 2) . '</td><td>' . number_format($commamount, 2) . '</td></tr>';
                    array_push($sumamount, (float) $row['amount']);
                    array_push($sumcommmamount, (float) ($commamount));
                    array_push($sumtotalamount, (float) ($netamount));
                }
            }
        }
        echo '<tr><td><b>TOTAL COMMISSION</b></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td><b>' . number_format(@array_sum($sumamount), 2) . '</b></td><td><b>' . number_format(@array_sum($sumcommmamount), 2) . '</b></td></tr>';
        echo '<tr><td><b>TOTAL PENALTIES</b></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td><b>' . number_format(@array_sum($sumpenaltyamount), 2) . '</b></td></tr>';
        $resultpay = $mysqli->query("SELECT DISTINCT (paytrans.payno),paytrans.amount,paytrans.payno,paytrans.pmode,paytrans.paydate,paytrans.rmks,bkaccounts.acname,bills.bill_amnt,bills.bill_paid_amnt,(bills.bill_amnt -bills.bill_paid_amnt) as balance,bills.remarks FROM paytrans LEFT JOIN bkaccounts ON paytrans.supp_id=bkaccounts.glcode LEFT JOIN bills ON paytrans.billnopaid=bills.bill_no  WHERE paytrans.property_id='0' AND paytrans.paydate between '$startdate' AND '$enddate' GROUP BY payno") or die($mysqli->error);

        $counter = 1;
        while ($rowpay= $resultpay->fetch_assoc()) {
        
            $payno = $rowpay['payno'];
            $paydate = $rowpay['paydate'];
            $paymode = getPayMode($rowpay['pmode']);
            $rmks = $rowpay['remarks'];
            $billamount = $rowpay['amount'];
            $paidamount = $rowpay['bill_paid_amnt'];
            $balance = $rowpay['balance'];
            $sumbillamount[] = ($billamount);
            $sumpaidamount[] = $paidamount;
            $sumbal[] = $balance;
            echo '<tr><td></td><td></td><td></td><td></td><td></td><td><a href="defaultreports.php?report=printvoucher&voucherno=' . $payno . '&propid=' . $propid . '&user=' . $_SESSION['username'] . '" target="blank">' . $payno . '</a></td><td>' . $paymode[0]['paymode'] . '</td><td>' . date('d-m-Y', strtotime($paydate)) . '</td><td>&nbsp;' . $rmks . '</td><td></td><td style="color:red">&nbsp;&nbsp;' . number_format($paidamount) . '</td></tr>';
        }

        echo '</tbody>';
        echo '<tr><td><b>TOTAL EXPENSE</b></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td><b>' . number_format(@array_sum($sumpaidamount), 2) . '</b></td></tr>';

        echo '<tfoot><tr><td><b>NET AMOUNT</b></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td><b>' . number_format(((array_sum($sumcommmamount) + array_sum($sumpenaltyamount)) - array_sum($sumpaidamount)), 2) . '</b></td></tr></tfoot>';
        echo '</table></div>';
        echo '<hr/>';
        echo '<i>Printed by:</i> ' . $user . '&nbsp;&nbsp;&nbsp;&nbsp;' . date("d-m-y") . '&nbsp;' . $time;
    }
}

//arrears/prepayments
//fetch tenant/property rent statement
function fetcharrearsprepayment($tenantid, $propid,$fromdate,$enddate, $count, $flag) {
    include_once '../includes/config.php';
    $mysqli = getMysqliConnection();
    date_default_timezone_set('Africa/Nairobi');
    $accountsopening = getAccountsOpeningDate();
    //$startdate = date("Y-m-d", strtotime($accountsopening));

    $end=DateTime::createFromFormat("d/m/Y",$enddate);
     $enddate=$end->format("Y-m-d");
      $start=DateTime::createFromFormat("d/m/Y",$fromdate);
     $startdate=$start->format("Y-m-d");
    $invoicenos = array();
    $invoicedetails = array();
    $allinvoicedetails = array();
    $suminvoiceamount = array();
    $sumpaidamount = array();
    $sumbal = array();
    if ($flag == 'A') {
        $arrearsprepayments = 'Arrears';
    } else if ($flag == 'P') {
        $arrearsprepayments = 'Prepayment';
    }
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") ";
    }
    if ($count == '0') {
        $res = $mysqli->query("SELECT invoices.invoiceno,invoices.invoicedate,invoices.amount as credit,invoices.remarks as narration FROM invoices WHERE invoices.idno='$tenantid' AND invoices.revsd=0 AND property_id='$propid' AND invoicedate between '$startdate' AND '$enddate' ") or die($mysqli->error);
        $entity = strtoupper(findtenantbyid($tenantid));
        $property = findpropertybyid($propid);
    } elseif ($count == "2") {
        $res = $mysqli->query("SELECT invoices.property_id,invoices.invoiceno,invoices.idno,invoices.invoicedate,invoices.amount as credit,invoices.remarks as narration FROM invoices WHERE  invoices.revsd=0 AND invoicedate between '$startdate' AND '$enddate' ORDER BY invoices.property_id ASC ") or die($mysqli->error);
        $entity = 'ALL PROPERTIES';
        $property = "ALL PROPERTIES";
    } else {
        $res = $mysqli->query("SELECT invoices.invoiceno,invoices.idno,invoices.invoicedate,invoices.amount as credit,invoices.remarks as narration FROM invoices WHERE property_id='$propid' AND invoices.revsd=0 AND invoicedate between '$startdate' AND '$enddate'") or die($mysqli->error);
        $entity = 'ALL';
        $property = findpropertybyid($propid);
    }
    echo '
<table class="treport" border="0">
 <tr><td ><img src="../images/cursors/logo.jpeg" style="height:auto;width:140px;"></td><td colspan="9" style="background-color:beige"><h3><center>' . strtoupper($arrearsprepayments) . ' REPORT FOR <u>' . $entity . '</u> OF <i>' . strtoupper($property) . '</i>&nbsp;FOR THE PERIOD&nbsp;' . $startdate . '&nbsp;TO&nbsp;' . $enddate . '</center></h3></td></tr></table>  
    <table class=" sortable treport" ><thead>
<tr>
<th><center><u>Invoice No</u></center></th>
<th><center><u>Date</u></center></th>
<th><center><u>Property</u></center></th>
<th><center><u>Customer/Tenant Name</u></center></th>
<th><center><u>House</u></center></th>
<th><center><u>Narration</u></center></th>
<th><center><u>Credit</center></u></th>
<th><center><u>Debit</center></u></th>
<th><center><u>' . $arrearsprepayments . '</center></u></percent></th><th>Percent</th></tr></thead><tbody>';
    while ($row = $res->fetch_assoc()) {
//get all payments from recptrans for a given invoice 

        $invoicedetails['invoiceno'] = $row['invoiceno'];
        $invoicedetails['invoicedate'] = $row['invoicedate'];
        $invoicedetails['propertyname'] = findpropertybyid($row['property_id']);
        $tenantdetails = getTenantDetails($row['idno']);
       $invoicedetails['name'] = $tenantdetails['name'].'('.$tenantdetails['phone'].':'.$tenantdetails['email'].'N/Kin:'.$tenantdetails['kinsname'].'-'.$tenantdetails['kinstel'].')';
        $aptid = getApartmentFromTenant($row['idno']);
        $aptdetails = getApartmentDetails($aptid);
        $invoicedetails['aptname'] = $aptdetails[0]['apt_tag'];
        $invoicedetails['remarks'] = $row['narration'];
        $invoicedetails['credit'] = $row['credit'];
               $invoicedetails['paidamount'] = getInvoiceReceipts($row['invoiceno']);
        array_push($allinvoicedetails, $invoicedetails);
        unset($invoicedetails);
    }

    foreach ($allinvoicedetails as $invoicedetail) {
        $paidamount = $invoicedetail['paidamount'];
        $bal = $invoicedetail['credit'] - $paidamount;
        $credit=$invoicedetail['credit'];
        if($bal<=0){
            $percent=100;
        }
            else{
                $percent= $paidamount/$credit*100;
            }
        //arrears
        if ( $flag == 'A') {
            array_push($suminvoiceamount, $invoicedetail['credit']);
            array_push($sumpaidamount, $paidamount);
            array_push($sumbal, $bal);
            if($bal>0){
            echo '<tr><td>' . $invoicedetail['invoiceno'] . '</td><td>' . date('d-m-Y', strtotime($invoicedetail['invoicedate'])) . '</td><td>' . $invoicedetail['propertyname'] . '</td><td>' . $invoicedetail['name'] . '</td><td>' . $invoicedetail['aptname'] . '</td><td>&nbsp;&nbsp;&nbsp;&nbsp;' . $invoicedetail['remarks'] . '</td><td style="color:red">&nbsp;&nbsp;' . number_format($invoicedetail['credit']) . '</td><td style="color:green">&nbsp;&nbsp;' . number_format($paidamount) . '</td><td>&nbsp;&nbsp;' . $bal . '</td><td>'.$percent.'</td></tr>';
        }
        } else if ($flag == 'P') {
            array_push($suminvoiceamount, $invoicedetail['credit']);
            array_push($sumpaidamount, $paidamount);
            array_push($sumbal, $bal);
            if($bal>0){
            echo '<tr><td>' . $invoicedetail['invoiceno'] . '</td><td>' . date('d-m-Y', strtotime($invoicedetail['invoicedate'])) . '<td>' . $invoicedetail['propertyname'] . '</td></td><td>' . $invoicedetail['name'] . '</td><td>' . $invoicedetail['aptname'] . '</td><td>&nbsp;&nbsp;&nbsp;&nbsp;' . $invoicedetail['remarks'] . '</td><td style="color:red">&nbsp;&nbsp;' . number_format($invoicedetail['credit']) . '</td><td style="color:green">&nbsp;&nbsp;' . number_format($paidamount) . '</td><td>&nbsp;&nbsp;' . $bal . '</td></tr>';
        }
        }
    }
    echo '</tbody>';

    echo '<tfoot><tr><td><b>GRAND TOTAL</b></td><td></td><td></td><td></td><td></td><td></td><td><b>' . number_format(@array_sum($suminvoiceamount), 2) . '</b></td><td><b>' . number_format(@array_sum($sumpaidamount), 2) . '</b></td><td><b>' . number_format(@array_sum($sumbal), 2) . '</b></td></tr></tfoot>';
    echo '</table>';
    $mysqli->close();
}
//performance
function fetchplotperformanceAll($propid,$fromdate,$enddate,$flag){
    $fdate=$fromdate;
    $edate=$enddate;
//    // die(var_dump($flag));
//     if($flag=="four"){
//         // die('dd');
//     }
  include_once '../includes/config.php';
    $mysqli = getMysqliConnection();
    date_default_timezone_set('Africa/Nairobi');
    $accountsopening = getAccountsOpeningDate();
    //$startdate = date("Y-m-d", strtotime($accountsopening));

    $end=DateTime::createFromFormat("m/d/Y",$enddate);
     $enddate=$end->format("Y-m-d");
      $start=DateTime::createFromFormat("m/d/Y",$fromdate);
     $startdate=$start->format("Y-m-d");
    $invoicenos = array();
    $invoicedetails = array();
    $allinvoicedetails = array();
    $suminvoiceamount = array();
    $sumpaidamount = array();
    $sumbal = array();
  
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") ";
    }
    //die($startdate);
   
     // $res = $mysqli->query
     //    ("SELECT invoices.invoiceno,invoices.idno,invoices.invoicedate,invoices.amount as credit,invoices.remarks as narration FROM invoices WHERE property_id='$propid' AND invoices.revsd=0 AND invoicedate between '$startdate' AND '$enddate'") or die($mysqli->error);
     //    $entity = 'ALL';
        $property = findpropertybyid($propid);
    
    echo '
<table class="treport" border="0">
 <tr><td ><img src="../images/cursors/logo.jpeg" style="height:auto;width:140px;"></td><td colspan="8" style="background-color:beige"><h3><center>' . strtoupper($arrearsprepayments) . ' REPORT FOR <u>' . $entity . '</u> OF <i>' . strtoupper($property) . '</i>&nbsp;FOR THE PERIOD&nbsp;' . $startdate . '&nbsp;TO&nbsp;' . $enddate . '</center></h3></td></tr></table>  
    <table class=" sortable treport" ><thead>
<tr>
<th><center><u>No</u></center></th>

<th><center><u>Property</u></center></th>


<th><center><u>Debit</center></u></th>
<th><center><u>Credit</center></u></th>
<th><center><u>Balance</center></u></percent></th><th>Percent</th></tr></thead><tbody>';

//die(print_r($allproperties));
if($flag=="four"){
    $agent_id=$_REQUEST['agentid'];
    $res = $mysqli->query("select prop.property_id , sum(prop.debit)as debit,sum(prop.credit) as credit,sum(prop.bal) as bal from (select property_id,x.idno,ifnull(sum(x.credit),0) as debit,ifnull(sum(x.debit),0) as credit,(ifnull(sum(x.credit),0)-ifnull(sum(x.debit),0)) as bal from (SELECT property_id,invoices.idno,invoices.amount as credit,(SELECT sum(amount) as debit FROM recptrans WHERE invoicenopaid=invoices.invoiceno AND revsd=0  ) as debit FROM invoices  where   invoices.revsd=0 AND invoicedate between '$startdate' AND '$enddate' )x group by x.idno)  prop where prop.property_id in(SELECT property_id FROM `agentproperty` WHERE  agent_id= $agent_id ) group by prop.property_id") or die($mysqli->error);
    $total=$mysqli->query("select  sum(prop.debit)as debit,sum(prop.credit) as credit,sum(prop.bal) as bal from (select property_id,x.idno,ifnull(sum(x.credit),0) as debit,ifnull(sum(x.debit),0) as credit,(ifnull(sum(x.credit),0)-ifnull(sum(x.debit),0)) as bal from (SELECT property_id,invoices.idno,invoices.amount as credit,(SELECT sum(amount) as debit FROM recptrans WHERE invoicenopaid=invoices.invoiceno AND revsd=0  ) as debit FROM invoices  where   invoices.revsd=0 AND invoicedate between '$startdate' AND '$enddate' )x group by x.idno) prop where prop.property_id in(SELECT property_id FROM `agentproperty` WHERE  agent_id= $agent_id )");

}else{
    $res = $mysqli->query("select prop.property_id , sum(prop.debit)as debit,sum(prop.credit) as credit,sum(prop.bal) as bal from (select property_id,x.idno,ifnull(sum(x.credit),0) as debit,ifnull(sum(x.debit),0) as credit,(ifnull(sum(x.credit),0)-ifnull(sum(x.debit),0)) as bal from (SELECT property_id,invoices.idno,invoices.amount as credit,(SELECT sum(amount) as debit FROM recptrans WHERE invoicenopaid=invoices.invoiceno AND revsd=0  ) as debit FROM invoices  where   invoices.revsd=0 AND invoicedate between '$startdate' AND '$enddate' )x group by x.idno) prop  group by prop.property_id") or die($mysqli->error);
    $total=$mysqli->query("select  sum(prop.debit)as debit,sum(prop.credit) as credit,sum(prop.bal) as bal from (select property_id,x.idno,ifnull(sum(x.credit),0) as debit,ifnull(sum(x.debit),0) as credit,(ifnull(sum(x.credit),0)-ifnull(sum(x.debit),0)) as bal from (SELECT property_id,invoices.idno,invoices.amount as credit,(SELECT sum(amount) as debit FROM recptrans WHERE invoicenopaid=invoices.invoiceno AND revsd=0  ) as debit FROM invoices  where   invoices.revsd=0 AND invoicedate between '$startdate' AND '$enddate' )x group by x.idno) prop");

}
        //select ag.agent_id as agents,prop.property_id , sum(prop.debit)as debit,sum(prop.credit) as credit,sum(prop.bal) as bal from (select property_id,x.idno,ifnull(sum(x.credit),0) as debit,ifnull(sum(x.debit),0) as credit,(ifnull(sum(x.credit),0)-ifnull(sum(x.debit),0)) as bal from (SELECT property_id,invoices.idno,invoices.amount as credit,(SELECT sum(amount) as debit FROM recptrans WHERE invoicenopaid=invoices.invoiceno AND revsd=0 ) as debit FROM invoices where invoices.revsd=0 AND invoicedate )x group by x.idno) prop inner join agentproperty ag on ag.property_id=prop.property_id group by prop.property_id       //  
         $totals=$total->fetch_assoc();
        $entity = 'ALL';
            $i=1;
    while ($row = $res->fetch_assoc()) {
//get all payments from recptrans for a given invoice 

       //  $invoicedetails['invoiceno'] = $row['invoiceno'];
       //  $invoicedetails['invoicedate'] = $row['invoicedate'];
        $prop_name = findpropertybyid($row['property_id']);

      
 
        $debit = $row['debit'];
        $credit = $row['credit'];
        $bal=$row['bal'];
        if($bal<=0){
                $percent=100;
            }
        else{
            $percent= number_format($credit/$debit*100,1);
    }
       //         $invoicedetails['paidamount'] = getInvoiceReceipts($row['invoiceno']);
       //  array_push($allinvoicedetails, $invoicedetails);
       //  unset($invoicedetails);

        echo '<tr><td>'.($i++).'</td><td><a href="./defaultreports.php?report=fetchplotperformance&fromdate='.$fdate.'&enddate='.$edate.'&flag=one&propid='.$row['property_id'].'"> 
        ' .$prop_name. '</a></td><td style="color:red">&nbsp;&nbsp;' . number_format($debit) . '</td><td style="color:green">&nbsp;&nbsp;' . number_format($credit) . '</td><td>&nbsp;&nbsp;' . number_format($row['bal']) . '</td><td>'.$percent.'</td></tr>';
    }

    
    echo '</tbody>';
    echo '<tfoot><tr><td><b>GRAND TOTAL</b></td><td></td><td><b>' . number_format(($totals['debit']), 2) . '</b></td><td><b>' .number_format(($totals['credit']), 2) . '</b></td>

    <td><b>' . number_format(($totals['bal']), 2)  . '</b></td>
<td><b>'.number_format((($totals['credit']/$totals['debit'])*100),1).'%</b></td>

    </tr></tfoot>';
    
    echo '</table>';
    $mysqli->close();
}
function fetchplotperformanceAgent($propid,$fromdate,$enddate){
    $fdate=$fromdate;
    $edate=$enddate;

  include_once '../includes/config.php';
    $mysqli = getMysqliConnection();
    date_default_timezone_set('Africa/Nairobi');
    $accountsopening = getAccountsOpeningDate();
    //$startdate = date("Y-m-d", strtotime($accountsopening));

    $end=DateTime::createFromFormat("m/d/Y",$enddate);
     $enddate=$end->format("Y-m-d");
      $start=DateTime::createFromFormat("m/d/Y",$fromdate);
     $startdate=$start->format("Y-m-d");
    $invoicenos = array();
    $invoicedetails = array();
    $allinvoicedetails = array();
    $suminvoiceamount = array();
    $sumpaidamount = array();
    $sumbal = array();
  
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") ";
    }
    //die($startdate);
   
     // $res = $mysqli->query
     //    ("SELECT invoices.invoiceno,invoices.idno,invoices.invoicedate,invoices.amount as credit,invoices.remarks as narration FROM invoices WHERE property_id='$propid' AND invoices.revsd=0 AND invoicedate between '$startdate' AND '$enddate'") or die($mysqli->error);
     //    $entity = 'ALL';
        $property = findpropertybyid($propid);
    
    echo '
<table class="treport" border="0">
 <tr><td ><img src="../images/cursors/logo.jpeg" style="height:auto;width:140px;"></td><td colspan="8" style="background-color:beige"><h3><center>' . strtoupper($arrearsprepayments) . ' REPORT FOR <u>' . $entity . '</u> OF <i>' . strtoupper($property) . '</i>&nbsp;FOR THE PERIOD&nbsp;' . $startdate . '&nbsp;TO&nbsp;' . $enddate . '</center></h3></td></tr></table>  
    <table class=" sortable treport" ><thead>
<tr>
<th><center><u>No</u></center></th>
<th><center><u>Agent Name</u></center></th>
<th><center><u>Debit</center></u></th>
<th><center><u>Credit</center></u></th>
<th><center><u>Balance</center></u></percent></th><th>Percent</th></tr></thead><tbody>';

//die(print_r($allproperties));

         $res = $mysqli->query("select ag.agent_id as agents,a.agentname as name,prop.property_id , sum(prop.debit)as debit,sum(prop.credit) as credit,sum(prop.bal) as bal from (select property_id,x.idno,ifnull(sum(x.credit),0) as debit,ifnull(sum(x.debit),0) as credit,(ifnull(sum(x.credit),0)-ifnull(sum(x.debit),0)) as bal from (SELECT property_id,invoices.idno,invoices.amount as credit,(SELECT sum(amount) as debit FROM recptrans WHERE invoicenopaid=invoices.invoiceno AND revsd=0 ) as debit FROM invoices where invoices.revsd=0 AND invoicedate )x group by x.idno) prop inner join agentproperty ag on ag.property_id=prop.property_id inner join agents a on ag.agent_id=a.agentid group by `agent_id`") or die($mysqli->error);
         $total=$mysqli->query("select  sum(prop.debit)as debit,sum(prop.credit) as credit,sum(prop.bal) as bal from (select property_id,x.idno,ifnull(sum(x.credit),0) as debit,ifnull(sum(x.debit),0) as credit,(ifnull(sum(x.credit),0)-ifnull(sum(x.debit),0)) as bal from (SELECT property_id,invoices.idno,invoices.amount as credit,(SELECT sum(amount) as debit FROM recptrans WHERE invoicenopaid=invoices.invoiceno AND revsd=0  ) as debit FROM invoices  where   invoices.revsd=0 AND invoicedate between '$startdate' AND '$enddate' )x group by x.idno) prop");
 //select ag.agent_id as agents,prop.property_id , sum(prop.debit)as debit,sum(prop.credit) as credit,sum(prop.bal) as bal from (select property_id,x.idno,ifnull(sum(x.credit),0) as debit,ifnull(sum(x.debit),0) as credit,(ifnull(sum(x.credit),0)-ifnull(sum(x.debit),0)) as bal from (SELECT property_id,invoices.idno,invoices.amount as credit,(SELECT sum(amount) as debit FROM recptrans WHERE invoicenopaid=invoices.invoiceno AND revsd=0 ) as debit FROM invoices where invoices.revsd=0 AND invoicedate )x group by x.idno) prop inner join agentproperty ag on ag.property_id=prop.property_id group by prop.property_id       //  
         $totals=$total->fetch_assoc();
        $entity = 'ALL';
            $i=1;
    while ($row = $res->fetch_assoc()) {
//get all payments from recptrans for a given invoice 

       //  $invoicedetails['invoiceno'] = $row['invoiceno'];
       //  $invoicedetails['invoicedate'] = $row['invoicedate'];
        $prop_name = findpropertybyid($row['property_id']);

      
 
        $debit = $row['debit'];
        $credit = $row['credit'];
        $bal=$row['bal'];
        if($bal<=0){
                $percent=100;
            }
        else{
            $percent= number_format($credit/$debit*100,1);
    }
       //         $invoicedetails['paidamount'] = getInvoiceReceipts($row['invoiceno']);
       //  array_push($allinvoicedetails, $invoicedetails);
       //  unset($invoicedetails);

        echo '<tr><td>'.($i++).'</td><td><a href="./defaultreports.php?report=fetchplotperformance&agentid='.$row['agents'].'&fromdate='.$fdate.'&enddate='.$edate.'&flag=four&propid='.$row['property_id'].'"> 
        ' .$row['name']. '</a></td><td style="color:red">&nbsp;&nbsp;' . number_format($debit) . '</td><td style="color:green">&nbsp;&nbsp;' . number_format($credit) . '</td><td>&nbsp;&nbsp;' . number_format($row['bal']) . '</td><td>'.$percent.'</td></tr>';
    }

    
    echo '</tbody>';
//     echo '<tfoot><tr><td><b>GRAND TOTAL</b></td><td></td><td><b>' . number_format(($totals['debit']), 2) . '</b></td><td><b>' .number_format(($totals['credit']), 2) . '</b></td>

//     <td><b>' . number_format(($totals['bal']), 2)  . '</b></td>
// <td><b>'.number_format((($totals['credit']/$totals['debit'])*100),1).'%</b></td>

//     </tr></tfoot>';
    
    echo '</table>';
    $mysqli->close();
}

function fetchplotperformanceOne($propid,$fromdate,$enddate) {
   // die("hee");
      $fdate=$fromdate;
    $edate=$enddate;
    include_once '../includes/config.php';
    $mysqli = getMysqliConnection();
    date_default_timezone_set('Africa/Nairobi');
    $accountsopening = getAccountsOpeningDate();
    //$startdate = date("Y-m-d", strtotime($accountsopening));

    $end=DateTime::createFromFormat("m/d/Y",$enddate);
     $enddate=$end->format("Y-m-d");
      $start=DateTime::createFromFormat("m/d/Y",$fromdate);
     $startdate=$start->format("Y-m-d");
    $invoicenos = array();
    $invoicedetails = array();
    $allinvoicedetails = array();
    $suminvoiceamount = array();
    $sumpaidamount = array();
    $sumbal = array();
  
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") ";
    }
    //die($startdate);
   
     // $res = $mysqli->query
     //    ("SELECT invoices.invoiceno,invoices.idno,invoices.invoicedate,invoices.amount as credit,invoices.remarks as narration FROM invoices WHERE property_id='$propid' AND invoices.revsd=0 AND invoicedate between '$startdate' AND '$enddate'") or die($mysqli->error);
     //    $entity = 'ALL';
        $property = findpropertybyid($propid);
    
    echo '
<table class="treport" border="0">
 <tr><td ><img src="../images/cursors/logo.jpeg" style="height:auto;width:140px;"></td><td colspan="9" style="background-color:beige"><h3><center>' . strtoupper($arrearsprepayments) . ' REPORT FOR <u>' . $entity . '</u> OF <i>' . strtoupper($property) . '</i>&nbsp;FOR THE PERIOD&nbsp;' . $startdate . '&nbsp;TO&nbsp;' . $enddate . '</center></h3></td></tr></table>  
    <table class=" sortable treport" ><thead>
<tr>
<th><center><u>No</u></center></th>

<th><center><u>Appartment</u></center></th>
<th><center><u>Customer/Tenant Name</u></center></th>
<th><center><u>House</u></center></th>
<th><center><u>Debit</center></u></th>
<th><center><u>Credit</center></u></th>

<th><center><u>Balance</center></u></percent></th><th>Percent</th></tr></thead><tbody>';

//die(print_r($allproperties));

         $res = $mysqli->query("select property_id,x.idno,ifnull(sum(x.credit),0) as debit,ifnull(sum(x.debit),0) as credit,(ifnull(sum(x.credit),0)-ifnull(sum(x.debit),0)) as bal from (SELECT property_id,invoices.idno,invoices.amount as credit,(SELECT sum(amount) as debit FROM recptrans WHERE invoicenopaid=invoices.invoiceno AND revsd=0  ) as debit FROM invoices  where  property_id='$propid'  and invoices.revsd=0 AND invoicedate between '$startdate' AND '$enddate' )x group by x.idno") or die($mysqli->error);
         $total=$mysqli->query("select sum(prop.debit) as debit,sum(prop.credit)as credit,sum(prop.bal)as bal from (select property_id,x.idno,ifnull(sum(x.credit),0) as debit,ifnull(sum(x.debit),0) as credit,(ifnull(sum(x.credit),0)-ifnull(sum(x.debit),0)) as bal from (SELECT property_id,invoices.idno,invoices.amount as credit,(SELECT sum(amount) as debit FROM recptrans WHERE invoicenopaid=invoices.invoiceno AND revsd=0  ) as debit FROM invoices  where  property_id='$propid'  and invoices.revsd=0 AND invoicedate between '$startdate' AND '$enddate' )x group by x.idno) prop");
         $totals=$total->fetch_assoc();
       //  die(print_r($totals));
        $entity = 'ALL';
      $i=1;
    while ($row = $res->fetch_assoc()) {
//get all payments from recptrans for a given invoice 

       //  $invoicedetails['invoiceno'] = $row['invoiceno'];
       //  $invoicedetails['invoicedate'] = $row['invoicedate'];
        $prop_name = findpropertybyid($row['property_id']);

       $invoicedetails=[];
        $tenantdetails = getTenantDetails($row['idno']);
        $invoicedetails['name'] = $tenantdetails['name'].'('.$tenantdetails['phone'].':'.$tenantdetails['email'].'N/Kin:'.$tenantdetails['kinsname'].'-'.$tenantdetails['kinstel'].')';
      //  die( $invoicedetails['name']);
        $aptid = getApartmentFromTenant($row['idno']);
        $aptdetails = getApartmentDetails($aptid);
         $invoicedetails['aptname'] = $aptdetails[0]['apt_tag'];
       //  die( $invoicedetails['aptname'] );
         $invoicedetails['remarks'] = $row['narration'];
        $debit = $row['debit'];
        $credit = $row['credit'];
        $bal=$row['bal'];
        if($bal<=0){
                $percent=100;
            }
        else{
            $percent= number_format($credit/$debit*100,1);
    }
       //         $invoicedetails['paidamount'] = getInvoiceReceipts($row['invoiceno']);
       //  array_push($allinvoicedetails, $invoicedetails);
       //  unset($invoicedetails);
// report=fetchstatement&startdate=07/04/2022&enddate=07/13/2022&clientid=256&count=0&propid=3
        echo '<tr><td>'.($i++).'</td><td><a href="./defaultreports.php?report=fetchstatement&startdate='.$fdate.'&enddate='.$edate.'&clientid='.$row['idno'].'&count=0&propid='.$row['property_id'].'"> 
        ' .$aptid. '</a></td><td>'.$invoicedetails['name'].'</td><td>' . $invoicedetails['aptname'] . '</td><td style="color:red">&nbsp;&nbsp;' . number_format($debit) . '</td><td style="color:green">&nbsp;&nbsp;' . number_format($credit) . '</td><td>&nbsp;&nbsp;' . $row['bal'] . '</td><td>'.$percent.'</td></tr>';
    }

    
    echo '</tbody>';
    echo '<tfoot><tr><td><b>GRAND TOTAL</b></td><td></td><td></td><td></td><td><b>' . number_format(($totals['debit']), 2) . '</b></td><td><b>' .number_format(($totals['credit']), 2) . '</b></td>

    <td><b>' . number_format(($totals['bal']), 2)  . '</b></td>
<td><b>'.number_format((($totals['credit']/$totals['debit'])*100),1).'%</b></td>

    </tr></tfoot>';
    
    echo '</table>';
    $mysqli->close();

}
function fetchplotperformancebypercentageOne($propid,$fromdate,$enddate,$percentage,$percentangeto) {
    //die($percentangeto);
    $fdate=$fromdate;
    $edate=$enddate;
    include_once '../includes/config.php';
    $mysqli = getMysqliConnection();
    date_default_timezone_set('Africa/Nairobi');
    $accountsopening = getAccountsOpeningDate();
    //$startdate = date("Y-m-d", strtotime($accountsopening));

    $end=DateTime::createFromFormat("m/d/Y",$enddate);
     $enddate=$end->format("Y-m-d");
      $start=DateTime::createFromFormat("m/d/Y",$fromdate);
     $startdate=$start->format("Y-m-d");
    $invoicenos = array();
    $invoicedetails = array();
    $allinvoicedetails = array();
    $suminvoiceamount = array();
    $sumpaidamount = array();
    $sumbal = array();
  
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") ";
    }
    //die($startdate);
   
     // $res = $mysqli->query
     //    ("SELECT invoices.invoiceno,invoices.idno,invoices.invoicedate,invoices.amount as credit,invoices.remarks as narration FROM invoices WHERE property_id='$propid' AND invoices.revsd=0 AND invoicedate between '$startdate' AND '$enddate'") or die($mysqli->error);
     //    $entity = 'ALL';
        $property = findpropertybyid($propid);
    
    echo '
<table class="treport" border="0">
 <tr><td ><img src="../images/cursors/logo.jpeg" style="height:auto;width:140px;"></td><td colspan="9" style="background-color:beige"><h3><center>' . strtoupper($arrearsprepayments) . ' REPORT FOR <u>' . $entity . '</u> OF <i>' . strtoupper($property) . '</i>&nbsp;FOR THE PERIOD&nbsp;' . $startdate . '&nbsp;TO&nbsp;' . $enddate . '</center></h3></td></tr></table>  
    <table class=" sortable treport" ><thead>
<tr>
<th><center><u>No</u></center></th>

<th><center><u>Property</u></center></th>
<th><center><u>Customer/Tenant Name</u></center></th>
<th><center><u>House</u></center></th>

<th><center><u>Debit</center></u></th>
<th><center><u>Credit</center></u></th>
<th><center><u>Balance</center></u></percent></th><th>Percent</th></tr></thead><tbody>';

//die(print_r($allproperties));

         $res = $mysqli->query("select property_id,x.idno,ifnull(sum(x.credit),0) as debit,ifnull(sum(x.debit),0) as credit,(ifnull(sum(x.credit),0)-ifnull(sum(x.debit),0)) as bal from (SELECT property_id,invoices.idno,invoices.amount as credit,(SELECT sum(amount) as debit FROM recptrans WHERE invoicenopaid=invoices.invoiceno AND revsd=0  ) as debit FROM invoices  where  property_id='$propid'  and invoices.revsd=0 AND invoicedate between '$startdate' AND '$enddate' )x group by x.idno") or die($mysqli->error);
         $total=$mysqli->query("select sum(prop.debit) as debit,sum(prop.credit)as credit,sum(prop.bal)as bal from (select property_id,x.idno,ifnull(sum(x.credit),0) as debit,ifnull(sum(x.debit),0) as credit,(ifnull(sum(x.credit),0)-ifnull(sum(x.debit),0)) as bal from (SELECT property_id,invoices.idno,invoices.amount as credit,(SELECT sum(amount) as debit FROM recptrans WHERE invoicenopaid=invoices.invoiceno AND revsd=0  ) as debit FROM invoices  where  property_id='$propid'  and invoices.revsd=0 AND invoicedate between '$startdate' AND '$enddate' )x group by x.idno) prop");
         $totals=$total->fetch_assoc();
       //  die(print_r($totals));
        $entity = 'ALL';
      $i=1;
      $total_credit=array();
      $total_debit=array();
    while ($row = $res->fetch_assoc()) {
//get all payments from recptrans for a given invoice 

       //  $invoicedetails['invoiceno'] = $row['invoiceno'];
       //  $invoicedetails['invoicedate'] = $row['invoicedate'];
        $prop_name = findpropertybyid($row['property_id']);

       $invoicedetails=[];
        $tenantdetails = getTenantDetails($row['idno']);
        $invoicedetails['name'] = $tenantdetails['name'].'('.$tenantdetails['phone'].':'.$tenantdetails['email'].'N/Kin:'.$tenantdetails['kinsname'].'-'.$tenantdetails['kinstel'].')';
      //  die( $invoicedetails['name']);
        $aptid = getApartmentFromTenant($row['idno']);
        $aptdetails = getApartmentDetails($aptid);
         $invoicedetails['aptname'] = $aptdetails[0]['apt_tag'];
       //  die( $invoicedetails['aptname'] );
         $invoicedetails['remarks'] = $row['narration'];
        $debit = $row['debit'];
        $credit = $row['credit'];
        $bal=$row['bal'];
        if($bal<=0){
                $percent=100;
            }
        else{
            $percent= $credit/$debit*100;
    }

 if(round($percent,0)>=$percentage&&round($percent,0)<=$percentangeto){

array_push($total_credit,$credit);
array_push($total_debit,$debit);

       //         $invoicedetails['paidamount'] = getInvoiceReceipts($row['invoiceno']);
       //  array_push($allinvoicedetails, $invoicedetails);
       //  unset($invoicedetails);
// report=fetchstatement&startdate=07/04/2022&enddate=07/13/2022&clientid=256&count=0&propid=3
        echo '<tr><td>'.($i++).'</td><td><a href="./defaultreports.php?report=fetchstatement&startdate='.$fdate.'&enddate='.$edate.'&clientid='.$row['idno'].'&count=0&propid='.$row['property_id'].'"> 
        ' .$prop_name. '</a></td><td>'.$invoicedetails['name'].'</td><td>' . $invoicedetails['aptname'] . '</td><td style="color:red">&nbsp;&nbsp;' . number_format($debit) . '</td><td style="color:green">&nbsp;&nbsp;' . number_format($credit) . '</td><td>&nbsp;&nbsp;' . $row['bal'] . '</td><td>'.number_format($percent,1).'</td></tr>';
    }
    }

    
    echo '</tbody>';
    echo '<tfoot><tr><td><b>GRAND TOTAL</b></td><td></td><td></td><td></td><td><b>' . number_format(array_sum($total_debit), 2) . '</b></td><td><b>' .number_format(array_sum($total_credit), 2) . '</b></td>

    <td><b>' . number_format((array_sum($total_debit)-array_sum($total_credit)), 2)  . '</b></td>


    </tr></tfoot>';
    
    echo '</table>';
    $mysqli->close();

}
function fetchplotperformancebypercentageall($propid,$fromdate,$enddate,$flag,$percentage,$percentangeto) {
    //die($percentangeto);
    $fdate=$fromdate;
    $edate=$enddate;
    include_once '../includes/config.php';
    $mysqli = getMysqliConnection();
    date_default_timezone_set('Africa/Nairobi');
    $accountsopening = getAccountsOpeningDate();
    //$startdate = date("Y-m-d", strtotime($accountsopening));

    $end=DateTime::createFromFormat("m/d/Y",$enddate);
     $enddate=$end->format("Y-m-d");
      $start=DateTime::createFromFormat("m/d/Y",$fromdate);
     $startdate=$start->format("Y-m-d");
    $invoicenos = array();
    $invoicedetails = array();
    $allinvoicedetails = array();
    $suminvoiceamount = array();
    $sumpaidamount = array();
    $sumbal = array();
  
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") ";
    }
    //die($startdate);
   
     // $res = $mysqli->query
     //    ("SELECT invoices.invoiceno,invoices.idno,invoices.invoicedate,invoices.amount as credit,invoices.remarks as narration FROM invoices WHERE property_id='$propid' AND invoices.revsd=0 AND invoicedate between '$startdate' AND '$enddate'") or die($mysqli->error);
     //    $entity = 'ALL';
        $property = findpropertybyid($propid);
    
    echo '
<table class="treport" border="0">
 <tr><td ><img src="../images/cursors/logo.jpeg" style="height:auto;width:140px;"></td><td colspan="9" style="background-color:beige"><h3><center>' . strtoupper($arrearsprepayments) . ' REPORT FOR <u>' . $entity . '</u> OF <i>' . strtoupper($property) . '</i>&nbsp;FOR THE PERIOD&nbsp;' . $startdate . '&nbsp;TO&nbsp;' . $enddate . '</center></h3></td></tr></table>  
    <table class=" sortable treport" ><thead>
<tr>
<th><center><u>No</u></center></th>

<th><center><u>Property</u></center></th>
<th><center><u>Customer/Tenant Name</u></center></th>
<th><center><u>House</u></center></th>
<th><center><u>Debit</center></u></th>
<th><center><u>Credit</center></u></th>

<th><center><u>Balance</center></u></percent></th><th>Percent</th></tr></thead><tbody>';

//die(print_r($allproperties));

         $res = $mysqli->query("select property_id,x.idno,ifnull(sum(x.credit),0) as debit,ifnull(sum(x.debit),0) as credit,(ifnull(sum(x.credit),0)-ifnull(sum(x.debit),0)) as bal from (SELECT property_id,invoices.idno,invoices.amount as credit,(SELECT sum(amount) as debit FROM recptrans WHERE invoicenopaid=invoices.invoiceno AND revsd=0  ) as debit FROM invoices  where   invoices.revsd=0 AND invoicedate between '$startdate' AND '$enddate' )x where x.credit > 0 group by x.idno ") or die($mysqli->error);
         $total=$mysqli->query("select sum(prop.debit) as debit,sum(prop.credit)as credit,sum(prop.bal)as bal from (select property_id,x.idno,ifnull(sum(x.credit),0) as debit,ifnull(sum(x.debit),0) as credit,(ifnull(sum(x.credit),0)-ifnull(sum(x.debit),0)) as bal from (SELECT property_id,invoices.idno,invoices.amount as credit,(SELECT sum(amount) as debit FROM recptrans WHERE invoicenopaid=invoices.invoiceno AND revsd=0  ) as debit FROM invoices  where   invoices.revsd=0 AND invoicedate between '$startdate' AND '$enddate' )x group by x.idno) prop");
         $totals=$total->fetch_assoc();
       //  die(print_r($totals));
        $entity = 'ALL';
      $i=1;
      $total_credit=array();
      $total_debit=array();
    while ($row = $res->fetch_assoc()) {
//get all payments from recptrans for a given invoice 

       //  $invoicedetails['invoiceno'] = $row['invoiceno'];
       //  $invoicedetails['invoicedate'] = $row['invoicedate'];
        $prop_name = findpropertybyid($row['property_id']);
       $invoicedetails=[];
        $tenantdetails = getTenantDetails($row['idno']);
        $invoicedetails['name'] = $tenantdetails['name'].'('.$tenantdetails['phone'].':'.$tenantdetails['email'].'N/Kin:'.$tenantdetails['kinsname'].'-'.$tenantdetails['kinstel'].')';
      //  die( $invoicedetails['name']);
        $aptid = getApartmentFromTenant($row['idno']);
        $aptdetails = getApartmentDetails($aptid);
         $invoicedetails['aptname'] = $aptdetails[0]['apt_tag'];
       //  die( $invoicedetails['aptname'] );
         $invoicedetails['remarks'] = $row['narration'];
        $debit = $row['debit'];
        $credit = $row['credit'];
        $bal=$row['bal'];
        if($bal<=0){
                $percent=100;
            }
        else{
            $percent= $credit/$debit*100;
    }
// die($credit/$debit);
 if(floor($percent)>=$percentage&&floor($percent)<=$percentangeto){

array_push($total_credit,$credit);
array_push($total_debit,$debit);

       //         $invoicedetails['paidamount'] = getInvoiceReceipts($row['invoiceno']);
       //  array_push($allinvoicedetails, $invoicedetails);
       //  unset($invoicedetails);
// report=fetchstatement&startdate=07/04/2022&enddate=07/13/2022&clientid=256&count=0&propid=3
        echo '<tr><td>'.($i++).'</td><td><a href="./defaultreports.php?report=fetchstatement&startdate='.$fdate.'&enddate='.$edate.'&clientid='.$row['idno'].'&count=0&propid='.$row['property_id'].'"> 
        ' .$prop_name. '</a></td><td>'.$invoicedetails['name'].'</td><td>' . $invoicedetails['aptname'] . '</td><td style="color:red">&nbsp;&nbsp;' . number_format($debit) . '</td><td style="color:green">&nbsp;&nbsp;' . number_format($credit) . '</td><td>&nbsp;&nbsp;' . $row['bal'] . '</td><td>'.number_format($percent,1).'</td></tr>';
    }
    }

    
    echo '</tbody>';
    echo '<tfoot><tr><td><b>GRAND TOTAL</b></td><td></td><td></td><td></td><td><b>' . number_format(array_sum($total_debit), 2) . '</b></td><td><b>' .number_format(array_sum($total_credit), 2) . '</b></td>

    <td><b>' . number_format((array_sum($total_debit)-array_sum($total_credit)), 2)  . '</b></td>


    </tr></tfoot>';
    
    echo '</table>';
    $mysqli->close();

}




//penalty
function fetchpenalty($tenantid, $propid, $enddate, $count) {
    include_once '../includes/config.php';
    $mysqli = getMysqliConnection();
    date_default_timezone_set('Africa/Nairobi');
    $accountsopening = getAccountsOpeningDate();
    $startdate = date("Y-m-d", strtotime($accountsopening));
    $enddate = date("Y-m-d", strtotime($enddate));
    $invoicenos = array();
    $invoicedetails = array();
    $allinvoicedetails = array();
    $suminvoiceamount = array();
    $sumpaidamount = array();
    $sumbal = array();

    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") ";
    }
    if ($count == '0') {
        $res = $mysqli->query("SELECT invoices.invoiceno,invoices.invoicedate,invoices.amount as credit,invoices.remarks as narration,invoices.is_penalty,invoices.recpno FROM invoices WHERE invoices.idno='$tenantid' AND invoices.is_penalty=1 AND invoices.revsd=0 AND property_id='$propid' AND invoicedate between '$startdate' AND '$enddate' ") or die($mysqli->error);
        $entity = strtoupper(findtenantbyid($tenantid));
    }
//    else if($count==2){
//        
//    }
    else {
        $res = $mysqli->query("SELECT invoices.invoiceno,invoices.idno,invoices.invoicedate,invoices.amount as credit,invoices.remarks as narration,invoices.is_penalty,invoices.recpno FROM invoices WHERE property_id='$propid' AND invoices.is_penalty=1 AND invoices.revsd=0 AND invoicedate between '$startdate' AND '$enddate'") or die($mysqli->error);
        $entity = 'ALL';
    }
    echo '<table class="treport1" style="width:800px"><thead>
<tr><td colspan="8" style="background-color:beige"><h3><center>PENALTY REPORT FOR <u>' . $entity . '</u> OF <i>' . strtoupper(findpropertybyid($propid)) . '</i>&nbsp;FOR THE PERIOD&nbsp;' . $startdate . '&nbsp;TO&nbsp;' . $enddate . '</center></h3></td></tr>
<tr>
<th><center><u>Invoice No</u></center></th>
<th><center><u>Date</u></center></th>
<th><center><u>Customer/Tenant Name</u></center></th>
<th><center><u>House</u></center></th>
<th><center><u>Narration</u></center></th>
<th><center><u>Credit</center></u></th>
<th><center><u>Debit</center></u></th>
<th><center><u>Penalty</center></u></th></tr></thead><tbody>';
    while ($row = $res->fetch_assoc()) {
//get all payments from recptrans for a given invoice 

        $invoicedetails['invoiceno'] = $row['invoiceno'];
        $invoicedetails['invoicedate'] = $row['invoicedate'];
        $tenantdetails = getTenantDetails($row['idno']);
        $invoicedetails['name'] = $tenantdetails['name'];
        $aptid = getApartmentFromTenant($row['idno']);
        $aptdetails = getApartmentDetails($aptid);
        $invoicedetails['aptname'] = $aptdetails[0]['apt_tag'];
        $invoicedetails['remarks'] = $row['narration'];
        $invoicedetails['credit'] = $row['credit'];
        $invoicedetails['paidamount'] = getInvoiceReceipts($row['invoiceno']);
        array_push($allinvoicedetails, $invoicedetails);
    }
    foreach ($allinvoicedetails as $invoicedetail) {
        $paidamount = $invoicedetail['paidamount'];
        $bal = $invoicedetail['credit'] - $paidamount;

        //arrears

        array_push($suminvoiceamount, $invoicedetail['credit']);
        array_push($sumpaidamount, $paidamount);
        array_push($sumbal, $bal);
        echo '<tr><td>' . $invoicedetail['invoiceno'] . '</td><td>' . date('d-m-Y', strtotime($invoicedetail['invoicedate'])) . '</td><td>' . $invoicedetail['name'] . '</td><td>' . $invoicedetail['aptname'] . '</td><td>&nbsp;&nbsp;&nbsp;&nbsp;' . $invoicedetail['remarks'] . '</td><td style="color:red">&nbsp;&nbsp;' . number_format($invoicedetail['credit']) . '</td><td style="color:green">&nbsp;&nbsp;' . number_format($paidamount) . '</td><td>&nbsp;&nbsp;' . $bal . '</td></tr>';
    }
    echo '</tbody>';

    echo '<tfoot><tr><td><b>GRAND TOTAL</b></td><td></td><td></td><td></td><td></td><td><b>' . number_format(@array_sum($suminvoiceamount), 2) . '</b></td><td><b>' . number_format(@array_sum($sumpaidamount), 2) . '</b></td><td><b>' . number_format(@array_sum($sumbal), 2) . '</b></td></tr></tfoot>';
    echo '</table><br><br>';

    $mysqli->close();
}

//all penalties (unpaid)
function fetchUnPaidPenalties($enddate, $count) {

    include_once '../includes/config.php';
    $mysqli = getMysqliConnection();
    date_default_timezone_set('Africa/Nairobi');
    $accountsopening = getAccountsOpeningDate();
    $startdate = date("Y-m-d", strtotime($accountsopening));

    $enddate = date("Y-m-d", strtotime($enddate));


    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") ";
    }

    $resultset = $mysqli->query("SELECT invoices.invoiceno,invoices.property_id,invoices.idno,invoices.invoicedate,invoices.amount as credit,invoices.remarks as narration,invoices.is_penalty,invoices.recpno FROM invoices WHERE  invoices.is_penalty=1 AND invoices.revsd=0 AND invoicedate between '$startdate' AND '$enddate' ORDER BY invoices.property_id") or die($mysqli->error);
    while ($row = $resultset->fetch_assoc()) {
        $penalties[] = $row;
    }
    return $penalties;
}

//get paid penalties
function fetchPaidPenalties($startdate, $enddate, $count) {
    include_once '../includes/config.php';
    $mysqli = getMysqliConnection();
    date_default_timezone_set('Africa/Nairobi');

    $startdate = date("Y-m-d", strtotime($startdate));
    $enddate = date("Y-m-d", strtotime($enddate));


    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") ";
    }

    $resultset = $mysqli->query("SELECT invoices.invoiceno,invoices.property_id,invoices.idno as tenant_id,invoices.invoicedate,invoices.amount as credit,invoices.paidamount as paidamount,invoices.remarks as narration,invoices.is_penalty,invoices.recpno FROM invoices WHERE invoices.paidamount>0 AND invoices.is_penalty=1 AND invoices.revsd=0  AND invoicedate between '$startdate' AND '$enddate' ") or die($mysqli->error);
    while ($row = $resultset->fetch_assoc()) {
        $penalties[] = $row;
    }
    return $penalties;
}

//get receipts for an invoice 
function getInvoiceReceipts($invoiceno) {
    $mysqli = getMysqliConnection();
    $paidamount = array();
    $results = $mysqli->query("SELECT amount as debit FROM recptrans WHERE invoicenopaid='$invoiceno' AND revsd=0 ") or die($mysqli->error);
//paid amounts
    while ($rows = $results->fetch_assoc()) {
        array_push($paidamount, $rows['debit']);
    }
    return array_sum($paidamount);
}
//get receipts for an invoice 
function getTenantRecptrans($tid) {
    $mysqli = getMysqliConnection();
    $paidamount = array();
   // die("SELECT amount as debit FROM recptrans WHERE idno='$tid' AND revsd=0 ")
    $results = $mysqli->query("SELECT amount as debit FROM recptrans WHERE idno='$tid' AND revsd=0 ") or die($mysqli->error);
//paid amounts
    while ($rows = $results->fetch_assoc()) {
        array_push($paidamount, $rows['debit']);
    }
    return array_sum($paidamount);
}
function getReceiptsFromInvoice($invoiceno,$yearmonthday=NULL) {
    $mysqli = getMysqliConnection();
    $receipts = array();
    $yearmonth=date("Y-m",strtotime($yearmonthday));
   // echo "SELECT * FROM recptrans WHERE invoicenopaid='$invoiceno' AND revsd=0 AND DATE_FORMAT(`rdate`,'%Y-%m') //='$yearmonth' ";
    if($yearmonth){
        $results = $mysqli->query("SELECT * FROM recptrans WHERE invoicenopaid='$invoiceno' AND revsd=0 AND DATE_FORMAT(`rdate`,'%Y-%m') ='$yearmonth' AND  is_deleted=0 ") or die($mysqli->error);
    } else{
       // die("dsdsd");
    $results = $mysqli->query("SELECT * FROM recptrans WHERE invoicenopaid='$invoiceno' AND revsd=0 AND  is_deleted=0 ") or die($mysqli->error);
}
//paid amounts
    while ($rows = $results->fetch_assoc()) {
        array_push($receipts, $rows);
    }
    return $receipts;
}

//tenant deposit
function getTenantDeposit($id, $startdate = "", $enddate = "") {
    $mysqli = getMysqliConnection();
    $deposits = array();
    $receiptstable = getReceiptsTable();
    if ($startdate && $enddate) {
        $resultset = $mysqli->query("SELECT * FROM {$receiptstable} WHERE is_deposit='D'  AND refunded='0' AND idno='$id' AND rdate between '$startdate' AND '$enddate' ") or die($mysqli->error);
    } else {
        $resultset = $mysqli->query("SELECT * FROM {$receiptstable} WHERE is_deposit='D'  AND refunded='0' AND idno='$id'") or die($mysqli->error);
    }
    while ($rows = $resultset->fetch_assoc()) {
        array_push($deposits, $rows);
    }
    return $deposits;
}

//tenant receipts
function getTenantReceipts($id, $startdate = "", $enddate = "") {
    $mysqli = getMysqliConnection();
    $deposits = array();
    $receiptstable = getReceiptsTable();
    if ($startdate && $enddate) {
        $resultset = $mysqli->query("SELECT * FROM {$receiptstable} WHERE  revsd=0  AND idno='$id' AND rdate between '$startdate' AND '$enddate' ") or die($mysqli->error);
    } else {
        $resultset = $mysqli->query("SELECT * FROM {$receiptstable} WHERE revsd=0  AND idno='$id'") or die($mysqli->error);
    }
    while ($rows = $resultset->fetch_assoc()) {
        array_push($deposits, $rows);
    }
    return $deposits;
}

//income statement
function incomestatement($propid, $startdate, $enddate) {
    include_once '../includes/config.php';
    $mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME, 3306);
    date_default_timezone_set('Africa/Nairobi');
    $startdate = date("Y-m-d", strtotime($startdate));
    $enddate = date("Y-m-d", strtotime($enddate));
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") ";
    }
//get all receipts that have not been deleted or reversed
    $res = $mysqli->query("SELECT * FROM recptrans WHERE property_id='$propid' AND rdate between '$startdate' AND '$enddate' AND is_deleted=0 AND revsd=0 ") or die($mysqli->error);  //also consider paytrans later as debit for suppliers
    $entity = 'ALL';


    echo '<table class="treport" ><thead>
<tr><td><img src="../images/cursors/logo1.png" style="height:100px;width:140px;"></td><td colspan="5" style="background-color:beige"><h3><center>INCOME STATEMENT FOR ' . strtoupper(findpropertybyid($propid)) . '</i>&nbsp;FOR THE PERIOD&nbsp;' . date("d-m-Y", strtotime($startdate)) . '&nbsp;TO&nbsp;' . date("d-m-Y", strtotime($enddate)) . '</center></h3></td></tr>
<tr>
<th><center><u>Recp No/Pay No</u></center></th>
<th><center><u>Date</u></center></th>
<th><center><u>Customer/Tenant Name</center></u></th>
<th><center><u>House</center></u></th>
<th><center><u>Narration</u></center></th>
<th><center><u>Debit</center></u></th>
</tr></thead><tbody>';
    while ($row = $res->fetch_assoc()) {
        $recpno = $row['recpno'];
        $invoicedate = $row['rdate'];
        $invoicermks = $row['rmks'];
        $tenantdetails = getTenantDetails($row['idno']);
        $customername = $tenantdetails['name'];
        $aptid = getApartmentFromTenant($row['idno']);
        $aptdetails = getApartmentDetails($aptid);
        $aptname = $aptdetails[0]['apt_tag'];
        $ramount = $row['amount'];
        $paidamount = 0;
        $suminvoiceamount[] = $ramount;
        $sumpaidamount[] = $paidamount;
        echo '<tr><td>' . $recpno . '</td><td>' . date("d-m-Y", strtotime($invoicedate)) . '</td><td>' . $customername . '</td><td>' . $aptname . '</td><td>&nbsp;&nbsp;&nbsp;&nbsp;' . $invoicermks . '</td><td style="color:green">&nbsp;&nbsp;' . number_format($ramount) . '</td></tr>';
    }
    echo '</tbody><tfoot><tr><td><b>GRAND TOTAL</b></td><td></td><td></td><td></td><td></td><td><b>' . number_format(@array_sum($suminvoiceamount), 2) . '</b></td></tr></tfoot>';
    echo '</table>';
    mysqli_close($mysqli);
}

//bankstatement
//acctid,$acctype,from,to
function getBankStatement($detailsarray) {
    $mysqli = getMysqliConnection();
    $receiptstable = getReceiptsTable();
    $paytrans = getPaymentsListTable();
    $statement = array();
    $startdate = date("Y-m-d", strtotime($detailsarray['fromdate']));
    $enddate = date("Y-m-d", strtotime($detailsarray['todate']));
    $acctid = $detailsarray['acctid'];
    if ($detailsarray['acctype'] != 'E') {
        $res = $mysqli->query("SELECT * FROM {$receiptstable} WHERE bankacc='$acctid' AND rdate between '$startdate' AND '$enddate' AND is_deleted=0 AND revsd=0 ") or die($mysqli->error);
    } else {
        $res = $mysqli->query("SELECT * FROM {$paytrans} WHERE expenseacct='$acctid' AND paydate between '$startdate' AND '$enddate' AND revsd=0 ") or die($mysqli->error);
    }
    while ($row = $res->fetch_assoc()) {
        array_push($statement, $row);
    }
    $mysqli->close();
    return $statement;
}

//accts
function findAccountById($id) {
    $mysqli = getMysqliConnection();
    $accountdetails = array();
    $accountstable = getAccountsTable();
    $res = $mysqli->query("SELECT * FROM {$accountstable} WHERE acno='$id'") or die($mysqli->error);

    while ($row = $res->fetch_assoc()) {
        array_push($accountdetails, $row);
    }
    $mysqli->close();
    return $accountdetails;
}

function getGlAccounts() {
    $mysqli = getMysqliConnection();
    $accountdetails = array();
    $accountstable = getAccountsTable();
    $res = $mysqli->query("SELECT * FROM {$accountstable} LIMIT 4000") or die($mysqli->error);

    while ($row = $res->fetch_assoc()) {
        array_push($accountdetails, $row);
    }
    $mysqli->close();
    return $accountdetails;
}

//get paymodes
function getPayMode($id) {
    $mysqli = getMysqliConnection();
    $res = $mysqli->query("SELECT * FROM paymodes WHERE id='$id'") or die($mysqli->error);
    $paymodes = array();
    while ($row = $res->fetch_assoc()) {
        array_push($paymodes, $row);
    }
    $mysqli->close();
    return $paymodes;
}

function get_commissions_list($fromdate, $todate) {
    include '../includes/config.php';
    $mysqli = getMysqliConnection();
    $propertiestable = getPropertiesTable();
    $propertydetails = array();
    $res = $mysqli->query("SELECT propertyid,agent_commission FROM {$propertiestable}") or die($mysqli->error);
    while ($row = $res->fetch_assoc()) {
        $propid = $row['propertyid'];
        $propertydetail['propid'] = $propid;
        $propertydetail['propertyname'] = findpropertybyid($propid);
        $propertydetail['rentsum'] = getPropertyCommission($propid, $fromdate, $todate);
        $propertydetail['commission'] = $row['agent_commission'];
        array_push($propertydetails, $propertydetail);
    }
    mysqli_close($mysqli);
    return $propertydetails;
}

//get property commission

function getPropertyCommissionRate($propid) {

    $mysqli = getMysqliConnection();
    $propertiestable = getPropertiesTable();
    $res = $mysqli->query("SELECT propertyid,agent_commission FROM {$propertiestable} WHERE propertyid='$propid'") or die($mysqli->error);
    while ($row = $res->fetch_assoc()) {
        $commission = $row['agent_commission'];
    }
    mysqli_close($mysqli);
    return $commission;
}

//commissions list
function get_commissions_listProperty($propid, $fromdate, $todate) {
    include '../includes/config.php';
    $mysqli = getMysqliConnection();
    $propertiestable = getPropertiesTable();
    $propertydetails = array();
    $res = $mysqli->query("SELECT propertyid,agent_commission FROM {$propertiestable} WHERE propertyid='$propid'") or die($mysqli->error);
    while ($row = $res->fetch_assoc()) {
        $propid = $row['propertyid'];
        $propertydetail['propid'] = $propid;
        $propertydetail['propertyname'] = findpropertybyid($propid);
        $propertydetail['rentsum'] = getPropertyCommission($propid, $fromdate, $todate);
        $propertydetail['commission'] = $row['agent_commission'];
        array_push($propertydetails, $propertydetail);
    }
    $mysqli->close();
    return $propertydetails;
}

//get commission
//get property commission between dates
function getPropertyCommission($propid, $startdate, $enddate) {

    $mysqli = getMysqliConnection();
    $invoicetable = getInvoiceTable();
    $invoiceitemstable = invoiceitemsTable();
    $startdate = date("Y-m-d", strtotime($startdate));
    $enddate = date("Y-m-d", strtotime($enddate));
    $propertynameandrentsum = array();
    $rentsum = array();
//return invoice matching these date periods and propid and is not a penalty
    $res = $mysqli->query("SELECT invoiceno,property_id FROM {$invoicetable} WHERE invoicedate between '$startdate' AND '$enddate' AND property_id='$propid' AND is_penalty=0 AND revsd=0 ") or die($mysqli->error);
    while ($row = $res->fetch_assoc()) {

        $rentforinvoice = getRentItemFromInvoice($row['invoiceno']);
        array_push($rentsum, $rentforinvoice);
    }
    return array_sum($rentsum);
}

function getRentItemFromInvoice($invoiceno) {
    $mysqli = getMysqliConnection();
    $invoiceitemstable = invoiceitemsTable();
    $res = $mysqli->query("SELECT item_name,amount FROM {$invoiceitemstable} WHERE invoiceno like '$invoiceno' AND item_name like 'rent' LIMIT 1");
    if ($res) {
        $rent =0;
        while ($row = $res->fetch_assoc()) {
            $rent = $row['amount'];
        }
        if($rent){
            return $rent;
        }else{
            return 0;
        }
       // return $rent;
    } else {
        return 0;
    }
}

function fetchaccountdetails() {
    include_once '../includes/config.php';
    $mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME, 3306);
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") ";
    }
    $res = $mysqli->query("SELECT * FROM bkaccounts") or die($mysqli->error);
    echo '<table class="treport" ><thead>
<tr>
<th><center><u>Account No</u></center></th>
<th><center><u>Account Name</u></center></th>
<th><center><u>Type</u></center></th>
<th><center><u>Balance</u></center></th>
<th><center><u>Creator</u></center></th>
<th><center><u>Statement</u></center></th>
<th><center><u>Edit</u></center></th>
<th><center>Delete</center></th></tr></thead><tbody>';

    while ($row = $res->fetch_assoc()) {
        $accno = $row['acno'];
        echo "<tr><td>" . $row['acno'] . "</td><td>" . $row['acname'] . "</td><td>" . $row['type'] . "</td><td>" . $row['bal'] . "</td><td>" . $row['us'] . "</td><td><input type='radio' name='statementradio' class='accountstatement' value='$accno' title=" . $row['type'] . "></td><td><a href='#' id='editaccount' title=" . $row['acno'] . "><img src='../images/grid.png'>Edit</a></td><td><a href='#' id='delaccount' title=" . $row['acno'] . "><img src='../images/close.png'>Remove</a></td>";
    }
    echo '</tbody></table>';
    mysqli_close($mysqli);
}

//account management
function manageaccts($action, $id, $accname = '', $type = 'default', $user = 'default') {
    include_once '../includes/config.php';
    $mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME, 3306);
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") ";
    }if ($action == 'edit') {
        $res = $mysqli->query("UPDATE bkaccounts SET accname='$accname',type='$type',user='$user' WHERE acno='$id'") or die($mysqli->error);
    } else {
        $res = $mysqli->query("DELETE FROM bkaccounts WHERE acno='$id'") or die($mysqli->error);
    }
    mysqli_close($mysqli);
}

//billing
function getsuppliersdropdown($propid) {
    $mysqli = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME, 3306);
    $tablename = getSupplierListTable();
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") ";
    }
    $res = $mysqli->query("SELECT * FROM $tablename WHERE active='1' AND `property_id` like '$propid' ORDER BY $tablename.suppliername ASC ") or die($mysqli->error);
    while ($row = $res->fetch_assoc()) {
        $suppid = $row['sup_id'];
        $name = $row['suppliername'];
        $address = $row['address'];
        $email = $row['email'];
        $city = $row['city'];
        $phone = $row['phone'];
        echo "<option value='$suppid' title='$email' class='supplier' >" . htmlspecialchars($name) . '&nbsp;(Phone&nbsp' . $phone . ")</option>";
    }
    mysqli_close($mysqli);
}

//supplier acct
//@param array('startdate',enddate,$propid,$suppid)
function getSupplierAcctStatement($arraydetails) {
    $supplierstable = getSupplierListTable();
    $paymentstable = getPaymentsListTable();
    $mysqli = getMysqliConnection();
    $allacctdetails = array();
    $startdate = date('Y-m-d', strtotime($arraydetails['startdate']));
    $enddate = date('Y-m-d', strtotime($arraydetails['enddate']));
    $propid = $arraydetails['property_id'];
    $suppid = $arraydetails['supp_id'];
    $resultset = $mysqli->query("SELECT *,sup_id FROM {$paymentstable} JOIN {$supplierstable} ON $paymentstable.supp_id=$supplierstable.sup_id WHERE $paymentstable.paydate between '$startdate' AND '$enddate' AND $paymentstable.property_id ='$propid' AND $paymentstable.supp_id ='$suppid' AND revsd=0 ") or die($mysqli->error);

    while ($row = $resultset->fetch_assoc()) {
        $supplieracct['payno'] = $row['payno'];
        $supplieracct['amount'] = $row['amount'];
        $supplieracct['date'] = $row['paydate'];
        $supplieracct['paymode'] = $row['paymode'];
        $supplieracct['billno'] = $row['billnopaid'];
        $supplieracct['expenseaccount'] = $row['expenseacct'];
        $supplieracct['remarks'] = $row['rmks'];
        array_push($allacctdetails, $supplieracct);
    }
    return $allacctdetails;
}

//create supplier bill
function create_supplier_bill($supp_id, $billdate, $items, $billamount, $rmks, $propid, $fperiod, $glcode) {
    $db = new MySQLDatabase();
    date_default_timezone_set('Africa/nairobi');
    $db->open_connection();

    $myDateTime = DateTime::createFromFormat('d/m/Y', trim($billdate));
    $billdate = $myDateTime->format('Y-m-d');

    $tablename = "bills";
    $billno = incrementnumber("billno");
    $query = "INSERT into $tablename(`bill_no`,`supp_id`,`glcode`,`bill_amnt`,`bill_paid_amnt`,`bill_date`,`bill_items`,`remarks`,`reversed`,`prop_id`) VALUES ('$billno','$supp_id','$glcode','$billamount','0','$billdate','$items','$rmks','0','$propid')";
    if (!$db->query($query)) {
        echo mysql_error();
    } else {

        //create journal entry for the expense acct
        $entry = createJournalEntry(array('glcode' => $glcode, 'document_ref' => $billno, 'debit' => $billamount, 'ttype' => 'BILL', 'property_id' => $propid, 'desc' => $rmks, 'idclose_period' => $fperiod));
        //if office expense add a credit entry for agentbank
        if ($propid == 0) {
            $glaccountal = getGLCodeForAccount(array('gl' => 'AgentBank'));
            $glcode = $glaccountal['glcode'];
            $entry = createJournalEntry(array('glcode' => $glcode, 'document_ref' => $billno, 'credit' => $billamount, 'ttype' => 'BILL', 'property_id' => $propid, 'desc' => $rmks, 'idclose_period' => $fperiod));
        }

        header('Content-Type: application/json');
        $response_array['status'] = $billno;
        echo json_encode($response_array);
    }
    $db->close_connection();
}

//fetch bill details
function fetchbilldetails($glcode) {
    $mysqli = getMysqliConnection();
    $tablename = "bills";
    $gltable = getAccountsTable();
    $billnos = array();
    $billids = array();
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") ";
    }
    $res = $mysqli->query("SELECT *,acname FROM $tablename JOIN $gltable ON $gltable.glcode=$tablename.glcode WHERE $tablename.glcode like '$glcode' AND ($tablename.bill_amnt - $tablename.bill_paid_amnt)>0 AND reversed=0") or die($mysqli->error);
    $rowcount = mysqli_num_rows($res);
    echo '<table class="treport" class="receipting"><thead>
<tr>
 <th><center><u>s/no</u></center></th>
<th><center><u>Bill No</u></center></th>
<th><center><u>Bill Date</u></center></th>
<th><center><u>Billed Amount</u></center></th>
<th><center>Paid Amount</center></th>
<th><center>Balance</center></th>
<th><center>Part/Over pay</center></th>
<th><center>Full Payment</center></th> </tr></thead><tbody>';
    $i = 1;
    while ($row = $res->fetch_assoc()) {
        $id = $row['bl_id'];
        $amount = $row['bill_amnt'];
        $pdamount = $row['bill_paid_amnt'];
        $name = $row['acname'];
        $billno = $row['bill_no'];
        $billdate = $row['bill_date'];
        $topay = '<input type="hidden" id="payamountbill' . $id . '" title="' . $billno . '" style="width:100px; height:15px;"/>';
        $checkpay = '<input type="checkbox" id="billcheck' . $id . '" id="client" class="' . $amount . '" title="' . $billno . '" style="text-align:central;">';
        echo '<tr><td>' . $i++ . '</td><td id="billnotd">' . $billno . '</td><td id="billdatetd">' . $billdate . '</td><td><input id="billamount' . $id . '" value="' . $amount . '" style="width:100px; height:15px;" readonly/>' . '</td><td id="paidamount' . $id . '">' . $pdamount . '</td><td id="balancetd">' . ($amount - $pdamount) . '</td><td id="topay">' . $topay . '</td><td id="checkpaybill">' . $checkpay . '</td></tr>';
        array_push($billids, $id . '&');
        array_push($billnos, $billno . '&');
    }
    echo '</tbody></table>';
    $billnos_string = implode($billnos);
    echo '<input type="hidden" id="billcount" value="' . implode($billids) . '"/>';
    echo '<input type="hidden" id="billnos" value="' . $billnos_string . '"/>';
    mysqli_close($mysqli);
}

//payment 
function pay_bill($billdate, $payamounts, $paymode, $expenseacct, $chequedetails, $chequeno, $chequedate, $remarks, $supp_id, $billnos, $user, $propid, $fperiod, $isrefund = 0, $cashacct) {
    $db = new MySQLDatabase();
    $db->open_connection();
    $tablename = "paytrans";
    $tablename1 = 'bills';
    $tablename2 = getSupplierListTable();
    $newentrydate = DateTime::createFromFormat('d/m/Y', $billdate);
    $entrydate = trim($newentrydate->format('Y-m-d'));
    $billnossplit = explode('*', $billnos);
    $payamountssplit = explode('*', $payamounts);
    $count = sizeof($billnossplit) - 1;
    $totalpaid = array();
    for ($i = 0; $i < sizeof($billnossplit) - 1; $i++) {
        $payno = incrementnumber("payno");
        $query = $db->query("INSERT into $tablename(`paydate`,`amount`,`pmode`,`payno`,`chqdet`,`chqno`,`chequedate`,`rmks`,`supp_id`,`billnopaid`,`expenseacct`,`us`,`property_id`,`idclose_periods`,`is_refund`) VALUES ('$entrydate','$payamountssplit[$i]','$paymode','$payno','$chequedetails','$chequeno','$chequedate','$remarks','$supp_id','$billnossplit[$i]','$expenseacct','$user','$propid','$fperiod','$isrefund') ") or die(mysql_error());
        $db->query("UPDATE $tablename1 SET bill_paid_amnt=$payamountssplit[$i] WHERE bill_no=$billnossplit[$i]") or die(mysql_error());

        array_push($totalpaid, $payamountssplit[$i]);
    }unset($i);
    header('Content-Type: application/json');
    if ($count > 1) {
        $response_array['status'] = $payno;
        $response_array['count'] = $count;
    } else {
        $response_array['count'] = $count;
        $response_array['status'] = $payno;
    }
    //change balance of cash/bank accounts
    if ($cashacct) {
        $cahaccount = getBankDetails($cashacct);
        $data = array("recpno" => $payno, "amount" => -array_sum($totalpaid), "date" => date("Y-m-d H:i:s", strtotime($entrydate)), "bank_type" => $cahaccount["bank_code"], "is_credit" => 0, "is_debit" => 1, "narration" => $remarks);
        saveUndepositedCash($data);
    }

    echo json_encode($response_array);

    $db->close_connection();
}

function pay_refund($billdate, $payamount, $paymode, $expenseacct, $chequedetails, $chequeno, $chequedate, $remarks, $supp_id, $billno, $user, $propid, $fperiod, $isrefund = 0, $recpno) {
    $db = new MySQLDatabase();
    $db->open_connection();
    $tablename = "paytrans";
    $tablename1 = 'bills';
    $tablename2 = getSupplierListTable();
    $receipts = getReceiptsTable();
	$billdate=str_replace("/","-",$billdate);
    $newentrydate = DateTime::createFromFormat('d-m-Y', $billdate);
    $entrydate = trim($newentrydate->format('Y-m-d'));
    $payno = incrementnumber("payno");
    $query = $db->query("INSERT into $tablename(`paydate`,`amount`,`pmode`,`payno`,`chqdet`,`chqno`,`chequedate`,`rmks`,`supp_id`,`billnopaid`,`expenseacct`,`us`,`property_id`,`idclose_periods`,`is_refund`) VALUES ('$entrydate','$payamount','$paymode','$payno','$chequedetails','$chequeno','$chequedate','$remarks','$supp_id','$billno','$expenseacct','$user','$propid','$fperiod','$isrefund') ") or die(mysql_error());
    $db->query("UPDATE $receipts SET `refunded`=1 WHERE `recpno`='$recpno'") or die(mysql_error());

    header('Content-Type: application/json');
    $response_array['tenant'] = $supp_id;
    $response_array['count'] = 0;
    $response_array['status'] = $payno;

    echo json_encode($response_array);

    $db->close_connection();
}

//landlord payment
function makeLandlordpayment($params) {
    $db = new MySQLDatabase();
    $db->open_connection();
    $tablename = "landlordpayments";

    $newentrydate = DateTime::createFromFormat('d-m-Y', $params["paydate"]);
    $entrydate = trim($newentrydate->format('Y-m-d'));
    $paydateperiod = trim($newentrydate->format('d/m/Y'));
    $chequedatenonformatted = DateTime::createFromFormat('d-m-Y', $params["chequedate"]);
    $chequedate = trim($chequedatenonformatted->format('Y-m-d'));
    $payno = incrementnumber("payno");
    $amount = $params["amount"];
    $paymode = $params["amount"];
    $chequedetails = $params["chequedetails"];
    $chequeno = $params["chequeno"];

    $remarks = "Landlord Payment";
    $user = $params["user"];
    $propid = $params["property_id"];
    $reason=$params["reason"];
    $fperiod = getPeriodByDate($paydateperiod);

    $query = $db->query("INSERT into $tablename(`paydate`,`amount`,`pmode`,`payno`,`chqdet`,`chqno`,`chequedate`,`rmks`,`us`,`property_id`,`revsd`,`idclose_periods`,`reason`) VALUES ('$entrydate','$amount','$paymode','$payno','$chequedetails','$chequeno','$chequedate','$remarks','$user','$propid',0,'$fperiod','$reason') ") or die(mysql_error());

    //deduct from bank account
    $bank = getBankDetails($bank);
    $data = array("recpno" => $payno, "amount" => $amount, "date" => date("Y-m-d H:i:s", strtotime($entrydate)), "bank_type" => $bank["bank_code"], "is_credit" => 0, "is_debit" => 1, "narration" => "Landlord Payment for the date ending" . $entrydate);
    saveUndepositedCash($data);
    header('Content-Type: application/json');

    $response_array['count'] = 0;
    $response_array['status'] = $payno;

    echo json_encode($response_array);

    $db->close_connection();
}

function getLandLordPaidAmountsForMonth($yearmonth, $propid) {
    $tablename = "landlordpayments";
    $mysqli = getMysqliConnection();
    $allpayments = array();
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") ";
    }
    $res = $mysqli->query("SELECT * FROM $tablename WHERE DATE_FORMAT(`paydate`,'%Y-%m') like '$yearmonth' AND `property_id`='$propid' ") or die($mysqli->error);
    while ($row = $res->fetch_assoc()) {
        $payment["amount"] = $row["amount"];
        $payment["payno"] = $row["payno"];
        $payment["chequeno"] = $row["chqno"];
        $payment["chequedate"] = $row["chequedate"];

        array_push($allpayments, $payment);
    }

    return $allpayments;
}

function getDepositRefundList($startdate, $enddate, $propid) {
    $db = new MySQLDatabase();
    $db->open_connection();
    $tablename = "paytrans";

    $sql = $db->query("SELECT * FROM $tablename WHERE `paydate` between '$startdate' AND '$enddate' AND is_refund=1 ") or die($db->error());

    while ($row = $db->fetch_array($sql)) {
        $alldetails[] = $row;
        $paynos = $row['payno'];
        $paydate = $row['paydate'];
        $amount = $row['amount'];
        $rmks = $row['rmks'];
        $id = $row['supp_id']; //tenant_id
        $billno = $row['billnopaid'];
        $paymode = $row['pmode'];
        $chequeno = $row['chqno'];
        $chequedate = $row['chequedate'];
        if ($paymode == 1) {
            $chequecash = 'cheque';
            $chequeno1 = 'Cheque No&nbsp;&nbsp;' . $chequeno . '&nbsp;|&nbsp;';
            $chqdate = $chequedate;
            $chequedate1 = 'Cheque Date';
        } else {
            $chequecash = 'cash';
        }
    }
    return $alldetails;
}

//get payment ledger


function printdepositvoucher($payno, $propid, $user, $tenantid) {
    $db = new MySQLDatabase();
    date_default_timezone_set('Africa/Nairobi');
    $date = date("d/m/y");
    $time = date('h:i A');
    $db->open_connection();
    $tablename = "paytrans";
    $tablename1 = "bkaccounts";
    $tablename2 = "bills";
    $tableitems = [];
    $sql = $db->query("SELECT * FROM $tablename WHERE `payno` like '$payno'") or die(mysql_error());
    if (mysql_numrows($sql) > 0) {
        while ($row = mysql_fetch_array($sql)) {
            $paynos = $row['payno'];
            $paydate = $row['paydate'];
            $amount = $row['amount'];
            $rmks = $row['rmks'];
            $id = $row['supp_id']; //glcode
            $billno = $row['billnopaid'];
            $paymode = $row['pmode'];
            $chequeno = $row['chqno'];
            $chequedate = $row['chequedate'];
            if ($paymode == 1) {
                $chequecash = 'cheque';
                $chequeno1 = 'Cheque No&nbsp;&nbsp;' . $chequeno . '&nbsp;|&nbsp;';
                $chqdate = $chequedate;
                $chequedate1 = 'Cheque Date';
            } else {
                $chequecash = 'cash';
            }
        }
    }
    $supplierlisttable = getSupplierListTable();
    $sql2 = $db->query("SELECT acname FROM {$tablename1} WHERE `glcode`='$id'") or die(mysql_error());
    while ($row2 = mysql_fetch_array($sql2)) {
        $sname = $row2['acname'];
    }
    $sql3 = $db->query("SELECT bill_items,bill_amnt,bill_paid_amnt,remarks,prop_id FROM $tablename2 WHERE bill_no='$billno'") or die($db->error());
    while ($row3 = mysql_fetch_array($sql3)) {
        $item_name = $row3['bill_items'];
        $bal_amount = $row3['bill_amnt'] - $row3['bill_paid_amnt'];
        $propid = $row3['prop_id'];
        $remarks = $row3['remarks'];
        array_push($tableitems, '<tr><td style="color:blue;"><u>' . $item_name . '</u></td><td>BALANCE Ksh:' . number_format($bal_amount, 2) . '</td></tr>');
    }
    $settings = getSettings();
    $tenant = getTenantDetails($tenantid);
    echo '<center><table class="printable" style="width:800px;"><div id="printheader">
        <tr><td colspan="3" ><span id="copy"></span><center><h2>' . $settings['company_name'] . '</h2></center></td></tr>
        <tr><td colspan="3" ><span id="copy"></span><center><span id="invoice">PAYMENT VOUCHER</span></center></td></tr>
</div>';
    echo '<tr><td style="width:50%"><span id="invoiceno">PAYMENT NO&nbsp;' . $payno . '</span></td><td colspan="3">Date  ' . date('d-m-Y', strtotime($paydate)) . '</td><td></td></tr>';
    echo '<tr><td colspan="3"><br/><td></tr>';
    echo '<tr><td style="width:50%"><b>CREDITOR :&nbsp;</b> ' . ucwords($tenant['name']) . '<br/><br/><b>PAYER: </b>' . $settings['company_name'] . '</td><td><b>AMOUNT: Kshs ' . number_format($amount, 2) . '</b></td></tr>';
    echo '<tr><td colspan="1"></td><td><b>Ksh&nbsp;' . convert_number_to_words($amount) . ' only</b></td></tr>';
    echo '<tr><td width="50%" style="font-size:11px;color:grey"><b>being payment for: </b>' . @$rmks . '</td></tr>';
    echo '<tr><td width="80%" style="font-size:11px;color:grey">' . @$chequecash . '&nbsp;|&nbsp;' . @$chequeno1 . '&nbsp;' . @$chequedate1 . '&nbsp;&nbsp;' . @$chqdate . '</td></tr>';
    echo '<tr><td colspan="3"><hr/>Authorised by.......................................................................................</td></tr>';
    echo '<tr><td colspan="3">Authority Signature....................................................... |&nbsp;&nbsp;&nbsp; Recepient Signature...............................................</td></tr>';

    $userdetail = getUserById($user);
    echo '<tr><td colspan="3"><i>' . $userdetail['username'] . '&nbsp;&nbsp;' . $date . '&nbsp;&nbsp;' . $time . '</i></td></tr>';
    echo '</table></center>';
}

//deposit voucher
function printvoucher($payno, $propid, $user) {
    $db = new MySQLDatabase();
    date_default_timezone_set('Africa/Nairobi');
    $date = date("d/m/y");
    $time = date('h:i A');
    $db->open_connection();
    $tablename = "paytrans";
    $tablename1 = "bkaccounts";
    $tablename2 = "bills";
    $tableitems = [];
    $sql = $db->query("SELECT * FROM $tablename WHERE `payno` like '$payno'") or die(mysql_error());
    if (mysql_numrows($sql) > 0) {
        while ($row = mysql_fetch_array($sql)) {
            $paynos = $row['payno'];
            $paydate = $row['paydate'];
            $amount = $row['amount'];
            $id = $row['supp_id']; //glcode
            $billno = $row['billnopaid'];
            $paymode = $row['pmode'];
            $chequeno = $row['chqno'];
            $chequedate = $row['chequedate'];
            if ($paymode == 1) {
                $chequecash = 'cheque';
                $chequeno1 = 'Cheque No&nbsp;&nbsp;' . $chequeno . '&nbsp;|&nbsp;';
                $chqdate = $chequedate;
                $chequedate1 = 'Cheque Date';
            } else {
                $chequecash = 'cash';
            }
        }
    }
    $supplierlisttable = getSupplierListTable();
    $sql2 = $db->query("SELECT acname FROM {$tablename1} WHERE `glcode`='$id'") or die(mysql_error());
    while ($row2 = mysql_fetch_array($sql2)) {
        $sname = $row2['acname'];
    }
    $sql3 = $db->query("SELECT bill_items,bill_amnt,bill_paid_amnt,remarks,prop_id FROM $tablename2 WHERE bill_no='$billno'") or die($db->error());
    while ($row3 = mysql_fetch_array($sql3)) {
        $item_name = $row3['bill_items'];
        $bal_amount = $row3['bill_amnt'] - $row3['bill_paid_amnt'];
        $propid = $row3['prop_id'];
        $remarks = $row3['remarks'];
        array_push($tableitems, '<tr><td style="color:blue;"><u>' . $item_name . '</u></td><td>BALANCE Ksh:' . number_format($bal_amount, 2) . '</td></tr>');
    }
    $settings = getSettings();
    echo '<center><table class="printable" style="width:210mm !important;height:148mm !important"><div id="printheader">
        <tr><td colspan="3" ><span id="copy"></span><center><h2>' . $settings['company_name'] . '</h2></center></td></tr>
        <tr><td colspan="3" ><span id="copy"></span><center><span id="invoice">PAYMENT VOUCHER</span></center></td></tr>
</div>';
    echo '<tr><td style="width:50%"><span id="invoiceno">PAYMENT NO&nbsp;' . $payno . '</span></td><td colspan="3">Date  ' . date('d-m-Y', strtotime($paydate)) . '</td><td></td></tr>';
    echo '<tr><td colspan="3"><br/><td></tr>';
    echo '<tr><td style="width:50%"><b>CREDITOR :&nbsp;</b> ' . ucwords(@$sname) . '<br/><br/><b>PAYER: </b>' . ucwords(str_replace('_', " ", findpropertybyid($propid))) . '</td><td><b>AMOUNT: Kshs ' . number_format($amount, 2) . '</b></td></tr>';
    echo '<tr><td colspan="1"></td><td><b>Ksh&nbsp;' . convert_number_to_words($amount) . ' only</b></td></tr>';
    echo '<tr><td width="50%" style="font-size:11px;color:grey"><b>being payment for: </b>' . @$remarks . '</td></tr>';
    echo '<tr><td width="80%" style="font-size:11px;color:grey">' . @$chequecash . '&nbsp;|&nbsp;' . @$chequeno1 . '&nbsp;' . @$chequedate1 . '&nbsp;&nbsp;' . @$chqdate . '</td></tr>';
    echo '<tr><td colspan="3"><hr/>Authorised by.......................................................................................</td></tr>';
    echo '<tr><td colspan="3">Authority Signature....................................................... |&nbsp;&nbsp;&nbsp; Recepient Signature...............................................</td></tr>';

    $userdetail = getUserById($user);
    echo '<tr><td colspan="3"><i>' . $userdetail['username'] . '&nbsp;&nbsp;' . $date . '&nbsp;&nbsp;' . $time . '</i></td></tr>';
    echo '</table></center>';
}

//landlord payment voucher
function printlandlordvoucher($payno, $propid, $user) {
    $db = new MySQLDatabase();
    date_default_timezone_set('Africa/Nairobi');
    $date = date("d/m/y");
    $time = date('h:i A');
    $db->open_connection();
    $tablename = "landlordpayments";
    $tablename1 = "bkaccounts";
    $tablename2 = "bills";
    $tableitems = [];
    $sql = $db->query("SELECT * FROM $tablename WHERE `payno` like '$payno'") or die(mysql_error());
    if (mysql_numrows($sql) > 0) {
        while ($row = mysql_fetch_array($sql)) {
            $paynos = $row['payno'];
            $paydate = $row['paydate'];
            $amount = $row['amount'];
            $remarks = $row["rmks"];
            $sname = strtoupper(findpropertybyid($row["property_id"]));

            $chequeno = $row['chqno'];
            $chequedate = $row['chequedate'];
            $reason = $row['reason'];
            $chequecash = 'Cheque Payment';
            $chequeno1 = 'Cheque No&nbsp;&nbsp;' . $chequeno . '&nbsp;|&nbsp;';
            $chqdate = $chequedate;
            $chequedate1 = 'Cheque Date';
        }
    }

    $settings = getSettings();
    echo '<center><table class="printable" style="width:800px;"><div id="printheader">
        <tr><td colspan="3" ><span id="copy"></span><center><h2>' . $settings['company_name'] . '</h2></center></td></tr>
        <tr><td colspan="3" ><span id="copy"></span><center><span id="invoice">PAYMENT VOUCHER</span></center></td></tr>
</div>';
    echo '<tr><td style="width:50%"><span id="invoiceno">PAYMENT NO&nbsp;' . $payno . '</span></td><td colspan="3">Date  ' . date('d-m-Y', strtotime($paydate)) . '</td><td></td></tr>';
    echo '<tr><td colRspan="3"><br/><td></tr>';
    echo '<tr><td style="width:50%"><b>CREDITOR :&nbsp;</b> ' . strtoupper(str_replace('_', " ", findpropertybyid($propid))) . '<br/><br/><b>PAYER: </b>' . $sname . '</td><td><b>AMOUNT: Kshs ' . number_format($amount, 2) . '</b></td></tr>';
    echo '<tr><td colspan="1"></td><td><b>Ksh&nbsp;' . convert_number_to_words($amount) . ' only</b></td></tr>';
    echo '<tr><td width="50%" style="font-size:11px;color:grey"><b>Remarks: </b>' . $remarks . '('.$reason.')</td></tr>';
    echo '<tr><td width="80%" style="font-size:11px;color:grey">' . $chequecash . '&nbsp;|&nbsp;' . $chequeno1 . '&nbsp;' . $chequedate1 . '&nbsp;&nbsp;' . $chqdate . '</td></tr>';
    echo '<tr><td colspan="3"><hr/>Authorised by.......................................................................................</td></tr>';
    echo '<tr><td colspan="3">Authority Signature....................................................... |&nbsp;&nbsp;&nbsp; Recepient Signature...............................................</td></tr>';

    $userdetail = getUserById($user);
    echo '<tr><td colspan="3"><i>' . $userdetail['username'] . '&nbsp;&nbsp;' . $date . '&nbsp;&nbsp;' . $time . '</i></td></tr>';
    echo '</table></center>';
}

//payments list
function paymentslistsupplier($startdate, $enddate, $propid, $user, $officeexpense, $filterexpense) {
    include_once '../includes/config.php';
    @session_start();
    $mysqli = getMysqliConnection();
    date_default_timezone_set('Africa/Nairobi');
    $startdate = date("Y-m-d", strtotime($startdate));
    $enddate = date("Y-m-d", strtotime($enddate));
    
    
  //  die($startdate."ssdfsd".$enddate);
    if ($mysqli->connect_errno) {
        echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") ";
    }
    $addquery = "";
    if ($filterexpense) {
        $addquery = "AND paytrans.supp_id=$filterexpense";
    }

    if ($officeexpense == 1) {
        
        $res = $mysqli->query("SELECT DISTINCT (paytrans.payno),paytrans.billnopaid,paytrans.property_id,paytrans.amount,paytrans.payno,paytrans.pmode,paytrans.paydate,paytrans.rmks,bkaccounts.acname,bills.bill_amnt,bills.bill_paid_amnt,(bills.bill_amnt -bills.bill_paid_amnt) as balance,bills.remarks FROM paytrans LEFT JOIN bkaccounts ON paytrans.supp_id=bkaccounts.glcode LEFT JOIN bills ON paytrans.billnopaid=bills.bill_no  WHERE paytrans.property_id='0'  AND `is_refund`=0 AND  DATE_FORMAT(paytrans.paydate,'%Y-%m-%d') between '$startdate' AND '$enddate' $addquery GROUP BY payno") or die($mysqli->error);
        $entity = "OFFICE";
        $property = '';
    } 
    else if ($officeexpense == 2) {
        
       $res = $mysqli->query("SELECT DISTINCT (paytrans.payno),paytrans.billnopaid,paytrans.revsd,paytrans.property_id,paytrans.amount,paytrans.payno,paytrans.pmode,paytrans.paydate,paytrans.rmks,bkaccounts.acname,bills.bill_amnt,bills.bill_paid_amnt,(bills.bill_amnt -bills.bill_paid_amnt) as balance,bills.remarks FROM paytrans LEFT JOIN bkaccounts ON paytrans.supp_id=bkaccounts.glcode LEFT JOIN bills ON paytrans.billnopaid=bills.bill_no  WHERE `is_refund`=0  AND DATE_FORMAT(paytrans.paydate,'%Y-%m-%d') between '$startdate' AND '$enddate' GROUP BY payno") or die($mysqli->error);
        $entity ="ALL EXPENSES";
        $property = "FOR ALL PROPERTIES";
    } 
    else if ($suppid && $officeexpense == 0) {
        $res = $mysqli->query("SELECT DISTINCT (paytrans.payno),paytrans.billnopaid,paytrans.revsd,paytrans.property_id,paytrans.amount,paytrans.payno,paytrans.pmode,paytrans.paydate,paytrans.rmks,bkaccounts.acname,bills.bill_amnt,bills.bill_paid_amnt,(bills.bill_amnt -bills.bill_paid_amnt) as balance,bills.remarks FROM paytrans LEFT JOIN bkaccounts ON paytrans.supp_id=bkaccounts.glcode LEFT JOIN bills ON paytrans.billnopaid=bills.bill_no  WHERE paytrans.property_id='$propid' AND bills.supp_id='$suppid' AND `is_refund`=0  AND DATE_FORMAT(paytrans.paydate,'%Y-%m-%d') between '$startdate' AND '$enddate' $addquery GROUP BY payno") or die($mysqli->error);
        $entity = strtoupper(findSupplieryById($suppid));
        $property = "OF " . findpropertybyid($propid);
    } else {
        //die("SELECT DISTINCT (paytrans.payno),paytrans.amount,paytrans.payno,paytrans.pmode,paytrans.paydate,paytrans.rmks,bkaccounts.acname,bills.bill_amnt,bills.bill_paid_amnt,(bills.bill_amnt -bills.bill_paid_amnt) as balance,bills.remarks FROM paytrans LEFT JOIN bkaccounts ON paytrans.supp_id=bkaccounts.glcode LEFT JOIN bills ON paytrans.billnopaid=bills.bill_no  WHERE paytrans.property_id='$propid' AND `is_refund`=0 AND DATE_FORMAT(paytrans.paydate,'%Y-%m-%d') between '$startdate' AND '$enddate' $addquery GROUP BY payno");
        $res = $mysqli->query("SELECT DISTINCT (paytrans.payno),paytrans.billnopaid,paytrans.revsd,paytrans.property_id,paytrans.amount,paytrans.payno,paytrans.pmode,paytrans.paydate,paytrans.rmks,bkaccounts.acname,bills.bill_amnt,bills.bill_paid_amnt,(bills.bill_amnt -bills.bill_paid_amnt) as balance,bills.remarks FROM paytrans LEFT JOIN bkaccounts ON paytrans.supp_id=bkaccounts.glcode LEFT JOIN bills ON paytrans.billnopaid=bills.bill_no  WHERE paytrans.property_id='$propid' AND `is_refund`=0 AND DATE_FORMAT(paytrans.paydate,'%Y-%m-%d') between '$startdate' AND '$enddate' $addquery GROUP BY payno") or die($mysqli->error);
        $entity = 'ALL';
        $property = "OF " . findpropertybyid($propid);
    }
    echo '<table class="treport1 width70" ><thead>
<tr><td><img src="../images/cursors/logo1.png" style="height:60px;width:90px;"></td><td colspan=10 style="background-color:beige"><h3><center>PAYMENTS LIST FOR <u>' . $entity . '</u> <i>' . strtoupper($property) . '</i>&nbsp;FOR THE PERIOD&nbsp;' . date('d-m-Y', strtotime($startdate)) . '&nbsp;TO&nbsp;' . date('d-m-Y', strtotime($enddate)) . '</center></h3></td></tr>
<tr>
<th><center><u>s/No</u></center></th>
<th><center><u>Property</u></center></th>
<th><center><u>Bill No</u></center></th>
<th><center><u>Voucher No</u></center></th>
<th><center><u>Paymode</u></center></th>
<th><center><u>Date</u></center></th>
<th><center><u>Supplier/Expense</u></center></th>
<th><center><u>Narration</u></center></th>
<th><center><u>Credit</u></center></th>
<th><center><u>Debit</center></u></th>
<th><center><u>Balance</center></u></th></tr></thead><tbody>';
    $counter = 1;
    while ($row = $res->fetch_assoc()) {
       $propid = $row['property_id'];
     
           $property=  findpropertybyid($propid);
          
       $reversed=$row['revsd'];
       if($reversed){
        $payno = "R".$row['payno'];
        $billno = "R".$row['billnopaid'];
        $billamount = 0;
        $paidamount = 0;
        $balance = 0;
        $sumbillamount[] = ($billamount);
        $sumpaidamount[] = $paidamount;
        $sumbal[] = $balance;
       }else {
        $payno = $row['payno'];
        $billno=$row['billnopaid'];
        $billamount = $row['amount'];
        $paidamount = $row['bill_paid_amnt'];
        $balance = $row['balance'];
        $sumbillamount[] = ($billamount);
        $sumpaidamount[] = $paidamount;
        $sumbal[] = $balance;
       }
        $paydate = $row['paydate'];
        $paymode = getPayMode($row['pmode']);
        $rmks = $row['remarks'];
       
        
        
        echo '<tr><td>' . $counter++ . '</td><td>'.$property.'</td><td>'.$billno.'</td><td><a href="defaultreports.php?report=printvoucher&voucherno=' . $payno . '&propid=' . $propid . '&user=' . $_SESSION['username'] . '" target="blank">' . $payno . '</a></td><td>' . $paymode[0]['paymode'] . '</td><td>' . date('F j,Y', strtotime($paydate)) . '</td><td>' . $row['acname'] . '</td><td>&nbsp;' . $rmks . '</td><td style="color:red">&nbsp;&nbsp;' . number_format($billamount) . '</td><td style="color:green">&nbsp;&nbsp;' . number_format($paidamount) . '</td><td>&nbsp;&nbsp;' . $balance . '</td></tr>';
    }
    echo '</tbody><tfoot>'
    . '<tr><td><b>GRAND TOTAL</b></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td><b>' . number_format(@array_sum($sumbillamount), 2) . '</b></td><td><b>' . number_format(@array_sum($sumpaidamount), 2) . '</b></td><td><b>' . number_format(@array_sum($sumbal), 2) . '</b></td></tr>';
    echo '</tfoot></table>';
    unset($counter);
    $mysqli->close($mysqli);
}

//daily cash movement
function getDailyCash($date) {
    date_default_timezone_set('Africa/Nairobi');
    $time = date('h:i A');
    $mysqli = getMysqliConnection();
    $tablename = "recptrans";
    $tablename2 = "tenants";
    $paymodes = "paymodes";
    $sumamount = array();
    $sumvatamount = array();
    $sumtotalamount = array();

    $allproperties = getProperties();
    echo '<table class="treport1 width70" ><thead>
<tr><td><img src="../images/cursors/logo1.png" style="height:60px;width:90px;"></td><td colspan="9"><center><span style="font-size:18px;font-weight:bold">' . $_SESSION['clientname'] . '</span><span style="font-size:18px;font-weight:normal; float:right;"></span><center>
<br/><span style="font-size:16px;font-weight:normal;"><centreceiptlier>' . str_repeat('&nbsp;', 25) . 'CASH RECEIVED ON <b> ' . date("d-m-Y", strtotime($date)) . '</b> </center></span></td></tr>';
    echo'<tr>
<u><th>S/no</th><th>Property</th> <th>Receipt No</th><th>Receipt Date</th> <th>House No</th><th>Tenant/Other Name</th><th>Narration</th><th>Paymode</th><th>Total Amount</th></u></tr>';
    echo '</thead><tbody>';
    $i = 1;
    foreach ($allproperties as $property) {
        $propid = $property['property_id'];
        $query = $mysqli->query("SELECT $tablename.recpno,$tablename.rdate,$tablename.rmks,$tablename.amount,$tablename.pmode,$paymodes.paymode,$tablename2.Id,$tablename2.tenant_name,$tablename2.Apartment_tag,$tablename2.property_name FROM $tablename JOIN $paymodes ON $tablename.pmode=$paymodes.id LEFT JOIN $tablename2 ON $tablename.idno=$tablename2.Id WHERE ($tablename.rdate BETWEEN '$date' AND '$date') AND $tablename2.property_id='$propid' ORDER BY $tablename2.Apartment_tag ASC ") or die($mysqli->error);
        while ($row = $query->fetch_assoc()) {

            echo '<tr><td>' . $i++ . '</td><td>' . findpropertybyid($propid) . '</td><td><a class="whitetext"  href="defaultreports.php?report=printreceipt&receiptno=' . $row['recpno'] . '" target="blank"><span style="color:blue">' . $row['recpno'] . '</span></a></td><td>' . $row['rdate'] . '</td><td>' . $row['Apartment_tag'] . '</td><td>' . $row['tenant_name'] . '</td><td>' . $row['rmks'] . '</td><td>' . $row['paymode'] . '</td><td>' . number_format($row['amount'], 2) . '</td></tr>';
            array_push($sumamount, (float) $row['amount']);
            array_push($sumvatamount, (float) ($row['amount']));
            array_push($sumtotalamount, (float) ($row['amount']));
        }
    }

    echo '</tbody>';
    echo '<tfoot><tr><td><b>GRAND TOTAL</b></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td><b>' . number_format(@array_sum($sumtotalamount), 2) . '</b></td></tr></tfoot>';
    echo '</table>';
    echo '<hr/>';





    echo '<table class="treport1 width70" ><thead>
<tr><td><img src="../images/cursors/logo1.png" style="height:60px;width:90px;"></td><td colspan="9"><h3><center>PAYMENTS LIST FOR ' . date('d-m-Y', strtotime($date)) . '&nbsp;</center></h3></td></tr>
<tr>
<th><center><u>S/No</u></center></th>
<th><center><u>Property</u></center></th>
<th><center><u>Payment No</u></center></th>
<th><center><u>PayDate</u></center></th>
<th><center><u>Supplier/Expense</u></center></th>
<th><center><u>Narration</u></center></th>
<th><center><u>Credit</u></center></th>
<th><center><u>Debit</center></u></th>
<th><center><u>Paymode</center></u></th>
<th><center><u>Balance</center></u></th></tr></thead><tbody>';
    $counter = 1;
    foreach ($allproperties as $property) {
        $propid = $property['property_id'];
        $res = $mysqli->query("SELECT DISTINCT (paytrans.payno),paytrans.amount,paytrans.property_id,paytrans.pmode,paytrans.payno,paytrans.paydate,paytrans.rmks,bkaccounts.acname,bills.bill_amnt,bills.bill_paid_amnt,(bills.bill_amnt -bills.bill_paid_amnt) as balance,bills.remarks FROM paytrans LEFT JOIN bkaccounts ON paytrans.supp_id=bkaccounts.glcode LEFT JOIN bills ON paytrans.billnopaid=bills.bill_no  WHERE paytrans.property_id='$propid' AND paytrans.paydate between '$date' AND '$date' GROUP BY payno") or die($mysqli->error);
        while ($row = $res->fetch_assoc()) {
            $payno = $row['payno'];
            $paydate = $row['paydate'];
            $propertyid = $row['property_id'];
            $propertyname = findpropertybyid($propertyid);
            $pmode = $row['pmode'];
            $paymode = getPayMode($pmode);
            $rmks = $row['remarks'];
            $billamount = $row['amount'];
            $paidamount = $row['bill_paid_amnt'];
            $balance = $row['balance'];
            $sumbillamount[] = ($billamount);
            $sumpaidamount[] = $paidamount;
            $sumbal[] = $balance;
            echo '<tr><td>' . $counter++ . '</td><td>' . $propertyname . '</td><td><a href="defaultreports.php?report=printvoucher&voucherno=' . $payno . '&propid=' . $propid . '&user=' . $_SESSION['username'] . '" target="blank">' . $payno . '</a></td><td>' . date('F j,Y', strtotime($paydate)) . '</td><td>' . $row['acname'] . '</td><td>&nbsp;' . $rmks . '</td><td style="color:red">&nbsp;&nbsp;' . number_format($billamount) . '</td><td style="color:green">&nbsp;&nbsp;' . number_format($paidamount) . '</td><td>' . $paymode[0]['paymode'] . '</td><td>&nbsp;&nbsp;' . $balance . '</td></tr>';
        }
    }
    echo '</tbody><tfoot>'
    . '<tr><td><b>GRAND TOTAL</b></td><td></td><td></td><td></td><td></td><td></td><td><b>' . number_format(@array_sum($sumbillamount), 2) . '</b></td><td><b>' . number_format(@array_sum($sumpaidamount), 2) . '</b></td><td></td><td><b>' . number_format(@array_sum($sumbal), 2) . '</b></td></tr>';
    echo '</tfoot></table>';
    echo '<hr/>';
    echo '<i>Printed by:</i> ' . $_SESSION['username'] . '&nbsp;&nbsp;&nbsp;&nbsp;' . date("d-m-y") . '&nbsp;' . $time;
    unset($counter);
    $mysqli->close($mysqli);
}

//@param array('startdate','enddate','propid','user','count','suppid')
function getPaymentsForProperty($arraydetails) {
    $mysqli = getMysqliConnection();
    $startdate = date("Y-m-d", strtotime($arraydetails['startdate']));
    $enddate = date("Y-m-d", strtotime($arraydetails['enddate']));
    $allbilldetails = array();
    $propid = $arraydetails['propid'];
    $suppid = $arraydetails['suppid'];
    if ($suppid) {
        $res = $mysqli->query("SELECT DISTINCT (paytrans.payno),paytrans.amount,paytrans.payno,paytrans.paydate,paytrans.rmks,supplierexpenselist.suppliername,bills.bill_amnt,bills.bill_paid_amnt,(bills.bill_amnt -bills.bill_paid_amnt) as balance,bills.bill_items,bills.remarks FROM paytrans LEFT JOIN supplierexpenselist ON paytrans.supp_id=supplierexpenselist.sup_id LEFT JOIN bills ON paytrans.billnopaid=bills.bill_no  WHERE paytrans.property_id='$propid' AND bills.supp_id='$suppid' AND paytrans.paydate between '$startdate' AND '$enddate' AND revsd=0  GROUP BY payno") or die($mysqli->error);
        $entity = strtoupper(findSupplieryById($suppid));
    } else {

        $res = $mysqli->query("SELECT DISTINCT (paytrans.payno),paytrans.amount,paytrans.payno,paytrans.paydate,paytrans.rmks,supplierexpenselist.suppliername,bills.bill_amnt,bills.bill_paid_amnt,(bills.bill_amnt -bills.bill_paid_amnt) as balance,bills.bill_items,bills.remarks FROM paytrans LEFT JOIN supplierexpenselist ON paytrans.supp_id=supplierexpenselist.sup_id LEFT JOIN bills ON paytrans.billnopaid=bills.bill_no  WHERE paytrans.property_id='$propid' AND paytrans.paydate between '$startdate' AND '$enddate' AND revsd=0 GROUP BY payno") or die($mysqli->error);
        $entity = 'ALL';
    }
    while ($row = $res->fetch_assoc()) {
        $paydetails['payno'] = $row['payno'];
        $paydetails['paydate'] = $row['paydate'];
        $paydetails['bill_items'] = $row['bill_items'];
        $paydetails['remarks'] = $row['remarks'];
        $paydetails['amount'] = $row['amount'];
        $paydetails['billpaid'] = $row['bill_paid_amnt'];
        $paydetails['balance'] = $row['balance'];
        array_push($allbilldetails, $paydetails);
    }
    return $allbilldetails;
}

//manage system users
function get_system_users() {
    $db = new MySQLDatabase();
    $db->open_connection();
    $tablename = "accesslevels";
    echo '<table class="treport" >
<tr>
<th><center>&nbsp;&nbsp;</center></th>
<th><center><u>Username</u></center></th>
<th><center><u>Password</u></center></th>
<th><center><u>Group</u></center></th>
<th><center>Status</center></th>
<th><center><u>Edit</u></center></th>
<th><center><u>Delete</u></center></th></tr>';

    $sql = $db->query("SELECT * FROM $tablename") or die($db->error());
    while ($row = $db->fetch_array($sql)) {
        $id = $row['accessgrpid'];
        $userid = $row['agent_id'];
        $username = $row['username'];
        $password = $row['password'];
        $group = $row['group'];
        $status = $row['status'];

        if (strtoupper($status) == 'ACTIVE') {
            $statusfield = "<option value='INACTIVE'>INACTIVE</option>";
        } else {
            $statusfield = "<option value='ACTIVE'>ACTIVE</option>";
        }

        echo "<tr><td><input type='hidden' id='accessgrpid$id' class='input1' value='$id'/><input type='hidden' id='userid$id' readonly='true' class='input1' value='$userid'/></td><td><input id='username$id' type='text' class='username' class='input1'  value='$username'/></td><td><input type='password' id='userpassword$id'  class='input1' value='$password'/></td><td><input type='text' id='usergroup$id' class='usergroup input1' value='$group'/></td><td><select type='text' id='userstatus$id' class='input1 selectstatus'>"
        . "<option selected value='$status'>$status</option>"
        . $statusfield
        . "</select>"
        . "</td><td><a href='#' title='$id' class='edituser'><img src='./images/grid.png'/></a></td><td><a href='#' title='$id' id='deleteuser'><img src='./images/close.png'/></a></td></tr>";
    }
    echo '</table>';
    $db->close_connection();
}

//update users
function updateUser($userdetails) {
    $mysqli = getMysqliConnection();
    $userstable = getUsersTable();
    $userid = $userdetails['user_id'];
    $username = $userdetails['username'];
    $password = $userdetails['password'];
    $group = $userdetails['group'];
    $status = $userdetails['status'];

    $resultset = $mysqli->query("UPDATE $userstable  SET `username`='$username',`password`='$password',`group`='$group',`status`='$status' WHERE `accessgrpid`='$userid' ") or die($mysqli->error);
    header('Content-Type: application/json');
    if ($resultset) {
        $response_array['status'] = 'Successfully updated user details';
    } else {
        $response_array['status'] = 'Failed to update user.Try again';
    }
    echo json_encode($response_array);
}

function getUserById($id) {
    $mysqli = getMysqliConnection();
    $userstable = getUsersTable();
    $userdetails = array();
    $resultset = $mysqli->query("SELECT * FROM $userstable WHERE accessgrpid='$id' ") or die($mysqli->error);
    while ($row = $resultset->fetch_assoc()) {
        $userdetails['username'] = $row['username'];
        $userdetails['agent_id'] = $row['agent_id'];
        $userdetails['password'] = $row['password'];
        $userdetails['group'] = $row['group'];
    }
    return $userdetails;
}

/*
 * @param:recepient addresses
 */

function emailrecepient($recepients, $content) {
    
}

//get date and time
function getDateAndTime() {
    date_default_timezone_set('Africa/Nairobi');
    $date = date("Y-m-d");
    $time = date('h:i A');
    $datetime = date('Y-m-d h:i A');
    return array('todaysdate' => $date, 'time' => $time, 'datetime' => $datetime);
}

//Generate access token -c2b
function generateToken(){
   
	$url = 'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

	$curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    $credentials = base64_encode('ESGePLx5jBT1NLSGm27qs8bj3OcyaTzs:Gr7kyLeb7ia2O7Gd');
	curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Basic '.$credentials)); // setting a custom header
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
	
	$curl_response = curl_exec($curl);

	$json_decode = json_decode($curl_response);

	$access_token = $json_decode->access_token;

	return $access_token;
}

//Register validation and confirmation urls
function registerURL(){
    $url = 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/registerurl';

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer:Glle59RuBRmUYKnNsIzBK08XJCPj  '.generateToken()));

    $curl_post_data = array(
        //Fill in the request parameters with valid values
        'ShortCode' => '600000',
        'ResponseType' => 'Completed',
        'ConfirmationURL' => 'https://4fd5-41-90-111-246.eu.ngrok.io/property-rivercourt/modules/confirmation/',
        'ValidationURL' => 'https://4fd5-41-90-111-246.eu.ngrok.io/property-rivercourt/modules/validation/'
      );

    $data_string = json_encode($curl_post_data);

	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

	$curl_response = curl_exec($curl);

	return $curl_response;
}
//Simulate c2b
function simulateC2B($amount, $msisdn){
    $url = 'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/simulate';

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.generateToken()));


    $curl_post_data = array(
           'ShortCode' => '600000',
           'CommandID' => 'CustomerPayBillOnline',
           'Amount' => $amount,
           'Msisdn' => $msisdn,
           'BillRefNumber' => 'test'
    );

    $data_string = json_encode($curl_post_data);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
    $curl_response = curl_exec($curl);

    return $curl_response;
}

//insert mpesa response to database
// function insert_response($callbackData){
//     $db = new MySQLDatabase();
//     $db->open_connection();
//     $tablename = "mobile_payments";

//     // $query = $db->query("INSERT INTO $tablename(`TransactionType`, `TransID`, `TransTime`, `TransAmount`, `BusinessShortCode`, `BillRefNumber`, `InvoiceNumber`, `OrgAccountBalance`, `ThirdPartyTransID`, `MSISDN`, `FirstName`, `MiddleName`, `LastName)
//     //              VALUES ($TransactionType, $TransID, $TransTime, $TransAmount, $BusinessShortCode, $BillRefNumber, $InvoiceNumber, $OrgAccountBalance, $ThirdPartyTransID, $MSISDN, $FirstName, $MiddleName, $LastName)") or die(mysql_error());

//       $query = $db->query("INSERT into $tablename(`TransactionType`, `TransID`, `TransTime`, `TransAmount`, `BusinessShortCode`, `BillRefNumber`, `InvoiceNumber`, `OrgAccountBalance`, `ThirdPartyTransID`, `MSISDN`, `FirstName`, `MiddleName`, `LastName`) VALUES (TransactionType, TransID, TransTime, TransAmount, BusinessShortCode, BillRefNumber, InvoiceNumber, OrgAccountBalance, ThirdPartyTransID, MSISDN, FirstName, MiddleName, LastName) ") or die(mysql_error());
//       $query->execute((array)($callbackData));

//     $db->close_connection();
// }

function insert_response($callbackData){

		$dbName = DB_NAME;
		$dbHost = DB_SERVER;
		$dbUser = DB_USER;
		$dbPass = DB_PASS;

	# establish a connection
	try{
		$con = new PDO("mysql:dbhost=$dbHost;dbname=$dbName", $dbUser, $dbPass);
		echo "Connection was successful";
	}
	catch(PDOException $e){
		die("Error Connecting ".$e->getMessage());
	}

	# 1.1.2 Insert Response to Database
	try{
		$insert = $con->prepare("INSERT INTO `mobile_payments`(`TransactionType`, `TransID`, `TransTime`, `TransAmount`, `BusinessShortCode`, `BillRefNumber`, `InvoiceNumber`, `OrgAccountBalance`, `ThirdPartyTransID`, `MSISDN`, `FirstName`, `MiddleName`, `LastName`) 
                   VALUES (:TransactionType, :TransID, :TransTime, :TransAmount, :BusinessShortCode, :BillRefNumber, :InvoiceNumber, :OrgAccountBalance, :ThirdPartyTransID, :MSISDN, :FirstName, :MiddleName, :LastName)");
		$insert->execute((array)($callbackData));

		# 1.1.2o Optional - Log the transaction to a .txt or .log file(May Expose your transactions if anyone gets the links, be careful with this. If you don't need it, comment it out or secure it)
		$Transaction = fopen('Transaction.txt', 'a');
		fwrite($Transaction, json_encode($callbackData));
		fclose($Transaction);
	}
	catch(PDOException $e){

		# 1.1.2b Log the error to a file. Optionally, you can set it to send a text message or an email notification during production.
		$errLog = fopen('error.txt', 'a');
		fwrite($errLog, $e->getMessage());
		fclose($errLog);

		# 1.1.2o Optional. Log the failed transaction. Remember, it has only failed to save to your database but M-PESA Transaction itself was successful. 
		$logFailedTransaction = fopen('failedTransaction.txt', 'a');
		fwrite($logFailedTransaction, json_encode($callbackData));
		fclose($logFailedTransaction);
	}
}


?>