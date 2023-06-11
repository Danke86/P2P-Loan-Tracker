<?php include('header.php'); ?>

<section class = "main">
    <h1 id="main_title">RECENT HISTORY</h1>
    <div class="container">
      <h2>Recent Expenses Made With A Friend (Within this Month)</h2>

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
          </tr>
        </thead>
        <tbody>
          <?php

            $query = "SELECT u.userid 'userid', e.userid 'friendid', p.expenseid 'expenseid', expense_type, expensename, date_incurred, original_amount, amount, payerid, groupid
                      FROM user_incurs_expense u
                      JOIN user_incurs_expense e on u.expenseid=e.expenseid and u.userid != e.userid
                      JOIN expenses p on u.expenseid=p.expenseid
                      WHERE u.userid=".$_SESSION['user_id']." AND p.expense_type = 'friend'
                      AND p.date_incurred BETWEEN ADDDATE(NOW(), INTERVAL -30 DAY) AND NOW()
                      ORDER BY p.date_incurred DESC
                      ";
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
                  echo 0.00;
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
                  // echo $curBal;
                  if($curBal > 0){
                    echo "<span class='negative-bal-text'> $curBal </span>";
                  }else{
                    echo "<span> $curBal </span>";
                  }
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
        </tr>
        <?php
              }
            }
          ?>
      </tbody>
    </table>
  </div>
  <div class="container">
      <h2>Recent Expenses Made With A Group (Within this Month)</h2>

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
        </tr>
      </thead>
      <tbody>
        <?php

          $query = "SELECT * 
                    FROM `expenses` e
                    JOIN `user_incurs_expense` u ON e.expenseid = u.expenseid
                    WHERE u.userid = ".$_SESSION['user_id']." AND e.expense_type = 'group'
                    AND e.date_incurred BETWEEN ADDDATE(NOW(), INTERVAL -30 DAY) AND NOW()
                    ORDER BY e.date_incurred DESC
                    ";
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
                echo 0.00;
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