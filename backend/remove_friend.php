<?php
include('../config.php');
session_start(); // Add this line to start the session


// Retrieve friend ID from form submission
$friendId = $_POST['friendid'];

// Delete friendship from befriends table (both ends)
$userId = $_SESSION['user_id'];
$deleteQuery_userSide = "DELETE FROM befriends WHERE userid = '$userId' AND friendid = '$friendId'";
$deleteQuery_friendSide = "DELETE FROM befriends WHERE userid = '$friendId' AND friendid = '$userId'";

$deleteResult_userSide = mysqli_query($mysqli, $deleteQuery_userSide);
$deleteResult_friendSide = mysqli_query($mysqli, $deleteQuery_friendSide);


if (!$deleteResult_userSide && !$deleteResult_friendSide) {
  die("Delete query failed: " . mysqli_error($mysqli));
} else {
  echo "Friend removed successfully.";
  // redirect back to the friends page here if desired
  header("Location: ../pages/groups.php");
  exit();
}
?>
