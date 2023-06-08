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
  <div class="container">
    <h2>Groups</h2>
    <table class="table table-hover table-bordered table-str">
      <thead>
        <tr>
          <th>Group Name</th>
          <th>Member Count</th>
          <th>Group Members</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $userId = $_SESSION['user_id'];

          $query = "SELECT * FROM `groups` JOIN `is_member_of` ON `groups`.`groupid` = `is_member_of`.`groupid` WHERE `is_member_of`.`userid` = $userId";
          $result = mysqli_query($mysqli, $query);

          if (!$result) {
            die("Query failed: " . mysqli_error($mysqli));
          } else {
            while ($row = mysqli_fetch_assoc($result)) {
              $groupId = $row['groupid'];
              $groupName = $row['groupname'];
              $memberCount = $row['member_count'];

              // Get group members
              $groupMembersQuery = "SELECT `users`.`uname` FROM `users` JOIN `is_member_of` ON `users`.`userid` = `is_member_of`.`userid` WHERE `is_member_of`.`groupid` = $groupId AND `is_member_of`.`userid` != $userId";
              $groupMembersResult = mysqli_query($mysqli, $groupMembersQuery);

              if (!$groupMembersResult) {
                die("Query failed: " . mysqli_error($mysqli));
              }

              $groupMembers = array();
              while ($memberRow = mysqli_fetch_assoc($groupMembersResult)) {
                $groupMembers[] = $memberRow['uname'];
              }

              ?>
              <tr>
                <td><?php echo $groupName; ?></td>
                <td><?php echo $memberCount; ?></td>
                <td>
                  <ul>
                    <?php
                      foreach ($groupMembers as $member) {
                        echo "<li>" . $member . "</li>";
                      }
                    ?>
                  </ul>
                </td>
              </tr>
              <?php
            }
          }
        ?>
      </tbody>
    </table>
  </div>
</section>

<?php include('footer.php'); ?>
