
<div id="tabs">
<ul>
<li><a href="#tabs-1">Accounts Management</a></li>
<li><a href="#tabs-2">Financial Periods</a></li>
<li><a href="#tabs-3">System Settings</a></li>
</ul>
<div id="tabs-1">
<p> <div id="addaccount" style="display:none">
                        <form action="../modules/accountsprocess.php?addgl=true" method="POST" title="Add GL">  
                                                <input type="text"  class="ui-widget-content fullwidth" name="account_name" placeholder="Account Name">
                        <br><br>
                        <select name="accounttype" class="fullwidth">
                           <?php $accounttypes=  getAccountTypes();
 foreach ($accounttypes as $accounttype) { ?>
                            <option  value="<?php echo $accounttype['id']?>"><?php echo $accounttype['desc']?></option>   
 <?php }?>
                                   </select>
                        <br><br>
                        <select name="idaccounttype_categories" class="fullwidth">
                            <option value="0">Select Account Type Category</option>
                            <?php $accountcategories= getAccountCategories();
                           
 foreach ($accountcategories as $accountcategory) { ?>
                            <option  value="<?php echo $accountcategory['id']?>"><?php echo $accountcategory['alias']?>(<?php echo $accountcategory['code']?>)</option>   
 <?php }?>
                                   </select>
                        <br>
                        <label>For which property</label>
                        <br>
                          <select name="forproperty" class="fullwidth">
                            <option value="">Select Property</option>
                            <?php $properties= getProperties();
                           
 foreach ($properties as $property) { ?>
                            <option  value="<?php echo $property['property_id']?>"><?php echo $property['property_name']?></option>   
 <?php }?>
                                   </select>
                      
                        <br><label>Is Bank Account?</label>
                        <input type="checkbox" name="is_bank" value="1"/>
                       
                        <br><label>Is Office Account?</label>
                        <input type="checkbox" name="is_office" value="1"/>
                        <br>
                         <label>Is Landlord Account?</label>
                        <input type="checkbox" name="is_landlord" value="1"/>
                        
                         <br><label>Is Agent Account?</label>
                        <input type="checkbox" name="is_agent" value="1"/>
                        <br>
                                                <input type="text" placeholder="balance" class="fullwidth" name="balance"/>
                        <br>
                          <br>
                        <button class="text ui-widget-content ui-corner-aEll linkright" type="submit"><img src="../images/cursors/available.png">&nbsp;Save GL Account</button>
                    </form> 
                        
                    </div>
<br><br><a class="addaccount linkright greenfont" href="#">+Add Ledger Account</a>
                 <p>     <table class="accounts" style="width:100%">
                     <thead><tr><th>GlCode</th><th>Account Name</th><th>Account Type</th><th>Account Category</th><th>Landlord Acct</th><th>Agent Acct</th><th>House Acct</th><th>Balance</th><th>Property</th><th>Status</th><th>Action</th></tr></thead>
                
                        <?php  $glaccounts=getGlAccounts();
  foreach ($glaccounts as $glaccount) {     
       $account=  getAccountType($glaccount['idacct_types']);
       if($glaccount['status']==1){$status="Active";}else{$status="INACTIVE";}
       $accountcategory=getAccountCategory($glaccount['idaccounttype_categories']);
       if( @$glaccount['property_id']){
           $property=  findpropertybyid($glaccount['property_id']);
       }
     
                        ?>
                     <tr> <td><?php echo $glaccount['glcode']?></td> <td><?php  echo $glaccount['acname']?></td><td><?php echo $account['desc'] ?></td><td><?php echo $accountcategory[0]['alias'] ?></td><td><?php echo $glaccount['is_landlord'] ?></td><td><?php echo $glaccount['is_agent'] ?></td><td><?php echo $glaccount['is_tenant'] ?></td><td><?php echo number_format($glaccount['bal'],2)?></td><td><?php if(!is_array($property)){echo $property;} ?></td><td><?php echo $status?></td><td><a href="#" id="<?php echo $glaccount['acno']?>" class="deletegl danger">X</a></td></tr>          
                    
                    <?php
  }
 
                        ?>                            </table> </p>
