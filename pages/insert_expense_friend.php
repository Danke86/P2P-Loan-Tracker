<?php include('../config.php'); ?>
<?php include('../backend/login_checker.php'); ?>
<?php
    if(isset($_POST['add_expense_friend'])) {
        $e_name = $_POST['e_name'];
        $orig_amount = $_POST['orig_amount'];
        $payer_id = $_POST['f_payer_names'];
        $friend_id = $_POST['friend_names'];
        
        if($e_name == "" || empty($e_name) || 
            $orig_amount == "" || empty($orig_amount) || 
            $payer_id == "" || empty($payer_id) || 
            $friend_id == "" || empty($friend_id)) {
            header('location:dashboard.php?friend_message=Please fill in all fields!');
            exit;
        } 

        else {

            $current_auto_query = "SELECT `AUTO_INCREMENT` FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'p2p' AND TABLE_NAME = 'expenses'";
            $current_auto_result = mysqli_query($mysqli, $current_auto_query);
            $current_auto = mysqli_fetch_assoc($current_auto_result);

            $query1 = "INSERT INTO `expenses` (`expense_type`, `expensename`, `date_incurred`, `original_amount`, `amount`, `payerid`, `groupid`) VALUES('friend', '$e_name', NOW(), $orig_amount, $orig_amount, '$payer_id', null)";
            $query2 = "INSERT INTO `user_incurs_expense` (`userid`, `expenseid`) VALUES(".$_SESSION['user_id'].", ".$current_auto['AUTO_INCREMENT']." )";
            $query3 = "INSERT INTO `user_incurs_expense` (`userid`, `expenseid`) VALUES('$friend_id', ".$current_auto['AUTO_INCREMENT'].")";
            
            $result = mysqli_query($mysqli, $query1);
            $result2 = mysqli_query($mysqli, $query2);
            $result3 = mysqli_query($mysqli, $query3);

            if(!$result && !$result2 && !$result3) {
                die("Query Failed".mysqli_error());
            }
            else {
                header('location:dashboard.php?insert_msg=Expense has been added successfully!');
                exit;
            }

        }
    }
?>