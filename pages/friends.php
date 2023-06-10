<?php include('header.php'); ?>

<section class="main">
  <h1 id="main_title">FRIENDS</h1>
  <div class="container">
    <div class="box1">
      <h2>List of Friends</h2>
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
            echo "<button type='button' class='btn btn-primary add-to-group-btn' data-friendid='" . $row['userid'] . "' data-bs-toggle='modal' data-bs-target='#addToGroupModal' data-groupid='" . $row['userid'] . "'>Add to Group</button>";
            echo "</form>";
            echo "</td>";
            echo "</tr>";

            // Add modal for each friend
            echo "<div class='modal fade' id='addToGroupModal' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>";
            echo "<div class='modal-dialog' role='document'>";
            echo "<div class='modal-content'>";
            echo "<div class='modal-header'>";
            echo "<h5 class='modal-title' id='exampleModalLabel'>Add Friend to Group</h5>";
            echo "<button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>";
            echo "</div>";
            echo "<div class='modal-body'>";
            echo "<label for='groupSelect'>Select Group</label>";
            echo "<select class='form-select' id='groupSelect' name='groupSelect'>";
            
            $friendId = $row['userid'];
            $queryGroups = "SELECT g.groupid, g.groupname
                            FROM groups g
                            LEFT JOIN is_member_of m ON g.groupid = m.groupid AND m.userid = '$friendId'
                            WHERE m.userid IS NULL";

            $resultGroups = mysqli_query($mysqli, $queryGroups);

            if (!$resultGroups) {
              die("Query failed: " . mysqli_error($mysqli));
            }

            while ($groupRow = mysqli_fetch_assoc($resultGroups)) {
              echo "<option value='" . $groupRow['groupid'] . "'>" . $groupRow['groupname'] . "</option>";
            }

            echo "</select>";
            echo "</div>";
            echo "<div class='modal-footer'>";
            echo "<button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>";
            echo "<button type='button' class='btn btn-primary' id='addToGroupBtn'>Add to Group</button>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
          }
        }
        ?>
      </tbody>
    </table>
  </div>
</section>

<!-- Friend Modal -->
<form action="../backend/add_friend.php" method="post">
  <div class="modal fade" id="addfriendModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add friend</h5>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- NOT FRIEND DROPDOWN -->
          <?php
          $queryNames = "SELECT * from users where userid not in (SELECT u.userid FROM `users` u JOIN `befriends` b ON u.userid=b.friendid WHERE b.userid=" . $_SESSION['user_id'] . ") and userid!=" . $_SESSION['user_id'] . "";
          $resultNames = mysqli_query($mysqli, $queryNames);
          ?>
          <label for="notfriend_names">Select user</label>
          <select class="form-select" aria-label="Default select example" name="notfriend_names">
            <?php
            if ($resultNames->num_rows > 0) {
              while ($row = $resultNames->fetch_assoc()) {
                echo '<option value=' . $row['userid'] . '>' . $row['uname'] . '</option>';
              }
            }
            ?>
          </select>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <input type="submit" class="btn btn-success" name="add_friend" value="Add Friend">
        </div>
      </div>
    </div>
  </div>
</form>

<!-- JavaScript -->
<script>
  $(document).ready(function() {
    // Friend List Search
    $("#friendlistSearchInput").on("keyup", function() {
      var value = $(this).val().toLowerCase();
      $("#friendlistTable tbody tr").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
      });
    });

    // Add to Group Modal
    $(".add-to-group-btn").click(function() {
      var friendId = $(this).data('friendid');
      var groupId = $(this).data('groupid');
      $("#addToGroupModal").find('#groupSelect').val(groupId);
      $("#addToGroupModal").find('#addToGroupBtn').attr('data-friendid', friendId);
    });

    // Add to Group Button
    $("#addToGroupBtn").click(function() {
      var friendId = $(this).data('friendid');
      var groupId = $("#addToGroupModal").find('#groupSelect').val();
      $.ajax({
        type: "POST",
        url: "../backend/add_to_group.php",
        data: {
          friendid: friendId,
          groupid: groupId
        },
        success: function(response) {
          alert(response);
          location.reload();
        }
      });
    });
  });
</script>

<?php include('footer.php'); ?>