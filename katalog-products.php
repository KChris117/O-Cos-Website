<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Katalog Produk - O'Cos</title>
  <link rel="stylesheet" href="./styles/global.css"/>
  <link rel="stylesheet" href="./styles/katalog.css"/>
</head>
<body class="katalog-page">

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
    <div class="katalog-header">
      <h1 class="katalog-title">Katalog Produk O'Cos</h1>
      <p class="katalog-subtitle">Temukan koleksi lengkap kosmetik premium untuk berbagai kebutuhan Anda.</p>
    </div>
    
    <div class="katalog-grid">
      <?php
      require_once 'config.php';
      
      $query = "SELECT * FROM products ORDER BY id ASC";
      $result = mysqli_query($conn, $query);

      if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
      ?>
      <div class="product-card">
        <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" class="product-image" onerror="this.src='https://images.unsplash.com/photo-1556228578-0d85b1a4d571?auto=format&fit=crop&w=300&q=80'" />
        <h3 class="product-name"><?php echo htmlspecialchars($row['name']); ?></h3>
        <p class="product-price">Rp. <?php echo number_format($row['price'], 0, ',', '.'); ?></p>
        <button class="btn btn-outline product-btn" onclick="window.location.href='product-detail.php?id=<?php echo $row['id']; ?>'">Detail Produk</button>
      </div>
      <?php
          }
      } else {
          echo "<p style='grid-column: 1 / -1; text-align: center; color: var(--text-muted); padding: 40px;'>Belum ada produk di database. Silakan jalankan script SQL.</p>";
      }
      ?>
    </div>

    <!-- Pagination controls -->
    <div class="pagination">
      <div class="page-btn">←</div>
      <div class="page-btn active">1</div>
      <div class="page-btn">2</div>
      <div class="page-btn">3</div>
      <div class="page-btn">→</div>
    </div>
  </div>

  <!-- Footer -->
  <footer class="footer">
    <div class="container">
      <div class="footer-content">
        <div class="footer-brand">
          <h2>O'Cos</h2>
          <p style="color: #bbb; max-width: 300px; font-weight: 300;">
            Platform kosmetik premium yang dipercaya untuk mempercantik harimu.
          </p>
        </div>
        <div class="footer-contact">
          <h3>Contact Us</h3>
          <p>📧 O'Cos@company.id</p>
          <p>📍 Jl. Batu Besar No 101/Blok C</p>
        </div>
      </div>
      <div class="footer-bottom">
        <p>&copy; 2026 O'Cos Cosmetics. All rights reserved.</p>
      </div>
    </div>
  </footer>

</body>
</html>
