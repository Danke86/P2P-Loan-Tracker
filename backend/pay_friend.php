<?php include('../config.php'); ?>
<?php include('../backend/login_checker.php'); ?>
<?php 
    if(isset($_POST['expense_id_friend'])&&isset($_POST['amount_paid_friend'])){
        $expenseid = $_POST['expense_id_friend'];
        $amountpaid = $_POST['amount_paid_friend'];

        if($expenseid == "" || empty($expenseid) || 
        $amountpaid == "" || empty($amountpaid) ) {
        header('location:../pages/dashboard.php?friend_message=Please fill in all fields!');
        } else{
            //check if you are the payer
            $query = "SELECT payerid FROM expenses WHERE expenseid = $expenseid";
            $result = mysqli_query($mysqli, $query);
            $resultid = mysqli_fetch_assoc($result);

            if($resultid['payerid'] == $_SESSION['user_id']){
                header('location:../pages/dashboard.php?insert_msg=You cannot pay yourself!');
                exit;
            }

            //check if sobra da payment
            $query1 = "SELECT (e.amount) - COALESCE(SUM(p.amount), 0) AS totaldebt 
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
            // $query2 = "INSERT INTO `payments`(`amount`, `date_incurred`, `userid`, `expenseid`) VALUES ($amountpaid, NOW(), ".$_SESSION["user_id"].", $expenseid)";
            $query2 = "INSERT INTO `payments`(`amount`, `date_incurred`, `userid`, `expenseid`) VALUES ($amountpaid, NOW(), ".$_SESSION["user_id"].", $expenseid)";
            $result2 = mysqli_query($mysqli, $query2);

            // $query3 = "UPDATE expenses SET amount = amount - $amountpaid WHERE expenseid = $expenseid";
            // $result3 = mysqli_query($mysqli, $query3);

            if(!$result && !$result1 && !$result2 && !$result3) {
                die("Query Failed".mysqli_error($mysqli));
            }
            else {
                header('location:../pages/dashboard.php?insert_msg=Amount deducted!');
                exit;
            }
        }

    }else{
        header('location:../pages/dashboard.php?friend_message=Fields not set!');
        exit;
    }
?>