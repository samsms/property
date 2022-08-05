<?php
session_start();
ini_set('display_errors',1);     # don't show any errors...
error_reporting(E_ALL | E_STRICT);
include './includes/database.php';
include './modules/functions.php';
//echo getApartmentFromTenant(3);
//echo calculatePenalty(array('propertyid'=>74, 'tenantid'=>22,'rdate'=>'18-09-2014','amount'=>18000));    
/*$period=getPeriodByDate("03/25/2015");
print_r($period);**/
//$propid=$_SESSION['propertyid'];
//$deposits=getTenantDeposit(770);
//print_r($deposits);
 
//$suppliers=getsuppliersdropdown($propid);
//$charges=getChargeItems($propid);
//create_batch_invoice(date("m/d/Y"),"all", 0, 0,"administrator", $propid,"February Rent",7);

//get properties

$properties=getProperties();
foreach ($properties as $property){
$apartments=getPropertyApartments($property['property_id']);
foreach ($apartments as $aptment) {
    //add GL for a tenant/apt
    $gl=array();
         $gl['username']=$_SESSION['username'];
      $gl['account_name']=$property['property_name'].'('.$aptment['apt_tag'].')';
       $gl['idaccounttype_categories']=7; //Agent Asset
   $gl['idaccount_type']=3; //income
     $gl['is_bank']=0;
        $gl['balance']=0;
         $gl['is_tenant']=1;
           $gl['apt_id']=$aptment['apt_id'];
        $gl['property_id']= $property['property_id'];
            $saved=saveGL($gl,'insert');
    
}
}

//create gl accounts --increase php/ini max execution time 

foreach ($properties as $property){
    $lastid=$property['property_id'];
    $propertyname=$property['property_name'];
 
//    //add GL for a tenant/apt
 $apartments=getPropertyApartments($property['property_id']);
foreach ($apartments as $aptment) {
    //add GL for a tenant/apt
    $gl=array();
         $gl['username']=$_SESSION['username'];
      $gl['account_name']=$property['property_name'].'('.$aptment['apt_tag'].')';
       $gl['idaccounttype_categories']=7; //Agent Asset
   $gl['idaccount_type']=3; //income
     $gl['is_bank']=0;
        $gl['balance']=0;
         $gl['is_tenant']=1;
           $gl['apt_id']=$aptment['apt_id'];
        $gl['property_id']= $property['property_id'];
            $saved=saveGL($gl,'insert');
    
}
                $commsacctlandlord=array();
         $commsacctlandlord['username']=$_SESSION['username'];
     $commsacctlandlord['account_name']='Landlord Commissions Account('.$propertyname.')';
       $commsacctlandlord['idaccounttype_categories']=4; //Agent Income
         $commsacctlandlord['is_landlord']=1; 
            $commsacctlandlord['is_commission']=1;
   $commsacctlandlord['idaccount_type']=2; //Expense
    $commsacctlandlord['is_bank']=0;
        $commsacctlandlord['balance']=0;
        $commsacctlandlord['property_id']= $lastid;
            $savedcomm=saveGL($commsacctlandlord,'insert');  
       $gl=array();
         $gl['username']=$_SESSION['username'];
      $gl['account_name']=$property['property_name'].'(Rent)';
       $gl['idaccounttype_categories']=5; //landlord income
       $rentincometype=getIncomeType("Rent");
      $gl['idincometypes']=$rentincometype['idincometypes'];
   $gl['idaccount_type']=1; //income
     $gl['is_bank']=0;
      $gl['is_landlord']=1; //is landlord
        $gl['balance']=0;
        $gl['property_id']= $lastid;
            $saved=saveGL($gl,'insert');
              //create agent gl account for Agent of type Asset (we expect money from him)  
        $glnew=array();
         $glnew['username']=$_SESSION['username'];
      $glnew['account_name']='Agent Account'.'('.$propertyname.')';
       $glnew['idaccounttype_categories']=9; //landlord asset
   $glnew['idaccount_type']=3; //income
     $glnew['is_landlord']=1; //is landlord
        $glnew['balance']=0;
        $glnew['property_id']= $lastid;
            $saved=saveGL($glnew,'insert');
      //create main gl account for landlord  
        $gl1=array();
         $gl1['username']=$_SESSION['username'];
      $gl1['account_name']=$property['property_name'].'(Main)';
       $gl1['idaccounttype_categories']=6; //landlord Bank
   $gl1['idaccount_type']=3; //internal bank
     $gl1['is_bank']=0;
        $gl1['balance']=0;
         $gl1['is_landlord']=1;
        $gl1['property_id']= $lastid;
            $saved1=saveGL($gl1,'insert');      
//if vat add another expense gl account
            if($property['has_vat']){
        $vataccount=array();
        $vataccount['username']=$_SESSION['username'];
      $vataccount['account_name']=$property['property_name'].'(VAT)';
       $vataccount['idaccounttype_categories']=4; //landlord Expense
   $vataccount['idaccount_type']=2; //expense
     $vataccount['is_bank']=0;
        $vataccount['balance']=0;
        $vataccount['property_id']= $lastid;
            $saved2=saveGL($vataccount,'insert');       
            }
             //create landlord account on agent side of type liability
                    $glacct=array();
         $glacct['username']=$_SESSION['username'];
      $glacct['account_name']='Landlord Account('.$propertyname.')';
       $glacct['idaccounttype_categories']=8; //Agent Liability
       $glacct['is_agent']=1; 
   $glacct['idaccount_type']=4; //Liability
     $glacct['is_bank']=0;
        $glacct['balance']=0;
        $glacct['property_id']= $lastid;
            $saved1=saveGL($glacct,'insert'); 
             
    $commsacct=array();
         $commsacct['username']=$_SESSION['username'];
      $commsacct['account_name']='Commissions Account('.$propertyname.')';
       $commsacct['idaccounttype_categories']=3; //Agent Income
       $commsacct['is_agent']=1; 
   $commsacct['idaccount_type']=1; //Income
     $commsacct['is_bank']=0;
     $incometype=getIncomeType("Commission");
      $commsacct['idincometypes']=$incometype['idincometypes'];
        $commsacct['balance']=0;
        $commsacct['property_id']= $lastid;
            $saved=saveGL($commsacct,'insert'); 
            
            //penalty acct
} 

//$landlordgls=getLandlordGls($_SESSION['propertyid']);
 
 
$agentexpense=getAgentExpenseAccount();
//print_r($agentexpense);