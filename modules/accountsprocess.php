<?php
// ini_set('display_errors',1);
// ini_set('display_startup_errors',1);
// error_reporting(-1);
// header('content-type:application/json');
include 'functions.php';
if ($_REQUEST['getInvpoiceDetails']) {
    $entry=json_decode(getTempInvoicesById($_REQUEST['id'])->data);
    $tenant = getTenantDetailsFromRow($entry->idno);
    echo json_encode(array("success"=>true,"tenant"=>$tenant,"details"=>$entry));
}

else if($_REQUEST['prep']){ 
    
    $data=  json_decode(getPrepayment(338));
    foreach($data as $dt){
        echo $dt->monthlyincome;
    }
}
else if($_REQUEST['report_prepayment']){
    header("content-type:application/json");
    $propid=$_SESSION['propertyid'] ;
    $apartment= ($_REQUEST['id']);
    $msg=reportPrepayment($propid,$apartment);
   echo json_encode(array("success"=>true,"msg"=>$msg));
 

}
else if ($_REQUEST['approve_invoice']){
    header("content-type:application/json");
    $receipt_id=$_REQUEST['id'];
    $data=(getTempInvoicesById(($_REQUEST['id'])));
    //die("ss".$data->count);
    if($data->count!=0){
        $_REQUEST=(array)json_decode($data->data);
        // die($data->data);
    }
    else{
        echo "failed";
    }
    $id=$_REQUEST['idno'];
    $entrydate=$_REQUEST['invoicedate'];
    $fperiod=$_REQUEST['fperiod'];
    $incomeacct=$_REQUEST['incomeaccount'];
    $amount=$_REQUEST['amount'];
    $billing=$_REQUEST['billing'];
    $crdtinvce = $_REQUEST['invoiceno'];
    $items=$_REQUEST["items"];
    $invoicebbf=$_REQUEST["invoicebbf"];
    // $counter=$_REQUEST['counter'];
    $currentreading=$_REQUEST['currentreading'];
    $aptid=$_REQUEST['aptid'];
    $chargenames=json_decode($_REQUEST['chargenames'],true);
    $counter=count($chargenames);
    $charges=json_decode($_REQUEST['chargeamounts'],true);
    if($billing=$_REQUEST['billing'] =='1'){
        echo create_crdtnote($id,$entrydate,$incomeacct,$amount,$billing,$_SESSION['username'],$_SESSION['propertyid'],$_REQUEST['remarks'],$chargenames,$charges,$counter,$currentreading,$aptid,$fperiod,$items,$crdtinvce);//change session propertyid later
    }  else {
        echo create_invoice($id,$entrydate,$incomeacct,$amount,$billing,$_SESSION['username'],$_SESSION['propertyid'],$_REQUEST['remarks'],$chargenames,$charges,$counter,$currentreading,$aptid,$fperiod,$items,$invoicebbf);//change session propertyid later

    }
    ApproveInvoice($receipt_id);
}
else if($_REQUEST['approve']){
  //  die("ss");
  header("content-type:application/json");
  $receipt_id=$_REQUEST['id'];
   $data=(getTempReceiptsById($_REQUEST['id']));
   //die("ss".$data->count);
   if($data->count!=0){
    $_REQUEST=(array)json_decode($data->data);
    // die($data->data);
   } 
   else{
       echo "failed";
   }
    $bankdeposit=$_REQUEST['bankdeposit'];
   $invoicenos=json_decode($_REQUEST['invoicenosarray'],true);
    $amount=json_decode($_REQUEST['recpamountarray'],true);
    $counter=htmlspecialchars($_REQUEST['counter']);
    $fperiod=htmlspecialchars($_REQUEST['fperiod']);
    $penalty=htmlspecialchars($_REQUEST['applypenalty']);
     $penaltygl=htmlspecialchars($_REQUEST['penaltygl']);
     $idno=htmlspecialchars($_REQUEST['tenantid']);
      $receiptdate=htmlspecialchars($_REQUEST['receiptdate']);
     $paymode=htmlspecialchars($_REQUEST['paymode']);
     $cashaccount=htmlspecialchars($_REQUEST['cashacct']) ; 
     $bankaccount=htmlspecialchars($_REQUEST['chequeacct']) ;
      $chequedate=htmlspecialchars($_REQUEST['chequedate']) ;
      $chequeno=htmlspecialchars($_REQUEST['chequeno']) ; 
       $chequedetails= htmlspecialchars($_REQUEST['chequedetails']) ;
       $remarks=htmlspecialchars($_REQUEST['remarks']) ;
       $recpamount=  htmlspecialchars($_REQUEST['recpamountarray']);
       $reference=htmlspecialchars($_REQUEST['reference']);
       $propid=  htmlspecialchars($_REQUEST['propid']);
        $paidby='0'; // htmlspecialchars($_REQUEST['paidby']);
        $user=$_SESSION['username'];
    $receipt=0;    
        for($i=0;$i<$counter;$i++) {
            if($bankdeposit){
                $recept++;
                update_invoice($invoicenos[$i],$amount[$i],$idno,$receiptdate,$paymode,$cashaccount,$bankaccount,$chequedate,$chequeno,$chequedetails,$remarks,$paidby,$user,$counter,$propid,$penalty,$penaltygl,$fperiod,$bankdeposit,$reference);
            }
            else{
                $recept++;
                 update_invoice($invoicenos[$i],$amount[$i],$idno,$receiptdate,$paymode,$cashaccount,$bankaccount,$chequedate,$chequeno,$chequedetails,$remarks,$paidby,$user,$counter,$propid,$penalty,$penaltygl,$fperiod,$bankdeposit=0,$reference);
            }
  }


  //  die("hee");
   ApproveReceipt($receipt_id);

}else 
if (isset($_REQUEST['newinvoice'])){
    if ($_SESSION["usergroup"] != "1") {

        /// create_temp_invoice($_REQUEST);
        echo json_encode(array("success"=>true,"data" => create_temp_invoice($_REQUEST)));
    } else {

    $id=$_REQUEST['idno'];
    $entrydate=$_REQUEST['invoicedate'];
     $fperiod=$_REQUEST['fperiod'];
   $incomeacct=$_REQUEST['incomeaccount'];
     $amount=$_REQUEST['amount'];
   $billing=$_REQUEST['billing'];
   $crdtinvce = $_REQUEST['invoiceno'];
   $items=$_REQUEST["items"];
   $invoicebbf=$_REQUEST["invoicebbf"];
   // $counter=$_REQUEST['counter'];
   $currentreading=$_REQUEST['currentreading'];
    $aptid=$_REQUEST['aptid'];
   $chargenames=json_decode($_REQUEST['chargenames'],true);
   $counter=count($chargenames);
    $charges=json_decode($_REQUEST['chargeamounts'],true);
   if($billing=$_REQUEST['billing'] =='1'){
    echo create_crdtnote($id,$entrydate,$incomeacct,$amount,$billing,$_SESSION['username'],$_SESSION['propertyid'],$_REQUEST['remarks'],$chargenames,$charges,$counter,$currentreading,$aptid,$fperiod,$items,$crdtinvce);//change session propertyid later
    }  else {
      echo create_invoice($id,$entrydate,$incomeacct,$amount,$billing,$_SESSION['username'],$_SESSION['propertyid'],$_REQUEST['remarks'],$chargenames,$charges,$counter,$currentreading,$aptid,$fperiod,$items,$invoicebbf);//change session propertyid later
  
    }
}
      
}
elseif(isset ($_REQUEST['propertyinfo'])){
$id=htmlspecialchars($_REQUEST['id']);
//echo '1';
echo propertyinfo($id);
    
}
elseif(isset ($_REQUEST['update_prop'])){
    
    $propid=htmlspecialchars($_REQUEST['propid']);
  	 $url = filter_var($_REQUEST['mapurl'], FILTER_SANITIZE_URL);
	 $plot_no = filter_var($_REQUEST['plot_no'], FILTER_SANITIZE_STRING);
	 $propname = filter_var($_REQUEST['propname'], FILTER_SANITIZE_STRING);
	 $titledeed_no = filter_var($_REQUEST['titledeed_no'], FILTER_SANITIZE_STRING);
    
echo update_prop($propid,$url,$plot_no,$propname,$titledeed_no);
   
}
elseif(isset ($_REQUEST['batchinvoice'])){
      $entrydate=$_REQUEST['invoicedate'];
   $incomeacct=$_REQUEST['incomeaccount'];
     $amount=$_REQUEST['amount'];
   $billing=$_REQUEST['billing'];
    $fperiod=$_REQUEST['fperiod'];
    if($_REQUEST['batch_all']=="1"){

        foreach(getProperties() as $prop){
            if($prop!='Vacated'){
                 echo create_batch_invoice($entrydate,$incomeacct,$amount,$billing,$_SESSION['username'],$prop['property_id'],$_REQUEST['remarks'],$fperiod); 
            }
           // =$prop['property_id'];
         
        }
        
    }else{
 
 echo create_batch_invoice($entrydate,$incomeacct,$amount,$billing,$_SESSION['username'],$_SESSION['propertyid'],$_REQUEST['remarks'],$fperiod);

    }}
