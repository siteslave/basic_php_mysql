<?php

  session_start();

  if (!isset($_SESSION['username'])) header('Location: login.php');

  include('config.php');
  include('header.php');

  if (isset($_POST['submit'])) {

    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];
    $id = $_POST['id'];

    if (empty($fullname) || empty($email) || empty($telephone) || empty($id)) {
      header('Location: edit.php?ok=0&msg=Invalid data&id=' . $id);
    } else {
      $sql = '
      UPDATE members
      SET fullname=:fullname,
      email=:email,
      telephone=:telephone
      WHERE id=:id
      ';

      $stmt = $dbh->prepare($sql);
      $stmt->bindParam(':fullname', $fullname);
      $stmt->bindParam(':email', $email);
      $stmt->bindParam(':telephone', $telephone);
      $stmt->bindParam(':id', $id);

      try {
        $stmt->execute();
        $rs = $stmt->fetch(PDO::FETCH_OBJ);
        $dbh = null;
        header('Location: edit.php?ok=1&id=' . $id);
      } catch (PDOException $e) {
        $error = $e->getMessage();
        header('Location: edit.php?ok=0&msg=' . $error);
      }
    }

  } else {

    $id = $_GET['id'];
    $sql = 'SELECT * FROM members WHERE id=:id';
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':id', $id);

    try {
        $stmt->execute();
        $members = $stmt->fetch(PDO::FETCH_OBJ);
        $dbh = null;
      } catch (PDOException $e) {
        $error = $e->getMessage();
        $rs = null;
        header('Location: edit.php?ok=0&msg=' . $error);
      }
?>

  <div class="row">
    <div class="col-md-6">
      <h1><i class="glyphicon glyphicon-plus-sign"></i> Update member.</h1>
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

      <form action="edit.php" method="POST">
        <input type="hidden" name="id" value="<?=$members->id?>">
        <legend>Member detail</legend>
        <label>Full name:</label>
        <input type="text" name="fullname" class="form-control" value="<?=$members->fullname?>">
        <label>Email:</label>
        <input type="text" name="email" class="form-control" value="<?=$members->email?>">
        <label>Telephone:</label>
        <input type="text" name="telephone" class="form-control" value="<?=$members->telephone?>">
        <br>
        <button type="submit" name="submit" class="btn btn-success bnt-lg">
          <i class="glyphicon glyphicon-floppy-disk"></i> Update
        </button>
        <a href="index.php" class="btn btn-default bnt-lg">
          <i class="glyphicon glyphicon-home"></i> Home
        </a>
      </form>
    </div>
  </div>

<?php include('footer.php'); ?>

<?php } // end form ?>