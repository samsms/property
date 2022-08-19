<?php
ini_set('display_errors', 1);
include 'db_connect.php' ;
include 'admin_class.php';
$crud = new Action();
echo $crud->loan_next(342);
// extract($_GET);
// // if (isset($id)) {
// //     $qry = $conn->query("SELECT * FROM payments where id=" . $_POST['id']);
// //     foreach ($qry->fetch_array() as $k => $val) {
// //         $$k = $val;
// //     }
// // }
// $loan = $conn->query("SELECT l.*,owner as name, owner as contact_no,owner as address from loan_list l inner join properties b on b.propertyid = l.borrower_id where l.id = '14'");
// foreach ($loan->fetch_array() as $k => $v) {
//     $meta[$k] = $v;
// }
// $type_arr = $conn->query("SELECT * FROM loan_types where id = '" . $meta['loan_type_id'] . "' ")->fetch_array();

// $plan_arr = $conn->query("SELECT *,concat(months,' month/s [ ',interest_percentage,'%, ',penalty_rate,' ]') as plan FROM loan_plan where id  = '" . $meta['plan_id'] . "' ")->fetch_array();
// $monthly = ($meta['amount'] + ($meta['amount'] * ($plan_arr['interest_percentage'] / 100))) / $plan_arr['months'];
// $penalty = $monthly * ($plan_arr['penalty_rate'] / 100);
// $payments = $conn->query("SELECT * from payments where loan_id =".$id);
// $paid = $payments->num_rows;
// $offset = $paid > 0 ? " offset $paid" : "";
// //die("SELECT * FROM loan_schedules where loan_id = '".$_POST['loan_id']."'  order by date(date_due) asc limit 1 $offset");

// $next = $conn->query("SELECT * FROM loan_schedules where loan_id = '$id'  order by date(date_due) asc limit 1  $offset")->fetch_assoc()['date_due'];
// $sum_paid = 0;
// while ($p = $payments->fetch_assoc()) {
//     $sum_paid += ($p['amount'] - $p['penalty_amount']);
// }
// $monthly_paid = ($plan_arr['months'] * $monthly);
// $monthly_balance = $monthly_paid - $sum_paid;
// if(is_null($next)){
//     echo json_encode(array("success"=>"false","loan"=>"No Active Loan"));

// }else{
//     $loan=json_encode(array("success"=>true,"month"=>$next,"amount"=>$monthly));
//     echo $loan;
// }




