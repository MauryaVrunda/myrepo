<?php
session_start();
require 'connect.php';

// ✅ Allow only admin access
if (!isset($_SESSION['user_email']) || $_SESSION['user_email'] !== 'admin@gmail.com') {
    header("Location: login.php");
    exit();
}

$search = $_GET['search'] ?? '';
$status_filter = $_GET['status'] ?? '';

$error = $_SESSION['order_error'] ?? '';
unset($_SESSION['order_error']);

// Build dynamic query
$query = "SELECT orders.*,   users.name AS user_name, 
         users.email AS user_email, 
         products.name AS product_name , products.image 
          FROM orders
          JOIN users ON orders.user_id = users.id
          JOIN products ON orders.product_id = products.id
          WHERE 1 = 1";

if ($search) {
    $searchTerm = "%$search%";
    $query .= " AND (users.email LIKE ? OR products.name LIKE ?)";
}
if ($status_filter) {
    $query .= " AND orders.status = ?";
}

$query .= " ORDER BY orders.order_date DESC";

// Prepare and bind
$stmt = $conn->prepare($query);

if ($search && $status_filter) {
    $stmt->bind_param("sss", $searchTerm, $searchTerm, $status_filter);
} elseif ($search) {
    $stmt->bind_param("ss", $searchTerm, $searchTerm);
} elseif ($status_filter) {
    $stmt->bind_param("s", $status_filter);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>View Orders</title>
  <link rel="stylesheet" href="styles/admin.css">
  <style>
    .container {
      max-width: 1100px;
      margin: 30px auto;
      background: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0,0,0,0.08);
    }

    h1 {
      text-align: center;
      color: #222;
      margin-bottom: 25px;
    }
    
  form input[type="text"], form select {
    padding: 6px;
    margin-right: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
  }
  form button {
    padding: 6px 12px;
    background: #009fff;
    color: white;
    border: none;
    border-radius: 4px;
  }

    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 15px;
    }

    th, td {
      border: 1px solid #ccc;
      padding: 12px;
      text-align: center;
    }

    th {
      background-color: #f8f8f8;
    }

    tr:nth-child(even) {
      background-color: #fafafa;
    }
        .status-form select {
      padding: 4px;
      font-weight: bold;
    }
    .status-form {
      display: inline;
    }
    table img {
      width: 40px;
      height: auto;
    }

    .highlight {
      font-weight: bold;
      color: #2c3e50;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>All Orders</h1>
<form method="GET" style="margin-bottom: 20px;">
  <input type="text" name="search" placeholder="Search email or product..." value="<?= htmlspecialchars($search) ?>" />
  
  <select name="status">
    <option value="">-- All Statuses --</option>
    <option value="Pending" <?= $status_filter == 'Pending' ? 'selected' : '' ?>>Pending</option>
    <option value="Shipped" <?= $status_filter == 'Shipped' ? 'selected' : '' ?>>Shipped</option>
    <option value="Delivered" <?= $status_filter == 'Delivered' ? 'selected' : '' ?>>Delivered</option>
    <option value="Cancelled" <?= $status_filter == 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
  </select>
  
  <button type="submit">🔍 Search</button>
</form>
    <?php if ($error): ?>
      <p style="color:red; font-weight:bold;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <?php if ($result->num_rows > 0): ?>
        
      <table>
        <tr>
          <th>Order ID</th>
          <th>User</th>
          <th>Email</th>
          <th>Product</th>
          <th>Qty</th>
          <th>Total (₹)</th>
          <th>Address</th>
          <th>Phone</th>
          <th>Ordered On</th>
          <th>Status</th>
          <th>Update</th>
        </tr>
        <?php while ($order = $result->fetch_assoc()): ?>
          <tr>
            <td><?= $order['id'] ?></td>
            <td class="highlight"><?= htmlspecialchars($order['user_name']) ?></td>
            <td><?= htmlspecialchars($order['user_email']) ?></td>
            <td><?= htmlspecialchars($order['product_name']) ?></td>
            <td><?= $order['quantity'] ?></td>
            <td>₹<?= $order['total_price'] ?></td>
            <td><?= htmlspecialchars($order['address']) ?></td>
            <td><?= htmlspecialchars($order['phone']) ?></td>
            <td><?= date("d M Y, H:i", strtotime($order['order_date'])) ?></td>
            <td><?= $order['status'] ?></td>
            <td>
            <form method="POST" action="update_order_status.php" class="status-form">
              <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
              <select name="status" onchange="this.form.submit()">
                <option <?= $order['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                <option <?= $order['status'] == 'Shipped' ? 'selected' : '' ?>>Shipped</option>
                <option <?= $order['status'] == 'Delivered' ? 'selected' : '' ?>>Delivered</option>
              </select>
            </form>
          </td>
          </tr>
        <?php endwhile; ?>
      </table>
    <?php else: ?>
      <p>No orders placed yet.</p>
    <?php endif; ?>
  </div>
</body>
</html>