<?php include('header.php'); ?>
<?php include('../config.php'); ?>

<section class="main">
  <h1 id="main_title">VIEW EXPENSES</h1>
    <div class="container">

      <div class="box1">
        <h2>All Expenses Made With A Friend</h2>
        <!-- <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#payfriendModal">PAY FRIEND</button> -->
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#friendModal">ADD EXPENSE</button>
      </div>
      <table class="table table-hover table-bordered table-str">
        <thead>
          <tr>
            <th>Expense ID</th>
            <th>Expense name</th>
            <th>Date incurred</th>
            <th>Original amount</th>
            <th>Amount to be paid</th>
            <th>Friend</th>
<<<<<<< HEAD
            <th>Update</th>
            <th>Delete</th>
=======
            <th>Operations</th>
>>>>>>> 9c8ebcf768688b3983219ddc8ea000fb1464a9ee
          </tr>
        </thead>
        <tbody>
          <?php

            $query = "SELECT * FROM `expenses` NATURAL JOIN `user_incurs_expense` NATURAL JOIN `befriends` WHERE `userid` = ".$_SESSION['user_id']."";
            $result = mysqli_query($mysqli, $query);

            if (!$result) {
              die("query failed".mysqli_error());
            } else {
              while($row = mysqli_fetch_assoc($result)){
                ?>
                  <tr data-expense-id="<?php echo $row['expenseid']; ?>">
                    <td><?php echo $row['expenseid'] ?></td>
                    <td><?php echo $row['expensename'] ?></td>
                    <td><?php echo $row['date_incurred'] ?></td>
                    <td><?php echo $row['original_amount'] ?></td>
                    <td>
                      <?php
                        // $actualamount = $row['amount']/2;
                        // echo $actualamount;
                        $payer = $row['payerid'];
                        if($payer == $_SESSION['user_id']){
                          echo 0;
                        }else{
                          $totalPaidQuery = "SELECT COALESCE(SUM(p.amount),0) AS totalpaid
                                              FROM expenses AS e
                                              LEFT JOIN payments p 
                                              ON e.expenseid = p.expenseid 
                                              WHERE e.expenseid = ".$row['expenseid']."
                                              GROUP BY e.expenseid
                                            ";
                          $resultTotalPaid = mysqli_query($mysqli, $totalPaidQuery);
                          $totalPaid = mysqli_fetch_assoc($resultTotalPaid);
                          $curBal = $row['original_amount'] - $totalPaid['totalpaid'];
                          echo $curBal;
                        }
                      ?>
                      </td>
                    <?php //get friendname using friendid
                      $friendid = $row['friendid'];
                      $friendName = "SELECT * FROM `users` WHERE `userid` = $friendid";
                      $friendResult = mysqli_query($mysqli, $friendName);
                    ?>
                    <td><?php //name
                      $name = mysqli_fetch_assoc($friendResult);
                      echo $name['uname'];
                    ?></td>
<<<<<<< HEAD
                    <td><a href="update_friend_expense.php?id=<?php echo $row['expenseid'] ?>" class="btn btn-success">Update</td>
                    <td><a href="delete_friend_expense.php?id=<?php echo $row['expenseid'] ?>" class="btn btn-danger">Delete</td>
=======
                    <td>
                      <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#payfriendModal" data-expense-id="<?php echo $row['expenseid']; ?>">PAY</button>
                    </td>
>>>>>>> 9c8ebcf768688b3983219ddc8ea000fb1464a9ee
                  </tr>
                <?php
              }
            }
          ?>
        </tbody>
    </table>
    
    <!-- get form validation message -->
    <?php
      if(isset($_GET['friend_message'])) {
        echo "<h6>".$_GET['friend_message']."</h6>";
      }
    ?>

    <!-- get insert successful message -->
    <?php
      if(isset($_GET['insert_msg'])) {
        echo "<h6>".$_GET['insert_msg']."</h6>";
      }
    ?>

    <!-- get updat successful message -->
    <?php
      if(isset($_GET['update_f_message'])) {
        echo "<h6>".$_GET['update_f_message']."</h6>";
      }
    ?>

    <!-- get delete successful message -->
    <?php
      if(isset($_GET['delete_f_message'])) {
        echo "<h6>".$_GET['delete_f_message']."</h6>";
      }
    ?>
  </div>

  <div class="container">
      <div class="box1">
        <h2>All Expenses Made With A Group</h2>
        <button class="btn btn-primary" id="2" data-bs-toggle="modal" data-bs-target="#groupModal">ADD EXPENSE</button>
      </div>
      <table class="table table-hover table-bordered table-str">
        <thead>
          <tr>
            <th>Expense ID</th>
            <th>Expense name</th>
            <th>Date incurred</th>
            <th>Original amount</th>
            <th>Amount to be paid</th>
            <th>Group</th>
            <th>Update</th>
            <th>Delete</th>
          </tr>
        </thead>
        <tbody>
        <?php

          $query = "SELECT * FROM `expenses` NATURAL JOIN `is_member_of` WHERE `userid` = ".$_SESSION['user_id']."";
          $result = mysqli_query($mysqli, $query);

          if (!$result) {
            die("query failed".mysqli_error());
          } else {
            while($row = mysqli_fetch_assoc($result)){
              ?>
                <tr>
                  <td><?php echo $row['expenseid'] ?></td>
                  <td><?php echo $row['expensename'] ?></td>
                  <td><?php echo $row['date_incurred'] ?></td>
                  <td><?php echo $row['original_amount'] ?></td>
                  <td><?php echo $row['amount'] ?></td>
                  <?php 
                    $groupid = $row['groupid'];
                    $groupName = "SELECT * FROM `groups` WHERE `groupid` = $groupid";
                    $groupResult = mysqli_query($mysqli, $groupName);
                  ?>
                  <td><?php //get groupname using groupid
                    $name = mysqli_fetch_assoc($groupResult);
                    echo $name['groupname'];
                  ?></td>
                  <td><a href="update_group_expense.php?id=<?php echo $row['expenseid'] ?>" class="btn btn-success">Update</td>
                  <td><a href="delete_group_expense.php?id=<?php echo $row['expenseid'] ?>" class="btn btn-danger">Delete</td>
                </tr>
              <?php
            }
          }
          ?>
        </tbody>
    </table>

    <!-- get form validation message -->
    <?php
      if(isset($_GET['group_message'])) {
        echo "<h6>".$_GET['group_message']."</h6>";
      }
    ?>

    <!-- get insert successful message -->
    <?php
      if(isset($_GET['group_insert_msg'])) {
        echo "<h6>".$_GET['group_insert_msg']."</h6>";
      }
    ?>

    <!-- get update successful message -->
    <?php
      if(isset($_GET['update_g_message'])) {
        echo "<h6>".$_GET['update_g_message']."</h6>";
      }
    ?>

    <!-- get delete successful message -->
    <?php
      if(isset($_GET['delete_g_message'])) {
        echo "<h6>".$_GET['delete_g_message']."</h6>";
      }
    ?>
  </div>

  <!-- Friend Modal -->
  <form action="insert_expense_friend.php" method="post">
  <div class="modal fade" id="friendModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add expense with a friend</h5>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div class="form-group">
              <label for="e_name">Expense name</label>
              <input type="text" name="e_name" class="form-control">
            </div>

            <div class="form-group">
              <label for="orig_amount">Original amount</label>
              <input type="number" step=".01" name="orig_amount" class="form-control">
            </div>

            <!-- PAYER DROPDOWN -->
            <?php
              $queryNames = "SELECT u.userid, u.uname FROM `users` u JOIN `befriends` b ON u.userid=b.friendid WHERE b.userid=".$_SESSION['user_id']."";
              $resultNames = mysqli_query($mysqli, $queryNames);
            ?>

            <label for="f_payer_names">Select payer</label>
            <select class="form-select" aria-label="Default select example" name="f_payer_names">
              <?php
                $queryUsername = "SELECT * FROM `users` WHERE userid=".$_SESSION['user_id']."";
                $resultName = mysqli_query($mysqli, $queryUsername);
                
                //get username of current userid
                $username = mysqli_fetch_assoc($resultName);
                if ($resultNames->num_rows > 0) {
                  echo '<option value='.$username['userid'].'>' .$username['uname'] . '</option>';
                  while ($row = $resultNames->fetch_assoc()) {
                      echo '<option value='.$row['userid'].'>' . $row['uname'] . '</option>';
                  }
              }
              ?>
            </select>
            
            <!-- FRIEND DROPDOWN -->
            <?php
              $queryNames = "SELECT u.userid, u.uname FROM `users` u JOIN `befriends` b ON u.userid=b.friendid WHERE b.userid=".$_SESSION['user_id']."";
              $resultNames = mysqli_query($mysqli, $queryNames);
            ?>
            <label for="friend_names">Select friend</label>
            <select class="form-select" aria-label="Default select example" name="friend_names">
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
          <input type="submit" class="btn btn-success" name="add_expense_friend" value="Add expense">
        </div>
      </div>
    </div>
  </div>
  </form>

  <!-- Group Modal -->
  <form action="insert_expense_group.php" method="post">
    <div class="modal fade" id="groupModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Add expense with a group</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
              <div class="form-group">
                <label for="e_name">Expense name</label>
                <input type="text" name="g_e_name" class="form-control">
              </div>

            <div class="form-group">
              <label for="orig_amount">Original amount</label>
              <input type="number" step=".01" name="g_orig_amount" class="form-control">
            </div>

              <!-- PAYER DROPDOWN -->
              <?php
                $queryNames = "SELECT u.userid, u.uname FROM `users` u JOIN `befriends` b ON u.userid=b.friendid WHERE b.userid=".$_SESSION['user_id']."";
                $resultNames = mysqli_query($mysqli, $queryNames);
              ?>

              <label for="g_payer_names">Select payer</label>
              <select class="form-select" name="g_payer_names">
                <?php
                  $queryUsername = "SELECT * FROM `users` WHERE userid=".$_SESSION['user_id']."";
                  $resultName = mysqli_query($mysqli, $queryUsername);
                  
                  //get username of current userid
                  $username = mysqli_fetch_assoc($resultName);
                  if ($resultNames->num_rows > 0) {
                    echo '<option value='.$username['userid'].'>' .$username['uname'] . '</option>';
                    while ($row = $resultNames->fetch_assoc()) {
                        echo '<option value='.$row['userid'].'>' .$row['uname']. '</option>';
                    }
                }
                ?>
              </select>

              <!-- GROUP DROPDOWN -->
              <?php
                $queryGroup = "SELECT * FROM is_member_of NATURAL JOIN groups WHERE userid = ".$_SESSION['user_id']."";
                $resultGroup = mysqli_query($mysqli, $queryGroup);
              ?>
              <label for="group_names">Select group</label>
              <select class="form-select" aria-label="Default select example" name="group_names">
                <?php
                  if ($resultGroup->num_rows > 0) {
                    while ($row = $resultGroup->fetch_assoc()) {
                        echo '<option value='.$row['groupid'].'>' . $row['groupname'] . '</option>';
                    }
                  }
                  
                ?>
              </select>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <input type="submit" class="btn btn-success" name="add_expense_group" value="Add expense">
          </div>

        </div>
      </div> 
    </div>
  </form>

  <!-- pay friend modal -->
  <form action="../backend/pay_friend.php" method="POST">
  <div class="modal fade" id="payfriendModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Pay friend</h5>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
          <input type="hidden" name="expense_id" value="">
          <div class="modal-body">
            <div class="form-group">
              <label for="amount_paid_friend">Amount to Pay:</label>
              <input type="number" step=".01" name="amount_paid_friend" class="form-control">
              </div>

            </select>
          
          </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <input type="submit" class="btn btn-success" name="pay_expense_friend" value="Pay expense">
        </div>
      </div>
    </div>
  </div>
  </form>



  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Data</h4>
            </div>
            <div class="modal-body">
                <div class="fetched-data"></div> //Here Will show the Data
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
</section>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const payButtons = document.querySelectorAll('[data-bs-target="#payfriendModal"]');

    payButtons.forEach(function (button) {
      button.addEventListener('click', function () {
        const expenseId = this.getAttribute('data-expense-id');
        const expenseIdInput = document.querySelector('input[name="expense_id"]');
        expenseIdInput.value = expenseId;
      });
    });
  });
</script>

<?php include('footer.php'); ?>