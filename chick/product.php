<?php
require 'includes/bootstrap.php';

if (!isset($_GET['id'])) {
  echo "❌ Product not specified.";
  exit;
}

$estimated_date = date('l, d M Y', strtotime('+5 days')); // 5-day delivery
$product_id = intval($_GET['id']);

// Fetch main product details
$product = find_product($conn, $product_id);

if (!$product) {
  echo "❌ Product not found!";
  exit;
}

// Fetch additional images from product_images table
$image_query = $conn->prepare("SELECT image_path FROM product_images WHERE product_id = ?");
$image_query->bind_param("i", $product_id);
$image_query->execute();
$image_result = $image_query->get_result();

$images = [];
while ($row = $image_result->fetch_assoc()) {
  $images[] = $row['image_path'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title><?= htmlspecialchars($product['name']) ?> - View</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <style>
    body {
      font-family: Arial, sans-serif;
      background: linear-gradient(to right,rgb(220, 248, 244),rgb(233, 221, 247));
      margin: 0;
      padding: 20px;
    }

    .container {
  max-width: 1000px;
  margin: auto;
  display: flex;
  flex-direction: row;
  gap: 40px;
  background: #fff;
  padding: 30px;
  border-radius: 10px;
  box-shadow: 0 0 8px rgba(0,0,0,0.1);
  flex-wrap: wrap;
}

@media (max-width: 768px) {
  .container {
    flex-direction: column;
    padding: 20px;
  }

  .main-image {
    height: auto;
    max-height: 300px;
  }

  .thumbnails {
    justify-content: center;
  }

  .product-details {
    margin-top: 20px;
  }

  .buttons {
    display: flex;
    flex-direction: column;
    gap: 10px;
  }

  .buttons button {
    width: 100%;
    margin-right: 0;
  }
}
    .image-gallery {
      flex: 1;
    }

    .main-image {
      width: 100%;
      height: 400px;
      object-fit: contain;
      border-radius: 10px;
      border: 1px solid #ccc;
    }

    .thumbnails {
      margin-top: 15px;
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
    }

    .thumbnail {
      width: 70px;
      height: 70px;
      cursor: pointer;
      border: 2px solid transparent;
      border-radius: 5px;
      object-fit: cover;
    }

    .thumbnail:hover {
      border-color: #007BFF;
    }

    .estimated-date {
  background: #ecfdf5;
  padding: 10px 15px;
  border-radius: 10px;
  display: inline-block;
  font-weight: bold;
  color: #065f46;
  margin-top: 10px;
}
    .product-details {
      flex: 1;
    }

    h2 {
      margin-bottom: 10px;
    }

    .price {
      font-size: 24px;
      color: #e91e63;
      margin: 10px 0;
    }

    .description {
      font-size: 16px;
      margin-top: 15px;
    }

    .buttons {
      margin-top: 30px;
    }

    .buttons button {
      background-color :#7f5af0;
      color: #fff;
      padding: 12px 20px;
      border: none;
      border-radius: 5px;
      margin-right: 10px;
      cursor: pointer;
      font-size: 16px;
    }

    .buttons button:hover {
      background-color: #9c7dfc;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="image-gallery">
      <img id="mainImage" src="images/<?= htmlspecialchars($product['image']) ?>" alt="Product" class="main-image" />

      <div class="thumbnails">
        <!-- Main image as first thumbnail -->
        <img src="images/<?= htmlspecialchars($product['image']) ?>" class="thumbnail" onclick="changeImage(this.src)" />

        <!-- Additional thumbnails -->
        <?php foreach ($images as $img): ?>
          <img src="images/<?= htmlspecialchars($img) ?>" class="thumbnail" onclick="changeImage(this.src)" />
        <?php endforeach; ?>
      </div>
    </div>

    <div class="product-details">
      <h2><?= htmlspecialchars($product['name']) ?></h2>
      <div class="price">₹<?= htmlspecialchars($product['price']) ?></div>
      <p class="estimated-date">📅 Estimated Delivery: <?= $estimated_date ?></p>
      <p class="description"><?= nl2br(htmlspecialchars($product['description'])) ?></p>
      <div class="buttons">
<form method="POST" action="add_to_cart.php" style="display:inline;">
      <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
      <button type="submit">🛒 Add to Cart</button>
    </form>

    <!-- Add to Wishlist Form -->
    <form method="POST" action="add_to_wishlist.php" style="display:inline;">
      <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
      <button type="submit">🤍 Wishlist</button><br><br>
    </form>

    <?php if (is_logged_in()): ?>
  <form action="place_order.php" method="POST">
    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
    <button type="submit">Place Order</button>
  </form>
<?php else: ?>
  <p><a href="login.php">Login</a> to place an order.</p>
<?php endif; ?>

      </div>
    </div>
  </div>

  <script>
    function changeImage(src) {
      document.getElementById('mainImage').src = src;
    }
  </script>
</body>
</html>