<?php include('../config.php'); ?>
<?php include('../backend/login_checker.php'); ?>
<?php 
    if(isset($_POST['expense_id_group'])&&isset($_POST['amount_paid_group'])){
        $expenseid = $_POST['expense_id_group'];
        $amountpaid = $_POST['amount_paid_group'];

        if($expenseid == "" || empty($expenseid) || 
        $amountpaid == "" || empty($amountpaid) ) {
        header('location:../pages/dashboard.php?group_message=Please fill in all fields!');
        } else{
            //get group id
            $querygroupid = "SELECT groupid FROM expenses WHERE expenseid = $expenseid";
            $resultgroupid = mysqli_query($mysqli, $querygroupid);
            $groupidrows = mysqli_fetch_assoc($resultgroupid);
            $groupid = $groupidrows['groupid'];
            
            //get member_count
            $querymemcount = "SELECT member_count FROM groups WHERE groupid = $groupid";
            $resultmemcount = mysqli_query($mysqli, $querymemcount);
            $memcountrows = mysqli_fetch_assoc($resultmemcount);
            $memcount = $memcountrows['member_count'];

            if($memcount <= 1){
                header('location:../pages/dashboard.php?group_message=Get sum friend in your group!');
                exit;
            }

            //check if you are the payer
            $query = "SELECT payerid FROM expenses WHERE expenseid = $expenseid";
            $result = mysqli_query($mysqli, $query);
            $resultid = mysqli_fetch_assoc($result);

            if($resultid['payerid'] == $_SESSION['user_id']){
                header('location:../pages/dashboard.php?group_message=You cannot pay yourself!');
                exit;
            }

            //check if sobra da payment
            $query1 = "SELECT (e.amount) - COALESCE((SELECT SUM(COALESCE(c.amount,0)) FROM payments c WHERE c.userid = ".$_SESSION['user_id']." AND c.expenseid = e.expenseid),0) AS totaldebt 
                        FROM expenses AS e 
                        LEFT JOIN payments AS p ON e.expenseid = p.expenseid 
                        WHERE e.expenseid = $expenseid
                        GROUP BY e.expenseid";
            $result1 = mysqli_query($mysqli, $query1);
            $debt = mysqli_fetch_assoc($result1);

            if($debt['totaldebt'] < $amountpaid){
                header('location:../pages/dashboard.php?group_message=Amount is over your debt!');
                exit;
            }

            //insert new payment
            // $query2 = "INSERT INTO `payments`(`amount`, `date_incurred`, `userid`, `expenseid`) VALUES ($amountpaid, NOW(), ".$_SESSION["user_id"].", $expenseid)";
            $query2 = "INSERT INTO `payments`(`amount`, `date_incurred`, `userid`, `expenseid`) VALUES ($amountpaid, NOW(), ".$_SESSION["user_id"].", $expenseid)";
            $result2 = mysqli_query($mysqli, $query2);

            // $query3 = "UPDATE expenses SET amount = amount - $amountpaid WHERE expenseid = $expenseid";
            // $result3 = mysqli_query($mysqli, $query3);

            if(!$result && !$result1 && !$result2) {
                die("Query Failed".mysqli_error($mysqli));
            }
            else {
                header('location:../pages/dashboard.php?group_message=Amount deducted!');
                exit;
            }
        }

    }else{
        header('location:../dashboard.php?group_message=Please fill in all fields!');
        exit;
    }
?>