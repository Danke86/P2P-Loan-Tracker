<?php
include('../config.php');
include('login_checker.php'); // Add this line to start the session

$friendid = $_POST['friendid'];

// first select the expenses of current user
// from there, get the expenses current user had with friend (where current user is the payerid) 
// get the sum amount of expenses
$friend_expenseQ = "SELECT COALESCE(sum(amount),0) 'amount' from user_incurs_expense natural join expenses where expenseid in (SELECT expenseid from user_incurs_expense natural join expenses where userid = ".$_SESSION['user_id']." and payerid=".$_SESSION['user_id']." and expense_type='friend') and userid=$friendid";
$friend_expenseR = mysqli_query($mysqli, $friend_expenseQ);
$friend_expense = mysqli_fetch_assoc($friend_expenseR);

// first select the expenses of current user
// from there, get the expenses current user had with friend (where current user is the payerid, because it means friendid is the one needed to pay) 
// get the sum amount of payments by friend in those expenses they made tgt
$friend_paymentQ = "SELECT COALESCE(sum(amount),0) 'amount' from payments where expenseid in (SELECT expenseid from user_incurs_expense natural join expenses where expenseid in (SELECT expenseid from user_incurs_expense natural join expenses where userid = ".$_SESSION['user_id']." and payerid=".$_SESSION['user_id']." and expense_type='friend') and userid=$friendid) and userid=$friendid";
$friend_paymentR = mysqli_query($mysqli, $friend_paymentQ);
$friend_payment = mysqli_fetch_assoc($friend_paymentR);

$friend = $friend_expense['amount'] - $friend_payment['amount'];


// first select the expenses of current user
// from there, get the expenses current user had with friend (where friend is the payerid) 
// get the sum amount of expenses
$user_expenseQ = "SELECT COALESCE(sum(amount),0) 'amount' from user_incurs_expense natural join expenses where expenseid in (SELECT expenseid from user_incurs_expense natural join expenses where userid = $friendid and payerid=$friendid and expense_type='friend') and userid=".$_SESSION['user_id']."";
$user_expenseR = mysqli_query($mysqli, $user_expenseQ);
$user_expense = mysqli_fetch_assoc($user_expenseR);

// first select the expenses of current user
// from there, get the expenses current user had with friend (where friend is the payerid, current user is the one who needed to pay) 
// get the sum amount of payments by current user in those expenses they made tgt
$user_paymentQ = "SELECT COALESCE(sum(amount),0) 'amount' from payments where expenseid in 
(SELECT expenseid from user_incurs_expense natural join expenses where expenseid in 
(SELECT expenseid from user_incurs_expense natural join expenses where userid = $friendid and payerid=$friendid 
and expense_type='friend') and userid=".$_SESSION['user_id'].") and userid=".$_SESSION['user_id']."";
$user_paymentR = mysqli_query($mysqli, $user_paymentQ);
$user_payment = mysqli_fetch_assoc($user_paymentR);

$user = $user_expense['amount'] - $user_payment['amount'];

// if part of group
$ifgroupQ = "select count(groupid) 'count' from is_member_of natural join groups where groupid in (select groupid from is_member_of natural join groups where userid=".$_SESSION['user_id'].") AND userid=$friendid";
$ifgroupR = mysqli_query($mysqli, $ifgroupQ);
$ifgroup = mysqli_fetch_assoc($ifgroupR);

if (($friend != 0) || ($user != 0)) { //cant delete friend if there is unpaid payment
  header('location:../pages/friends.php?remove_friend=Friend cannot be removed due to unresolved payment of both/either parties.');
} else if ($ifgroup['count'] > 0) {
  header('location:../pages/friends.php?remove_friend=Friend cannot be removed because they are part of your group.');
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
      header("Location: ../pages/friends.php?remove_friend=Friend removed successfully!");
    exit();
  }
}
?>
