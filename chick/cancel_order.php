<?php
require 'includes/bootstrap.php';
require 'includes/mailer.php';

// Show loading animation first (this runs before logic)
echo "<!DOCTYPE html>
<html>
<head>
  <meta http-equiv='refresh' content='2;url=cancel_response.php'>
  <title>Canceling...</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #fff0f5;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }
    .loader {
      border: 6px solid #f3f3f3;
      border-top: 6px solid #a855f7;
      border-radius: 50%;
      width: 50px;
      height: 50px;
      animation: spin 1s linear infinite;
      margin-bottom: 20px;
    }
    .msg {
      font-size: 20px;
      color: #a855f7;
    }
    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
  </style>
</head>
<body>
  <div class='loader'></div>
  <div class='msg'>✨ Cancelling your order... please wait...</div>
</body>
</html>";

if (!is_logged_in() || !isset($_POST['order_id'])) {
  set_flash('cancel_result', ['status' => 'fail', 'msg' => 'Invalid request.']);
  exit();
}

$order_id = intval($_POST['order_id']);
$user_id = current_user_id();

// Validate the order
$check = $conn->prepare("SELECT o.*, p.name AS product_name FROM orders o
                         JOIN products p ON o.product_id = p.id
                         WHERE o.id = ? AND o.user_id = ? AND o.status = 'Placed'");
$check->bind_param("ii", $order_id, $user_id);
$check->execute();
$result = $check->get_result();

if ($result->num_rows === 1) {
  $order = $result->fetch_assoc();

  // Update status to 'Cancelled'
  $update = $conn->prepare("UPDATE orders SET status = 'Cancelled' WHERE id = ?");
  $update->bind_param("i", $order_id);
  $update->execute();

  // Email notification to admin
  send_site_mail('⚠️ Order Cancelled - Chic Charm Beads', "
      <h2>Order Cancelled</h2>
      <p><strong>Order ID:</strong> {$order_id}</p>
      <p><strong>Product:</strong> {$order['product_name']}</p>
      <p><strong>Quantity:</strong> {$order['quantity']}</p>
      <p><strong>Total:</strong> ₹{$order['total_price']}</p>
      <p><strong>User ID:</strong> {$order['user_id']}</p>
    ");

  set_flash('cancel_result', ['status' => 'success', 'msg' => 'Order cancelled successfully!']);
} else {
  set_flash('cancel_result', ['status' => 'fail', 'msg' => 'Order not found or cannot be cancelled.']);
}

exit();
?>