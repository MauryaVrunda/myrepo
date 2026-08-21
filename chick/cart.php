<?php
session_start();
require 'connect.php';

if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

$user_id = $_SESSION['user_id'];

$error = $_SESSION['cart_error'] ?? '';
unset($_SESSION['cart_error']);

// Remove product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove'], $_POST['cart_id'])) {
  $cart_id = intval($_POST['cart_id']);
  try {
    $stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $cart_id, $user_id);
    $stmt->execute();
  } catch (mysqli_sql_exception $e) {
    error_log("Remove from cart failed for user $user_id, cart item $cart_id: " . $e->getMessage());
    $error = "Could not remove the item from your cart. Please try again.";
  }
}

// Fetch cart items
$stmt = $conn->prepare("SELECT c.id AS cart_id, c.quantity, p.id AS product_id, p.name, p.image, p.price 
                        FROM cart c JOIN products p ON c.product_id = p.id 
                        WHERE c.user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$cart_items = [];
while ($row = $result->fetch_assoc()) {
  $cart_items[] = $row;
}
?><!DOCTYPE html><html>
<head>
  <title>Your Shopping Cart</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
     background: linear-gradient(to right,rgb(197, 247, 240),rgb(228, 207, 250));
    }

    .container {
      max-width: 1000px;
      margin: auto;
    }

    .navbar {
      background-color: #222;
      color: white;
      padding: 1rem 2rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap:wrap;
    }

    .navbar .logo {
      font-size: 24px;
      font-weight: bold;
    }

    .navbar ul {
      list-style: none;
      display: flex;
      gap: 20px;
      flex-wrap:wrap;
    }

    .navbar li a {
      color: white;
      text-decoration: none;
      padding: 6px 10px;
    }

    .navbar li a:hover {
      background-color: #555;
      border-radius: 4px;
    }

    h2 {
  text-align: center;
      color: #7f5af0;
  margin-bottom: 30px;
}

.cart-container {
  display: flex;
  flex-wrap: wrap;
  gap: 30px;
  justify-content: center;
}

.cart-items {
  flex: 1;
  min-width: 400px;
}

.cart-box {
  display: flex;
  align-items: center;
  background-color: #f9f9f9;
  padding: 15px;
  border-radius: 15px;
  margin-bottom: 20px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}

.cart-box img {
  width: 90px;
  height: 90px;
  object-fit: cover;
  border-radius: 10px;
  margin-right: 20px;
}

.cart-box h3 {
  margin: 0;
  color: #444;
}

.cart-box .controls {
  margin-left: auto;
  text-align: right;
}

.cart-box input[type=number] {
  width: 60px;
  padding: 6px;
  border-radius: 5px;
  border: 1px solid #ccc;
}

.cart-box button {
  margin-top: 6px;
        background-color: #7f5af0;

  color: white;
  border: none;
  padding: 6px 10px;
  border-radius: 6px;
  cursor: pointer;
}

 .cart-box button:hover {
      background-color: #9c7dfc;
    }

.summary {
  width: 300px;
        background-color: #f9f9f9;

  padding: 20px;
  border-radius: 15px;
  box-shadow: 0 0 8px rgba(0,0,0,0.1);
}

.summary h3 {
  color: #444;
}

.summary p {
  font-weight: bold;
  margin: 10px 0;
}

.summary input[type=text] {
  width: 100%;
  padding: 8px;
  border-radius: 6px;
  margin-bottom: 10px;
  border: 1px solid #ccc;
}

.summary button {
  width: 100%;
  padding: 10px;
        background-color: #7f5af0;

  color: white;
  font-weight: bold;
  border: none;
  border-radius: 8px;
  cursor: pointer;
}
 .summary button:hover {
      background-color: #9c7dfc;
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
<!-- Navbar -->
<div class="navbar">
  <div class="logo">Chic Charm Beads</div>
  <ul>
    <li><a href="index.php">Home</a></li>
    <li><a href="shop.php">Shop</a></li>
    <li><a href="wishlist.php">Wishlist</a></li>
    <li><a href="cart.php">Cart</a></li>
    <li><a href="user_dashboard.php">Account</a></li>
  </ul>
</div>
<h2>🛍️ Your Shopping Cart</h2>
<?php if ($error): ?>
  <p style="color:red; font-weight:bold;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>
<div class="cart-container">
  <div class="cart-items">
    
    <?php if (count($cart_items) > 0): ?>
      <?php foreach ($cart_items as $item): ?>
        <div class="cart-box" data-price="<?= $item['price'] ?>">
          <img src="images/<?= $item['image'] ?>" alt="<?= htmlspecialchars($item['name']) ?>">
          <div>
            <h3><?= htmlspecialchars($item['name']) ?></h3>
            <p>₹<?= number_format($item['price'], 2) ?> × 
              <input type="number" class="qty-input" data-cart-id="<?= $item['cart_id'] ?>" value="<?= $item['quantity'] ?>" min="1"> = 
              ₹<span class="item-total"><?= number_format($item['price'] * $item['quantity'], 2) ?></span>
            </p>
            <form method="POST">
              <input type="hidden" name="cart_id" value="<?= $item['cart_id'] ?>">
              <button type="submit" name="remove">Remove</button>
            </form>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p>No items in your cart.</p>
    <?php endif; ?>
  </div>  <div class="summary">
    <h3>Order Summary</h3>
    <p>Subtotal: ₹<span id="subtotal">0.00</span></p>
    <p>Total: ₹<span id="total">0.00</span></p>
    <input type="text" placeholder="Enter Coupon Code" disabled>
    <button disabled>Apply</button>
    <form action="checkout.php" method="GET">
    <br><button type="submit">Proceed to Checkout</button>
    </form>
  </div>
</div>
<!-- Footer -->
<footer class="footer">
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
<script>
  function recalculateTotals() {
    let subtotal = 0;
    document.querySelectorAll('.cart-box').forEach(box => {
      const price = parseFloat(box.dataset.price);
      const qtyInput = box.querySelector('.qty-input');
      const quantity = parseInt(qtyInput.value);
      const itemTotal = price * quantity;
      box.querySelector('.item-total').innerText = itemTotal.toFixed(2);
      subtotal += itemTotal;
    });
    document.getElementById('subtotal').innerText = subtotal.toFixed(2);
    document.getElementById('total').innerText = subtotal.toFixed(2);
  }

  document.querySelectorAll('.qty-input').forEach(input => {
    input.addEventListener('change', () => {
      if (parseInt(input.value) < 1) input.value = 1;
      recalculateTotals();
    });
  });

  recalculateTotals();
</script></body>
</html>