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

try {
    // ✅ Get the image filename to delete later
    $stmt = $conn->prepare("SELECT image FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $stmt->bind_result($imageName);
    $found = $stmt->fetch();
    $stmt->close();

    if (!$found) {
        echo "❌ Product not found.";
        exit();
    }

    // ✅ Delete product from database
    $delete = $conn->prepare("DELETE FROM products WHERE id = ?");
    $delete->bind_param("i", $product_id);
    $delete->execute();
} catch (mysqli_sql_exception $e) {
    error_log("Product deletion failed for product $product_id: " . $e->getMessage());
    echo "❌ Failed to delete product.";
    exit();
}

// ✅ Delete image file (optional): the product row is already gone, so a
// failure here is logged rather than surfaced to the admin.
$imagePath = "images/" . $imageName;
if (file_exists($imagePath) && !unlink($imagePath)) {
    error_log("Could not delete product image $imagePath");
}

header("Location: manage_products.php?msg=deleted");
exit();
?>