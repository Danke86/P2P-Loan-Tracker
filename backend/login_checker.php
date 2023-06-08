<?php
//prevent user from accessing page before logging in
session_start();
if ( isset( $_SESSION['user_id'] ) ) {
} else {
    header("Location: ../index.php");
}
?>