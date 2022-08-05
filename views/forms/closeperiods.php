
<div id="tabs">
<ul>
    <li><a href="#tabs-1">Close Periods For FY <?php echo date("d-m-Y",  strtotime(@$_REQUEST['start_date'])). ' to '.date("d-m-Y",strtotime(@$_REQUEST['end_date']));?></a> </li>
    <a  class="linkright" href="template.php?page=repairs#tabs-2">Back</a>
</ul>
<div id="tabs-1">
<p>    <div id="addcloseperiod" style="display:none">
<form action="../modules/accountsprocess.php?addcp=true" method="POST" title="Financial Year">  
                        <input type="text"  class="ui-widget-content width50 datepicker" name="start_date_cp" placeholder="Start Date">
                        <br><br>
                        <input type="text"  class="ui-widget-content width50 datepicker"  name="end_date_cp" placeholder="End Date">
                        <input type="hidden" name="financial_year" value="<?php echo $_REQUEST['fy']?>"/>
                        <input type="hidden" name="start_fy" value="<?php echo $_REQUEST['start_date']?>"/>
                         <input type="hidden" name="end_fy" value="<?php echo $_REQUEST['end_date']?>"/>
                         <br>
                         <label>Is Active</label>
                                                 <input type="checkbox" name="is_active" value="1"/>
                                                 <br>
                        <button class="text ui-widget-content ui-corner-aEll linkright" type="submit"><img src="../images/cursors/available.png">&nbsp;Add Close Period</button>
                    </form>
        </div>
<br><br><a class="addclosep linkright greenfont" href="#">+Add Close Period</a>
                 <p>     <table class="accounts" style="width:100%">
                        <thead><tr><th>No</th><th>Start Date</th><th>End Date</th><th>Status</th><th>Edit</th><th>Delete</th></tr></thead>
                
                        <?php  $closeperiods=  getClosePeriods($_REQUEST['fy']);
                        $count=1;
  foreach ($closeperiods as $closeperiod) {     
                                     ?>
                        <tr><td><?php echo $count;?></td> <td><?php echo date("d-m-Y",  strtotime( $closeperiod['start_date']))?></td> <td><?php  echo date("d-m-Y",  strtotime($closeperiod['end_date']))?></td><td><?php echo $closeperiod['is_active'] ?></td><td><?php if ($closeperiod['is_active']==1){?><a href="#" title="<?php echo $closeperiod['idclose_periods']?>" class="editclosep" id="0">Deactivate</a><?php } else {?> <a href="#" title="<?php echo $closeperiod['idclose_periods']?>" class="editclosep" id="1">Activate</a><?php }?></td>
                            <td><a href="#" class="deleteclosepa" id="<?php echo $closeperiod['idclose_periods']?>">X</a></td></tr>          
                    
                    <?php
                     $count++;
  }

                        ?>                            </table> </p>
</div>

</div>

<div id="deleteclosep" title="Confirm Delete" style="display:none;">Do you really want to delete close period?  </div>





