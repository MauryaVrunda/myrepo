<?php
session_start();
require 'connect.php';

// Redirect if user is not logged in
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

$user_id = $_SESSION['user_id'];

// Fetch wishlist products
$sql = "SELECT p.id, p.name, p.price, p.image 
        FROM wishlist w
        JOIN products p ON w.product_id = p.id
        WHERE w.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>My Wishlist - Chic Charm Beads</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
     background: linear-gradient(to right,rgb(197, 247, 240),rgb(228, 207, 250));
    }

    .container {
      max-width: 1000px;
      margin: auto;
    }

    .navbar {
      background-color: #222;
      color: white;
      padding: 1rem 2rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap:wrap;
    }

    .navbar .logo {
      font-size: 24px;
      font-weight: bold;
    }

    .navbar ul {
      list-style: none;
      display: flex;
      gap: 20px;
      flex-wrap:wrap;
    }

    .navbar li a {
      color: white;
      text-decoration: none;
      padding: 6px 10px;
    }

    .navbar li a:hover {
      background-color: #555;
      border-radius: 4px;
    }

    h2 {
      text-align: center;
      margin: 30px 0 10px;
      color: #333;
    }

    .wishlist-container {
      padding: 20px;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
    

    }

    .product-card {
      width: 220px;
     background-color: #f9f9f9;
      padding: 15px;
      border-radius: 10px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.05);
      text-align: center;
    }

    .product-card img {
         max-width: 100%;
    height: 150px;
    object-fit: contain;
    border-radius: 8px;
    }

    .product-card h4 {
      margin: 10px 0 5px;
    }

    .product-card p {
      color: #e91e63;
      font-weight: bold;
      font-size: 18px;
    }

    .product-card a button {
      padding: 8px 14px;
     background-color: #7f5af0;
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-weight: bold;
      margin-top: 10px;
    }

    .product-card a button:hover {
      background-color: #9c7dfc;
    }
      .footer-buttons {
      text-align: center;
      margin-top: 30px;
    }
    .footer-buttons a {
      text-decoration: none;
      background-color: #7f5af0;
      color: white;
      padding: 10px 20px;
      border-radius: 6px;
      margin: 0 10px;
      font-weight: bold;
    }
       .footer-buttons a:hover{
        background-color: #9c7dfc;
       }

    .empty {
      text-align: center;
      font-size: 18px;
      margin-top: 50px;
      color: #777;
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

<!-- Navbar -->
<div class="navbar">
  <div class="logo">Chic Charm Beads</div>
  <ul>
    <li><a href="index.php">Home</a></li>
    <li><a href="shop.php">Shop</a></li>
    <li><a href="wishlist.php">Wishlist</a></li>
    <li><a href="cart.php">Cart</a></li>
    <li><a href="user_dashboard.php">Account</a></li>
  </ul>
</div>

<!-- Wishlist Section -->
<div class="container">
<h2>Your Wishlist</h2>

<?php if ($result->num_rows > 0): ?>
  <div class="wishlist-container">
    <?php while ($row = $result->fetch_assoc()): ?>
      <div class="product-card">
        <a href="product.php?id=<?= $row['id'] ?>">
          <img src="images/<?= htmlspecialchars($row['image']) ?>" alt="<?= htmlspecialchars($row['name']) ?>">
        </a>
        <h4><?= htmlspecialchars($row['name']) ?></h4>
        <p>₹<?= htmlspecialchars($row['price']) ?></p>
        <a href="remove_from_wishlist.php?id=<?= $row['id'] ?>"><button>Remove</button></a>
      </div>
    <?php endwhile; ?>
  </div>
     <div class="footer-buttons">
      <a href="shop.php">← Continue Shopping</a>
      
    </div>
<?php else: ?>
  <p class="empty">🩷 Your wishlist is empty. Start adding beautiful beads!</p>
   <div class="footer-buttons">
      <a href="shop.php">← Start Shopping</a>
</div>  
<?php endif; ?>
</div>
<!-- Footer -->
<br><br><br><footer class="footer">
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