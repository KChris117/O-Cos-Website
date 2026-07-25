<?php
session_start();
require_once 'config.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 1;
$stmt_prod = mysqli_prepare($conn, "SELECT * FROM products WHERE id = ? LIMIT 1");
mysqli_stmt_bind_param($stmt_prod, "i", $id);
mysqli_stmt_execute($stmt_prod);
$result = mysqli_stmt_get_result($stmt_prod);
$product = mysqli_fetch_assoc($result);

if (!$product) {
    $product = [
        'name' => 'Product Not Found',
        'price' => 0,
        'description' => 'Sorry, the product you are looking for is not available. Please return to the catalog.',
        'image' => 'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?auto=format&fit=crop&w=600&q=80'
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?php echo htmlspecialchars($product['name']); ?> - O'Cos</title>
  <link rel="stylesheet" href="./styles/global.css"/>
  <style>
    .product-detail-page {
      padding-top: 120px;
      padding-bottom: 80px;
    }
    .breadcrumb {
      margin-bottom: 30px;
      color: var(--text-muted);
    }
    .breadcrumb a {
      color: var(--secondary);
      font-weight: 500;
    }
    .breadcrumb a:hover {
      color: var(--primary);
    }
    .product-detail-container {
      display: flex;
      gap: 50px;
      background: var(--bg-card);
      padding: 40px;
      border-radius: 20px;
      box-shadow: var(--shadow-sm);
    }
    .product-gallery {
      flex: 1;
      border-radius: 16px;
      overflow: hidden;
      background: var(--accent);
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }
    .product-gallery img {
      width: 100%;
      max-width: 400px;
      mix-blend-mode: multiply;
    }
    .product-info {
      flex: 1;
    }
    .product-title {
      font-size: 2.2rem;
      color: var(--primary-dark);
      margin-bottom: 10px;
    }
    .product-price {
      font-size: 1.8rem;
      font-weight: 700;
      color: var(--secondary);
      margin-bottom: 20px;
    }
    .product-desc {
      color: var(--text-muted);
      line-height: 1.8;
      margin-bottom: 30px;
    }
    .product-actions {
      display: flex;
      gap: 15px;
    }
    .product-actions .btn {
      flex: 1;
    }
    
    @media (max-width: 768px) {
      .product-detail-container {
        flex-direction: column;
        padding: 20px;
      }
    }
  </style>
</head>
<body class="product-detail-page">

  <!-- Navigation -->
  <nav class="navbar">
    <div class="container">
      <a href="index.php" class="nav-logo">O'Cos</a>
      <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="about-us.php">About Us</a>
        <a href="katalog-products.php" class="active">Catalog</a>
        <?php if(isset($_SESSION['user_id'])): ?>
          <a href="favorites.php" style="color: var(--primary-dark); font-weight: 600;">❤️ Favorites</a>
          <a href="my-orders.php" style="color: var(--primary-dark); font-weight: 600;">📦 My Orders</a>
          <a href="cart.php" style="color: var(--primary-dark); font-weight: 600;">🛒 Cart</a>
          <?php if($_SESSION['role'] === 'admin'): ?>
            <a href="dashboard.php" style="color: var(--primary); font-weight: 600;">Dashboard</a>
          <?php endif; ?>
          <a href="logout.php" class="btn btn-outline" style="padding: 8px 20px;">Log Out</a>
        <?php else: ?>
          <a href="log-in.php" class="btn btn-outline" style="padding: 8px 20px;">Log In</a>
        <?php endif; ?>
      </div>
    </div>
  </nav>

  <div class="container">
    <div class="breadcrumb">
      <a href="katalog-products.php">← Back to Catalog</a>
    </div>
    
    <div class="product-detail-container">
      <div class="product-gallery">
        <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" onerror="this.src='https://images.unsplash.com/photo-1556228578-0d85b1a4d571?auto=format&fit=crop&w=600&q=80'" />
      </div>
      
      <div class="product-info">
        <h1 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h1>
        <div style="display: flex; gap: 5px; color: #f1c40f; margin-bottom: 15px;">
          ★ ★ ★ ★ ★ <span style="color: var(--text-muted); font-size: 0.9rem; margin-left: 5px;">(50 Reviews)</span>
        </div>
        
        <div class="product-price">Rp. <?php echo number_format($product['price'], 0, ',', '.'); ?></div>
        
        <div class="product-desc">
          <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
        </div>
        
        <div class="product-actions">
          <?php if(isset($_SESSION['user_id'])): ?>
            <form action="cart.php" method="POST" style="flex: 1; display: flex;">
              <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
              <input type="hidden" name="action" value="add" />
              <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>" />
              <button type="submit" class="btn btn-primary" style="width: 100%;">🛒 Add to Cart</button>
            </form>
            
            <?php
            // Check if already favorited
            $user_id = $_SESSION['user_id'];
            $stmt_fav = mysqli_prepare($conn, "SELECT id FROM favorites WHERE user_id = ? AND product_id = ?");
            mysqli_stmt_bind_param($stmt_fav, "ii", $user_id, $product['id']);
            mysqli_stmt_execute($stmt_fav);
            $fav_check = mysqli_stmt_get_result($stmt_fav);
            $is_favorited = mysqli_num_rows($fav_check) > 0;
            ?>
            <form action="favorites.php" method="POST" style="flex: 1; display: flex;">
              <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
              <input type="hidden" name="action" value="<?php echo $is_favorited ? 'remove' : 'add'; ?>" />
              <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>" />
              <?php if($is_favorited): ?>
                <button type="submit" class="btn btn-outline" style="width: 100%; background: #ffebee; color: #c62828; border-color: #ffcdd2;">❤️ Favorited</button>
              <?php else: ?>
                <button type="submit" class="btn btn-outline" style="width: 100%;">🤍 Favorite</button>
              <?php endif; ?>
            </form>
          <?php else: ?>
            <button class="btn btn-primary" onclick="window.location.href='log-in.php'" style="flex: 1;">🛒 Login to Buy</button>
            <button class="btn btn-outline" onclick="window.location.href='log-in.php'" style="flex: 1;">🤍 Favorite</button>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>

</body>
</html>
