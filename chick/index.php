<?php
require 'includes/bootstrap.php';



// Fetch 10 featured products
$sql = "SELECT * FROM products ORDER BY id DESC LIMIT 10";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Chic Charm Beads - Home</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="styles/home.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<style>
    * {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: 'Segoe UI', sans-serif;
  background: #f5f5f5;
  color: #333;
}

.navbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: #222;
  padding: 1rem 2rem;
  color: white;
  flex-wrap:wrap;
}

.navbar .logo {
  font-size: 24px;
  font-weight: bold;
}

.nav-links {
  list-style: none;
  display: flex;
  gap: 20px;
  flex-wrap:wrap;
}

.nav-links li a {
  color: white;
  text-decoration: none;
  font-weight: 500;
}

.navbar .nav-links li a {
  color: white;
  text-decoration: none;
  padding: 6px 10px;
}
.navbar .nav-links li a.active,
.navbar .nav-links li a:hover {
  background-color: #555;
  border-radius: 4px;
}

.search input {
  padding: 6px 12px;
  border-radius: 5px;
  border: none;
}

.hero {
  text-align: center;
  padding: 80px 20px;
       background: linear-gradient(to right,rgb(168, 255, 242),rgb(205, 159, 255));
  color: white;
}

.hero .cta {
  display: inline-block;
  margin-top: 20px;
  padding: 10px 25px;
  background: #fff;
  color: #7f5af0;

  border-radius: 25px;
  text-decoration: none;
  font-weight: bold;
}

    /* Carousel */
    .carousel {
      position: relative;
      overflow: hidden;
      max-width: 100%;
    }

    .slides {
      display: flex;
      transition: transform 0.5s ease-in-out;
    }

    .slide {
      min-width: 100%;
      position: relative;
    }

    .slide img {
      width: 100%;
      height: auto;
      display: block;
      object-fit: cover;
    }

    .slide-text {
      position: absolute;
      top: 40%;
      left: 10%;
      color: white;
      background: rgba(0, 0, 0, 0.4);
      padding: 20px;
      border-radius: 10px;
    }

    .slide-text h2 {
      font-size: 32px;
      margin-bottom: 10px;
    }

    .slide-text p {
      font-size: 18px;
    }

    .slide-text a {
      display: inline-block;
      margin-top: 10px;
      padding: 8px 20px;
      background-color: #9c7dfc;
      color: black;
      border-radius: 20px;
      text-decoration: none;
    }

    /* Arrows */
    .prev, .next {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      background: rgba(0,0,0,0.4);
      color: white;
      border: none;
      padding: 10px;
      cursor: pointer;
      font-size: 20px;
      z-index: 10;
    }

    .prev { left: 10px; }
    .next { right: 10px; }

    /* Dots */
    .dots {
      position: absolute;
      left:47%;
      text-align: center;
      margin-top: 10px;
    }
    .dot {
      display: inline-block;
      width: 12px;
      height: 12px;
      background-color: #ccc;
      border-radius: 50%;
      margin: 0 5px;
      cursor: pointer;
    }
    .dot.active { background-color: #7f5af0; }

.featured {
  padding: 40px 20px;
  background:rgb(191, 181, 223);
}

.featured h2 {
  text-align: center;
  margin-bottom: 20px;
}

.scroll-container {
  display: flex;
  overflow-x: auto;
  gap: 20px;
  padding: 10px;
  scroll-snap-type: x mandatory;
}

.product-card {
  min-width: 220px;
  background: #fefefe;
  border-radius: 10px;
  padding: 10px;
  text-align: center;
  scroll-snap-align: start;
  box-shadow: 0 0 10px rgba(0,0,0,0.1);
}

.product-card img {
  width: 100%;
 
  object-fit: cover;
  border-radius: 10px;
}

.product-card h4 {
  margin: 10px 0 5px;
}

.product-card button {
  padding: 8px 12px;
  margin-top: 10px;
        background-color: #7f5af0;

  color: white;
  border: none;
  border-radius: 6px;
  cursor: pointer;
}

.product-card button:hover{
          background-color: #9c7dfc;
}

.blob-card {
  padding: 15px;
  text-align: center;
  background: #fff;
  border-radius: 16px;
  box-shadow: 0 0 12px rgba(0,0,0,0.1);
  transition: transform 0.3s ease;
}

.blob-card:hover {
  transform: translateY(-6px);
}

.blob-image-container {
  width: 100%;
  aspect-ratio: 3/4;
  clip-path: url(#blobPath);
  transition: clip-path 0.6s ease;
}

.blob-card:hover .blob-image-container {
  clip-path: url(#blobPathHover);
}

.blob-image {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.4s ease-in-out;
}

.blob-card:hover .blob-image {
  transform: scale(1.08);
}
.wave-divider {
  width: 100%;
  overflow: hidden;
  line-height: 0;
  background-color:rgb(191, 181, 223);
}

.wave-divider svg {
  display: block;
  width: 100%;
  height: 100px;
}

.wave-bottom {
  width: 100%;
  overflow: hidden;
  line-height: 0;
  position: relative;
   background-color:rgb(191, 181, 223);
}

.wave-bottom svg {
  display: block;
  width: 100%;
  height: 100px;
  transform: rotate(180deg); 
}

/*price-category*/
#portfolio{
    background:#fefefe;
    color:#fff;
    padding: 50px 0;
}
.work-list{
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    grid-gap: 40px;
    margin-top: 50px;
}
.work{
    border-radius: 10px;
    position: relative;
    overflow: hidden;
   
}
.work img{
    width: 100%;
    height: 300px;
    border-radius: 10px;
    display: block;
    transition: transform 0.5s;
}
.layer{
    width: 100%;
    height: 0;
    background: linear-gradient(rgba(255, 255, 255, 0.5),rgb(205, 159, 255));
    border-radius: 10px;
    position: absolute;
    left: 0;
    bottom: 0;
    overflow: hidden;
    justify-content: center;  
    padding: 0 40px;
    text-align: center;
    font-size: 14px;
    transition: height 0.5s;
}
.layer p{
        font-size: 18px;
}
.layer h3{
     font-size: 32px;
      margin-bottom: 10px;
}
.layer a{
      display: inline-block;
      margin-top: 10px;
      padding: 8px 20px;
      background-color: #9c7dfc;
      color: black;
      border-radius: 20px;
      text-decoration: none;
}
.work:hover img{
    transform: scale(1.1);
}
.work:hover .layer{
    height: 100%;
}

.why-choose {
  padding: 50px 20px;
 background-color:rgb(191, 181, 223);
}

.why-choose h2 {
  text-align: center;
  margin-bottom: 30px;
}

.reasons {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 20px;
}

.reason {
  background: white;
  padding: 20px;
  border-left: 5px solid  #7f5af0;
  border-radius: 10px;
  box-shadow: 0 0 8px rgba(0, 0, 0, 0.05);
}

.footer {
  background: #222;
  color: white;
  padding: 30px 20px 10px;
  font-size: 14px;
  width: 100%;
}

.footer-main {
  display: flex;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 30px;
}

.footer-left,
.footer-right {
  flex: 1;
  min-width: 250px;
}

.footer-left p {
  margin: 6px 200px;
}

.footer-right h4 {
  margin-bottom: 10px;
  color: #f0e68c;
}

.footer-right ul {
  list-style: none;
  padding: 0;
  margin: 0 200px;
}

.footer-right ul li {
  margin: 8px 0;
}

.footer-right a {
  color: #ddd;
  text-decoration: none;
}

.footer-right a i {
  margin-right: 8px;
}

.footer-right a:hover {
  color: #fff;
  text-decoration: underline;
}

.social a {
  display: inline-block;
  margin-right: 10px;
  color: #ddd;
  text-decoration: none;
}

.social a i {
  margin-right: 6px;
}

.footer-bottom {
  text-align: center;
  padding-top: 15px;
  margin-top: 20px;
  border-top: 1px solid #444;
  font-size: 13px;
  width: 100%;
}

/* Mobile Responsive */
@media (max-width: 600px) {
  .footer-main {
    flex-direction: column;
    text-align: center;
  }

  .footer-left,
  .footer-right {
    align-items: center;
  }

  .footer-right ul {
    padding-left: 0;
  }

  .footer-right ul li {
    display: inline-block;
    margin: 6px 10px;
  }
}
</style>
<body>
  <svg width="0" height="0">
  <defs>
    <clipPath id="blobPath" clipPathUnits="objectBoundingBox">
      <path d="M0.5,0 
               C0.7,0, 1,0.3, 1,0.5 
               C1,0.7, 0.7,1, 0.5,1 
               C0.3,1, 0,0.7, 0,0.5 
               C0,0.3, 0.3,0, 0.5,0Z" />
    </clipPath>
    <clipPath id="blobPathHover" clipPathUnits="objectBoundingBox">
      <path d="M0.5,0 
               C0.9,0, 1,0.3, 1,0.5 
               C1,0.9, 0.7,1, 0.5,1 
               C0.3,1, 0,0.9, 0,0.5 
               C0,0.3, 0.3,0, 0.5,0Z" />
    </clipPath>
  </defs>
</svg>
<!-- Navigation -->
<nav class="navbar">
  <div class="logo">Chic Charm Beads</div>
  <ul class="nav-links">
    <li><a href="#"><i class="fas fa-home"></i></a></li>
    <li><a href="shop.php"><i class="fas fa-store"></i></a></li>
    <li><a href="wishlist.php"><i class="fas fa-heart"></i> </a></li>
    <li><a href="cart.php"><i class="fas fa-shopping-cart"></i></a></li>
    <?php if (is_admin()): ?>
    <li><a href="admin_dashboard.php"><i class="fas fa-user"></i></a></li>
<?php elseif (is_logged_in()): ?>
    <li><a href="user_dashboard.php"><i class="fas fa-user"></i></a></li>
<?php else: ?>
    <li><a href="login.php"><i class="fas fa-user"></i></a></li>
<?php endif; ?>
  </ul>
  <div class="search">
    <!-- Search Bar -->
<form method="GET" action="shop.php" style="display: flex;">
  <input type="text" name="search" placeholder="Search products..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" style="padding: 8px; border-radius: 5px; border: 1px solid #ccc;" />
</form>
  </div>
</nav>

<!-- Hero Section -->
<header class="hero">
  <h1>Welcome to Chic Charm Beads</h1>
  <p>Discover handmade elegance & style in every piece.</p>
  <a href="shop.php" class="cta">Shop Now</a>
</header>

<!-- Carousel Section -->
<div class="carousel">
  <div class="slides" id="slides">
    <div class="slide">
      <img src="images/product1.jpeg" alt="Banner 1" style="width:100%; height:350px; object-fit:cover;">
      <div class="slide-text">
        <h2>Chic Necklaces</h2>
        <p>Glam up your look today</p>
        <a href="shop.php?category=necklace">Shop Necklaces</a>
      </div>
    </div>
    <div class="slide">
      <img src="images/product10.jpeg" alt="Banner 2" style="width:100%; height:350px; object-fit:cover;">
      <div class="slide-text">
        <h2>Handmade Bracelets</h2>
        <p>Elegant, light & beautiful</p>
        <a href="shop.php?category=bracelet">Explore Now</a>
      </div>
    </div>
    <div class="slide">
      <img src="images/slider3.jpeg" alt="Banner 3" style="width:100%; height:350px; object-fit:cover;">
      <div class="slide-text">
        <h2>Beaded Keychains</h2>
        <p>Style that hangs with you</p>
        <a href="shop.php?category=keychains">Get Yours</a>
      </div>
    </div>
  </div>
  <button class="prev" onclick="prevSlide()">❮</button>
  <button class="next" onclick="nextSlide()">❯</button>
</div>
<div class="dots">
  <span class="dot active" onclick="showSlide(0)"></span>
  <span class="dot" onclick="showSlide(1)"></span>
  <span class="dot" onclick="showSlide(2)"></span>
</div>


<!-- Featured Products -->
<section class="featured" id="featured">
  <br><br><br><h2>Featured Products</h2>
  <div class="scroll-container">
    <?php while($product = $result->fetch_assoc()): ?>
  <div class="product-card blob-card">
  <div class="blob-image-container">
         <a href="product.php?id=<?= $product['id'] ?>">
          <img class="blob-image" src="images/<?= $product['image'] ?>" alt="<?= htmlspecialchars($product['name']) ?>">
        </a>
  </div>
  <h4><?= htmlspecialchars($product['name']) ?></h4>
  <p>₹<?= htmlspecialchars($product['price']) ?></p>
    <!-- Add to Cart Form -->
    <form method="POST" action="add_to_cart.php" style="display:inline;">
      <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
      <button type="submit">🛒 Add to Cart</button>
    </form>

    <!-- Add to Wishlist Form -->
    <form method="POST" action="add_to_wishlist.php" style="display:inline;">
      <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
      <button type="submit">🤍</button>
    </form>
  </div>
<?php endwhile; ?>

  </div>
</section>
<!-- Static Wave Divider -->
<div class="wave-divider">
  <svg viewBox="0 0 1440 320" preserveAspectRatio="none">
    <path fill="#fff" 
          d="M0,128L48,138.7C96,149,192,171,288,170.7C384,171,480,149,576,160C672,171,768,213,864,218.7C960,224,1056,192,1152,176C1248,160,1344,160,1392,160L1440,160L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z">
    </path>
  </svg>
</div>


<!--price-catagory-->

<div id="portfolio">
    <div class="container">
        <div class="work-list">
            <div class="work">
                <img src="images/u1.jpeg" >
                <div class="layer">
                  <div class="slide-text">
                    <h3>Under</h3>
                    <p><strong>49/-</strong><br>----- Budget-friendly picks! -----</p>
                    <a href="shop.php?price=49"><strong>Explore Now</strong></a>
                  </div>
                </div>
            </div>
            <div class="work">
                <img src="images/u2.jpeg" >
                <div class="layer">
                <div class="slide-text">
                <h3>Under</h3>
                <p><strong>149/-</strong><br>----- Popular & cute pieces! -----</p>
                <a href="shop.php?price=149"><strong>Explore Now</strong></a>
                </div>
            </div>
            </div>
            <div class="work">
                <img src="images/u3.jpeg" >
                <div class="layer">
                 <div class="slide-text"> 
                <h3>Under</h3>
                <p><strong>299/-</strong><br>----- Premium yet affordable! -----</p>
                <a href="shop.php?price=299"><strong>Explore Now</strong></a></div>
                </div>
            </div>
        </div> 
    </div>
</div>

<!-- Wave at Bottom -->
<div class="wave-bottom">
  <svg viewBox="0 0 1440 320" preserveAspectRatio="none">
    <path fill="#fff" d="M0,96L48,122.7C96,149,192,203,288,208C384,213,480,171,576,160C672,149,768,171,864,160C960,149,1056,107,1152,117.3C1248,128,1344,192,1392,224L1440,256L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z">
    </path>
  </svg>
</div>
<!-- Why Choose Us -->
<section class="why-choose">
  <h2>Why Choose Chic Charm Beads?</h2>
  <div class="reasons">
    <div class="reason">
      <h3><img src="icons/handcraft.png" alt="handcrafted" style="width:50px; vertical-align: middle;"><br> Handcrafted Design</h3>
      <p>Each product is made by hand with precision, patience, and passion - giving every bead its own unique soul. we believe in art over mass production.</p>
    </div>
    <div class="reason">
      <h3><img src="icons/quality.png" alt="premium materials" style="width:50px; vertical-align: middle;"><br>Premium Materials</h3>
      <p>We use only top-grade beads, wires and findings to ensure youe accessory is not just pretty, but long-lasting and safe to wear.</p>
    </div>
    <div class="reason">
      <h3><img src="icons/gear.png" alt="Customization" style="width:50px; vertical-align: middle;"><br>Customozation</h3>
      <p>Want something personal? We craft products that refects you - your vibe, your moments, your magic. Just tell us what you love!</p>
    </div>
    <div class="reason">
      <h3><img src="icons/bracelet.png" alt="trendy" style="width:50px; vertical-align: middle;"><br>Trendy & Stylish</h3>
      <p>From everyday chic to to festival glam our designs follow the latest trends - made to keep you effortlessly stylish always.</p>
    </div>
  </div>
</section>

<footer class="footer">
  <div class="footer-main">
    <!-- Left: Contact & Social -->
    <div class="footer-left">
      <p><i class="fas fa-phone-alt"></i> +91 9328594884</p>
      <p><i class="fas fa-envelope"></i> vrundamaurya07@gmail.com</p>
      <div class="social">
        <a href="#"><i class="fab fa-instagram"></i> Instagram</a><br>
        <a href="#"><i class="fab fa-facebook"></i> Facebook</a><br>
        <a href="#"><i class="fab fa-pinterest"></i> Pinterest</a>
      </div>
    </div>

    <!-- Right: Quick Links -->
    <div class="footer-right">
      <h4>Quick Links</h4>
      <ul>
        <li><a href="index.php"><i class="fas fa-home"></i> Home</a></li>
        <li><a href="shop.php"><i class="fas fa-store"></i> Shop</a></li>
        <li><a href="wishlist.php"><i class="fas fa-heart"></i> Wishlist</a></li>
        <li><a href="cart.php"><i class="fas fa-shopping-cart"></i> Cart</a></li>
        <li><a href="user_dashboard.php"><i class="fas fa-user"></i> My Account</a></li>
        <li><a href="about.php"><i class="fas fa-info-circle"></i> About Us</a></li>
        <li><a href="contact.html"><i class="fas fa-envelope-open-text"></i> Contact Us</a></li>
      </ul>
    </div>
  </div>

  <!-- Bottom -->
  <div class="footer-bottom">
    <p>&copy; <?= date("Y") ?> Chic Charm Beads. All rights reserved.</p>
    <p>Developed by <strong>Vrunda Maurya</strong></p>
  </div>
</footer>
<script>
let currentSlide = 0;
const slides = document.getElementById("slides");
const dots = document.querySelectorAll(".dot");

function showSlide(index) {
  slides.style.transform = `translateX(-${index * 100}%)`;
  dots.forEach(dot => dot.classList.remove("active"));
  dots[index].classList.add("active");
  currentSlide = index;
}

function nextSlide() {
  currentSlide = (currentSlide + 1) % 3;
  showSlide(currentSlide);
}

function prevSlide() {
  currentSlide = (currentSlide - 1 + 3) % 3;
  showSlide(currentSlide);
}

setInterval(nextSlide, 2000); // auto-slide every 2 sec
</script>

</body>
</html>