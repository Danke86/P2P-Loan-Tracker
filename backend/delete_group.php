<?php
include("../config.php");
include('login_checker.php');

// Retrieve group ID from form submission
$groupId = $_POST['groupid'];

$userid = $_SESSION['user_id'];
// first select the expenses of current user
// from there, get the expenses current user had with group (where current user is the payerid) 
// get the sum amount of expenses
$group_expenseQ = "SELECT COALESCE(SUM(e.amount),0) 'total'
                    FROM user_incurs_expense u 
                    JOIN expenses e ON u.expenseid = e.expenseid 
                    WHERE e.expense_type='group' AND e.payerid = $userid AND u.userid != $userid AND e.groupid = $groupid";
$group_expenseR = mysqli_query($mysqli, $group_expenseQ);
$group_expense = mysqli_fetch_assoc($group_expenseR);

// first select the expenses of current user
// from there, get the expenses current user had with group (where current user is the payerid, because it means friendid is the one needed to pay) 
// get the sum amount of payments by friend in those expenses they made tgt
$group_paymentQ = "SELECT COALESCE(SUM(p.amount),0) 'total' 
                    FROM payments p JOIN expenses e ON p.expenseid = e.expenseid 
                    WHERE e.expense_type = 'group' AND e.payerid = $userid AND e.groupid = $groupid";

$group_paymentR = mysqli_query($mysqli, $group_paymentQ);
$group_payment = mysqli_fetch_assoc($group_paymentR);

$group = $group_expense['total'] - $group_payment['total'];

// first select the expenses of current user
// from there, get the expenses current user had with friend (where friend is the payerid) 
// get the sum amount of expenses
$user_expenseQ = "SELECT COALESCE(SUM(e.amount),0) 'total' 
                FROM user_incurs_expense u JOIN expenses e ON u.expenseid = e.expenseid 
                WHERE e.expense_type = 'group' AND e.groupid = $groupid AND e.payerid != $userid AND u.userid = $userid";
$user_expenseR = mysqli_query($mysqli, $user_expenseQ);
$user_expense = mysqli_fetch_assoc($user_expenseR);

// first select the expenses of current user
// from there, get the expenses current user had with friend (where friend is the payerid, current user is the one who needed to pay) 
// get the sum amount of payments by current user in those expenses they made tgt
$user_paymentQ = "SELECT COALESCE(SUM(p.amount),0) 'total' 
                FROM payments p JOIN expenses e ON p.expenseid = e.expenseid 
                WHERE e.expense_type = 'group' AND e.groupid = $groupid AND e.payerid != $userid AND p.userid = $userid";
$user_paymentR = mysqli_query($mysqli, $user_paymentQ);
$user_payment = mysqli_fetch_assoc($user_paymentR);

// $user = $user_expense['amount'] - $user_payment['amount'];
$user = $user_expense['total'] - $user_payment['total'];

if($group == 0 && $user == 0){
    // Delete group
    $deleteQuery_leave = "DELETE FROM is_member_of WHERE groupid = '$groupId'";
    $deleteQuery_group = "DELETE FROM groups WHERE groupid = '$groupId'";
    $result_leave = mysqli_query($mysqli, $deleteQuery_leave);
    $result_group = mysqli_query($mysqli, $deleteQuery_group);
}else{
    header('location:../pages/groups.php?delete_group=Group cannot be removed due to unresolved payment of parties.');
    exit;
}


if(!$result_leave && !$result_group) {
    die("Query failed: " . mysqli_error($mysqli));
} else {
    //redirect back to group page
    header('location:../pages/groups.php?delete_group=Group deleted successfully!');
    exit();
}
?>
