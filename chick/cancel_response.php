<?php
require 'auth.php';
$result = $_SESSION['cancel_result'] ?? ['status' => 'fail', 'msg' => 'Something went wrong.'];
unset($_SESSION['cancel_result']);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Cancel Response</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #fffefc;
      text-align: center;
      padding: 100px 20px;
    }
    .card {
      max-width: 500px;
      margin: auto;
      background: #fff;
      padding: 30px;
      border-radius: 15px;
      box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    .status {
      font-size: 24px;
      font-weight: bold;
      color: <?= $result['status'] === 'success' ? '#22c55e' : '#e11d48' ?>;
    }
    .msg {
      margin-top: 10px;
      font-size: 18px;
      color: #444;
    }
    .back-btn {
      display: inline-block;
      margin-top: 25px;
      padding: 10px 20px;
      background-color: #a855f7;
      color: white;
      border: none;
      border-radius: 25px;
      text-decoration: none;
      font-size: 16px;
    }
    .back-btn:hover {
      background-color: #9333ea;
    }
  </style>
</head>
<body>
  <div class="card">
    <div class="status">
      <?= $result['status'] === 'success' ? '🎉 Cancelled!' : '⚠️ Oops!' ?>
    </div>
    <div class="msg"><?= htmlspecialchars($result['msg']) ?></div>
    <a class="back-btn" href="user_dashboard.php">← Go Back to My Orders</a>
  </div>
</body>
</html>