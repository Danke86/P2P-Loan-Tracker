<?php include('../config.php'); ?>
<?php include('../backend/login_checker.php'); ?>
<?php
    session_start();
    if(isset($_POST['add_expense_group'])) {
        $e_name = $_POST['g_e_name'];
        $orig_amount = $_POST['g_orig_amount'];
        $payer_id = $_POST['g_payer_names']; //gets userid
        $group_id = $_POST['group_names'];
        
        if($e_name == "" || empty($e_name) || 
        $orig_amount == "" || empty($orig_amount) || 
        $payer_id == "" || empty($payer_id) || 
        $group_id == "" || empty($group_id)) {
            header('location:../pages/dashboard.php?group_message=Please fill in all fields!');
            exit();
        }

        else {
            $member_count_query = "SELECT * FROM `groups` WHERE groupid=$group_id";
            $member_count_result = mysqli_query($mysqli, $member_count_query);
            $member_count = mysqli_fetch_assoc($member_count_result);

            if($member_count['member_count'] <= 1){
                header('location:../pages/dashboard.php?group_message=You dont have friends in the group');
                exit();
            }

            $amount = $orig_amount / ($member_count['member_count'] - 1);

            $query = "INSERT INTO `expenses` (`expense_type`, `expensename`, `date_incurred`, `original_amount`, `amount`, `payerid`, `groupid`) VALUES('group', '$e_name', NOW(), '$orig_amount', '$amount', '$payer_id', '$group_id')";

            $result = mysqli_query($mysqli, $query);

            if(!$result) {
                die("Query Failed".mysqli_error());
            }
            else {
                header('location:../pages/dashboard.php?group_insert_msg=Expense has been added successfully!');
                exit();
            }
        }
    }
?>