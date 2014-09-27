<?php
session_start();

if (!isset($_SESSION['username'])) header('Location: login.php');

include('header.php');
include('config.php');

if (isset($_POST['submit'])) {
  $username = $_SESSION['username'];
  $oldpass = md5($_POST['oldpass']);
  $newpass = md5($_POST['newpass']); // md5 encrypted

  $sql = 'SELECT COUNT(*) AS total FROM users WHERE username=:username and password=:password';
  $stmt = $dbh->prepare($sql);
  $stmt->bindParam('username', $username);
  $stmt->bindParam('password', $oldpass);

  try {
    $stmt->execute();
    if ($stmt->fetch(PDO::FETCH_OBJ)->total) {
      // change pass
      $sql2 = "UPDATE users SET password=:password WHERE username=:username";
      $stmt2 = $dbh->prepare($sql2);
      $stmt2->bindParam('username', $username);
      $stmt2->bindParam('password', $newpass);

      $stmt2->execute();

      header('Location: index.php');

    } else {
      header('Location: changepass.php?error=Invalid old password');
    }
  } catch (PDOException $e) {
    $msg = $e->getMessage();
    header('Location: changepass.php?error=' . $msg);
  }

} else {

?>

<br>
<div class="row">
  <div class="col-md-4 col-md-offset-4">
    <form action="changepass.php" method="POST">
      <fieldset>
        <legend><i class="glyphicon glyphicon-user"></i> Change password</legend>
        <label>Old Password</label>
        <input type="password" name="oldpass" class="form-control">
        <label>New Password</label>
        <input type="password" name="newpass" class="form-control">

        <?php if (isset($_GET['error'])) { ?>
        <br>
        <div class="alert alert-danger">
          <strong>Error: </strong> <?=$_GET['error']?>
        </div>
        <?php } ?>
        <br>
        <button type="submit" name="submit" class="btn btn-success btn-lg">
          <i class="glyphicon glyphicon-floppy-disk"></i> Change password
        </button>
      </fieldset>
    </form>
  </div>
</div>

<?php } // else ?>
<?php include('footer.php'); ?>

