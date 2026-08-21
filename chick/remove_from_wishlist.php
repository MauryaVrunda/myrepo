<?php
session_start();
require 'connect.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

$user_id = $_SESSION['user_id'];
$product_id = intval($_GET['id'] ?? 0);

if ($product_id > 0) {
  try {
    $stmt = $conn->prepare("DELETE FROM wishlist WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
  } catch (mysqli_sql_exception $e) {
    error_log("Remove from wishlist failed for user $user_id, product $product_id: " . $e->getMessage());
    $_SESSION['wishlist_error'] = "Could not remove the item from your wishlist. Please try again.";
  }
} else {
  $_SESSION['wishlist_error'] = "No product was selected.";
}

header("Location: wishlist.php");
exit();