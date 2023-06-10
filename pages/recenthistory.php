<?php include('header.php'); ?>

<section class = "main">
    <h1 id="main_title">RECENT HISTORY</h1>
    <div class="container">
      <h2>Recent Expenses Made With A Friend (Within this month)</h2>

      <div>
        <input id="friendSearchInput" type="search" class="form-control rounded" placeholder="Search an expense made with a friend" aria-label="Search" aria-describedby="search-addon" />
      </div>
      <table id="friendTable" class="table table-hover table-bordered table-str">
        <thead>
          <tr>
            <th>Expense ID</th>
            <th>Expense name</th>
            <th>Date incurred</th>
            <th>Original Amount</th>
            <th>Amount to be paid</th>
            <th>Friend</th>
          </tr>
        </thead>
        <tbody>
          <?php

            $query = "SELECT u.userid 'userid', e.userid 'friendid', p.expenseid 'expenseid', expense_type, expensename, date_incurred, original_amount, amount, payerid, groupid
                      FROM user_incurs_expense u
                      JOIN user_incurs_expense e on u.expenseid=e.expenseid and u.userid != e.userid
                      JOIN expenses p on u.expenseid=p.expenseid
                      WHERE u.userid=".$_SESSION['user_id']." AND MONTH(`date_incurred`) = MONTH(curdate()) AND expense_type = 'friend' "; //change 001 to $_SESSION[userid]
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
                      $friendid = $row['friendid'];
                      $friendName = "SELECT * FROM `users` WHERE `userid` = $friendid";
                      $friendResult = mysqli_query($mysqli, $friendName);
                    ?>
                    <td><?php 
                    $name = mysqli_fetch_assoc($friendResult);
                    echo $name['uname'];
                    ?></td>
                  </tr>
                <?php
              }
            }
          ?>
        </tbody>
    </table>
  </div>
  <div class="container">
      <h2>Recent Expenses Made With A Group</h2>

      <div>
        <input id="groupSearchInput" type="search" class="form-control rounded" placeholder="Search an expense made with a group" aria-label="Search" aria-describedby="search-addon" />
      </div>
      <table id="groupTable" class="table table-hover table-bordered table-str">
        <thead>
          <tr>
            <th>Expense ID</th>
            <th>Expense name</th>
            <th>Date incurred</th>
            <th>Original Amount</th>
            <th>Amount to be paid</th>
            <th>Group</th>
          </tr>
        </thead>
        <tbody>
          <?php

            $query = "SELECT * FROM `expenses` NATURAL JOIN `is_member_of` WHERE `userid` = ".$_SESSION['user_id']." AND `groupid` IS NOT NULL AND expense_type = 'group' "; //change 001 to $_SESSION[userid]
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
                      $groupId = $row['groupid'];
                      $groupTable = "SELECT * FROM `groups` WHERE `groupid` = $groupId";
                      $groupResult = mysqli_query($mysqli, $groupTable);
                    ?>
                    <td><?php 
                    $name = mysqli_fetch_assoc($groupResult);
                    echo $name['groupname'];
                    ?></td>
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