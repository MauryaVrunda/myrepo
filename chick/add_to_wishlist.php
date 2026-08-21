<?php
require 'includes/bootstrap.php';

$user_id = require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $product_id = intval($_POST['product_id']);

  // Check if already in wishlist
  $check = $conn->prepare("SELECT id FROM wishlist WHERE user_id = ? AND product_id = ?");
  $check->bind_param("ii", $user_id, $product_id);
  $check->execute();
  $check->store_result();

  if ($check->num_rows === 0) {
    $insert = $conn->prepare("INSERT INTO wishlist (user_id, product_id) VALUES (?, ?)");
    $insert->bind_param("ii", $user_id, $product_id);
    $insert->execute();
  }

  redirect_to('added_to_wishlist.php');
}
?>