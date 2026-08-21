<?php
require 'includes/bootstrap.php';

$user_id = require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $cart_id = intval($_POST['cart_id']);

  $stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
  $stmt->bind_param("ii", $cart_id, $user_id);
  $stmt->execute();
}

redirect_to('cart.php');
?>