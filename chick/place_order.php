<?php
require 'includes/bootstrap.php';
require 'includes/mailer.php';

require_login();

$product_id = null;
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
  $product_id = intval($_GET['id']);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
  $product_id = intval($_POST['product_id']);
} else {
  echo "❌ No product selected.";
  exit();
}

$product = find_product($conn, $product_id);

if (!$product) {
  echo "❌ Product not found.";
  exit();
}

$success_message = "";
$error_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user_id = current_user_id();
  $address = trim($_POST['address'] ?? '');
  $phone = trim($_POST['phone'] ?? '');
  $quantity = intval($_POST['quantity'] ?? 1);
  $payment_method = trim($_POST['payment_method'] ?? 'Cash on Delivery');

  if ($address && $phone && $quantity > 0 && $payment_method) {
    $total_price = $product['price'] * $quantity;
    $delivery_date = date('Y-m-d', strtotime('+5 days'));
    $status = "Placed";

    $insert = $conn->prepare("INSERT INTO orders (user_id, product_id, quantity, total_price, address, phone, payment_method, order_date, delivery_date, status)
                              VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), ?, ?)");
    $insert->bind_param("iiidsssss", $user_id, $product_id, $quantity, $total_price, $address, $phone, $payment_method, $delivery_date, $status);

    if ($insert->execute()) {
      // 📨 Notify the shop owner
      send_site_mail('🧵 New Order Received - Chic Charm Beads', "
          <h2>New Order Placed</h2>
          <p><strong>Product:</strong> {$product['name']}</p>
          <p><strong>Quantity:</strong> $quantity</p>
          <p><strong>Total:</strong> ₹$total_price</p>
          <p><strong>Address:</strong> $address</p>
          <p><strong>Phone:</strong> $phone</p>
          <p><strong>Payment Method:</strong> $payment_method</p>
          <p><strong>Estimated Delivery:</strong> $delivery_date</p>
        ");

      set_flash('order_success', [
        'product' => $product['name'],
        'delivery_date' => $delivery_date,
        'payment_method' => $payment_method
      ]);

      redirect_to('thankyou.php');
    } else {
      $error_message = "❌ Error placing order.";
    }
  } else {
    $error_message = "❗ All fields are required.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Place Order</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: linear-gradient(to right, #fff4f4, #f5ecff);
      padding: 30px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .form-container {
      max-width: 500px;
      background: #fff;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    h2 {
      text-align: center;
      color: #a855f7;
    }

    label {
      display: block;
      margin-top: 15px;
      font-weight: 500;
      color: #333;
    }

    input, textarea, select {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      border-radius: 8px;
      border: 1px solid #ccc;
      font-size: 15px;
    }

    button {
      margin-top: 20px;
      width: 100%;
      padding: 12px;
      background-color: #a855f7;
      color: white;
      font-size: 16px;
      border: none;
      border-radius: 25px;
      cursor: pointer;
    }

    button:hover {
      background-color: #c084fc;
    }

    .msg {
      text-align: center;
      font-weight: bold;
      margin-bottom: 10px;
    }

    .msg.success {
      color: green;
    }

    .price-tag {
      margin-top: 10px;
      font-size: 16px;
      font-weight: bold;
      color: #444;
    }

    .back-link {
      display: block;
      text-align: center;
      margin-top: 15px;
      color: #a855f7;
      text-decoration: none;
    }

    .back-link:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>

<div class="form-container">
  <h2>Place Order for <?= htmlspecialchars($product['name']) ?></h2>

  <?php if ($success_message): ?>
    <p class="msg success"><?= $success_message ?></p>
  <?php elseif ($error_message): ?>
    <p class="msg"><?= $error_message ?></p>
  <?php endif; ?>

  <form method="POST">
    <input type="hidden" name="product_id" value="<?= $product_id ?>">
    <input type="hidden" id="base_price" value="<?= $product['price'] ?>">

    <p class="price-tag">Total Price: ₹<span id="price"><?= $product['price'] ?></span></p>

    <label for="quantity">Quantity</label>
    <input type="number" name="quantity" id="quantity" value="<?= isset($_POST['quantity']) ? htmlspecialchars($_POST['quantity']) : 1 ?>" min="1" required>

    <label for="address">Shipping Address</label>
    <textarea name="address" id="address" rows="3" required><?= htmlspecialchars($_POST['address'] ?? '') ?></textarea>

    <label for="phone">Phone Number</label>
    <input type="tel" name="phone" id="phone" pattern="[0-9]{10}" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>" required>

    <label for="payment_method">Payment Method</label>
    <select name="payment_method" id="payment_method" required>
      <option value="">-- Select Payment Method --</option>
      <option value="Cash on Delivery" <?= ($_POST['payment_method'] ?? '') === 'Cash on Delivery' ? 'selected' : '' ?>>Cash on Delivery</option>
      <option value="UPI" <?= ($_POST['payment_method'] ?? '') === 'UPI' ? 'selected' : '' ?>>UPI</option>
      <option value="Paytm" <?= ($_POST['payment_method'] ?? '') === 'Paytm' ? 'selected' : '' ?>>Paytm</option>
      <option value="Google Pay" <?= ($_POST['payment_method'] ?? '') === 'Google Pay' ? 'selected' : '' ?>>Google Pay</option>
      <option value="Bank Transfer" <?= ($_POST['payment_method'] ?? '') === 'Bank Transfer' ? 'selected' : '' ?>>Bank Transfer</option>
    </select>

    <button type="submit">Place Order</button>
  </form>

  <a class="back-link" href="product.php?id=<?= $product_id ?>">← Back to Product</a>
</div>

<!-- 💫 Live Price Calculation -->
<script>
  const qtyInput = document.getElementById('quantity');
  const priceElement = document.getElementById('price');
  const basePrice = parseFloat(document.getElementById('base_price').value);

  function updatePrice() {
    const qty = parseInt(qtyInput.value) || 1;
    priceElement.textContent = (qty * basePrice).toFixed(2);
  }

  qtyInput.addEventListener('input', updatePrice);
  window.addEventListener('load', updatePrice);
</script>

</body>
</html>