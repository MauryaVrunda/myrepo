<?php
require 'connect.php';

$query = "SELECT * FROM products";
$conditions = [];
$params = [];
$types = "";

// Search filter
$searchText = $_GET['search'] ?? "";
if (!empty($searchText)) {
    $conditions[] = "(name LIKE ? OR description LIKE ?)";
    $searchValue = "%" . $searchText . "%";
    $params[] = $searchValue;
    $params[] = $searchValue;
    $types .= "ss";
}

// Category filter
$selectedCategory = $_GET['category'] ?? "";
if (!empty($selectedCategory)) {
    $conditions[] = "category = ?";
    $params[] = $selectedCategory;
    $types .= "s";
}

// Price filter
$selectedPrice = $_GET['price'] ?? "";
if (!empty($selectedPrice)) {
    $conditions[] = "price <= ?";
    $params[] = intval($selectedPrice);
    $types .= "i";
}

// Apply filters to query
if (!empty($conditions)) {
    $query .= " WHERE " . implode(" AND ", $conditions);
}

// Sorting
$selectedSort = $_GET['sort'] ?? "";
if ($selectedSort === 'low') {
    $query .= " ORDER BY price ASC";
} elseif ($selectedSort === 'high') {
    $query .= " ORDER BY price DESC";
}

$stmt = $conn->prepare($query);

// Bind parameters if any
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Chic Charm Beads - Shop</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="styles/shop.css">
  <style>
    body{
      background: linear-gradient(to right,rgb(197, 247, 240),rgb(228, 207, 250));
    }
  .navbar {
    background-color: #222;
    padding: 15px 20px;
    color: #fff;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap:wrap;
  }

  .navbar .logo {
    font-weight: bold;
    font-size: 20px;
  }

  .navbar .nav-links {
    list-style: none;
    display: flex;
    gap: 20px;
    flex-wrap:wrap;
  }

  #filterForm select, #filterForm button {
    padding: 8px 12px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 14px;
    background-color: white;
    cursor: pointer;
  }

  .product-img {
    position: relative;
    width: 100%;
    height: 200px;
    overflow: hidden;
    border-radius: 10px;
  }

  .product-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: opacity 0.5s ease;
    position: absolute;
    top: 0;
    left: 0;
  }

  .product-img .hover-img {
    opacity: 0;
  }

  .product-img:hover .hover-img {
    opacity: 1;
  }

  .product-img:hover .main-img {
    opacity: 0;
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

<!-- Navigation -->
<nav class="navbar">
  <div class="logo">Chic Charm Beads</div>
  <ul class="nav-links">
    <li><a href="index.php">Home</a></li>
    <li><a href="shop.php" class="active">Shop</a></li>
    <li><a href="wishlist.php">Wishlist</a></li>
    <li><a href="cart.php">Cart</a></li>
    <li><a href="login.php">Account</a></li>
  </ul>
  <div class="search">
    <!-- Search Bar -->
<form method="GET" action="shop.php" style="display: flex;">
  <input type="text" name="search" placeholder="Search products..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>" style="padding: 8px; border-radius: 5px; border: 1px solid #ccc;" />
</form>
  </div>
</nav>

<!-- Filter Bar -->
<form method="GET" id="filterForm" style="margin: 20px 10px; display: flex; flex-wrap: wrap; gap: 15px; align-items: center;">
  <!-- Category Filter -->
  <select name="category" onchange="this.form.submit()">
    <option value="">All Categories</option>
    <option value="necklace" <?= $selectedCategory === 'necklace' ? 'selected' : '' ?>>Necklace</option>
    <option value="bracelet" <?= $selectedCategory === 'bracelet' ? 'selected' : '' ?>>Bracelet</option>
    <option value="keychains" <?= $selectedCategory === 'keychains' ? 'selected' : '' ?>>Keychains</option>
  </select>

  <!-- Price Filter -->
  <select name="price" onchange="this.form.submit()">
    <option value="">All Prices</option>
    <option value="49" <?= $selectedPrice === '49' ? 'selected' : '' ?>>Under ₹49</option>
    <option value="149" <?= $selectedPrice === '149' ? 'selected' : '' ?>>Under ₹149</option>
    <option value="299" <?= $selectedPrice === '299' ? 'selected' : '' ?>>Under ₹299</option>
  </select>

  <!-- Sort Filter -->
  <select name="sort" onchange="this.form.submit()">
    <option value="">Sort</option>
    <option value="low" <?= $selectedSort === 'low' ? 'selected' : '' ?>>Price: Low to High</option>
    <option value="high" <?= $selectedSort === 'high' ? 'selected' : '' ?>>Price: High to Low</option>
  </select>

  <!-- Remove Filters Button -->
  <?php if (!empty($_GET)): ?>
    <button type="button" onclick="window.location='shop.php'">❌ Remove Filters</button>
  <?php endif; ?>
</form>

<!-- Shop Grid Section -->
<main class="shop-section">
  <h1>
    <?php
    if (!empty($searchText)) {
    echo "Search Results for: <em>" . htmlspecialchars($searchText) . "</em>";
} elseif (!empty($selectedCategory)) {
    echo "Category: " . htmlspecialchars(ucfirst($selectedCategory));
} elseif (!empty($selectedPrice)) {
    echo "Products Under ₹" . htmlspecialchars($selectedPrice);
} else {
    echo "All Products";
}
    ?>
  </h1>

  <div class="product-grid">
    <?php while($product = $result->fetch_assoc()): ?>
      <div class="product-card">
        <a href="product.php?id=<?= $product['id'] ?>">
          <div class="product-img">
            <img src="images/<?= htmlspecialchars($product['image']) ?>" alt="main" class="main-img" />
            <img src="images/<?= htmlspecialchars($product['hover_image'] ?? $product['image']) ?>" alt="hover" class="hover-img" />
          </div>
        </a>
        <h4><?= htmlspecialchars($product['name']) ?></h4>
        <p>₹<?= htmlspecialchars($product['price']) ?></p>

        <!-- Add to Cart -->
        <form method="POST" action="add_to_cart.php" style="display:inline;">
          <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
          <button type="submit">🛒 Add to Cart</button>
        </form>

        <!-- Wishlist -->
        <form method="POST" action="add_to_wishlist.php" style="display:inline;">
          <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
          <button type="submit">🤍 Wishlist</button>
        </form>
      </div>
    <?php endwhile; ?>
  </div>
</main>

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