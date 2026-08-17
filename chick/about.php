<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>About Us | Chic Charm Beads</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: 'Segoe UI', sans-serif;

background: linear-gradient(to right,rgb(220, 248, 244),rgb(233, 221, 247));
      color: #5b0a1b;
      overflow-x: hidden;
      position: relative;
    }

    .about-container {
      max-width: 900px;
      margin: 60px auto;
      background: #f0f3f2;
      padding: 40px;
      border-radius: 20px;
      box-shadow: 0 0 12px rgba(0,0,0,0.1);
      animation: fadeIn 1s ease;
      position: relative;
      z-index: 10;
    }

    h1 {
      text-align: center;
      font-size: 2.5rem;
       color: #7f5af0;
    }

    p {
      font-size: 1.1rem;
      line-height: 1.8;
      margin: 20px 0;
    }

    .highlight {
      font-weight: bold;
       color: #7f5af0;    }

    .gallery {
      display: flex;
      justify-content: center;
      gap: 20px;
      margin-top: 30px;
      flex-wrap: wrap;
    }

    .gallery img {
      width: 180px;
      border-radius: 12px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }

    /* Confetti */
    .confetti {
      position: fixed;
      width: 100%;
      height: 100%;
      pointer-events: none;
      top: 0;
      left: 0;
      z-index: 1;
    }

    .confetti span {
      position: absolute;
      width: 8px;
      height: 8px;
      border-radius: 50%;
      opacity: 0.7;
      animation: fall 6s linear infinite;
    }

    @keyframes fall {
      0% {
        transform: translateY(-10vh) rotate(0deg);
        opacity: 1;
      }
      100% {
        transform: translateY(120vh) rotate(720deg);
        opacity: 0;
      }
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to   { opacity: 1; transform: translateY(0); }
    }

    .footer {
  background: #222;
  color: white;
  padding: 20px;
  text-align: center;
  font-size: 14px;
}

.footer a {
  color: #fff;
  text-decoration: underline;
  margin: 0 5px;
}
  </style>
</head>
<body>

<!-- Confetti Sprinkles -->
<div class="confetti">
  <?php for ($i = 0; $i < 50; $i++): ?>
    <span style="
      left: <?= rand(0, 100) ?>vw;
      background-color: hsl(<?= rand(320, 360) ?>, 90%, 85%);
      animation-delay: <?= rand(0, 3000) / 1000 ?>s;
    "></span>
  <?php endfor; ?>
</div>

<!-- About Content -->
<div class="about-container">
  <h1>🌸 About Chic Charm Beads</h1>
  
  <p>
    Welcome to <span class="highlight">Chic Charm Beads</span> — where imagination meets elegance!
  </p>

  <p>
    We’re a small but passionate team crafting <span class="highlight">handmade accessories</span> with love and sparkle. From adorable earrings to custom beaded keychains, each piece is created to express joy, identity, and charm.
  </p>

  <p>
    What started as a hobby, bloomed into a brand — built with patience, creativity, and dreams. Our mission is to spread smiles through tiny, thoughtful, and vibrant handmade treasures.
  </p>

  <p>
    Every bead tells a story. Let’s create yours ✨
  </p>

  <div class="gallery">
    <img src="images/product1.jpeg" alt="Beaded Earring">
    <img src="images/product10.jpeg" alt="Keychains">
    <img src="images/product9.jpeg" alt="Bracelet">
  </div>
</div>
<!-- Footer -->
<footer class="footer">
  <div class="contact">
    <p>📞 +91 9328594884 | ✉️ vrundamaurya07@gmail.com</p>
    <a href="about.php">About Us</a><br>
    <a href="contact.html">Contact Us</a>
  </div>
  <div class="social">
    <a href="#">Instagram</a> |
    <a href="#">Facebook</a> |
    <a href="#">Pinterest</a> 
  </div>
  <p>&copy; <?= date("Y") ?> Chic Charm Beads. All rights reserved.</p>
</footer>

</body>
</html>