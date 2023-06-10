<?php
include('../config.php');
include('login_checker.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get the group ID and new group name from the form
  $groupId = $_POST['groupid'];
  $newGroupName = $_POST['editGroupName'];

  // Prepare the UPDATE query with a placeholder
  $query = "UPDATE groups SET groupname = ? WHERE groupid = ?";

  // Create a prepared statement
  $stmt = mysqli_prepare($mysqli, $query);
  if (!$stmt) {
    die("Preparation of statement failed: " . mysqli_error($mysqli));
  }

  // Bind the parameters to the statement
  mysqli_stmt_bind_param($stmt, "si", $newGroupName, $groupId);

  // Execute the statement
  $result = mysqli_stmt_execute($stmt);

  if (!$result) {
    die("Query failed: " . mysqli_stmt_error($stmt));
  } else {
    // Redirect back to the groups page
    header("Location: ../pages/groups.php");
    exit();
  }

  // Close the statement
  mysqli_stmt_close($stmt);
}
?>
