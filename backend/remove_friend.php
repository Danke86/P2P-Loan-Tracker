<?php
include('../config.php');
include('login_checker.php'); // Add this line to start the session

$friendid = $_POST['friendid'];

$origamountQ = "SELECT sum(original_amount) 'amount' from (SELECT distinct expenseid, original_amount from user_incurs_expense NATURAL JOIN expenses WHERE userid IN (".$_SESSION['user_id'].", '$friendid')) amt";
$paymentsQ = "SELECT sum(amount) 'amount' from payments WHERE expenseid IN (SELECT expenseid from user_incurs_expense NATURAL JOIN expenses WHERE userid IN (".$_SESSION['user_id'].", '$friendid') group by expenseid)";
$origamountR = mysqli_query($mysqli, $origamountQ);
$paymentsR = mysqli_query($mysqli, $paymentsQ);

$origamount = mysqli_fetch_assoc($origamountR);
$payments = mysqli_fetch_assoc($paymentsR);

if ($origamount['amount'] - $payments['amount'] != 0) { //cant delete friend if there is unpaid payment
  header('location:../pages/friends.php?remove_friend=Friend cannot be removed due to unresolved payment of both/either parties.');
} else {
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
      header("Location: ../pages/friends.php?remove_friend=Friend removed successfully.");
    exit();
  }
}
?>
