<?php include('../config.php'); ?>
<?php include('login_checker.php'); ?>

<?php 
    if(isset($_POST['update_expense_friend'])) {
        $expense_id = $_POST['expense_id'];
        $expense_name = $_POST['update_friend_ename'];

        $getgroup_query = "SELECT * FROM `expenses` WHERE `expenseid`='$expense_id'";
        $getgroup_result = mysqli_query($mysqli, $getgroup_query);
        $getgroup = mysqli_fetch_assoc($getgroup_result);

        $query = "UPDATE `expenses` SET `expensename`='$expense_name' where `expenseid`='$expense_id'";
        $result = mysqli_query($mysqli, $query);

        if (!$result) {
            die("query Failed".mysqli_error($mysqli));
        } else {
            if ($getgroup['groupid'] != null) {
                header('location:../pages/dashboard.php?update_g_message=Updated successfully!');
            } else {
                header('location:../pages/dashboard.php?update_f_message=Updated successfully!');
            }
        }
    }
?>