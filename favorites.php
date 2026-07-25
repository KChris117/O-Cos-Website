<?php
session_start();
require_once 'config.php';

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: log-in.php");
    exit();
}

$user_id = intval($_SESSION['user_id']);

// Handle actions: Add or Remove
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    verify_csrf_token($_POST['csrf_token'] ?? '');
    $action = $_POST['action'];
    $product_id = intval($_POST['product_id']);
    
    if ($action === 'add') {
        // Insert if not exists (using IGNORE to prevent duplicate error if somehow submitted twice)
        $stmt_add = mysqli_prepare($conn, "INSERT IGNORE INTO favorites (user_id, product_id) VALUES (?, ?)");
        mysqli_stmt_bind_param($stmt_add, "ii", $user_id, $product_id);
        mysqli_stmt_execute($stmt_add);
    } elseif ($action === 'remove') {
        $stmt_rem = mysqli_prepare($conn, "DELETE FROM favorites WHERE user_id = ? AND product_id = ?");
        mysqli_stmt_bind_param($stmt_rem, "ii", $user_id, $product_id);
        mysqli_stmt_execute($stmt_rem);
    }
    
    // Redirect back to referring page or favorites
    if(isset($_SERVER['HTTP_REFERER'])) {
        header("Location: " . $_SERVER['HTTP_REFERER']);
    } else {
        header("Location: favorites.php");
    }
    exit();
}

// Fetch Favorited Items
$stmt_fetch = mysqli_prepare($conn, "
    SELECT f.id as fav_id, p.id as product_id, p.name, p.price, p.image 
    FROM favorites f
    JOIN products p ON f.product_id = p.id
    WHERE f.user_id = ?
    ORDER BY f.id DESC
");
mysqli_stmt_bind_param($stmt_fetch, "i", $user_id);
mysqli_stmt_execute($stmt_fetch);
$favorites = mysqli_stmt_get_result($stmt_fetch);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>My Favorites - O'Cos</title>
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
        <a href="about-us.php">About Us</a>
        <a href="katalog-products.php">Catalog</a>
        <a href="favorites.php" class="active" style="color: var(--primary-dark); font-weight: 600;">❤️ Favorites</a>
        <a href="my-orders.php" style="color: var(--primary-dark); font-weight: 600;">📦 My Orders</a>
        <a href="cart.php" style="color: var(--primary-dark); font-weight: 600;">🛒 Cart</a>
        <?php if($_SESSION['role'] === 'admin'): ?>
          <a href="dashboard.php" style="color: var(--primary); font-weight: 600;">Dashboard</a>
        <?php endif; ?>
        <a href="logout.php" class="btn btn-outline" style="padding: 8px 20px;">Log Out</a>
      </div>
    </div>
  </nav>

  <div class="container" style="padding-top: 100px; padding-bottom: 80px;">
    <div class="katalog-header">
      <h1 class="katalog-title">My Favorites ❤️</h1>
      <p class="katalog-subtitle">Your personalized collection of loved cosmetics.</p>
    </div>
    
    <div class="katalog-grid">
      <?php if (mysqli_num_rows($favorites) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($favorites)): ?>
        <div class="product-card">
          <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" class="product-image" onerror="this.src='https://images.unsplash.com/photo-1556228578-0d85b1a4d571?auto=format&fit=crop&w=300&q=80'" />
          <h3 class="product-name"><?php echo htmlspecialchars($row['name']); ?></h3>
          <p class="product-price">Rp. <?php echo number_format($row['price'], 0, ',', '.'); ?></p>
          
          <div style="display: flex; gap: 10px; margin-top: 15px;">
            <!-- Go to detail page -->
            <button class="btn btn-primary product-btn" style="flex: 2; margin-top: 0;" onclick="window.location.href='product-detail.php?id=<?php echo $row['product_id']; ?>'">View Details</button>
            
            <!-- Remove from favorites -->
            <form action="favorites.php" method="POST" style="flex: 1;">
              <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
              <input type="hidden" name="action" value="remove" />
              <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>" />
              <button type="submit" class="btn btn-outline product-btn" style="margin-top: 0; background: #ffebee; color: #c62828; border-color: #ffcdd2;" title="Remove">✖</button>
            </form>
          </div>
        </div>
        <?php endwhile; ?>
      <?php else: ?>
        <div style="grid-column: 1 / -1; text-align: center; color: var(--text-muted); padding: 60px;">
          <h3 style="margin-bottom: 15px;">No Favorites Yet</h3>
          <p>You haven't added any items to your favorites.</p>
          <br>
          <a href="katalog-products.php" class="btn btn-outline">Explore Catalog</a>
        </div>
      <?php endif; ?>
    </div>
  </div>

</body>
</html>
