<?php include('header.php'); ?>

<section class="main">
  <h1 id="main_title">FRIENDS</h1>
  <div class="container">
    <div class="box1">
    <h2>Current Balance from all Friend Expenses</h2>
    </div>
    <table id="obTable" class="table table-hover table-bordered table-str">
      <thead>
        <tr>
          <th>Friend</th>
          <th>Amount you owe</th>
          <th>Amount owed to you</th>    
          <th>Outstanding Balance</th>          
        </tr>
      </thead>
      <tbody>
        <?php 
          // get friend name
          // ".$_SESSION['user_id']."
          $friends = "SELECT u.userid, u.uname from befriends b join users u on b.friendid=u.userid where b.userid=".$_SESSION['user_id']."";
          $friendsR = mysqli_query($mysqli, $friends);
          while($row = mysqli_fetch_assoc($friendsR)){
        ?>
          <tr>  
            <td>
              <?php echo $row['uname'] ?>
            </td>
            <?php 
              $userid = $_SESSION['user_id'];
              $friendid = $row['userid'];
              // first select the expenses of current user
              // from there, get the expenses current user had with friend (where current user is the payerid) 
              // get the sum amount of expenses
              // $friend_expenseQ = "SELECT COALESCE(sum(amount),0) 'total' from user_incurs_expense natural join expenses where expenseid in (SELECT expenseid from user_incurs_expense natural join expenses where userid = ".$_SESSION['user_id']." and payerid=".$_SESSION['user_id']." and expense_type='friend') and userid=".$row['userid']."";
              $friend_expenseQ = "SELECT COALESCE(SUM(e.amount),0) 'total' FROM user_incurs_expense u JOIN expenses e ON u.expenseid = e.expenseid WHERE payerid = $userid AND u.userid = $friendid AND e.expense_type = 'friend'" ;
              $friend_expenseR = mysqli_query($mysqli, $friend_expenseQ);
              $friend_expense = mysqli_fetch_assoc($friend_expenseR);
              
              // first select the expenses of current user
              // from there, get the expenses current user had with friend (where current user is the payerid, because it means friendid is the one needed to pay) 
              // get the sum amount of payments by friend in those expenses they made tgt
              // $friend_paymentQ = "SELECT COALESCE(sum(amount),0) 'total' from payments where expenseid in (SELECT expenseid from user_incurs_expense natural join expenses where expenseid in (SELECT expenseid from user_incurs_expense natural join expenses where userid = ".$_SESSION['user_id']." and payerid=".$_SESSION['user_id']." and expense_type='friend') and userid=".$row['userid'].") and userid=".$row['userid']."";
              $friend_paymentQ = "SELECT COALESCE(SUM(p.amount),0) 'total' FROM payments p JOIN expenses e ON p.expenseid = e.expenseid WHERE e.payerid = $userid AND p.userid = $friendid AND e.expense_type = 'friend'";
              $friend_paymentR = mysqli_query($mysqli, $friend_paymentQ);
              $friend_payment = mysqli_fetch_assoc($friend_paymentR);

              // $friend = $friend_expense['amount'] - $friend_payment['amount'];
              $friend = $friend_expense['total'] - $friend_payment['total'];
            ?>
            
            <?php 
              // first select the expenses of current user
              // from there, get the expenses current user had with friend (where friend is the payerid) 
              // get the sum amount of expenses
              // $user_expenseQ = "SELECT COALESCE(sum(amount),0) 'total' from user_incurs_expense natural join expenses where expenseid in (SELECT expenseid from user_incurs_expense natural join expenses where userid = ".$row['userid']." and payerid=".$row['userid']." and expense_type='friend') and userid=".$_SESSION['user_id']."";
              $user_expenseQ = "SELECT COALESCE(SUM(e.amount),0) 'total' FROM user_incurs_expense u JOIN expenses e ON u.expenseid = e.expenseid WHERE payerid = $friendid AND u.userid = $userid AND e.expense_type = 'friend'";
              $user_expenseR = mysqli_query($mysqli, $user_expenseQ);
              $user_expense = mysqli_fetch_assoc($user_expenseR);

              // first select the expenses of current user
              // from there, get the expenses current user had with friend (where friend is the payerid, current user is the one who needed to pay) 
              // get the sum amount of payments by current user in those expenses they made tgt
              // $user_paymentQ = "SELECT COALESCE(sum(amount),0) 'total' from payments where expenseid in 
              // (SELECT expenseid from user_incurs_expense natural join expenses where expenseid in 
              // (SELECT expenseid from user_incurs_expense natural join expenses where userid = ".$row['userid']." and payerid=".$row['userid']." 
              // and expense_type='friend') and userid=".$_SESSION['user_id'].") and userid=".$_SESSION['user_id']."";
              $user_paymentQ = "SELECT COALESCE(SUM(p.amount),0) 'total' FROM payments p JOIN expenses e ON p.expenseid = e.expenseid WHERE e.payerid = $friendid AND p.userid = $userid AND e.expense_type = 'friend'";
              $user_paymentR = mysqli_query($mysqli, $user_paymentQ);
              $user_payment = mysqli_fetch_assoc($user_paymentR);

              // $user = $user_expense['amount'] - $user_payment['amount'];
              $user = $user_expense['total'] - $user_payment['total'];
            ?>
            <td>
              <?php 
              if ($user> 0){
                echo "<span class='negative-bal-text'> $user </span>";
              } else{
                echo "<span> $user </span>";
              }
              ?>
            </td>
            <td>
              <?php 
                if($friend > 0){
                  echo "<span class='positive-bal-text'> $friend </span>";
                }else{
                  echo "<span> $friend </span>";
                }
              
              ?>
              
            </td>
            <td>
              <?php
                $outbalance = $friend - $user;
                if($outbalance > 0){
                  echo "<span class='positive-bal-text'> $outbalance </span>";
                }else if($outbalance < 0){
                  echo "<span class='negative-bal-text'> $outbalance </span>";
                }else{
                  echo "<span> $outbalance </span>";
                }
              ?>
            </td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
  <div class="container">
    <div class="box1">
      <h2>List of Friends</h2>
      <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#addfriendModal">ADD A FRIEND</button>
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

            // $userID= $_SESSION['user_id'];
            // $friendID = $row['userid'];
            
            echo "<td>";
            echo "<form method='post' action='../backend/remove_friend.php'>";
            echo "<input type='hidden' name='friendid' value='" . $row['userid'] . "' />";
            echo "<button type='button' class='btn btn-success add-to-group-btn' data-friendid='" . $row['userid'] . "' data-bs-toggle='modal' data-bs-target='#addToGroupModal-" . $row['userid'] . "'>Add to Group</button>";
            echo "<button type='submit' class='btn btn-danger'>Unfriend</button>";
            echo "</form>";
            echo "</td>";
            echo "</tr>";

            // Add modal for each friend
            echo "<div class='modal fade' id='addToGroupModal-" . $row['userid'] . "' tabindex='-1' role='dialog' aria-labelledby='exampleModalLabel' aria-hidden='true'>";
            echo "<div class='modal-dialog' role='document'>";
            echo "<div class='modal-content'>";
            echo "<div class='modal-header'>";
            echo "<h5 class='modal-title' id='exampleModalLabel'>Add Friend to Group</h5>";
            echo "<button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>";
            echo "</div>";
            echo "<div class='modal-body'>";
            echo "<form method='post' action='../backend/add_to_group.php'>";
            echo "<input type='hidden' name='friendid' value='" . $row['userid'] . "' />";
            echo "<label for='groupSelect'>Select Group</label>";
            echo "<select class='form-select' id='groupSelect' name='groupid'>";
            
            $friendId = $row['userid'];
            $queryGroups = "SELECT g.groupid, g.groupname
                            FROM groups g
                            LEFT JOIN is_member_of m ON g.groupid = m.groupid AND m.userid = '$friendId'
                            WHERE m.userid IS NULL";

            $resultGroups = mysqli_query($mysqli, $queryGroups);

            if (!$resultGroups) {
              die("Query failed: " . mysqli_error($mysqli));
            } else {
              while ($groupRow = mysqli_fetch_assoc($resultGroups)) {
                echo "<option value='" . $groupRow['groupid'] . "'>" . $groupRow['groupname'] . "</option>";
              }
            }

            echo "</select>";
            echo "</div>";
            echo "<div class='modal-footer'>";
            echo "<button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Close</button>";
            echo "<button type='submit' class='btn btn-primary'>Add</button>";
            echo "</form>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
            echo "</div>";
          }
        }
        ?>
      </tbody>
    </table>

    <!-- get remove friend message -->
    <?php
      if(isset($_GET['remove_friend'])) {
        echo "<h6>".$_GET['remove_friend']."</h6>";
      }
    
      if(isset($_GET['friend_message'])) {
        echo "<h6>".$_GET['friend_message']."</h6>";
      }
      
      if(isset($_GET['add_friend_group'])) {
        echo "<h6>".$_GET['add_friend_group']."</h6>";
      }
    ?>

  </div>
