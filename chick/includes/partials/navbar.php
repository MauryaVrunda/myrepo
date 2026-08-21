<?php
// Storefront navigation bar.
// Optional variables: $active (link target of the current page), $navbar_extra (extra HTML on the right).
$nav_links = [
    'index.php' => 'Home',
    'shop.php' => 'Shop',
    'wishlist.php' => 'Wishlist',
    'cart.php' => 'Cart',
    'user_dashboard.php' => 'Account',
];
$active = $active ?? '';
$navbar_extra = $navbar_extra ?? '';
?>
<nav class="navbar">
  <div class="logo">Chic Charm Beads</div>
  <ul class="nav-links">
    <?php foreach ($nav_links as $href => $label): ?>
      <li><a href="<?= $href ?>"<?= $active === $href ? ' class="active"' : '' ?>><?= $label ?></a></li>
    <?php endforeach; ?>
  </ul>
  <?php if (!empty($navbar_extra)): ?>
    <div class="search"><?= $navbar_extra ?></div>
  <?php endif; ?>
</nav>
