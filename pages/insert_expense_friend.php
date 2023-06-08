<?php include('../config.php'); ?>
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
        } 

        else {
            $amount = $orig_amount / 2;

            $dateQuery = "SELECT now()";
            $dateResult = mysqli_query($mysqli, $dateQuery);
            $date = mysqli_fetch_assoc($dateResult);

            $current_auto_query = "SELECT `AUTO_INCREMENT` FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_SCHEMA = 'p2p' AND TABLE_NAME = 'expenses'";
            $current_auto_result = mysqli_query($mysqli, $current_auto_query);
            $current_auto = mysqli_fetch_assoc($current_auto_result);

            $query1 = "INSERT INTO `expenses` (`expense_type`, `expensename`, `date_incurred`, `original_amount`, `amount`, `payerid`, `groupid`) VALUES('friend', '$e_name', ".$date['now()'].", $orig_amount, $amount, '$payer_id', null)";
            $query2 = "INSERT INTO `user_incurs_expense` (`userid`, `expenseid`) VALUES(".$_SESSION['user_id'].", ".$current_auto['AUTO_INCREMENT']."";
            $query3 = "INSERT INTO `user_incurs_expense` (`userid`, `expenseid`) VALUES('$friend_id', ".$current_auto['AUTO_INCREMENT'].")";
            
            $result = mysqli_query($mysqli, $query1);
            $result2 = mysqli_query($mysqli, $query2);
            $result3 = mysqli_query($mysqli, $query3);

            if(!$result && !$result2 && !$result3) {
                die("Query Failed".mysqli_error());
            }
            else {
                header('location:dashboard.php?insert_msg=Expense has been added successfully!');
            }

            // $date2 = mysqli_query($mysqli, $date);
            // $date3 = mysqli_fetch_assoc($date2);
            // // echo $date3['date'];

            // $current_auto2 = mysqli_query($mysqli, $current_auto);
            // $current_auto3 = mysqli_fetch_assoc($current_auto2);
            //echo $amount .' - '. $date['date'] .' - '. $current_auto['AUTO_INCREMENT'];
            //get expense id from $query1

            // $query2 = "INSERT INTO `user_incurs_expense` (`userid`, `expenseid`) VALUES(001, $current_auto['AUTO_INCREMENT'])";
            // $query3 = "INSERT INTO `user_incurs_expense` (`userid`, `expenseid`) VALUES(002, $current_auto['AUTO_INCREMENT'])";

        }
    }
?>