<?php
require 'auth.php';
require 'connect.php';

require_admin();

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