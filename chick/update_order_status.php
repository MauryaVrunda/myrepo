<?php
session_start();
require 'connect.php';

if (!isset($_SESSION['user_email']) || $_SESSION['user_email'] !== 'admin@gmail.com') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_id = intval($_POST['order_id']);
    $status = $_POST['status'];

    $valid = ['Pending', 'Shipped', 'Delivered'];
    if (in_array($status, $valid)) {
        $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $order_id);
        $stmt->execute();
    }
}

header("Location: view_orders.php");
exit();