<?php
include('login_checker.php');
include('../config.php');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Retrieve the group name from the form
  $groupName = $_POST['groupName'];
  $userId = $_SESSION['user_id'];

  // Prepare and execute the query to insert the new group into the database
  $insertGroupQuery = "INSERT INTO groups (groupname, member_count) VALUES ('$groupName', 1)";
  $resultAddGroup = mysqli_query($mysqli, $insertGroupQuery);

  // Retrieve the auto-generated groupid
  $groupId = mysqli_insert_id($mysqli);

  // Prepare and execute the query to add the user to the group
  $insertUserQuery = "INSERT INTO is_member_of (userid, groupid) VALUES ('$userId', '$groupId')";
  $resultAddUser = mysqli_query($mysqli, $insertUserQuery);

  // Check if the queries were successful
  if (!$resultAddGroup && !$resultAddUser) {
    // Group creation failed
    echo "Error creating group: " . mysqli_error($mysqli);
  } else {
    // Group creation successful
    echo "Group created successfully!";
    header("Location: ../pages/groups.php");
    exit();
  }
}
?>