elseif(isset ($_REQUEST['reverseinvoice'])){
    
    $id=$_REQUEST['invoiceno'];
    echo getUnreversedinvoicebyid($id);
}
elseif(isset ($_REQUEST['deleteinvoice'])){
    
    $id=$_REQUEST['invoiceid'];
    echo reverseinvoice($id,$_SESSION['username']);
}
elseif(isset ($_REQUEST['createclient'])){
    
    $name=htmlspecialchars($_REQUEST['clientname']);
     $address=htmlspecialchars($_REQUEST['address']);
       $email=htmlspecialchars($_REQUEST['email']);
      $city=htmlspecialchars($_REQUEST['city']);
      $clientphone=  htmlspecialchars($_REQUEST['clientphone']);
       $usr=  htmlspecialchars($_REQUEST['user']);
     
    echo addclient($name,$address,$email,$city,$clientphone,$usr);
}

elseif(isset ($_REQUEST['clientdetails'])){
    
    $id=htmlspecialchars($_REQUEST['id']);
       
    echo fetchclientdetails($id);
}
elseif(isset ($_REQUEST['editclient'])){
    
    $name=htmlspecialchars($_REQUEST['clientname']);
     $address=htmlspecialchars($_REQUEST['address']);
       $email=htmlspecialchars($_REQUEST['email']);
      $city=htmlspecialchars($_REQUEST['city']);
      $clientphone=  htmlspecialchars($_REQUEST['clientphone']);
       $id=  htmlspecialchars($_REQUEST['clientid']);
     
    echo editclient($id,$name,$address,$email,$city,$clientphone);
}
//edit supplier
elseif(isset ($_REQUEST['createsupplier'])){
    $name=htmlspecialchars($_REQUEST['suppliername']);
    $items=htmlspecialchars($_REQUEST['items']);
     $address=htmlspecialchars($_REQUEST['address']);
       $email=htmlspecialchars($_REQUEST['email']);
      $city=htmlspecialchars($_REQUEST['city']);
      $clientphone=  htmlspecialchars($_REQUEST['supplierphone']);
       $usr=  htmlspecialchars($_REQUEST['user']);
       $propid=  htmlspecialchars($_REQUEST['property_id']);
     
    echo addsupplier($name,$items,$address,$email,$city,$clientphone,$usr,$propid);
}

