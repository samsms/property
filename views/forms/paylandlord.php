
<div id="tabs">
<ul>
    <li><a href="#tabs-1">Landlord Balances</a> </li>
    <a  class="linkright" href="accounts.php">Back To Accounts</a>
</ul>
<div id="tabs-1">

<br><br>


                 <p>     <table class="accounts" style="width:100%">
                        <thead><tr><th>GlCode</th><th>Account Name</th><th>Property</th><th>Account Type</th><th>Account Category</th><th>Agent Acct</th><th>Balance</th><th>Status</th></tr></thead>
                
                        <?php
                        $properties=  getProperties();
                        foreach ($properties as $property) {
                            $glaccount=getGLCodeForAccount(array('gl'=>'AgentLandlord','property_id'=>$property['property_id']));
                     
       $account=  getAccountType($glaccount['idacct_types']);
       if($glaccount['status']==1){$status="Active";}else{$status="INACTIVE";}
       $accountcategory=getAccountCategory($glaccount['idaccounttype_categories']);
     
                        ?>
                        <tr> <td><a href="template.php?page=journals&code=<?php echo $glaccount['glcode']?>&property_id=<?php echo $property['property_id'] ?>">PAY <?php echo $glaccount['glcode']?></a></td> <td><?php  echo $glaccount['acname']?></td><td><?php  echo $property['property_name']?></td><td><?php echo $account['desc'] ?></td><td><?php echo $accountcategory[0]['alias'] ?></td><td><?php echo $glaccount['is_agent'] ?></td><td><?php echo number_format($glaccount['bal'],2)?></td><td><?php echo $status?></td></tr>          
                    
                    <?php
  }
 
                        ?>                            </table> </p>
<br>
</div>

</div>






