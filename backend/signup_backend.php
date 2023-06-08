<?php
  require_once "../config.php";

  $newusername = filter_input(INPUT_POST, 'username');
  $newpassword = filter_input(INPUT_POST, 'password');

  $hashedpassword = md5($newpassword);

  $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

  if($conn === false){
    die("ERROR: Could not connect. " . $mysqli->connect_error);
  }

  if(isset($newusername)&&isset($newpassword)){
    //check if username exists
    $usernameInDb = mysqli_query($conn, "SELECT uname FROM users WHERE uname = '".$newusername."'");
    $unameResult = mysqli_fetch_assoc($usernameInDb);
    $unameInDb =  $unameResult['uname'];

    if($unameInDb === null){
      //create user if non exits in db
      $query = "INSERT INTO users (uname, upassword) VALUES('".$newusername."', '".$hashedpassword."')";
      if(mysqli_query($conn, $query)){
        $_SESSION['user_id'] = $row['userid'];
        echo "<script type='text/javascript'>
				window.confirm('Account Sucessfully Created');
				window.location.href = '../pages/dashboard.php';
				</script>";
      }else{
        echo "<script type='text/javascript'>
				window.confirm('Error: Something went wrong xD');
				window.location.href = '../index.php';
				</script>";
      }

    }else{
      echo "<script type='text/javascript'>
		    	window.confirm('Error Occured: Username is Taken');
		    	window.location.href = '../pages/signup.php';
		    	</script>";
    }

    
  }

  
