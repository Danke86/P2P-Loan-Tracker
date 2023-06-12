<?php include('../config.php'); ?>
<?php include('login_checker.php'); ?>

<?php
    if(isset($_GET['id'])) {
        $expense_id = $_GET['id'];
        
        // original amount of expenses - payments in that expenseid
        $query1 = "SELECT original_amount-(SELECT sum(amount) 'amount' FROM `payments` where `expenseid` = '$expense_id') 'amount' FROM `expenses` where `expenseid` = $expense_id";
        $result1 = mysqli_query($mysqli, $query1);
        $remaining_amount = mysqli_fetch_assoc($result1);

        if (($remaining_amount['amount']) != 0) {
            header('location:../pages/dashboard.php?delete_f_message=Expense cannot be deleted due to unresolved payment.');
        } else {
            $query = "DELETE FROM `user_incurs_expense` where `expenseid` = '$expense_id'";
            $result = mysqli_query($mysqli, $query);

            $query2 = "DELETE FROM `payments` where `expenseid` = '$expense_id'";
            $result2 = mysqli_query($mysqli, $query2);

            $query1 = "DELETE FROM `expenses` where `expenseid` = '$expense_id'";
            $result1 = mysqli_query($mysqli, $query1);

            if (!$result && !$result1 && !$result) {
                die("Query Failed".mysqli_error($mysqli));
            } else {
                header('location:../pages/dashboard.php?delete_f_message=Deleted successfully!');
            }
        }
    }
?>