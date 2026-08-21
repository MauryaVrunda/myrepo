<?php
require 'includes/bootstrap.php';

$user_id = require_login();

// Remove product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove'], $_POST['cart_id'])) {
  $cart_id = intval($_POST['cart_id']);
  $stmt = $conn->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
  $stmt->bind_param("ii", $cart_id, $user_id);
  $stmt->execute();
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
  <link rel="stylesheet" href="styles/layout.css">
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
  </style>
</head>
<?php $active = 'cart.php'; include 'includes/partials/navbar.php'; ?>
<h2>🛍️ Your Shopping Cart</h2><div class="cart-container">
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
<?php include 'includes/partials/footer.php'; ?>
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