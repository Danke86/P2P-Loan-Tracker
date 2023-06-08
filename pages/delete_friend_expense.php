<?php include('../config.php'); ?>

<?php
    if(isset($_GET['id'])) {
        $expense_id = $_GET['id'];

        $query = "DELETE FROM `user_incurs_expense` where `expenseid` = '$expense_id'";
        $result = mysqli_query($mysqli, $query);

        $query1 = "DELETE FROM `expenses` where `expenseid` = '$expense_id'";
        $result1 = mysqli_query($mysqli, $query1);

        if (!$result && !$result1) {
            die("Query Failed".mysqli_error());
        } else {
            header('location:dashboard.php?delete_f_message=Deleted successfully!');
        }
    }
?>