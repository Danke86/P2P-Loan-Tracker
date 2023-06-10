<?php include('../config.php'); ?>
<?php include('login_checker.php'); ?>

<?php
    if(isset($_GET['id'])) {
        $expense_id = $_GET['id'];

        $query3 = "SELECT * FROM `expenses` where `expenseid` = '$expense_id'";
        $result3 = mysqli_query($mysqli, $query3);
        $e_amount = mysqli_fetch_assoc($result3);

        if ($e_amount['amount'] != 0) {
            header('location:../pages/dashboard.php?delete_f_message=Expense cannot be deleted due to unresolved payment.');
        } else {
            $query = "DELETE FROM `user_incurs_expense` where `expenseid` = '$expense_id'";
            $result = mysqli_query($mysqli, $query);

            $query1 = "DELETE FROM `expenses` where `expenseid` = '$expense_id'";
            $result1 = mysqli_query($mysqli, $query1);

            $query2 = "DELETE FROM `payments` where `expenseid` = '$expense_id'";
            $result2 = mysqli_query($mysqli, $query2);

            if (!$result && !$result1 && !$result) {
                die("Query Failed".mysqli_error($mysqli));
            } else {
                header('location:../pages/dashboard.php?delete_f_message=Deleted successfully!');
            }
        }
    }
?>