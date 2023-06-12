<?php
include('login_checker.php'); // Add this line to start the session
include('../config.php');

// Retrieve group ID from form submission
$groupId = $_POST['groupid'];

// Retrieve the user ID of the logged-in user
$userId = $_SESSION['user_id'];

// Delete user's membership from is_member_of table
$deleteQuery = "DELETE FROM is_member_of WHERE userid = '$userId' AND groupid = '$groupId'";
$updateQuery = "UPDATE groups SET member_count = ((SELECT member_count FROM groups WHERE groupid = '$groupId') - 1) WHERE groupid = '$groupId'";

$result = mysqli_query($mysqli, $deleteQuery);
$result = mysqli_query($mysqli, $updateQuery);


if (!$result && !$result) {
  die("Query failed: " . mysqli_error($mysqli));
} else {
  header("Location: ../pages/groups.php?leave_group=You have successfully left the group.");
  exit();
}
?>
