<div id="tabs">
<ul>
    <li><a href="#tabs-1">Entries for GL <?php echo $_REQUEST['code']?></a> </li>
    <center><a href="#"  id="<?php echo $journals[$i]['journal_ref']?>" class="paylandlord redfont" title="<?php echo number_format(array_sum($debitsum),2)?>">Pay Landlord</a></center>
  <a  class="linkright" href="accounts.php">  &nbsp; Back To Accounts</a> <a  class="linkright" href="template.php?page=paylandlord">Agent Landlord Accounts |</a> 
</ul>
<div id="tabs-1">

<br><br><br>                 <table class="accounts" style="width:100%">
                        <thead><tr><th>Journal Ref</th><th>Credit</th><th>Debit</th><th>Transaction</th><th>Transaction Ref</th><th>Close Period</th><th>Property</th><th>Date</th></tr></thead>
                
                        <?php
                        $journals=getGLJournals($_REQUEST['code'],0,0);
                        $debitsum=array();
                        $closeperiods=array();
                        $count=count($journals);
                        for ($i=0;$i<$count;$i++) {
                            array_push($debitsum,$journals[$i]['total_debit']);
                            array_push($closeperiods, $journals[$i]['idclose_periods']);
                            $propertyname=findpropertybyid($journals[$i]['property_id']);
                                                   ?>
                        <tr> <td><?php echo $journals[$i]['journal_ref']?></td> <td><?php  echo $journals[$i]['total_debit']?></td><td><?php  echo $journals[$i]['total_credit']?></td><td><?php echo $journals[$i]['transaction_type'] ?></td><td><?php echo $journals[$i]['document_ref'] ?></td><td><?php echo $journals[$i]['idclose_periods'] ?></td><td><?php echo  $propertyname?></td><td><?php echo date("d-m-Y",strtotime($journals[$i]['booking_date']))?></td></tr>          
                    
          
          <?php
  }

                        ?>                            </table> 
<br>
</div>

</div>

<div id="payland" title="Confirm Payment" style="display:none;">
    <table width="100%">
        <tr><th>Ref(close Period)</th><th>Close Period</th><th>Amount</th><th>Action</th>
    <?php
    $property_id= $_REQUEST['property_id'];
$uniquecloseperiods=  array_unique($closeperiods);

foreach ($uniquecloseperiods as $idcloseperiod) {
     $totalsforcloseperiods=getTotalForJournalPeriod($_REQUEST['code'],$idcloseperiod);
   
    
     ?>
        <tr><th><?php echo $totalsforcloseperiods['closeperiod']?></th><th><?php echo date("d-m-Y",strtotime($totalsforcloseperiods['closeperioddetails']['start_date'])).'&nbsp;To&nbsp;'.date("d-m-Y",  strtotime($totalsforcloseperiods['closeperioddetails']['end_date']))?></th><th><?php echo number_format($totalsforcloseperiods['sum'],2)?></th><th><input type="radio" name="paylandlordradio" title="<?php echo $totalsforcloseperiods['closeperiod']?>" tabindex="<?php echo $property_id; ?>" value="<?php echo $totalsforcloseperiods['sum']?>" src="<?php echo $totalsforcloseperiods['journal_refs'] ?>"/></th></tr>
            <?php
    
}
?>
    </table>
</div>





