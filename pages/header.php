<!-- HEADER OF EVERY PAGE (for the sidebar)
TO USE: include header.php ?> -->
<?php include('../backend/login_checker.php'); ?>
<?php include('../config.php'); ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pays 2 Pays</title>
  <style media="screen">
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap');
  </style>
  <link rel="stylesheet" href="../css/styles.css">
  <link rel="stylesheet" href="../css/dashboard_style.css">

  <!-- allows modal to work -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">  
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js" charset="utf-8"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
  
  <!-- allows current ui design to work -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script></head>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
  </head>
<body>
  <div class="menu-btn">
    <i class="fas fa-bars"></i>
  </div>

  <div class="side-bar">
    <header>
      <div class="close-btn">
        <i class="fas fa-times"></i>
      </div>
      <img src="../images/p2p-noborder.png" alt="">
      <h1 id="user-email">
        <?php
          $query = "SELECT * FROM `users` WHERE `userid` = ".$_SESSION['user_id']."";
          $result = mysqli_query($mysqli, $query);
          $row = mysqli_fetch_assoc($result);
          echo $row['uname'];
        ?>
        <i class="fas fa-edit" data-bs-toggle="modal" data-bs-target="#updateUsername"></i>
      </h1>
    </header>
    <div class="menu">
      <div class="item"><a href="dashboard.php"><i class="fas fa-desktop"></i>Dashboard</a></div>
      <div class="item"><a href="recenthistory.php"><i class="fas fa-clipboard-list"></i>Recent History</a></div>
      <div class="item"><a href="friends.php"><i class="fas fa-user"></i>Friends</a></div>
      <div class="item"><a href="groups.php"><i class="fas fa-users"></i>Groups</a></div>
      <div class="item"><a href="../backend/logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a></div>
    </div>
  </div>

  <form action="../backend/update_username.php" method="POST">
    <div class="modal fade" id="updateUsername" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
      aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Change username</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">

            <div class="form-group">
              <label for="update_username">New username:</label>
              <input type="text" name="update_username" class="form-control">
            </div>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <input type="submit" class="btn btn-success" name="update_user" value="Change">
          </div>
        </div>
      </div>
    </div>
  </form>
