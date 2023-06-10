<?php include('header.php'); ?>

<section class="main">
  <h1 id="main_title">FRIENDS</h1>
  <div class="container">
    <h2>List of Friends</h2>

    <div>
      <input id="friendlistSearchInput" type="search" class="form-control rounded" placeholder="Search a friend" aria-label="Search" aria-describedby="search-addon" />
    </div>
    <table id="friendlistTable" class="table table-hover table-bordered table-str">
      <thead>
        <tr>
          <th>Friend Name</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $userId = $_SESSION['user_id'];
        $query = "SELECT u.userid, u.uname
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
            echo "<td>";
            echo "<form method='post' action='../backend/remove_friend.php'>";
            echo "<input type='hidden' name='friendid' value='" . $row['userid'] . "' />";
            echo "<button type='submit' class='btn btn-danger'>Unfriend</button>";
            echo "</form>";
            echo "</td>";
            echo "</tr>";
          }
        }
        ?>
      </tbody>
    </table>
  </div>
</section>

<?php include('footer.php'); ?>
