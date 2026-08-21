<?php
require 'includes/bootstrap.php';

require_admin();

// Ensure product ID is present
if (!isset($_GET['id'])) {
    echo "❌ No product selected.";
    exit();
}

$product_id = intval($_GET['id']);
$message = "";

$product = find_product($conn, $product_id);

if (!$product) {
    echo "❌ Product not found.";
    exit();
}

// Update form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $price = floatval($_POST['price']);
    $newImage = $_FILES['image'];

    $imageName = $product['image']; // default

    // If new image is uploaded
    if ($newImage['size'] > 0) {
        $imageName = store_product_image($newImage) ?? $product['image'];
    }

    // Update product
    $update = $conn->prepare("UPDATE products SET name = ?, price = ?, image = ? WHERE id = ?");
    $update->bind_param("sdsi", $name, $price, $imageName, $product_id);

    if ($update->execute()) {
        $message = "✅ Product updated successfully!";
        // Refresh product data
        $stmt->execute();
        $product = $stmt->get_result()->fetch_assoc();
    } else {
        $message = "❌ Error updating product.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Edit Product</title>
  <link rel="stylesheet" href="styles/admin.css">
  <style>
    .form-container {
      max-width: 600px;
      margin: 40px auto;
      background: white;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
    }

    h2 {
      text-align: center;
      color: #333;
      margin-bottom: 20px;
    }

    form label {
      display: block;
      margin-bottom: 6px;
      font-weight: 500;
    }

    form input[type="text"],
    form input[type="number"],
    form input[type="file"] {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 6px;
    }

    img.preview {
      width: 100px;
      display: block;
      margin-bottom: 10px;
      border-radius: 6px;
    }

    form button {
      width: 100%;
      background: #28a745;
      color: white;
      border: none;
      padding: 12px;
      font-size: 16px;
      border-radius: 6px;
      cursor: pointer;
    }

    form button:hover {
      background: #218838;
    }

    .message {
      text-align: center;
      margin-bottom: 15px;
      font-weight: bold;
    }

    .error { color: red; }
  </style>
</head>
<body>

<div class="form-container">
  <h2>Edit Product</h2>

  <?php if ($message): ?>
    <p class="message <?= strpos($message, '❌') !== false ? 'error' : '' ?>"><?= $message ?></p>
  <?php endif; ?>

  <form method="POST" enctype="multipart/form-data">
    <label>Product Name:</label>
    <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required>

    <label>Price (₹):</label>
    <input type="number" step="0.01" name="price" value="<?= $product['price'] ?>" required>

    <label>Current Image:</label>
    <img class="preview" src="images/<?= $product['image'] ?>" alt="Image">

    <label>Upload New Image (optional):</label>
    <input type="file" name="image" accept="image/*">

    <button type="submit">Update Product</button>
  </form>
</div>

</body>
</html>