elseif(isset ($_REQUEST['supplierdetails'])){
    
    $id=htmlspecialchars($_REQUEST['id']);
       
    echo fetchsupplierdetails($id);
}
elseif(isset ($_REQUEST['editsupplier'])){
    
    $name=htmlspecialchars($_REQUEST['suppliername']);
    $items=htmlspecialchars($_REQUEST['items']);
     $address=htmlspecialchars($_REQUEST['address']);
       $email=htmlspecialchars($_REQUEST['email']);
      $city=htmlspecialchars($_REQUEST['city']);
      $clientphone=  htmlspecialchars($_REQUEST['supplierphone']);
       $id=  htmlspecialchars($_REQUEST['supplierid']);
     
    echo editsupplier($id,$name,$items,$address,$email,$city,$clientphone);
}
elseif(isset ($_REQUEST['recpdetails'])){
    
    $tenantid=htmlspecialchars($_REQUEST['tenantid']);
     
    echo fetchinvoicedetails($tenantid);
}
elseif(isset ($_REQUEST['pendinginvoices'])){
    
    $tenantid=htmlspecialchars($_REQUEST['tenantid']);
     
    echo fetchpendinginvoicedetails($tenantid);
}
//get bbf for customer
elseif(isset ($_REQUEST['getbalance'])){
    
    $id=htmlspecialchars($_REQUEST['tenantid']);
    $propid = htmlspecialchars($_REQUEST['propid']);
    $invoiceno = '0';
    $invoicedate = date('Y-m-d');
    echo '<b>BALANCE.BF:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" disabled="disabled" readonly class="chargeitem1" value="'.number_format(getCorrectBalance($id,$invoiceno,$invoicedate),2).'" title="BALANCE"/><input type="text" hidden="true" readonly class="chargeitem1" value="'.getCorrectBalance($id,$invoiceno,$invoicedate).'" id="invoicebbf" title="BALANCE"/>';
  // $balance = getCorrectBalance($idno, $invoiceno,$invoicedate); 
}
elseif($_REQUEST['addpaymentreason']){
    $reason=htmlspecialchars($_REQUEST['reason']);
    echo storeData("Insert into lpayment_reasons (`reason`) values('$reason')");
}
elseif(isset ($_REQUEST['receiptclient'])){
   // die($_SESSION["usergroup"]);
    if($_SESSION["usergroup"]!="1"){
      
    
        echo json_encode(array("success"=>true,"data" =>create_temp_receipt($_REQUEST)));
    }else{

    
    $bankdeposit=$_REQUEST['bankdeposit'];
   $invoicenos=json_decode($_REQUEST['invoicenosarray'],true);
    $amount=json_decode($_REQUEST['recpamountarray'],true);
    $counter=htmlspecialchars($_REQUEST['counter']);
    $fperiod=htmlspecialchars($_REQUEST['fperiod']);
    $penalty=htmlspecialchars($_REQUEST['applypenalty']);
     $penaltygl=htmlspecialchars($_REQUEST['penaltygl']);
     $idno=htmlspecialchars($_REQUEST['tenantid']);
      $receiptdate=htmlspecialchars($_REQUEST['receiptdate']);
     $paymode=htmlspecialchars($_REQUEST['paymode']);
     $cashaccount=htmlspecialchars($_REQUEST['cashacct']) ; 
     $bankaccount=htmlspecialchars($_REQUEST['chequeacct']) ;
      $chequedate=htmlspecialchars($_REQUEST['chequedate']) ;
      $chequeno=htmlspecialchars($_REQUEST['chequeno']) ; 
       $chequedetails= htmlspecialchars($_REQUEST['chequedetails']) ;
       $remarks=htmlspecialchars($_REQUEST['remarks']) ;
       $recpamount=  htmlspecialchars($_REQUEST['recpamountarray']);
       $reference=htmlspecialchars($_REQUEST['reference']);
       $propid=  htmlspecialchars($_REQUEST['propid']);
        $paidby='0'; // htmlspecialchars($_REQUEST['paidby']);
        $user=$_SESSION['username'];
        
        for($i=0;$i<$counter;$i++) {
            if($bankdeposit){
update_invoice($invoicenos[$i],$amount[$i],$idno,$receiptdate,$paymode,$cashaccount,$bankaccount,$chequedate,$chequeno,$chequedetails,$remarks,$paidby,$user,$counter,$propid,$penalty,$penaltygl,$fperiod,$bankdeposit,$reference);
            }
            else{
                update_invoice($invoicenos[$i],$amount[$i],$idno,$receiptdate,$paymode,$cashaccount,$bankaccount,$chequedate,$chequeno,$chequedetails,$remarks,$paidby,$user,$counter,$propid,$penalty,$penaltygl,$fperiod,$bankdeposit=0,$reference);
            }
  }
}   
}

