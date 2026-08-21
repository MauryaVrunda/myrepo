<?php
require 'auth.php';
require 'connect.php';

// Protect user dashboard
require_login();

// Prevent admin from accessing user dashboard
if (is_admin()) {
    header("Location: admin_dashboard.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];

// Fetch user info including profile image

$profile_image = $user_data['profile_image'] ?? 'default-user.png';

// Fetch Wishlist
$wishlist_query = $conn->prepare("
  SELECT products.id, products.name, products.price, products.image 
  FROM wishlist 
  JOIN products ON wishlist.product_id = products.id 
  WHERE wishlist.user_id = ?
");
$wishlist_query->bind_param("i", $user_id);
$wishlist_query->execute();
$wishlist_result = $wishlist_query->get_result();
$wishlist_items = $wishlist_result->fetch_all(MYSQLI_ASSOC);

// Fetch Cart
$cart_query = $conn->prepare("
  SELECT products.id, products.name, products.price, products.image 
  FROM cart 
  JOIN products ON cart.product_id = products.id 
  WHERE cart.user_id = ?
");
$cart_query->bind_param("i", $user_id);
$cart_query->execute();
$cart_result = $cart_query->get_result();
$cart_items = $cart_result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f0f0f0;
      margin: 0;
    }

    .dashboard-container {
      max-width: 1000px;
      margin: auto;
      background: #fff;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 0 10px rgba(0,0,0,0.08);
    }

    .header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 25px;
    }

    .header h2 {
      margin: 0;
      font-size: 26px;
    }

    .actions {
      margin-top: 15px;
        display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 20px;
    }

    .actions a {
      text-decoration: none;
      background:rgb(247, 241, 241);
      padding: 20px;
      border-left: 5px solid  #7f5af0;
      border-right: 2px solid  #7f5af0;
      border-radius: 10px;
      box-shadow: 0 0 8px rgba(0, 0, 0, 0.05);
      display: inline-block;
      color:  #7f5af0
      font-weight: bold;
    }

    .actions a:hover {
      background:rgb(208, 202, 228);
    }

    h3 {
      text-align:center;  
      margin-top: 40px;
      margin-bottom: 15px;
      border-bottom: 2px solid #eee;
      padding-bottom: 5px;
      color: #333;
    }

    .products {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
    }

    .product-card {
      background: #fafafa;
      padding: 15px;
      border-radius: 10px;
      text-align: center;
      text-decoration: none;
      color: #222;
      box-shadow: 0 0 5px rgba(0,0,0,0.1);
      transition: transform 0.2s;
    }

    .product-card:hover {
      transform: scale(1.02);
    }

    .product-card img {
      width: 100%;
      height: 150px;
      object-fit: contain;
      border-radius: 8px;
    }

    .product-card .product-name {
      margin: 10px 0 5px;
      font-size: 16px;
      font-weight: 500;
    }

    .product-card .price {
      color: #e91e63;
      font-weight: bold;
    }

    .cancel-btn {
  margin-top: 10px;
  padding: 8px 12px;
  background-color: #ff4d4d;
  color: white;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  font-size: 14px;
}

.cancel-btn:hover {
  background-color: #e60000;
}
.footer {
  background: #222;
  color: white;
  padding: 20px;
  text-align: center;
  font-size: 14px;
}

.footer a {
  color: #fff;
  text-decoration: underline;
  margin: 0 5px;
}

  </style>
</head>
<body>

<div class="dashboard-container">
  <div class="header">
      <div>
        <h2>Welcome, <?= htmlspecialchars($user_name) ?>!</h2>
      </div>
  </div>

  <div class="actions">
    <a href="shop.php"><strong>🛍️ Back to Shop</strong></a>
    <a href="logout.php"><strong>🚪 Logout</strong></a>
  </div>

  <div class="actions">
    <a href="wishlist.php"><strong>Wishlist</strong></a>
    <a href="cart.php"><strong>Cart</strong></a>
  </div>

  <!-- Orders Section -->
<section class="dashboard-section">
  <h2>Your Orders</h2>

  <?php
  $stmt = $conn->prepare("SELECT orders.*, products.name, products.image FROM orders 
                          JOIN products ON orders.product_id = products.id 
                          WHERE orders.user_id = ? ORDER BY orders.order_date DESC");
  $stmt->bind_param("i", $user_id);
  $stmt->execute();
  $orders = $stmt->get_result();

  if ($orders->num_rows > 0):
  ?>
  <div class="product-grid">
    <?php while ($order = $orders->fetch_assoc()): ?>
      <div class="product-card">
        <img src="images/<?= $order['image'] ?>" alt="<?= htmlspecialchars($order['name']) ?>">
        <h4><?= htmlspecialchars($order['name']) ?></h4>
        <p>Quantity: <?= $order['quantity'] ?></p>
        <div class="price"><p>Total: ₹<?= $order['total_price'] ?></p></div>
        <p>Status: <strong><?= htmlspecialchars($order['status']) ?></strong></p>
        <p>Delivery by: <strong><?= date('d M Y', strtotime($order['delivery_date'])) ?></strong></p>
        <small>Ordered on: <?= date('d M Y, h:i A', strtotime($order['order_date'])) ?></small>

        <!-- Only show cancel option if order is still 'Placed' -->
        <?php if ($order['status'] === 'Placed'): ?>
          <form action="cancel_order.php" method="POST" onsubmit="return confirm('Are you sure you want to cancel this order?');">
            <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
            <button type="submit" class="cancel-btn">Cancel Order</button>
          </form>
        <?php endif; ?>
      </div>
    <?php endwhile; ?>
  </div>
<?php else: ?>
  <p>You have not placed any orders yet.</p>
<?php endif; ?>
</section>

</div>
<!-- Footer -->
<br><br><br><footer class="footer">
  <div class="contact">
    <p>📞 +91 9328594884 | ✉️ vrundamaurya07@gmail.com</p>
    <a href="about.php">About Us</a><br>
    <a href="contact.html">Contact Us</a>
  </div>
  <div class="social">
    <a href="#">Instagram</a> |
    <a href="#">Facebook</a> |
    <a href="#">Pinterest</a> 
  </div>
  <p>&copy; <?= date("Y") ?> Chic Charm Beads. All rights reserved.</p>
</footer>
</body>
</html>