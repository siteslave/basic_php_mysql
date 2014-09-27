<?php

include('config.php');

if (isset($_GET['id'])) {

  $id = $_GET['id'];

  $sql = 'DELETE FROM members WHERE id=:id';
  $stmt = $dbh->prepare($sql);
  $stmt->bindParam(':id', $id);

  try {
    $stmt->execute();
    $dbh = null;
    header('Location: index.php');
  } catch (PDOException $e) {
    echo $e->getMessage();
    die();
  }

} else {
  echo 'ID empty?';
  die();
}
