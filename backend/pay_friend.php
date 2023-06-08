<?php include('../config.php'); ?>
<?php include('../backend/login_checker.php'); ?>
<?php 
    if(isset($_POST['expense_id'])&&isset($_POST['amount_paid_friend'])){
        $expenseid = $_POST['expense_id'];
        $amountpaid = $_POST['amount_paid_friend'];

        if($expenseid == "" || empty($expenseid) || 
        $amountpaid == "" || empty($amountpaid) ) {
        header('location:dashboard.php?friend_message=Please fill in all fields!');
        } else{
            // $query = "UPDATE `expenses` SET `amount` = `amount` - $amountpaid WHERE expenseid = $expenseid ";
            // $result = mysqli_query($mysqli, $query);

            //check if sobra da payment
            $query1 = "SELECT (e.amount/2) - COALESCE(SUM(p.amount), 0) AS totaldebt 
                        FROM expenses AS e 
                        LEFT JOIN payments AS p ON e.expenseid = p.expenseid 
                        WHERE e.expenseid = $expenseid 
                        GROUP BY e.expenseid";
            $result1 = mysqli_query($mysqli, $query1);
            $debt = mysqli_fetch_assoc($result1);

            if($debt['totaldebt'] < $amountpaid){
                header('location:../pages/dashboard.php?insert_msg=Amount is over your debt!');
                exit;
            }

            //insert new payment
            // $query2 = "INSERT INTO `payments`(`amount`, `date_incurred`, `userid`, `expenseid`) VALUES ($amountpaid, NOW(), ".$_POST["user_id"].", $expenseid)";

            if(!$result1) {
                die("Query Failed".mysqli_error());
            }
            else {
                header('location:../pages/dashboard.php?insert_msg=Amount deducted!');
                exit;
            }
        }

    }else{
        header('location:dashboard.php?friend_message=Please fill in all fields!');
        exit;
    }
?>