<?php
  require 'database.php';
  $stmt = $conn->prepare('DELETE FROM items WHERE item = :item LIMIT 1');
  $stmt->bindParam(':item',$_GET['id']);
  $stmt->execute();

  header("Location: admin-client.php");
?>
