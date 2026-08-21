<?php
session_start();
require 'connect.php';

// Allow only admin
if (!isset($_SESSION['user_email']) || $_SESSION['user_email'] !== 'admin@gmail.com') {
    header("Location: login.php");
    exit();
}

$message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $price = floatval($_POST['price']);
    $image = $_FILES['image'];

    if ($name && $price && $image['size'] > 0) {
        $imageName = time() . "_" . basename($image['name']);
        $targetPath = "images/" . $imageName;

        if ($image['error'] !== UPLOAD_ERR_OK) {
            error_log("Product image upload failed with error code {$image['error']}");
            $message = "❌ Failed to upload image.";
        } elseif (move_uploaded_file($image['tmp_name'], $targetPath)) {
            try {
                $stmt = $conn->prepare("INSERT INTO products (name, price, image) VALUES (?, ?, ?)");
                $stmt->bind_param("sds", $name, $price, $imageName);
                $stmt->execute();
                $message = "✅ Product added successfully!";
            } catch (mysqli_sql_exception $e) {
                error_log("Product insert failed for '$name': " . $e->getMessage());
                // Don't leave an orphaned upload behind.
                if (!unlink($targetPath)) {
                    error_log("Could not remove orphaned upload $targetPath");
                }
                $message = "❌ Database error.";
            }
        } else {
            error_log("Could not move uploaded image to $targetPath");
            $message = "❌ Failed to upload image.";
        }
    } else {
        $message = "❌ All fields are required.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Add Product</title>
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

    form button {
      width: 100%;
      background: #007BFF;
      color: white;
      border: none;
      padding: 12px;
      font-size: 16px;
      border-radius: 6px;
      cursor: pointer;
    }

    form button:hover {
      background: #0056b3;
    }

    .message {
      text-align: center;
      margin-bottom: 15px;
      color: green;
      font-weight: bold;
    }

    .error {
      color: red;
    }
  </style>
</head>
<body>

<div class="form-container">
  <h2>Add New Product</h2>

  <?php if ($message): ?>
    <p class="message <?= strpos($message, '❌') !== false ? 'error' : '' ?>"><?= $message ?></p>
  <?php endif; ?>

  <form method="POST" enctype="multipart/form-data">
    <label>Product Name:</label>
    <input type="text" name="name" required>

    <label>Price (₹):</label>
    <input type="number" step="0.01" name="price" required>

    <label>Product Image:</label>
    <input type="file" name="image" accept="image/*" required>

    <button type="submit">Add Product</button>
  </form>
</div>

</body>
</html>