<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Product Catalog - O'Cos</title>
  <link rel="stylesheet" href="./styles/global.css"/>
  <link rel="stylesheet" href="./styles/katalog.css"/>
</head>
<body class="katalog-page">

  <!-- Navigation -->
  <nav class="navbar">
    <div class="container">
      <a href="index.php" class="nav-logo">O'Cos</a>
      <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="index.php#about">About Us</a>
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
    <div class="katalog-header">
      <h1 class="katalog-title">O'Cos Product Catalog</h1>
      <p class="katalog-subtitle">Find a complete collection of premium cosmetics for all your needs.</p>
    </div>
    
    <form method="GET" action="katalog-products.php" class="filter-bar">
      <div class="filter-group">
        <input type="text" name="search" placeholder="Search products..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" />
      </div>
      <div class="filter-group">
        <select name="category">
          <option value="">All Categories</option>
          <option value="FACE" <?php if(isset($_GET['category']) && $_GET['category'] == 'FACE') echo 'selected'; ?>>FACE</option>
          <option value="HAIR" <?php if(isset($_GET['category']) && $_GET['category'] == 'HAIR') echo 'selected'; ?>>HAIR</option>
          <option value="LIPS" <?php if(isset($_GET['category']) && $_GET['category'] == 'LIPS') echo 'selected'; ?>>LIPS</option>
          <option value="NAILS" <?php if(isset($_GET['category']) && $_GET['category'] == 'NAILS') echo 'selected'; ?>>NAILS</option>
        </select>
      </div>
      <div class="filter-group price-group">
        <input type="number" name="min_price" placeholder="Min Price" value="<?php echo isset($_GET['min_price']) && $_GET['min_price'] > 0 ? $_GET['min_price'] : ''; ?>" min="0" />
        <span style="color: var(--text-muted);">-</span>
        <input type="number" name="max_price" placeholder="Max Price" value="<?php echo isset($_GET['max_price']) && $_GET['max_price'] > 0 ? $_GET['max_price'] : ''; ?>" min="0" />
      </div>
      <button type="submit" class="btn btn-primary btn-filter">Apply</button>
      <?php if(isset($_GET['search']) || isset($_GET['category']) || isset($_GET['min_price']) || isset($_GET['max_price'])): ?>
        <a href="katalog-products.php" class="btn btn-outline btn-reset" style="padding: 12px 20px;">Reset</a>
      <?php endif; ?>
    </form>
    
    <div class="katalog-grid">
      <?php
      require_once 'config.php';
      
      $limit = 20;
      $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
      if ($page < 1) $page = 1;
      $offset = ($page - 1) * $limit;

      // Filter processing
      $search = isset($_GET['search']) ? mysqli_real_escape_string($conn, trim($_GET['search'])) : '';
      $category = isset($_GET['category']) ? mysqli_real_escape_string($conn, trim($_GET['category'])) : '';
      $min_price = isset($_GET['min_price']) ? (int)$_GET['min_price'] : 0;
      $max_price = isset($_GET['max_price']) ? (int)$_GET['max_price'] : 0;

      $where_clauses = [];
      if ($search !== '') {
          $where_clauses[] = "name LIKE '%$search%'";
      }
      if ($category !== '') {
          $where_clauses[] = "category = '$category'";
      }
      if ($min_price > 0) {
          $where_clauses[] = "price >= $min_price";
      }
      if ($max_price > 0) {
          $where_clauses[] = "price <= $max_price";
      }

      $where_sql = "";
      if (count($where_clauses) > 0) {
          $where_sql = "WHERE " . implode(" AND ", $where_clauses);
      }

      // Calculate total pages
      $count_query = "SELECT COUNT(*) as total FROM products $where_sql";
      $count_result = mysqli_query($conn, $count_query);
      $count_row = mysqli_fetch_assoc($count_result);
      $total_products = $count_row['total'];
      
      // Ensure at least 1 page is shown even if empty
      $total_pages = max(1, ceil($total_products / $limit));

      $query = "SELECT * FROM products $where_sql ORDER BY id ASC LIMIT $limit OFFSET $offset";
      $result = mysqli_query($conn, $query);

      if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
      ?>
      <div class="product-card">
        <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" class="product-image" onerror="this.src='https://images.unsplash.com/photo-1556228578-0d85b1a4d571?auto=format&fit=crop&w=300&q=80'" />
        <h3 class="product-name"><?php echo htmlspecialchars($row['name']); ?></h3>
        <p class="product-price">Rp. <?php echo number_format($row['price'], 0, ',', '.'); ?></p>
        <button class="btn btn-outline product-btn" onclick="window.location.href='product-detail.php?id=<?php echo $row['id']; ?>'">View Details</button>
      </div>
      <?php
          }
      } else {
          echo "<p style='grid-column: 1 / -1; text-align: center; color: var(--text-muted); padding: 40px;'>No products found in the database.</p>";
      }
      ?>
    </div>

    <!-- Pagination controls -->
    <?php
    function buildPageUrl($pageNum) {
        $params = $_GET;
        $params['page'] = $pageNum;
        return '?' . http_build_query($params);
    }
    ?>
    <div class="pagination">
      <?php if ($page > 1): ?>
        <a href="<?php echo buildPageUrl($page - 1); ?>" class="page-btn" style="text-decoration: none;">←</a>
      <?php endif; ?>

      <?php for ($i = 1; $i <= $total_pages; $i++): ?>
        <a href="<?php echo buildPageUrl($i); ?>" class="page-btn <?php echo ($i === $page) ? 'active' : ''; ?>" style="text-decoration: none;">
          <?php echo $i; ?>
        </a>
      <?php endfor; ?>

      <?php if ($page < $total_pages): ?>
        <a href="<?php echo buildPageUrl($page + 1); ?>" class="page-btn" style="text-decoration: none;">→</a>
      <?php endif; ?>
    </div>
  </div>

  <!-- Footer -->
  <footer class="footer">
    <div class="container">
      <div class="footer-content">
        <div class="footer-brand">
          <h2>O'Cos</h2>
          <p style="color: #bbb; max-width: 300px; font-weight: 300;">
            The trusted premium cosmetics platform to beautify your day.
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
