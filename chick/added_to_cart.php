<?php
require 'auth.php';
$product_name = $_GET['product'] ?? 'Product';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Added to Cart</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
background: linear-gradient(to right,rgb(220, 248, 244),rgb(233, 221, 247));
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      overflow: hidden;
      position: relative;
    }

    .message-box {
            background-color:rgb(242, 240, 247);

      padding: 40px;
      border-radius: 20px;
      text-align: center;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      position: relative;
      z-index: 10;
      animation: fadeIn 0.6s ease-out;
    }

    .message-box h2 {
      color: #7f5af0;

      margin-bottom: 10px;
    }

    .message-box p {
      font-size: 18px;
      font-weight: bold;
      color: #444;
    }

    .message-box button {
      margin: 15px 10px;
      padding: 10px 20px;
      border: none;
      border-radius: 8px;
      background-color: #7f5af0;
      color: white;
      font-weight: bold;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    .message-box button:hover {
      background-color:#9c7dfc;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: scale(0.95); }
      to   { opacity: 1; transform: scale(1); }
    }

    /* Confetti */
    .confetti {
      position: absolute;
      width: 100%;
      height: 100%;
      pointer-events: none;
      z-index: 1;
    }

    .confetti span {
      position: absolute;
      width: 10px;
      height: 10px;
      background: #f472b6;
      opacity: 0.8;
      animation: fall 4s linear infinite;
    }

    .confetti span:nth-child(odd) {
      background: #fb7185;
    }

    @keyframes fall {
      0% {
        transform: translateY(-10vh) rotate(0deg);
        opacity: 1;
      }
      100% {
        transform: translateY(110vh) rotate(360deg);
        opacity: 0;
      }
    }
  </style>
</head>
<body>

<div class="confetti">
  <?php for ($i = 0; $i < 50; $i++): ?>
    <span style="
      left: <?= rand(0, 100) ?>vw;
      animation-delay: <?= rand(0, 3000) / 1000 ?>s;
      background-color: hsl(<?= rand(300, 360) ?>, 100%, 80%);
    "></span>
  <?php endfor; ?>
</div>

<div class="message-box">
  <h2>🛍️ Added to Cart!</h2>
  <p><?= htmlspecialchars($product_name) ?></p>
  <button onclick="window.location.href='shop.php'">Continue Shopping</button>
  <button onclick="window.location.href='cart.php'">Go to Cart</button>
</div>

</body>
</html>