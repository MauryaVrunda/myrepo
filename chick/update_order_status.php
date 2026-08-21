<?php
session_start();
require 'connect.php';

if (!isset($_SESSION['user_email']) || $_SESSION['user_email'] !== 'admin@gmail.com') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = intval($_POST['order_id'] ?? 0);
    $status = $_POST['status'] ?? '';

    $valid = ['Pending', 'Shipped', 'Delivered'];
    if (!in_array($status, $valid)) {
        $_SESSION['order_error'] = "Invalid order status.";
    } else {
        try {
            $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
            $stmt->bind_param("si", $status, $order_id);
            $stmt->execute();

            if ($stmt->affected_rows === 0) {
                $_SESSION['order_error'] = "Order #$order_id was not updated.";
            }
        } catch (mysqli_sql_exception $e) {
            error_log("Order status update failed for order $order_id: " . $e->getMessage());
            $_SESSION['order_error'] = "Could not update the order status. Please try again.";
        }
    }
}

header("Location: view_orders.php");
exit();