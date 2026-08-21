
<?php
require 'includes/bootstrap.php';

$user_id = require_login();

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['full_name']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $payment_method = trim($_POST['payment_method']);
    $delivery_date = date('Y-m-d', strtotime('+5 days'));
    $status = "Placed";

    // ✅ Validate input
    if ($name && $phone && $address && $payment_method) {
        // 🛒 Get cart items
        $stmt = $conn->prepare("SELECT c.product_id, c.quantity, p.price 
                                FROM cart c JOIN products p ON c.product_id = p.id 
                                WHERE c.user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $cart_items = [];
        $total_price = 0;
        while ($row = $result->fetch_assoc()) {
            $cart_items[] = $row;
            $total_price += $row['price'] * $row['quantity'];
        }

        if (count($cart_items) > 0) {
            // ✅ Insert into orders table
            $stmt = $conn->prepare("INSERT INTO orders (user_id, customer_name, phone, address, payment_method, order_date, delivery_date, status)
                                    VALUES (?, ?, ?, ?, ?, NOW(), ?, ?)");
            $stmt->bind_param("issssss", $user_id, $name, $phone, $address, $payment_method, $delivery_date, $status);
            $stmt->execute();
            $order_id = $stmt->insert_id;

            // ✅ Insert each cart item into order_items
            $item_stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");

            foreach ($cart_items as $item) {
                $pid = $item['product_id'];
                $qty = $item['quantity'];
                $price = $item['price'];
                $item_stmt->bind_param("iiid", $order_id, $pid, $qty, $price);
                $item_stmt->execute();
            }

            // ✅ Clear cart
            $del = $conn->prepare("DELETE FROM cart WHERE user_id = ?");
            $del->bind_param("i", $user_id);
            $del->execute();

            // ✅ Redirect
            set_flash('order_success', [
                'total_price' => $total_price,
                'delivery_date' => $delivery_date,
                'payment_method' => $payment_method
            ]);
            redirect_to('thankyou.php');
        } else {
            $error = "🛒 Your cart is empty.";
        }
    } else {
        $error = "❗ All fields are required.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Checkout</title>
</head>
<body>
  <h2>Checkout</h2>
  <?php if ($error): ?><p style="color:red"><?= $error ?></p><?php endif; ?>
  <form method="POST">
    <label>Full Name:</label><br>
    <input type="text" name="full_name" required><br><br>

    <label>Phone:</label><br>
    <input type="tel" name="phone" pattern="[0-9]{10}" required><br><br>

    <label>Shipping Address:</label><br>
    <textarea name="address" required></textarea><br><br>

    <label>Payment Method:</label><br>
    <select name="payment_method" required>
      <option value="">-- Choose --</option>
      <option value="Cash on Delivery">Cash on Delivery</option>
      <option value="UPI">UPI</option>
      <option value="Paytm">Paytm</option>
    </select><br><br>

    <button type="submit">Place Order</button>
  </form>
</body>
</html>
<!DOCTYPE html>
<html>
<head>
  <title>Checkout</title>
</head>
<body>
  <h2>Checkout</h2>
  <?php if ($error): ?><p style="color:red"><?= $error ?></p><?php endif; ?>
  <form method="POST">
    <label>Full Name:</label><br>
    <input type="text" name="full_name" required><br><br>

    <label>Phone:</label><br>
    <input type="tel" name="phone" pattern="[0-9]{10}" required><br><br>

    <label>Shipping Address:</label><br>
    <textarea name="address" required></textarea><br><br>

    <label>Payment Method:</label><br>
    <select name="payment_method" required>
      <option value="">-- Choose --</option>
      <option value="Cash on Delivery">Cash on Delivery</option>
      <option value="UPI">UPI</option>
      <option value="Paytm">Paytm</option>
    </select><br><br>

    <button type="submit">Place Order</button>
  </form>
</body>
</html>