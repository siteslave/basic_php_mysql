<?php

  session_start();

  if (!isset($_SESSION['username'])) header('Location: login.php');

  include('header.php');
  include('config.php');

  if (isset($_POST['submit'])) {

    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];

    if (empty($fullname) || empty($email) || empty($telephone)) {
      header('Location: new.php?ok=0&msg=Invalid data');
    } else {
      $sql = '
      INSERT INTO members
      SET fullname=:fullname,
      email=:email,
      telephone=:telephone
      ';

      $stmt = $dbh->prepare($sql);
      $stmt->bindParam(':fullname', $fullname);
      $stmt->bindParam(':email', $email);
      $stmt->bindParam(':telephone', $telephone);

      try {
        $stmt->execute();
        $dbh = null;
        header('Location: new.php?ok=1');
      } catch (PDOException $e) {
        $error = $e->getMessage();
        header('Location: new.php?ok=0&msg=' . $error);
      }
    }

  } else {
?>

  <div class="row">
    <div class="col-md-6">
      <h1><i class="glyphicon glyphicon-plus-sign"></i> Add new member.</h1>
      <?php
        if (isset($_GET['ok'])) {
          if ($_GET['ok'] == '1') { // success
      ?>
      <div class="alert alert-success">
        Success
      </div>
      <?php } else { // error ?>
      <div class="alert alert-danger">
        Error <?=$_GET['msg']?>
      </div>
      <?php }} ?>

      <form action="new.php" method="POST">
        <legend>Member detail</legend>
        <label>Full name:</label>
        <input type="text" name="fullname" class="form-control">
        <label>Email:</label>
        <input type="text" name="email" class="form-control">
        <label>Telephone:</label>
        <input type="text" name="telephone" class="form-control">
        <br>
        <button type="submit" name="submit" class="btn btn-success bnt-lg">
          <i class="glyphicon glyphicon-floppy-disk"></i> Save
        </button>
        <a href="index.php" class="btn btn-default bnt-lg">
          <i class="glyphicon glyphicon-home"></i> Home
        </a>
      </form>
    </div>
  </div>
<?php include('footer.php'); ?>

<?php } // end form ?>