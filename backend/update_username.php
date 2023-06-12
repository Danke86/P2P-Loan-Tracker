<?php include('../config.php'); ?>
<?php include('login_checker.php'); ?>

<?php 
    if(isset($_POST['update_user'])) {
        $newusername = $_POST['update_username'];

        $usernameQ = "UPDATE `users` SET `uname`='$newusername' WHERE `userid`=".$_SESSION['user_id']."";
        $usernameR = mysqli_query($mysqli, $usernameQ);

        if (!$usernameR) {
            die("Query Failed".mysqli_error($mysqli));
        } else {
            header('location:../pages/dashboard.php?update_username=Username changed successfully!');
        }
    }
?>