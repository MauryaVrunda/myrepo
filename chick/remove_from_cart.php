<?php
session_start();
require 'connect.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $cart_id = intval($_POST['cart_id'] ?? 0);
  $user_id = $_SESSION['user_id'];

  try {
    $stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $cart_id, $user_id);
    $stmt->execute();

    if ($stmt->affected_rows === 0) {
      $_SESSION['cart_error'] = "That item is no longer in your cart.";
    }
  } catch (mysqli_sql_exception $e) {
    error_log("Remove from cart failed for user $user_id, cart item $cart_id: " . $e->getMessage());
    $_SESSION['cart_error'] = "Could not remove the item from your cart. Please try again.";
  }
}

header("Location: cart.php");
exit();
?>