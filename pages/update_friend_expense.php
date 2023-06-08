<?php include('header.php'); ?>
<?php include('../config.php'); ?>

<?php
    if(isset($_GET['id'])) {
        $expense_id = $_GET['id'];

        $query = "SELECT * from `expenses` where `expenseid` = '$expense_id'";
        $result = mysqli_query($mysqli, $query);

        if (!$result) {
            die("query Failed".mysqli_error());
        } else {
            $row = mysqli_fetch_assoc($result);
        }
    }
                ?>


<?php 
    if(isset($_POST['update_expense_friend'])) {
        if(isset($_GET['id_new'])) {
            $idnew = $_GET['id_new'];
        }
        $expense_name = $_POST['f_e_name'];

        $query = "UPDATE `expenses` SET `expensename`='$expense_name' where `expenseid`='$idnew'";
        $result = mysqli_query($mysqli, $query);

        if (!$result) {
            die("query Failed".mysqli_error());
        } else {
            header('location:dashboard.php?update_f_message=Updated successfully!');
        }
    
    }
?>
<section class="main ">
  <h1 id="main_title">EDIT EXPENSE</h1>
  <form action="update_friend_expense.php?id_new=<?php echo $expense_id?>" method="post">
    <div class="form-group">
        <label for="e_name">Expense name</label>
        <input type="text" name="f_e_name" class="form-control" value="<?php echo $row['expensename']?>">
    </div>
    <input type="submit" class="btn btn-success" name="update_expense_friend" value="Update expense">
  </form>
</section>

<?php include('footer.php'); ?>