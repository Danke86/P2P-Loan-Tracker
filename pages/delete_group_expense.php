<?php include('../config.php'); ?>

<?php
    if(isset($_GET['id'])) {
        $expense_id = $_GET['id'];

        $query = "DELETE FROM `expenses` where `expenseid`='$expense_id'";
        $result = mysqli_query($mysqli, $query);

        if (!$result) {
            die("Query Failed".mysqli_error());
        } else {
            header('location:dashboard.php?delete_g_message=Deleted successfully!');
        }
    }
?>