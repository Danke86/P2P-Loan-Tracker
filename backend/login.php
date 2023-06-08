<?php
  session_start();

  require_once "../config.php";

  $username = filter_input(INPUT_POST, 'username');
  $password = filter_input(INPUT_POST, 'password');
  $hashedpassword = md5($password);

  $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

  if($conn === false){
    die("ERROR: Could not connect. " . $mysqli->connect_error);
  }

  $query = "SELECT userid, uname, upassword FROM users WHERE uname=? AND upassword=?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("ss", $username, $hashedpassword);
  $stmt->execute();
  $result = $stmt->get_result();

  $row = $result->fetch_assoc();

  print_r($row);

  if ($row) {

    if ($row['uname'] == $username && $row['upassword'] == $hashedpassword) {
      echo "<script type='text/javascript'>
        window.alert('Login Successful. Redirecting..');
        window.location.href = '../index.php';
        </script>";
      header('Location: ../pages/dashboard.php');
      $_SESSION['user_id'] = $row['userid'];
        
    } else {
        echo "<script type='text/javascript'>
            window.alert('Login Failed. Please Check Your Credentials');
            window.location.href = '../index.php';
        </script>";
    }
} else {
    echo "<script type='text/javascript'>
        window.alert('Login Failed. Please Check Your Credentials');
        window.location.href = '../index.php';
    </script>";
}

$conn->close();
?>

