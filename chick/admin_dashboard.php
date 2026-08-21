<?php
require 'includes/bootstrap.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="admin_styles.css" />
</head>
<body>

<div class="navbar">
  <h1>Admin Panel</h1>
  <div>
    <a href="logout.php">Logout</a>
  </div>
</div>

<div class="dashboard">
  <h2>Welcome, Admin 👋</h2>

  <div class="card-grid">
    <div class="card">
      <h3>Manage Products</h3>
      <p>Edit, delete, or add new products to the store.</p>
      <a href="manage_products.php">Go</a>
    </div>

    <div class="card">
      <h3>View Users</h3>
      <p>See registered user data and manage accounts.</p>
      <a href="view_users.php">Go</a>
    </div>

    <div class="card">
      <h3>View Orders</h3>
      <p>Track all orders placed by users.</p>
      <a href="view_orders.php">Go</a>
    </div>
  </div>
</div>

<div class="footer">
  &copy; <?= date('Y') ?> Chic Charm Beads | Admin Dashboard
</div>

</body>
</html>