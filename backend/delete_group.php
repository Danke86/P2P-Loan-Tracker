<?php
session_start();
include("../config.php");

// Retrieve group ID from form submission
$groupId = $_POST['groupid'];


// Delete group
$deleteQuery_leave = "DELETE FROM is_member_of WHERE groupid = '$groupId'";
$deleteQuery_group = "DELETE FROM groups WHERE groupid = '$groupId'";
$result_leave = mysqli_query($mysqli, $deleteQuery_leave);
$result_group = mysqli_query($mysqli, $deleteQuery_group);


if(!$result_leave && !$result_group) {
    die("Query failed: " . mysqli_error($mysqli));
} else {
    echo "You have deleted the group";

    //redirect back to group page
    header("Location: ../pages/groups.php");
    exit();
}
?>
