<?php
session_start();
require 'connect.php';

// Admin authentication
if (!isset($_SESSION['user_email']) || $_SESSION['user_email'] !== 'admin@gmail.com') {
    header("Location: login.php");
    exit();
}

// Fetch all products
$result = $conn->query("SELECT * FROM products ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Manage Products</title>
  <link rel="stylesheet" href="styles/admin.css">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f4f6f8;
      margin: 0;
      padding: 0;
    }

    .container {
      max-width: 1000px;
      margin: 40px auto;
      padding: 20px;
      background: white;
      box-shadow: 0 0 10px rgba(0,0,0,0.05);
      border-radius: 8px;
    }

    h1 {
      text-align: center;
      margin-bottom: 25px;
      color: #333;
    }

    .btn {
      display: inline-block;
      padding: 10px 18px;
      background: #007BFF;
      color: white;
      border-radius: 6px;
      text-decoration: none;
      margin-bottom: 20px;
      transition: background 0.3s;
    }

    .btn:hover {
      background: #0056b3;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }

    th, td {
      padding: 12px 14px;
      text-align: left;
      border-bottom: 1px solid #ddd;
    }

    th {
      background: #343a40;
      color: white;
    }

    tr:hover {
      background: #f1f1f1;
    }

    img {
      border-radius: 4px;
    }

    a {
      text-decoration: none;
      color: #007BFF;
    }

    a:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Manage Products</h1>
    <a href="add_product.php" class="btn">➕ Add New Product</a>

    <table>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Price (₹)</th>
        <th>Image</th>
        <th>Actions</th>
      </tr>
      <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td><?= htmlspecialchars($row['name']) ?></td>
          <td><?= $row['price'] ?></td>
          <td><img src="images/<?= $row['image'] ?>" width="60" height="60" /></td>
          <td>
            <a href="edit_product.php?id=<?= $row['id'] ?>">✏️ Edit</a> |
            <a href="delete_product.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this product?')">❌ Delete</a>
          </td>
        </tr>
      <?php endwhile; ?>
    </table>
  </div>
</body>
</html>