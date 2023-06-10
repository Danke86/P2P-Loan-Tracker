<?php include('../config.php'); ?>
<?php 

  if(isset($_POST['groupid'])) {
    $groupid = $_POST['groupid'];

    $sql = mysqli_query($mysqli, "SELECT * FROM is_member_of i NATURAL JOIN groups g NATURAL JOIN users WHERE groupid = $groupid;");
  }?>
  <select class="form-select" name="g_payer_names">
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