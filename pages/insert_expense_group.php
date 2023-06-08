<?php
    if(isset($_POST['add_expense_friend'])) {
        $e_name = $_POST['g_e_name'];
        $orig_amount = $_POST['g_orig_amount'];
        $payer_uname = $_POST['g_payer_names']; //gets userid
        $group_uname = $_POST['group_names'];
        
        if($e_name == "" || empty($e_name) || 
        $orig_amount == "" || empty($orig_amount) || 
        $payer_uname == "" || empty($payer_uname) || 
        $group_uname == "" || empty($group_uname)) {
            header('location:dashboard.php?group_message=Please fill in all fields!');
        }
    }
?>