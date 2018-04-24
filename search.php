<?php
require_once __DIR__."/connect.php";

var_dump($_GET['txt']);

 $text = strip_tags(htmlspecialchars($_GET['txt']));

  $getName = $pdo->prepare("SELECT name FROM products WHERE name LIKE concat('%', :name, '%') ");

  $getName->execute(array('name' => $text));

  while ($names = $getName->fetch(PDO:: FETCH_ASSOC)){
      echo '<div>' .$names["name"]. '</div>';
  };
  ?>