elseif(isset ($_REQUEST['receiptother'])){
$counter=htmlspecialchars($_REQUEST['counter']);
    $fperiod=htmlspecialchars($_REQUEST['fperiod']);
    $penalty=htmlspecialchars($_REQUEST['applypenalty']);
     $penaltygl=htmlspecialchars($_REQUEST['penaltygl']);
     $customer=htmlspecialchars($_REQUEST['customer']);
      $receiptdate=htmlspecialchars($_REQUEST['receiptdate']);
     $paymode=htmlspecialchars($_REQUEST['paymode']);
     $cashaccount=htmlspecialchars($_REQUEST['cashacct']) ; 
     $bankaccount=htmlspecialchars($_REQUEST['chequeacct']) ;
      $chequedate=htmlspecialchars($_REQUEST['chequedate']) ;
      $chequeno=htmlspecialchars($_REQUEST['chequeno']) ; 
       $chequedetails= htmlspecialchars($_REQUEST['chequedetails']) ;
       $remarks=htmlspecialchars($_REQUEST['remarks']) ;
       $recpamount=  htmlspecialchars($_REQUEST['recpamountarray']);
       $propid=  htmlspecialchars($_REQUEST['propid']);
       $bankdeposit=  htmlspecialchars($_REQUEST['bankdeposit']);
       $reference=htmlspecialchars($_REQUEST['reference']);
   
       if(is_numeric($bankdeposit)){
            $bank=$bankdeposit;
       }else{
           $bank=0;
       }
        $paidby='0'; // htmlspecialchars($_REQUEST['paidby']);
        $user=$_SESSION['username'];
       
        create_other_receipt($customer, $receiptdate, $paymode, $recpamount, $cashaccount, $bankaccount, $chequedate, $chequeno, $chequedetails, $remarks, $paidby, $user, $propid, $penalty, $penaltygl, $fperiod,$bank,$response=1,$reference);
        
}


elseif(isset ($_REQUEST['reversereceipt'])){
    
    $id=$_REQUEST['receipno'];
    echo getrecpbyid($id,"normal");
}
elseif(isset ($_REQUEST['reverseotherreceipt'])){
    
    $id=$_REQUEST['receipno'];
    echo getrecpbyid($id,"other");
}