</div>
<div id="tabs-2">
    <div id="addfinancialyear" style="display:none">
<form action="../modules/accountsprocess.php?addfy=true" method="POST" title="Financial Year">  
                        <input type="text"  class="ui-widget-content width50 datepicker" name="start_date_year" placeholder="Year Start">
                        <br><br>
                        <input type="text"  class="ui-widget-content width50 datepicker"  name="end_date_year" placeholder="Year End">
                        
                         <br><label>Is Active?</label>
                         <input type="checkbox" name="is_active" value="1"/>
                        <button class="text ui-widget-content ui-corner-aEll linkright" type="submit"><img src="../images/cursors/available.png">&nbsp;Add Financial Year</button>
                    </form>
        </div>
    <br><br><a class="addfy linkright greenfont" href="#">+Add Financial Year</a>
    <table class="financialyear" style="width:100%">
                        <thead><tr><th>No</th><th>Start Date</th><th>End Date</th><th>Status</th><th>Action</th></tr></thead>
                
                        <?php  $financialyears=getFinancialYears();
                        $count=1;
  foreach ($financialyears as $financialyear) {     
   
                        ?>
                        <tr> <td><a href="template.php?page=closeperiods&fy=<?php echo $financialyear['idfinancial_year']?>&start_date=<?php echo $financialyear['start_date']?>&end_date=<?php echo $financialyear['end_date']?>" ><?php echo $count;?></a></td><td><?php echo date("d-m-Y",  strtotime($financialyear['start_date']))?></td> <td><?php  echo date("d-m-Y",  strtotime($financialyear['end_date']))?></td><td><?php echo $financialyear['status']?></td><td><a href="#" id="<?php echo $financialyear['idfinancial_year'] ;?>" class="deletefy">X</a></td></tr>          
                    
                    <?php
                    $count++;
  }
 
                        ?>                            </table>
 <div id="deleteyear" title="Confirm Delete" style="display:none;">Do you want to delete record?  </div>
  <div id="deleteaccount" title="Confirm Delete" style="display:none;">Do you really want to delete account?  </div>
</div>
<div id="tabs-3">
<form action="../modules/accountsprocess.php?settings=true" method="POST" title="System Settings">  
                        <input type="hidden"  value="<?php echo $settings['id']?>" name="id">
                        <input type="text" value="<?php echo $settings['company_name']; ?>" class="ui-widget-content width50" name="company_name" placeholder="Company Name">
                        <br><br>
                        <input type="text" value="<?php echo $settings['address']; ?>" class="ui-widget-content width50" name="address" placeholder="Company Address">
                        <br><br>
                        <input type="text" value="<?php echo $settings['from_email']; ?>" class="ui-widget-content width50" name="from_email" placeholder="Primary Email"><br><br>
                        <input type="text" value="<?php echo $settings['cc_email']; ?>" class="ui-widget-content width50" name="cc_email" placeholder="Secondary Email"><br><br>
                        <input type="text" value="<?php echo $settings['accounts_opening']; ?>" class="ui-widget-content datepicker width50" name="accounts_opening" placeholder="Accounts Opening Date"><br><br>
                        <input type="text" value="<?php echo $settings['tagline']; ?>" class="ui-widget-content width50" name="tagline" placeholder="Tag Line"><br><br>
                        <input type="text" value="<?php echo $settings['vat']; ?>" class="ui-widget-content width50" name="vat" placeholder="VAT"><br><br>
                        <input type="text" value="<?php echo $settings['pin']; ?>" class="ui-widget-content width50" name="pin" placeholder="PIN"><br>
                        <button class="text ui-widget-content ui-corner-aEll linkright" type="submit"><img src="../images/cursors/available.png">&nbsp;Save Settings</button>
                        <br><br>
                    </form>
</div>
</div>







