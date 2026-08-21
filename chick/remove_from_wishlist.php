<?php
require 'includes/bootstrap.php';

$user_id = require_login();
$product_id = intval($_GET['id'] ?? 0);

if ($product_id > 0) {
  $stmt = $conn->prepare("DELETE FROM wishlist WHERE user_id = ? AND product_id = ?");
  $stmt->bind_param("ii", $user_id, $product_id);
  $stmt->execute();
}

redirect_to('wishlist.php');