elseif(isset ($_REQUEST['reverseexpense'])){
    
    $id=$_REQUEST['billno'];
    echo getBillbyNo($id);
}
elseif(isset ($_REQUEST['finalizereverseexpense'])){
    
    $id=$_REQUEST['billno'];
    echo reverseBillbyNo($id);
}
elseif(isset ($_REQUEST['reverseexpensepayment'])){
    
    $id=$_REQUEST['payno'];
    echo reverseExpensebyNo($id);
}
elseif(isset ($_REQUEST['deletereceiptaction'])){
    
    $id=$_REQUEST['receiptid'];
	$type=$_REQUEST['type'];
    echo reversereceipt($id,$_SESSION['username'],$type);
}
elseif(isset ($_REQUEST['chargeitems'])){
          $propertyid=htmlspecialchars($_REQUEST['propid']);
    echo set_charge_items($propertyid);
}
elseif(isset ($_REQUEST['accountdetails'])){
        
    echo fetchaccountdetails();
}
elseif(isset($_REQUEST['apartmentid'])){
       $propertyid=htmlspecialchars($_REQUEST['propid']);
     echo populateapartmentselect($propertyid);   
   
}//create a bill
elseif(isset($_REQUEST['newbill'])){
     $billdate=htmlspecialchars($_REQUEST['billdate']);
    $billamount=htmlspecialchars($_REQUEST['billamount']);
     $items=htmlspecialchars($_REQUEST['billitems']);
    $rmks=htmlspecialchars($_REQUEST['remarks']);
     $supp_id=htmlspecialchars($_REQUEST['suppid']);
          $agentincomecct=htmlspecialchars($_REQUEST['agentincome']);
          $feepercent=htmlspecialchars($_REQUEST['feepercent']);
          $owedamount=htmlspecialchars($_REQUEST['owedamount']);
           $agentincome=(int)($feepercent*$owedamount)/100;
           $vat=0.16*($agentincomecct);
            $propid=htmlspecialchars($_REQUEST['propid']); //if 0 means office expense
      $fperiod=htmlspecialchars($_REQUEST['fperiod']);
      $glcode=htmlspecialchars($_REQUEST['glcode']);
           //if theres a charge on the expense by agent
           if(is_numeric($feepercent)){
               $agentincome=round(($feepercent/100)*$owedamount);
               $settings=  getSettings();
                   $myDateTime = DateTime::createFromFormat('d/m/Y',trim($billdate));
        $receiptdate = $myDateTime->format('m/d/Y');

               create_other_receipt($settings["company_name"],$receiptdate, 0, $agentincome, $agentincomecct, $agentincomecct, $chequedate="", $chequeno="", $chequedetails="","fee percent income","landlord", $_SESSION["username"], $propid, $penalty=0, $penaltygl=0, $fperiod, $bank=0,$response=0);
           }
     
    
     echo create_supplier_bill($supp_id,$billdate,$items,$billamount,$rmks,$propid,$fperiod,$glcode);
}
//fetch bill details
elseif(isset ($_REQUEST['billdetails'])){
    
    $id=htmlspecialchars($_REQUEST['suppid']);
     
    echo fetchbilldetails($id);
}
//paybill
elseif(isset($_REQUEST['paybill'])){
     $supp_id=htmlspecialchars($_REQUEST['suppid']);
          $fperiod=htmlspecialchars($_REQUEST['fperiod']);
    $billnos=htmlspecialchars($_REQUEST['billnos']);
     $payamounts=htmlspecialchars($_REQUEST['payamounts']);
    $paymode=htmlspecialchars($_REQUEST['paymode']);
     $billdate=htmlspecialchars($_REQUEST['billdate']);
     $expenseacct=htmlspecialchars($_REQUEST['expenseacct']);
     $chequeno=htmlspecialchars($_REQUEST['chequeno']);
     $chequedate=htmlspecialchars($_REQUEST['chequedate']);
     $chequedetails=htmlspecialchars($_REQUEST['chequedetails']);
     $remarks=htmlspecialchars($_REQUEST['remarks']);
     $user=htmlspecialchars($_REQUEST['user']);
     $propid=htmlspecialchars($_REQUEST['propid']);
      $cashaccount=htmlspecialchars($_REQUEST['cashacct']);     
   echo pay_bill($billdate,$payamounts,$paymode,$expenseacct,$chequedetails,$chequeno,$chequedate,$remarks,$supp_id,$billnos,$user,$propid,$fperiod,$isrefund=0,$cashaccount=0);
    
      }
      
      //pay landlord
      elseif(isset($_REQUEST['paylandlord'])){
  
    $bank=htmlspecialchars($_REQUEST['bank']);
     $amount=htmlspecialchars($_REQUEST['amount']);
    $paymode="cheque";
    $reason=htmlspecialchars($_REQUEST['reason']);
     $paydate=htmlspecialchars($_REQUEST['paydate']);

     $chequeno=htmlspecialchars($_REQUEST['chequeno']);
     $chequedate=htmlspecialchars($_REQUEST['chequedate']);
     $chequedetails=htmlspecialchars($_REQUEST['chequedetails']);

     $user=$_SESSION['username'];
     $propid=htmlspecialchars($_REQUEST['property']);
     require '../loan/admin_class.php';
     $crud = new Action();
     $loan=json_decode($crud->loan_next($_SESSION['propertyid']));
    
   if($loan->amount>0&&!$loan->ispaid){
    $before=$amount;
    $amount=$amount+$loan->amount;
    $payee="Auto Deducted";
    $loan_id=$loan->loan_id;
    //if($loan->ispaid){
        $crud-> pay_auto($payee,$loan->amount,$loan_id);

        $params=array("bank"=>$bank,"amount"=>$amount,"bank"=>$bank,"paymode"=>$paymode,"paydate"=>$paydate,"chequeno"=>$chequeno,"chequedate"=>$chequedate,"chequedetails"=>$chequedetails,"user"=>$user,"property_id"=>$propid,"reason"=>"Paid $before as $reason and  Deducted monthly loan of ".round($loan->amount,2));
      
    //}

   }else{
    $params=array("bank"=>$bank,"amount"=>$amount,"bank"=>$bank,"paymode"=>$paymode,"paydate"=>$paydate,"chequeno"=>$chequeno,"chequedate"=>$chequedate,"chequedetails"=>$chequedetails,"user"=>$user,"property_id"=>$propid,"reason"=>$reason);
  
   }
    //$params=array("bank"=>$bank,"amount"=>$amount,"bank"=>$bank,"paymode"=>$paymode,"paydate"=>$paydate,"chequeno"=>$chequeno,"chequedate"=>$chequedate,"chequedetails"=>$chequedetails,"user"=>$user,"property_id"=>$propid,"reason"=>$reason);
  
    
    echo makeLandlordpayment($params);
    
      }
      
      
      elseif (isset ($_REQUEST['systemusers'])) {
         echo get_system_users(); 
      }
      elseif(isset ($_REQUEST['edituser'])){
          $userdetails=array();
    $userdetails['user_id']=htmlspecialchars($_REQUEST['user_id']);
    $userdetails['username']=htmlspecialchars($_REQUEST['username']);
     $userdetails['password']=htmlspecialchars($_REQUEST['password']);
    $userdetails['group']=htmlspecialchars($_REQUEST['group']);
        $userdetails['status']=htmlspecialchars($_REQUEST['status']);
       $update=updateUser($userdetails);
   
}
elseif(isset ($_REQUEST['settings'])){
    $settings=array();
     $settings['id']=$_REQUEST['id'];
      $settings['company_name']=$_REQUEST['company_name'];
   $settings['address']=$_REQUEST['address'];
     $settings['from_email']=$_REQUEST['from_email'];
         $settings['cc_email']=$_REQUEST['cc_email'];
   $settings['accounts_opening']=$_REQUEST['accounts_opening'];
   $settings['tagline']=$_REQUEST['tagline'];
     $settings['vat']=$_REQUEST['vat'];
       $settings['pin']=$_REQUEST['pin'];
   $saved=saveSettings($settings,'update');
    header('Location: ../views/template.php?page=repairs#tabs-3');
}
elseif(isset ($_REQUEST['addgl'])){
    

        $allproperties=@$_REQUEST['allproperties'];
     
        if($allproperties>0){
           $properties= getProperties();
          
          foreach ($properties as $property) {
              $gl=array();
        $propid=$property['property_id'];
          $gl['username']=$_SESSION['username'];
      $gl['account_name']=@$_REQUEST['account_name'];
       $gl['idaccounttype_categories']=@$_REQUEST['idaccounttype_categories'];
   $gl['idaccount_type']=@$_REQUEST['accounttype'];
     $gl['is_bank']=@$_REQUEST['is_bank'];
      $gl['is_office']=@$_REQUEST['is_office'];
       $gl['is_tenant']=@$_REQUEST['is_tenant'];
        $gl['is_landlord']=@$_REQUEST['is_landlord'];
         $gl['is_agent']=@$_REQUEST['is_agent'];
        $gl['balance']=@$_REQUEST['balance'];
        $gl['vat']=@$_REQUEST['chargevat'];
        $gl['property_id']=$propid;

       $saved=saveGL($gl,'insert');
          }
        }
 else {
        
     $gl=array();
              $gl['property_id']=@$_REQUEST['forproperty'];
               $gl['username']=$_SESSION['username'];
      $gl['account_name']=@$_REQUEST['account_name'];
       $gl['idaccounttype_categories']=@$_REQUEST['idaccounttype_categories'];
   $gl['idaccount_type']=@$_REQUEST['accounttype'];
     $gl['is_bank']=@$_REQUEST['is_bank'];
      $gl['is_office']=@$_REQUEST['is_office'];
       $gl['is_tenant']=@$_REQUEST['is_tenant'];
        $gl['is_landlord']=@$_REQUEST['is_landlord'];
         $gl['is_agent']=@$_REQUEST['is_agent'];
        $gl['balance']=@$_REQUEST['balance'];
             $gl['vat']=@$_REQUEST['chargevat'];
               $saved=saveGL($gl,'insert');
             
}
  
       header('Location:'.getIP().'/views/template.php?page=repairs&result=saved');

   
  
}
elseif(isset ($_REQUEST['getofficeexpense'])){
     ?>
    <select id='supplliername'  name='supplliername'  style="width:250px;">
        <?php
        $agentexpense=getAgentExpenseAccount();
        foreach ($agentexpense as $expenseacct) {
             $glcode=$expenseacct['glcode'];
     $propertyid=0;
      echo "<option value='$glcode' title='$propertyid' class='supplier' >" . htmlspecialchars($expenseacct['acname']) . "</option>";         
        }
        ?>
        </select>
<?php

}

