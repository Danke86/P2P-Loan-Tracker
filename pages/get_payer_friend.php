<?php include('../config.php'); ?>
<?php include('login_checker.php'); ?>
<?php 

  if(isset($_POST['friendid'])) {
    $friendid = $_POST['friendid'];

    $sql = mysqli_query($mysqli, "SELECT * FROM users WHERE userid IN (".$_SESSION['user_id'].", $friendid)");
  }?>
  <select class="form-select" name="f_payer_names">
    <option value="" >Select payer</option>
    <?php
    while ($row = mysqli_fetch_array($sql)) {
      ?>
      <option value="<?php echo $row['userid'];?>"><?php echo $row['uname'];?></option>
      <?php
    }
    ?>
  </select>
  <?php
?>