</section>

<!-- Add a Friend Modal -->
<form action="../backend/add_friend.php" method="post" id="id_form">
  <div class="modal fade" id="addfriendModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add Friend</h5>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <label for="notfriend_names" class="form-label">Select User</label>
          <input class="form-control" name="notfriend_names" list="datalistOptions" id="exampleDataList" placeholder="Type to search...">
          <datalist id="datalistOptions">
            <?php
            $queryNames = "SELECT * FROM users WHERE userid NOT IN (SELECT u.userid FROM users u JOIN befriends b ON u.userid = b.friendid WHERE b.userid = " . $_SESSION['user_id'] . ") AND userid != " . $_SESSION['user_id'];
            $resultNames = mysqli_query($mysqli, $queryNames);

            if ($resultNames->num_rows > 0) {
              while ($row = $resultNames->fetch_assoc()) { ?>
            <option id="<?php echo $row['userid'] ?>" value="<?php echo $row['uname'] ?>">
            <?php  }
            }
            ?>
          </datalist>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <input type="submit" class="btn btn-success" name="add_friend" value="Add Friend">
        </div>
      </div>
    </div>
  </div>
</form>

<!-- JavaScript
<script>
  $(document).ready(function() {
    // Friend List Search
    $("#friendlistSearchInput").on("keyup", function() {
      var value = $(this).val().toLowerCase();
      $("#friendlistTable tbody tr").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
      });
    });
  });
</script> -->

<?php include('footer.php'); ?>