<?php include('../config.php'); ?>

<?php
    if(isset($_GET['id'])) {
        $expense_id = $_GET['id'];

        $query3 = "SELECT * FROM `expenses` where `expenseid` = '$expense_id'";
        $result3 = mysqli_query($mysqli, $query3);
        $e_amount = mysqli_fetch_assoc($result3);

        if ($e_amount['amount'] != 0) {
            header('location:../pages/dashboard.php?delete_g_message=Expense cannot be deleted due to unresolved payment.');
        } else {
            $query = "DELETE FROM `expenses` where `expenseid`='$expense_id'";
            $result = mysqli_query($mysqli, $query);

            if (!$result) {
                die("Query Failed".mysqli_error());
            } else {
                header('location:../pages/dashboard.php?delete_g_message=Deleted successfully!');
            }
        }
    }
?>