<?php include('header.php'); ?>

<section class="main">
  <h1 id="main_title">VIEW EXPENSES</h1>
  <div class="container">
    <div class="box1">
    <?php
      if(isset($_GET['update_username'])) {
        echo "<h6 style='color:green;'>".$_GET['update_username']."</h6>";
      }
    ?>
    <h2>Current Balance from all Expenses</h2>
    </div>
    <table class="table table-hover table-bordered table-str">
      <thead>
        <tr>
          <th>Amount you owe</th>
          <th>Amount owed to you</th>    
          <th>Current Balance</th>          
        </tr>
      </thead>
      <tbody>
        <tr>
          <?php 
            // the amount the current user owe
            // the expenses of current user in which they were not the payerid (they did not pay the initial amount) - the amount of payments the current user paid
            $curbalQuery1 = "SELECT COALESCE(sum(original_amount),0)-(SELECT COALESCE(sum(amount),0) from payments WHERE userid=".$_SESSION['user_id'].") 'curbal' from user_incurs_expense natural join expenses where userid = ".$_SESSION['user_id']." and payerid!=".$_SESSION['user_id']."";
            $curbalResult1 = mysqli_query($mysqli, $curbalQuery1);
          ?>
          <td>
            <?php
              $curbal1 = mysqli_fetch_assoc($curbalResult1);
              $curbalnegative = $curbal1['curbal'];
              if($curbalnegative > 0){
                echo "<span class='negative-bal-text'> $curbalnegative </span>";
              }else{
                echo "<span> $curbalnegative </span>";
              }
            ?>
          </td>
          <?php 
            // the amount owed to current user
            // the expenses of current user in which they were the payerid (they paid the initial amount) - the payments of other user (not the current user) where the expenseid are the ones the current user paid
            $curbalQuery2 = "SELECT COALESCE(sum(original_amount),0)-(SELECT COALESCE(sum(amount),0) from payments WHERE userid!=".$_SESSION['user_id']." and expenseid in (SELECT expenseid from user_incurs_expense natural join expenses where userid = ".$_SESSION['user_id']." and payerid=".$_SESSION['user_id']."))  'curbal' from user_incurs_expense natural join expenses where userid = ".$_SESSION['user_id']." and payerid=".$_SESSION['user_id']."";
            $curbalResult2 = mysqli_query($mysqli, $curbalQuery2);
          ?>
          <td>
            <?php
              $curbal2 = mysqli_fetch_assoc($curbalResult2);
              $curbalpositive = $curbal2['curbal'];
              if($curbalpositive > 0){
                echo "<span class='positive-bal-text'> $curbalpositive </span>";
              }else{
                echo "<span> $curbalpositive </span>";
              }
              
            ?>
          </td> 
          <td>
            <?php //get groupname using groupid
              $curbal = (-$curbal1['curbal'])+$curbal2['curbal'];
              if($curbal >= 0){
                echo "<span class='positive-bal-text bold-bal-text'> $curbal </span>";
              }else{
                echo "<span class='negative-bal-text bold-bal-text'> $curbal </span>";
              }
              
            ?>
          </td>  
        </tr>
      </tbody>
    </table>
  </div>
  <div class="container">

      <div class="box1">
        <h2>All Expenses Made With A Friend</h2>
        <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#friendModal">ADD EXPENSE</button>
      </div>
    
      <div>
        <input id="friendSearchInput" type="search" class="form-control rounded" placeholder="Search an expense made with a friend" aria-label="Search" aria-describedby="search-addon" />
      </div>

      <table id="friendTable" class="table table-hover table-bordered table-str">
        <thead>
          <tr>
            <th>Expense ID</th>
            <th>Expense name</th>
            <th>Date incurred</th>
            <th>Original amount</th>
            <th>Amount to be paid</th>
            <th>Friend</th>
            <th>Update</th>
            <th>Delete</th>
            <th>Pay</th>
          </tr>
        </thead>
        <tbody>
          <?php

            $query = "SELECT u.userid 'userid', e.userid 'friendid', p.expenseid 'expenseid', expense_type, expensename, date_incurred, original_amount, amount, payerid, groupid
                      FROM user_incurs_expense u
                      JOIN user_incurs_expense e on u.expenseid=e.expenseid and u.userid != e.userid
                      JOIN expenses p on u.expenseid=p.expenseid
                      WHERE u.userid=".$_SESSION['user_id']." AND p.expense_type = 'friend' order by date_incurred desc";
            $result = mysqli_query($mysqli, $query);

            if (!$result) {
              die("query failed".mysqli_error($mysqli));
            } else {
              while($row = mysqli_fetch_assoc($result)){
                ?>
        <tr data-expense-id="<?php echo $row['expenseid']; ?>">
          <td>
            <?php echo $row['expenseid'] ?>
          </td>
          <td>
            <?php echo $row['expensename'] ?>
          </td>
          <td>
            <?php
            $date = date_create($row['date_incurred']);
            echo date_format($date,"M d, Y h:i a"); ?>
          </td>
          <td>
            <?php echo $row['original_amount'] ?>
          </td>
          <td>
            <?php
                $payer = $row['payerid'];
                if($payer == $_SESSION['user_id']){
                  //change to see remaining to be paid by others
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
                  if($curBal > 0){
                    echo "<span class='positive-bal-text'> $curBal (remaining) </span>";
                  }else{
                    echo "<span> $curBal </span>";
                  }
                  // echo 0.00;
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
                  $curBal = $row['amount'] - $totalPaid['totalpaid'];
                  if($curBal > 0){
                    echo "<span class='negative-bal-text'> $curBal </span>";
                  }else{
                    echo "<span> $curBal </span>";
                  }
                  // echo $curBal;
                }
              ?>
          </td>
          <?php //get friendname using friendid
              $friendid = $row['friendid'];
              $friendName = "SELECT * FROM `users` WHERE `userid` = $friendid";
              $friendResult = mysqli_query($mysqli, $friendName);
            ?>
          <td>
            <?php //name
                      $name = mysqli_fetch_assoc($friendResult);
                      echo $name['uname'];
                    ?>
          </td>
          <td>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#updateModal"
              data-expense-id="<?php echo $row['expenseid']; ?>">Update</button>
          </td>
          <!-- <td><a href="update_friend_expense.php?id=<?php echo $row['expenseid'] ?>" class="btn btn-success">Update</td> -->
          <td><a href="../backend/delete_friend_expense.php?id=<?php echo $row['expenseid'] ?>" class="btn btn-danger">Delete</td>
          <td>
            <button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#payfriendModal"
              data-expense-id="<?php echo $row['expenseid']; ?>">PAY</button>
          </td>
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
    
      // get insert successful message
      if(isset($_GET['insert_msg'])) {
        echo "<h6>".$_GET['insert_msg']."</h6>";
      }
    
      // get update successful message
      if(isset($_GET['update_f_message'])) {
        echo "<h6>".$_GET['update_f_message']."</h6>";
      }
  
      // get delete successful message
      if(isset($_GET['delete_f_message'])) {
        echo "<h6>".$_GET['delete_f_message']."</h6>";
      }
    ?>
  </div>

  <div class="container">
    <div class="box1">
      <h2>All Expenses Made With A Group</h2>
      <button class="btn btn-warning" id="2" data-bs-toggle="modal" data-bs-target="#groupModal">ADD EXPENSE</button>
    </div>

      <div>
        <input id="groupSearchInput" type="search" class="form-control rounded" placeholder="Search an expense made with a group" aria-label="Search" aria-describedby="search-addon" />
      </div>
      
    <table id="groupTable" class="table table-hover table-bordered table-str">
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
          <th>Pay</th>
        </tr>
      </thead>
      <tbody>
        <?php

          $query = "SELECT * 
                    FROM `expenses` e
                    JOIN `user_incurs_expense` u ON e.expenseid = u.expenseid
                    WHERE u.userid = ".$_SESSION['user_id']." AND e.expense_type = 'group'  order by date_incurred desc";
          $result = mysqli_query($mysqli, $query);

          if (!$result) {
            die("query failed".mysqli_error($mysqli));
          } else {
            while($row = mysqli_fetch_assoc($result)){
              ?>
        <tr data-expense-id="<?php echo $row['expenseid']; ?>">
          <td>
            <?php echo $row['expenseid'] ?>
          </td>
          <td>
            <?php echo $row['expensename'] ?>
          </td>
          <td>
            <?php $date = date_create($row['date_incurred']);
            echo date_format($date,"M d, Y h:i a"); ?>
          </td>
          <td>
            <?php echo $row['original_amount'] ?>
          </td>
          <td>
            <?php 
              $payer = $row['payerid'];
              if($payer == $_SESSION['user_id']){
                // echo 0.00;
                $totalPaidQuery = "SELECT COALESCE(SUM(p.amount),0) AS totalpaid
                                    FROM expenses AS e
                                    LEFT JOIN payments p 
                                    ON e.expenseid = p.expenseid 
                                    WHERE e.expenseid = ".$row['expenseid']."
                                    AND p.userid != ".$_SESSION['user_id']."
                                    GROUP BY e.expenseid
                                    ";
                  $resultTotalPaid = mysqli_query($mysqli, $totalPaidQuery);
                  $totalPaid = mysqli_fetch_assoc($resultTotalPaid);
                  $curBal = $row['original_amount'] - ($totalPaid['totalpaid'] ?? 0);
                  if($curBal > 0){
                    echo "<span class='positive-bal-text'> $curBal (remaining) </span>";
                  }else{
                    echo "<span> $curBal </span>";
                  }
              }else{
                $totalPaidQuery = "SELECT COALESCE(SUM(p.amount),0) AS totalpaid
                                    FROM expenses AS e
                                    LEFT JOIN payments p 
                                    ON e.expenseid = p.expenseid 
                                    WHERE e.expenseid = ".$row['expenseid']."
                                    AND p.userid = ".$_SESSION['user_id']."
                                    GROUP BY e.expenseid
                                  ";
                $resultTotalPaid = mysqli_query($mysqli, $totalPaidQuery);
                $totalPaid = mysqli_fetch_assoc($resultTotalPaid);

                $curBal = ($row['amount']) - ($totalPaid['totalpaid'] ?? 0);
                // echo $curBal;
                if($curBal > 0){
                  echo "<span class='negative-bal-text'> $curBal </span>";
                }else{
                  echo "<span> $curBal </span>";
                }
              }
             ?>
          </td>
          <?php 
                    $groupid = $row['groupid'];
                    $groupName = "SELECT * FROM `groups` WHERE `groupid` = $groupid";
                    $groupResult = mysqli_query($mysqli, $groupName);
                  ?>
          <td>
            <?php //get groupname using groupid
                    $name = mysqli_fetch_assoc($groupResult);
                    echo $name['groupname'];
                  ?>
          </td>
          <td><button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#updateModal"
              data-expense-id="<?php echo $row['expenseid']; ?>">Update</button></td>
          <td><a href="../backend/delete_group_expense.php?id=<?php echo $row['expenseid'] ?>" class="btn btn-danger">Delete</td>
          <td><button class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#paygroupModal"
              data-expense-id="<?php echo $row['expenseid']; ?>">PAY</button></td>

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

      // get insert successful message -->
      if(isset($_GET['group_insert_msg'])) {
        echo "<h6>".$_GET['group_insert_msg']."</h6>";
      }

      // get update successful message -->
      if(isset($_GET['update_g_message'])) {
        echo "<h6>".$_GET['update_g_message']."</h6>";
      }

      // get delete successful message -->
      if(isset($_GET['delete_g_message'])) {
        echo "<h6>".$_GET['delete_g_message']."</h6>";
      }
    ?>
  </div>


  <!-- MODALS -->

  <!-- Friend Modal -->
  <form action="../backend/insert_expense_friend.php" method="post">
    <div class="modal fade" id="friendModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
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
            <div class="form-group">
              <label for="e_name">Expense name</label>
              <input type="text" name="e_name" class="form-control">
            </div>

            <div class="form-group">
              <label for="orig_amount">Original amount</label>
              <input type="number" step=".01" name="orig_amount" class="form-control">
            </div>

            <!-- FRIEND DROPDOWN -->
            <?php
              $queryNames = "SELECT u.userid, u.uname FROM `users` u JOIN `befriends` b ON u.userid=b.friendid WHERE b.userid=".$_SESSION['user_id']."";
              $resultNames = mysqli_query($mysqli, $queryNames);
            ?>
            <label for="friend_names">Select friend</label>
            <select class="friend form-select" aria-label="Default select example" name="friend_names">
              <option value="" selected="selected">Select friend</option>
              <?php
                if ($resultNames->num_rows > 0) {
                  while ($row = $resultNames->fetch_assoc()) {
                      echo '<option value='.$row['userid'].'>' . $row['uname'] . '</option>';
                  }
              }
              ?>
            </select>

            <!-- PAYER DROPDOWN -->
            <label for="f_payer_names">Select payer</label>
            <select class="fpayer form-select" name="f_payer_names">
              <option value="" selected="selected">Select payer</option>
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
  <form action="../backend/insert_expense_group.php" method="post">
    <div class="modal fade" id="groupModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
      aria-hidden="true">
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
              <label for="g_e_name">Expense name</label>
              <input type="text" name="g_e_name" class="form-control">
            </div>

            <div class="form-group">
              <label for="orig_amount">Original amount</label>
              <input type="number" step=".01" name="g_orig_amount" class="form-control">
            </div>

            <!-- GROUP DROPDOWN -->
            <?php
                $queryGroup = "SELECT * FROM is_member_of NATURAL JOIN groups WHERE userid = ".$_SESSION['user_id']."";
                $resultGroup = mysqli_query($mysqli, $queryGroup);
              ?>
            <label for="group_names">Select group</label>
            <select class="group form-select" name="group_names">
              <option value="" selected="selected">Select group</option>
              <?php
                  if ($resultGroup->num_rows > 0) {
                    while ($row = $resultGroup->fetch_assoc()) {
                        echo '<option value='.$row['groupid'].'>' . $row['groupname'] . '</option>';
                    }
                  }                  
                ?>
            </select>

            <!-- PAYER DROPDOWN -->
            <label for="g_payer_names">Select payer</label>
            <select class="payer form-select" name="g_payer_names">
              <option value="" selected="selected">Select payer</option>
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

  <!-- update friend expense modal -->
  <form action="../backend/update_expense.php" method="POST">
    <div class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
      aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Update expense</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <input type="hidden" name="expense_id" value="">
          <div class="modal-body">
            <div class="form-group">
              <label for="update_friend_ename">New expense name:</label>
              <input type="text" step=".01" name="update_friend_ename" class="form-control">
            </div>
            </select>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <input type="submit" class="btn btn-success" name="update_expense_friend" value="Update">
          </div>
        </div>
      </div>
    </div>
  </form>

  <!-- pay friend modal -->
  <form action="../backend/pay_friend.php" method="POST">
    <div class="modal fade" id="payfriendModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
      aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Pay friend</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <input type="hidden" name="expense_id_friend" value="">
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

  <!-- pay group modal -->
  <form action="../backend/pay_group.php" method="POST">
    <div class="modal fade" id="paygroupModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
      aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Pay group</h5>
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <input type="hidden" name="expense_id_group" value="">
          <div class="modal-body">
            <div class="form-group">
              <label for="amount_paid_group">Amount to Pay:</label>
              <input type="number" step=".01" name="amount_paid_group" class="form-control">
            </div>

            </select>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <input type="submit" class="btn btn-success" name="pay_expense_group" value="Pay expense">
          </div>
        </div>
      </div>
    </div>
  </form>
</section>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const payButtons = document.querySelectorAll('[data-bs-target="#payfriendModal"]');

    payButtons.forEach(function (button) {
      button.addEventListener('click', function () {
        const expenseId = this.getAttribute('data-expense-id');
        const expenseIdInput = document.querySelector('input[name="expense_id_friend"]');
        expenseIdInput.value = expenseId;
      });
    });
  });

  document.addEventListener('DOMContentLoaded', function () {
    const payButtonsgroup = document.querySelectorAll('[data-bs-target="#paygroupModal"]');

    payButtonsgroup.forEach(function (button) {
      button.addEventListener('click', function () {
        const expenseId = this.getAttribute('data-expense-id');
        const expenseIdInput = document.querySelector('input[name="expense_id_group"]');
        expenseIdInput.value = expenseId;
      });
    });
  });

  // update friend/group expense name using modal
  document.addEventListener('DOMContentLoaded', function () {
    const updateButtons = document.querySelectorAll('[data-bs-target="#updateModal"]');

    updateButtons.forEach(function (button) {
      button.addEventListener('click', function () {
        const expenseId = this.getAttribute('data-expense-id');
        const expenseIdInput = document.querySelector('input[name="expense_id"]');
        expenseIdInput.value = expenseId;
      });
    });
  });

  
</script>

<?php include('footer.php'); ?>