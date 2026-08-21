<?php
session_start();

if (!isset($_SESSION['order_success'])) {
  header("Location: index.php");
  exit();
}

// A cart checkout has no single product name, only a total.
$product = $_SESSION['order_success']['product'] ?? 'your items';
$delivery = $_SESSION['order_success']['delivery_date'] ?? date('Y-m-d', strtotime('+5 days'));
unset($_SESSION['order_success']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Order Confirmed - Chic Charm Beads</title>
  <style>
    body {
      background: linear-gradient(to right,rgb(197, 247, 240),rgb(228, 207, 250));
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      padding: 0;
      overflow-x: hidden;
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
    }

    .thank-you-box {
      background: #fff;
      padding: 40px;
      text-align: center;
      border-radius: 20px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
      max-width: 500px;
      position: relative;
      z-index: 2;
    }

    h1 {
      color: #7f5af0;
      font-size: 36px;
      margin-bottom: 10px;
      animation: pop 0.6s ease-out;
    }

    .emoji {
      font-size: 50px;
      margin-bottom: 15px;
      animation: float 2s ease-in-out infinite;
    }

    p {
      font-size: 18px;
      color: #444;
    }

    a {
      display: inline-block;
      margin-top: 30px;
      background: #7f5af0;
      color: white;
      text-decoration: none;
      padding: 12px 26px;
      font-size: 16px;
      border-radius: 30px;
      transition: 0.3s ease;
    }

    a:hover {
      background: #9c7dfc;
    }

    @keyframes pop {
      0% { transform: scale(0.7); opacity: 0; }
      100% { transform: scale(1); opacity: 1; }
    }

    @keyframes float {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-8px); }
    }

    /* Confetti canvas */
    #confetti-canvas {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      pointer-events: none;
      z-index: 1;
    }
  </style>
</head>
<body>

<canvas id="confetti-canvas"></canvas>

<div class="thank-you-box">
  <div class="emoji">🥰🎉</div>
  <h1>Thank You!</h1>
  <p>Your order for <strong><?= htmlspecialchars($product) ?></strong> has been placed.</p>
  <p>Estimated Delivery: <strong><?= date('d M Y', strtotime($delivery)) ?></strong></p>
  <a href="index.php">← Continue Shopping</a>
</div>

<!-- Confetti JS -->
<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
<script>
  const duration = 5 * 1000;
  const end = Date.now() + duration;

  (function frame() {
    confetti({
      particleCount: 2,
      angle: 60,
      spread: 55,
      origin: { x: 0 }
    });
    confetti({
      particleCount: 2,
      angle: 120,
      spread: 55,
      origin: { x: 1 }
    });

    if (Date.now() < end) {
      requestAnimationFrame(frame);
    }
  })();
</script>

</body>
</html>