elseif ($_REQUEST['getpropertyexpense']) { ?>
    <select id='supplliername'  name='supplliername'  style="width:250px;"><option selected="selected" value="">Select Expense Ledger</option>  
<?php
$propid=$_SESSION['propertyid'];
 $glaccountexp=  getLandlordExpenseAccounts(array('gl'=>'LandlordExpense','property_id'=>$propid));
 foreach ( $glaccountexp as $expenseacct) {
     $glcode=$expenseacct['glcode'];
     $vat=$expenseacct['has_vat'];
     $propertyid=$expenseacct['property_id'];
   echo "<option value='$glcode' title='$propertyid' class='supplier' >" . htmlspecialchars($expenseacct['acname']) . "</option>";  
 }
 ?>
       </select>
<?php
}
elseif(isset ($_REQUEST['deletegl'])){
     $response=array();
     $gl=$_REQUEST['gl'];
      $deleted=  deleteGL($gl);
      header('Content-type: application/json');
      $response['status']=$deleted;
      echo json_encode($response);
}
//deposit refund
elseif(isset ($_REQUEST['depositrefund'])){
     $response=array();
     $tenantid=$_REQUEST['tenant_id'];
     $propid=$_REQUEST['propid'];
     $amount=$_REQUEST['amount'];
        $refunddate=$_REQUEST['refunddate'];
         $paymode=$_REQUEST['paymode'];
          $chequedate=$_REQUEST['chequedate'];
          $chequedetails=$_REQUEST['chequedetails'];
          $chequeno=$_REQUEST['chequeno'];
           $item=$_REQUEST['deposititem'];
          $remarks=$_REQUEST['remarks'];
           $recpno=$_REQUEST['recpno'];
           $glaccountal=getGLCodeForAccount(array('gl'=>'AgentLiability'));
        $glcode3=$glaccountal['glcode'];
                $period=getPeriodByDate($refunddate);
        $fperiod=$period['idclose_periods'];
      
    echo pay_refund($refunddate,$amount,$paymode,$glcode3,$chequedetails,$chequeno,$chequedate,$remarks,$tenantid,"deposit",$user,0,$fperiod,$isrefund=1,$recpno);      
         
}

