<?php
//logout user
session_start();

session_destroy();
//redirect to login page
header("Location : index.php");
?>