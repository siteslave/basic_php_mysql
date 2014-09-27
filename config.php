<?php

  $host = '127.0.0.1';
  $port = 3306;
  $username = 'root';
  $password = '789124';
  $database = 'imember';

  $dbh = new PDO('mysql:host=' . $host . ';port=' . $port . ';dbname=' . $database . ';charset=utf8', $username, $password, array( PDO::ATTR_PERSISTENT => false));

?>