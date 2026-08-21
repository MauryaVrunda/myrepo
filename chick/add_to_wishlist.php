<?php
session_start();
require 'connect.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $product_id = intval($_POST['product_id']);

  try {
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
  } catch (mysqli_sql_exception $e) {
    error_log("Add to wishlist failed for user $user_id, product $product_id: " . $e->getMessage());
    $_SESSION['wishlist_error'] = "Could not add the item to your wishlist. Please try again.";
    header("Location: wishlist.php");
    exit();
  }

  header("Location: added_to_wishlist.php");
  exit();
}
?>