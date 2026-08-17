<?php
session_start();
require 'connect.php';

// Optional: Add authentication to ensure only admin accesses this page
// if ($_SESSION['user_role'] !== 'admin') {
//   header("Location: login.php");
//   exit();
//}

$sql = "SELECT orders.*, users.name AS user_name, products.name AS product_name, products.image 
        FROM orders
        JOIN users ON orders.user_id = users.id
        JOIN products ON orders.product_id = products.id
        ORDER BY orders.order_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin - All Orders</title>
  <link rel="stylesheet" href="styles/dashboard.css">
  <style>
    .admin-container {
      max-width: 1200px;
      margin: 30px auto;
      padding: 20px;
      background: #fff;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      border-radius: 10px;
    }

    h2 {
      text-align: center;
      color: #880e4f;
      margin-bottom: 30px;
    }

    .order-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
      gap: 20px;
    }

    .order-card {
      background: #f9f9f9;
      border: 1px solid #ddd;
      padding: 15px;
      border-radius: 10px;
    }

    .order-card img {
      width: 100%;
      height: 150px;
      object-fit: cover;
      border-radius: 8px;
    }

    .order-card h4 {
      margin-top: 10px;
      color: #333;
    }

    .order-card p {
      font-size: 14px;
      margin: 5px 0;
    }

    .order-card .total {
      font-weight: bold;
      color: #009688;
    }
  </style>
</head>
<body>

<div class="admin-container">
  <h2>All Customer Orders</h2>

  <?php if ($result->num_rows > 0): ?>
    <div class="order-grid">
      <?php while($order = $result->fetch_assoc()): ?>
        <div class="order-card">
          <img src="images/<?= $order['image'] ?>" alt="<?= $order['product_name'] ?>">
          <h4><?= htmlspecialchars($order['product_name']) ?></h4>
          <p><strong>Customer:</strong> <?= htmlspecialchars($order['user_name']) ?></p>
          <p><strong>Quantity:</strong> <?= $order['quantity'] ?></p>
          <p><strong>Phone:</strong> <?= $order['phone'] ?></p>
          <p><strong>Address:</strong> <?= htmlspecialchars($order['address']) ?></p>
          <p class="total">Total: ₹<?= $order['total_price'] ?></p>
          <small><?= date('d M Y, h:i A', strtotime($order['order_date'])) ?></small>
        </div>
      <?php endwhile; ?>
    </div>
  <?php else: ?>
    <p>No orders have been placed yet.</p>
  <?php endif; ?>
</div>

</body>
</html>