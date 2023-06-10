<?php include('../config.php'); ?>
<?php include('login_checker.php'); ?>
<?php
    
    if(isset($_POST['add_friend'])) {
        $userid = $_POST['notfriend_names'];
        
        if($userid == "" || empty($userid)) {
            header('location:../pages/friends.php?friend_message=Failed to add friend.');
            exit;
        } 
        else {
            //befriend each other
            $query1 = "INSERT INTO befriends (userid, friendid) VALUES(".$_SESSION['user_id'].", $userid)";
            $query2 = "INSERT INTO befriends (userid, friendid) VALUES($userid, ".$_SESSION['user_id'].")";
           
            $result1 = mysqli_query($mysqli, $query1);
            $result2 = mysqli_query($mysqli, $query2);

            if(!$result1 && !$result2) {
                die("Query Failed".mysqli_error($mysqli));
            }
            else {
                header('location:../pages/friends.php?friend_message=Added friend successfully!');
                exit;
            }

        }
    }
?>
