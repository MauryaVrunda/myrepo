<?php
require 'includes/bootstrap.php';

require_admin();

// ✅ Ensure a product ID is passed
if (!isset($_GET['id'])) {
    echo "❌ Product ID not provided.";
    exit();
}

$product_id = intval($_GET['id']);

$product = find_product($conn, $product_id);
$imageName = $product['image'] ?? '';

// ✅ Delete product from database
$delete = $conn->prepare("DELETE FROM products WHERE id = ?");
$delete->bind_param("i", $product_id);

if ($delete->execute()) {
    delete_product_image($imageName);

    redirect_to('manage_products.php?msg=deleted');
} else {
    echo "❌ Failed to delete product.";
}
?>