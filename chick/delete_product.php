<?php
session_start();
require 'connect.php';

// ✅ Ensure only admin can access
if (!isset($_SESSION['user_email']) || $_SESSION['user_email'] !== 'admin@gmail.com') {
    header("Location: login.php");
    exit();
}

// ✅ Ensure a product ID is passed
if (!isset($_GET['id'])) {
    echo "❌ Product ID not provided.";
    exit();
}

$product_id = intval($_GET['id']);

// ✅ Get the image filename to delete later
$stmt = $conn->prepare("SELECT image FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$stmt->bind_result($imageName);
$stmt->fetch();
$stmt->close();

// ✅ Delete product from database
$delete = $conn->prepare("DELETE FROM products WHERE id = ?");
$delete->bind_param("i", $product_id);

if ($delete->execute()) {
    // ✅ Delete image file (optional)
    $imagePath = "images/" . $imageName;
    if (file_exists($imagePath)) {
        unlink($imagePath); // remove file
    }

    header("Location: manage_products.php?msg=deleted");
    exit();
} else {
    echo "❌ Failed to delete product.";
}
?>