elseif(isset ($_REQUEST['addfy'])){
    $gl=array();
  
   
     $gl['start_date']=@$_REQUEST['start_date_year'];
        $gl['end_date']=@$_REQUEST['end_date_year'];
         $gl['is_active']=@$_REQUEST['is_active'];
        $saved=  saveFY($gl,'insert');
    header('Location: ../views/template.php?page=repairs#tabs-2');
}
elseif(isset ($_REQUEST['deleteyear'])){
   
     $id=@$_REQUEST['fy'];
       header('Content-type: application/json');
        $result= deleteFY($id);
        $responsearray['status']=$result;
    echo json_encode($responsearray);
               
   // header('Location: ../views/template.php?page=repairs#tabs-2');
}
elseif(isset ($_REQUEST['addcp'])){
    $gl=array();
  
   $gl['idfinancial_year']=@$_REQUEST['financial_year'];
     $gl['start_date']=@$_REQUEST['start_date_cp'];
        $gl['end_date']=@$_REQUEST['end_date_cp'];
        $gl['is_active']=@$_REQUEST['is_active'];
   
   $saved= saveClosePeriod($gl,'insert');
    header('Location: ../views/template.php?page=closeperiods&fy='.$gl['idfinancial_year'].'&start_date='.$_REQUEST['start_fy'].'&end_date='.$_REQUEST['end_fy']);
}

elseif($_REQUEST['getfinancialperiod']){?>
<select id='fperiod'  name='fperiod'  style="width:100%;">
<?php      $period=getPeriodByDate($_REQUEST['date']); 

        if(is_array($period)){
           
        ?>
<option selected="selected" value="<?php echo $period['idclose_periods']?>">FY<?php echo $period['idfinancial_year'] ?> (<?php echo  date("d-m-Y",  strtotime($period['start_date'])). ' to '. date("d-m-Y",  strtotime($period['end_date'])); ?>)</option>  
        <?php }
 else{?>
       <option selected="selected" value="">Financial Period Not Created Yet!</option>       
        <?php } ?>
        </select>
<?php
}

elseif($_REQUEST['getfinancialperiodbatch']){?>
<select id='fperiodbatch'  name='fperiodbatch'  style="width:100%;">
<?php      $period=getPeriodByDate($_REQUEST['date']); 
        if(is_array($period)){
           
        ?>
<option selected="selected" value="<?php echo $period['idclose_periods']?>">FY<?php echo $period['idfinancial_year'] ?> (<?php echo  date("d-m-Y",  strtotime($period['start_date'])). ' to '. date("d-m-Y",  strtotime($period['end_date'])); ?>)</option>  
        <?php }
 else{?>
       <option selected="selected" value="">Financial Period Not Created Yet!</option>       
        <?php } ?>
        </select>
<?php
}
elseif($_REQUEST['getfinancialperiodpayment']){?>
<select id='fperiodbatch'  name='fperiodbatch'  style="width:100%;">
<?php      $period=getPeriodByDate($_REQUEST['date']); 
        if(is_array($period)){
           
        ?>
<option selected="selected" value="<?php echo $period['idclose_periods']?>">FY<?php echo $period['idfinancial_year'] ?> (<?php echo  date("d-m-Y",  strtotime($period['start_date'])). ' to '. date("d-m-Y",  strtotime($period['end_date'])); ?>)</option>  
        <?php }
 else{?>
       <option selected="selected" value="">Financial Period Not Created Yet!</option>       
        <?php } ?>
        </select>
<?php
}

elseif($_REQUEST['getfinancialperiodpay']){?>
<select id="payperiod"  name="payperiod"  style="width:100%;">
<?php      $period=getPeriodByDate($_REQUEST['date']); 
        if(is_array($period)){
           
        ?>
<option selected="selected" value="<?php echo $period['idclose_periods']?>">FY<?php echo $period['idfinancial_year'] ?> (<?php echo  date("d-m-Y",  strtotime($period['start_date'])). ' to '. date("d-m-Y",  strtotime($period['end_date'])); ?>)</option>  
        <?php }
 else{?>
       <option selected="selected" value="">Financial Period Not Created Yet!</option>       
        <?php } ?>
        </select>
<?php
}
elseif($_REQUEST['getfinancialperiodexpense']){?>
<select id="expenseperiod"  name="expenseperiod"  style="width:100%;">
<?php      $period=getPeriodByDate($_REQUEST['date']); 
        if(is_array($period)){
           
        ?>
<option selected="selected" value="<?php echo $period['idclose_periods']?>">FY<?php echo $period['idfinancial_year'] ?> (<?php echo  date("d-m-Y",  strtotime($period['start_date'])). ' to '. date("d-m-Y",  strtotime($period['end_date'])); ?>)</option>  
        <?php }
 else{?>
       <option selected="selected" value="">Financial Period Not Created Yet!</option>       
        <?php } ?>
        </select>
<?php
}
elseif($_REQUEST['getcloseperiodpayments']){?>
<select id='closeperiodpay'  name='closeperiodpay'  style="width:100%;">
<?php      $closeperiods=  getClosePeriods($_REQUEST['fy']); 
        if(is_array($closeperiods)){
        foreach ($closeperiods as $period) {      
        ?>
<option selected="selected" value="<?php echo $period['idclose_periods']?>">FY<?php echo $period['idfinancial_year'] ?> (<?php echo  date("d-m-Y",  strtotime($period['start_date'])). ' to '. date("d-m-Y",  strtotime($period['end_date'])); ?>)</option>  
        <?php 
        }
        }
 else{?>
       <option selected="selected" value="">Financial Period Not Created Yet!</option>       
        <?php } ?>
        </select>
<?php
}

