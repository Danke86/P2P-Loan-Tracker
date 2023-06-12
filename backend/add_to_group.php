<?php
include('login_checker.php');
include('../config.php');
// Retrieve the selected group ID and friend ID from the form submission
$groupId = $_POST['groupid'];
$friendId = $_POST['friendid'];

// Perform the database queries to add the friend to the group
$insertQuery = "INSERT INTO is_member_of(userid, groupid) VALUES ('$friendId', '$groupId')";
$updateQuery = "UPDATE groups SET member_count = member_count + 1 WHERE groupid = '$groupId'";

// Execute the queries
$insertResult = mysqli_query($mysqli, $insertQuery);
$updateResult = mysqli_query($mysqli, $updateQuery);

if (!$insertResult && !$updateResult) {
  header('location:../pages/friends.php?add_friend_group=Failed to add friend to group. Please try again.');
} else {
  header('location:../pages/friends.php?add_friend_group=Friend added to group successfully!');
  exit();
}
?>
