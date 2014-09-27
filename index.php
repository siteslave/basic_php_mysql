<?php
  session_start();

  if (!isset($_SESSION['username'])) header('Location: login.php');

  include('config.php');
  include('header.php');

  if (isset($_GET['query'])) {

    // get query for search
    $query = $_GET['query'];
    $query = '%' . $query . '%';

    $sql = 'SELECT * FROM members WHERE fullname LIKE :query';
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':query', $query);

  } else {
    $sql = 'SELECT * FROM members;';
    $stmt = $dbh->prepare($sql);
  }

  // set default value
  $rs = null;

  try {
    $stmt->execute();
    $rs = $stmt->fetchAll(PDO::FETCH_OBJ); //PDO::FETCH_ASSOC for array
    // close connection
    $dbh = null;
  } catch (PDOException $e) {
    echo 'Error: ' . $e->getMessage();
  }
?>
  <h1><i class="glyphicon glyphicon-th-list"></i> Members list.</h1>
  <div class="row">
    <div class="col-md-2">
      <a href="new.php" class="btn btn-success pull-right">
        <i class="glyphicon glyphicon-plus-sign"></i> Add new
      </a>
    </div>
    <div class="col-md-5">
      <form action="" method="GET">
        <div class="input-group">
          <input type="text" class="form-control" name="query">
          <span class="input-group-btn">
            <button type="submit" class="btn btn-primary">
              <i class="glyphicon glyphicon-search"></i> Search
            </button>
          </span>
        </div>
      </form>
    </div>
  </div>
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Name</th>
        <th>Telephone</th>
        <th>Email</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
    <?php foreach($rs as $r): ?>
      <tr>
        <td><?php echo $r->fullname; ?></td>
        <td><?=$r->email?></td>
        <td><?=$r->telephone?></td>
        <td>
          <a href="edit.php?id=<?=$r->id?>" class="btn btn-success">
            <i class="glyphicon glyphicon-pencil"></i>
          </a>
          <a href="delete.php?id=<?=$r->id?>" onclick="return confirm('Are you sure?');" class="btn btn-danger">
            <i class="glyphicon glyphicon-trash"></i>
          </a>
        </td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
<?php include('footer.php'); ?>