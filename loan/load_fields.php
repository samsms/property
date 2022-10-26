<?php include 'db_connect.php' ?>
<?php
//extract($_POST);
if (isset($id)) {
    $qry = $conn->query("SELECT * FROM payments where id=" . $_POST['id']);
    foreach ($qry->fetch_array() as $k => $val) {
        $$k = $val;
    }
}
$loan = $conn->query("SELECT l.*,owner as name, owner as contact_no,owner as address from loan_list l inner join properties b on b.propertyid = l.borrower_id where l.id = " . $_POST['loan_id']);
foreach ($loan->fetch_array() as $k => $v) {
    $meta[$k] = $v;
}
$type_arr = $conn->query("SELECT * FROM loan_types where id = '" . $meta['loan_type_id'] . "' ")->fetch_array();

$plan_arr = $conn->query("SELECT *,concat(months,' month/s [ ',interest_percentage,'%, ',penalty_rate,' ]') as plan FROM loan_plan where id  = '" . $meta['plan_id'] . "' ")->fetch_array();
$monthly = ($meta['amount'] + ($meta['amount'] * ($plan_arr['interest_percentage'] / 100))) / $plan_arr['months'];
$penalty = $monthly * ($plan_arr['penalty_rate'] / 100);
$payments = $conn->query("SELECT * from payments where loan_id =" . $_POST['loan_id']);
$paid = $payments->num_rows;
$offset = $paid > 0 ? " offset $paid " : "";
//die("SELECT * FROM loan_schedules where loan_id = '".$_POST['loan_id']."'  order by date(date_due) asc limit 1 $offset");
$next = $conn->query("SELECT * FROM loan_schedules where loan_id = '" . $_POST['loan_id'] . "'  order by date(date_due) asc limit 1 $offset ")->fetch_assoc()['date_due'];
$sum_paid = 0;
while ($p = $payments->fetch_assoc()) {
    $sum_paid += ($p['amount'] - $p['penalty_amount']);
}
$monthly_paid = ($plan_arr['months'] * $monthly);
$monthly_balance = $monthly_paid - $sum_paid;

//else{
//    $bal=$monthly_balance;
//}

?>
<div class="col-lg-12">
    <hr>
    <div class="row">
        <div class="col-md-5">
            <div class="form-group">
                <label for="">Payee</label>
                <input name="payee" class="form-control" required=""
                       value="<?php echo isset($payee) ? $payee : (isset($meta['name']) ? $meta['name'] : '') ?>">
            </div>
        </div>

    </div>
    <hr>
    <div class="row">
        <div class="col-md-5">
            <?php if (!is_null($next)) {

                echo "Payment for " . date('M d, Y', strtotime($next));
            } else {
                echo "<p class='alert alert-success'>cleared<p></p>";
            }
            ?>
            <p><small>Total Amount Borrowed:<b><?php echo number_format($monthly_paid, 0) ?></b></small></p>
            <p><small>Total amount paid:<b><?php echo number_format($sum_paid, 0) ?></b></small></p>
            <p><small>Monthly Payable amount:<b><?php echo number_format($monthly, 0) ?></b></small></p>
            <p><small>Penalty :<b><?php echo $add = (date('Ymd', strtotime($next)) < date("Ymd")) ? $penalty : 0; ?></b></small>
            </p>
            <p><small>Payable Amount :<b><?php echo number_format($monthly + $add, 0) ?></b></small></p>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <?php if (!is_null($next)) {
                    ?>

                    <label for="">Amount</label>
                    <input type="number" name="amount" step="any" min="" class="form-control text-right" required=""
                            value="<?php echo(round($monthly,0)) ?>" max="<?=round($monthly,0)?>">
                    <input type="hidden" name="penalty_amount" value="<?php echo $add ?>">
                    <!-- <input type="hidden" name="loan_ids" value="<?php echo $_POST['loan_id'] ?>"> -->
                    <input type="hidden" name="overdue" value="<?php echo $add > 0 ? 1 : 0 ?>">
                <?php } else {

                    ?>
                    <input type="hidden" value="cleared" name="cleared"><?php } ?>
            </div>
        </div>
    </div>
</div>