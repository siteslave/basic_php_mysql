<?php
session_start();
include('header.php');
include('config.php');

if (isset($_POST['submit'])) {
  $username = $_POST['username'];
  $password = md5($_POST['password']); // md5 encrypted

  $sql = 'SELECT COUNT(*) AS total FROM users WHERE username=:username and password=:password';
  $stmt = $dbh->prepare($sql);
  $stmt->bindParam('username', $username);
  $stmt->bindParam('password', $password);

  try {
    $stmt->execute();
    if ($stmt->fetch(PDO::FETCH_OBJ)->total) {
      $_SESSION['username'] = $_POST['username'];
      header('Location: index.php');
    } else {
      header('Location: login.php?error=Invalid username or password');
    }
  } catch (PDOException $e) {
    $msg = $e->getMessage();
    header('Location: login.php?error=' . $msg);
  }

} else {

?>

<br>
<div class="row">
  <div class="col-md-4 col-md-offset-4">
    <form action="login.php" method="POST">
      <fieldset>
        <legend><i class="glyphicon glyphicon-user"></i> Please login</legend>
        <label>Username</label>
        <input type="text" name="username" class="form-control">
        <label>Password</label>
        <input type="password" name="password" class="form-control">

        <?php if (isset($_GET['error'])) { ?>
        <br>
        <div class="alert alert-danger">
          <strong>Error: </strong> <?=$_GET['error']?>
        </div>
        <?php } ?>
        <br>
        <button type="submit" name="submit" class="btn btn-success btn-lg">
          <i class="glyphicon glyphicon-log-in"></i> Login
        </button>
      </fieldset>
    </form>
  </div>
</div>

<?php } // else ?>
<?php include('footer.php'); ?>

