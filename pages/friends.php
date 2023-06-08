<?php include('header.php'); ?>
<?php include('../config.php'); ?>
<?php include('../backend/login_checker.php'); ?>

<section class="main">
  <h1 id="main_title">FRIENDS AND GROUPS</h1>
  <div class="container">
    <h2>List of Friends</h2>
    <table class="table table-hover table-bordered table-str">
      <thead>
        <tr>
          <th>Friend Name</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $userId = $_SESSION['user_id'];
        $query = "SELECT u.uname
                  FROM users u
                  JOIN befriends b ON u.userid = b.friendid
                  WHERE b.userid = '$userId'";
        $result = mysqli_query($mysqli, $query);

        if (!$result) {
          die("Query failed: " . mysqli_error($mysqli));
        } else {
          while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['uname'] . "</td>";
            echo "</tr>";
          }
        }
        ?>
      </tbody>
    </table>
  </div>
</section>

<?php include('footer.php'); ?>