elseif(isset ($_REQUEST['changeperiodstatus'])){
   
     $id=@$_REQUEST['idclose_periods'];
             $status=@$_REQUEST['status'];
       header('Content-type: application/json');
        $result= changePeriodStatus($id, $status);
        $responsearray['status']=$result;
    echo json_encode($responsearray);
               
 }
 elseif(isset ($_REQUEST['deletecloseperiod'])){
   
     $id=@$_REQUEST['idclose_periods'];
                   header('Content-type: application/json');
        $result= deleteClosePeriod($id);
        $responsearray['status']=$result;
    echo json_encode($responsearray);
               
 }
  elseif(isset ($_REQUEST['paylandlord'])){
      $params=array();
   $params['idclose_periods']=@$_REQUEST['idclose_periods'];
   $params['property_id']=@$_REQUEST['property_id'];
   $params['amount']=@$_REQUEST['amount'];
   $params['journal_refs']=array_filter(explode('*',@$_REQUEST['journal_refs']));
   
                     header('Content-type: application/json');
        $result= payLandlord($params);
        $responsearray['status']=$result;
    echo json_encode($responsearray);
               
 }
 //pay landlord from bank
 
  elseif(isset ($_REQUEST['paylandlordfrombank'])){
      $params=array();
   $params['amount']=@$_REQUEST['amount'];
   $params['date']=$_REQUEST['date'];
   $params['bank']=$_REQUEST['bank'];
    $params['chequedetails']=$_REQUEST['chequedetails'];
     $params['chequeno']=$_REQUEST['chequeno'];
      $params['chequedate']=$_REQUEST['chequedate'];
      $params['remarks']="landlord payment";
   
   $params['propertyid']=$_REQUEST['propertyid'];
   $paylandlord=makeLandlordpayment($params);
 
                     header('Content-type: application/json');
        $result= payLandlord($params);
        $responsearray['status']=$result;
    echo json_encode($responsearray);
               
 }
 
 elseif (isset ($_REQUEST['tenantdeposits'])) {
      $id=$_REQUEST['tenant_id'];
     $deposits=getTenantDeposit($id);
     ?>
<select id="tenantdeposits"  name="tenantdeposits"  style="width:100%;">
    <option selected="selected" value="">Select deposit to refund</option> 
    <?php         foreach ($deposits as $deposit) { ?>
       
    <option  value="<?php echo  $deposit['amount']?>" title="<?php echo $deposit['recpno'] ?>"><?php echo  $deposit['amount']?><?php echo  $deposit['rmks']?></option> 
       <?php  }
?> 
    
</select>
<?php
 }
 elseif(isset ($_REQUEST['transfer'])){
    
    $frombank=htmlspecialchars($_REQUEST['frombank']);
    $tobank=htmlspecialchars($_REQUEST['tobank']);
    $amount=htmlspecialchars($_REQUEST['amount']);
    $data=array();
    $datato=array();
           $date=date("Y-m-d H:i:s");
           $bank1=  getBankDetails($frombank);
            $bank2=  getBankDetails($tobank);
            if($bank1["total_balance"]<=0){
              echo json_encode(array("status"=>"fail","message"=>"Balance too low to effect transfer")); 
         exit();    
            }
            else{
       $data['recpno']=  incrementnumber('payno');
   $data['amount']=-$amount;
    $data["date"]=$date;
   $data["bank_type"]=$bank1["bank_code"];
    $data["is_credit"]=0;
   $data["is_debit"]=1;
    $data["narration"]="Transfer Out to ".$bank2["bank_name"];
       saveUndepositedCash($data);
       unset($data);
       //transfer in
        $datato['recpno']=  incrementnumber('payno');
   $datato['amount']=$amount;
    $datato["date"]=$date;
   $datato["bank_type"]=$bank2["bank_code"];
    $datato["is_credit"]=1;
   $datato["is_debit"]=0;
      $datato["narration"]="Transfer in from ".$bank1["bank_name"];   
       if(saveUndepositedCash($datato)){
         echo json_encode(array("status"=>"success","message"=>"Transfer of ".$amount." from  ".$bank1["bank_name"]." to ".$bank2["bank_name"]." successful")); 
         exit();
       }
            }
}

else{
die('No parameters set');
}


