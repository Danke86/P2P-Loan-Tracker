<?php include('../config.php'); ?>
<?php include('../backend/login_checker.php'); ?>
<?php
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
            $current_auto_query = "SELECT `AUTO_INCREMENT` FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'p2p' AND TABLE_NAME = 'expenses'";
            $current_auto_result = mysqli_query($mysqli, $current_auto_query);
            $current_auto = mysqli_fetch_assoc($current_auto_result);

            //git member count
            $member_count_query = "SELECT * FROM `groups` WHERE groupid=$group_id";
            $member_count_result = mysqli_query($mysqli, $member_count_query);
            $member_count = mysqli_fetch_assoc($member_count_result);
            
            //cant make an expense if ur alone aww
            if($member_count['member_count'] <= 1){
                header('location:../pages/dashboard.php?group_message=You dont have friends in the group');
                exit();
            }

            //divide the orig_amount by the number of current members
            $amount = $orig_amount / ($member_count['member_count'] - 1);

            //insert new group expense
            $query = "INSERT INTO `expenses` (`expense_type`, `expensename`, `date_incurred`, `original_amount`, `amount`, `payerid`, `groupid`) VALUES('group', '$e_name', NOW(), '$orig_amount', '$amount', '$payer_id', '$group_id')";
            $result = mysqli_query($mysqli, $query);
            
            //gets all users in group
            $users_in_group_query = "SELECT userid FROM is_member_of i WHERE groupid = $group_id";
            $result1 = mysqli_query($mysqli, $users_in_group_query);
            if(!$result1){
                die("Query Failed".mysqli_error($mysqli));
            }

            //insert into user_incurs_expenses this expense for every user in the group
            while($usersInGroup = mysqli_fetch_assoc($result1)){
                $user = mysqli_fetch_assoc($result1);
                $query2 = "INSERT INTO user_incurs_expense(userid,expenseid) VALUES(".$user['userid'].", ".$current_auto['AUTO_INCREMENT']."";
            }
            

            if(!$result) {
                die("Query Failed".mysqli_error($mysqli));
            }
            else {
                header('location:../pages/dashboard.php?group_insert_msg=Expense has been added successfully!');
                exit();
            }
        }
    }
?>