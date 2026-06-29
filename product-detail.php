<?php
session_start();
require_once 'config.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 1;
$query = "SELECT * FROM products WHERE id = $id LIMIT 1";
$result = mysqli_query($conn, $query);
$product = mysqli_fetch_assoc($result);

if (!$product) {
    $product = [
        'name' => 'Produk Tidak Ditemukan',
        'price' => 0,
        'description' => 'Maaf, produk yang Anda cari tidak tersedia. Silakan kembali ke katalog.',
        'image' => 'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?auto=format&fit=crop&w=600&q=80'
    ];
}
?>
<!DOCTYPE html>
<html lang="id">
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
        <a href="index.php">Beranda</a>
        <a href="katalog-products.php" class="active">Katalog</a>
        <?php if(isset($_SESSION['user_id'])): ?>
          <?php if($_SESSION['role'] === 'admin'): ?>
            <a href="dashboard.php" style="color: var(--primary); font-weight: 600;">Dashboard</a>
          <?php endif; ?>
          <span style="font-weight: 500;">Halo, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
          <a href="logout.php" class="btn btn-outline" style="padding: 8px 20px;">Log Out</a>
        <?php else: ?>
          <a href="log-in.php" class="btn btn-outline" style="padding: 8px 20px;">Log In</a>
        <?php endif; ?>
      </div>
    </div>
  </nav>

  <div class="container">
    <div class="breadcrumb">
      <a href="katalog-products.php">← Kembali ke Katalog</a>
    </div>
    
    <div class="product-detail-container">
      <div class="product-gallery">
        <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" onerror="this.src='https://images.unsplash.com/photo-1556228578-0d85b1a4d571?auto=format&fit=crop&w=600&q=80'" />
      </div>
      
      <div class="product-info">
        <h1 class="product-title"><?php echo htmlspecialchars($product['name']); ?></h1>
        <div style="display: flex; gap: 5px; color: #f1c40f; margin-bottom: 15px;">
          ★ ★ ★ ★ ★ <span style="color: var(--text-muted); font-size: 0.9rem; margin-left: 5px;">(50 Ulasan)</span>
        </div>
        
        <div class="product-price">Rp. <?php echo number_format($product['price'], 0, ',', '.'); ?></div>
        
        <div class="product-desc">
          <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
        </div>
        
        <div class="product-actions">
          <button class="btn btn-primary">🛒 Tambahkan ke Keranjang</button>
          <button class="btn btn-outline">❤️ Favorit</button>
        </div>
      </div>
    </div>
  </div>

</body>
</html>
