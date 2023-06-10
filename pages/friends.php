<?php include('header.php'); ?>

<section class="main">
  <h1 id="main_title">FRIENDS</h1>
  <div class="container">
      <div class="box1">
        <h2>List of Friends</h2>
        <!-- <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#payfriendModal">PAY FRIEND</button> -->
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addfriendModal">ADD FRIEND</button>
      </div>

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

  <!-- Friend Modal -->
  <form action="../backend/add_friend.php" method="post">
    <div class="modal fade" id="addfriendModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
      aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add expense with a friend</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <!-- NOT FRIEND DROPDOWN -->
            <?php
              $queryNames = "SELECT * from users where userid not in (SELECT u.userid FROM `users` u JOIN `befriends` b ON u.userid=b.friendid WHERE b.userid=".$_SESSION['user_id'].") and userid!=".$_SESSION['user_id']."";
              $resultNames = mysqli_query($mysqli, $queryNames);
            ?>
            <label for="notfriend_names">Select user</label>
            <select class="form-select" aria-label="Default select example" name="notfriend_names">
              <?php
                if ($resultNames->num_rows > 0) {
                  while ($row = $resultNames->fetch_assoc()) {
                      echo '<option value='.$row['userid'].'>' . $row['uname'] . '</option>';
                  }
              }
              ?>
            </select>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <input type="submit" class="btn btn-success" name="add_friend" value="Add friend">
          </div>
        </div>
      </div>
    </div>
  </form>

  <!-- get alert -->
  <?php
    if(isset($_GET['friend_message'])) {
      echo "<h6>".$_GET['friend_message']."</h6>";
    }
  ?>

  <?php
    if(isset($_GET['remove_friend'])) {
      echo "<h6>".$_GET['remove_friend']."</h6>";
    }
  ?>
</section>

<?php include('footer.php'); ?>
