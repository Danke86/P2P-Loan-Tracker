<?php
    if(isset($_POST['add_expense_friend'])) {
        $e_name = $_POST['e_name'];
        $orig_amount = $_POST['orig_amount'];
        $payer_uname = $_POST['f_payer_names'];
        $friend_uname = $_POST['friend_names'];
        
        if($e_name == "" || empty($e_name) || 
        $orig_amount == "" || empty($orig_amount) || 
        $payer_uname == "" || empty($payer_uname) || 
        $friend_uname == "" || empty($friend_uname)) {
            header('location:dashboard.php?friend_message=Please fill in all fields!');
        }
    }
?>