<?php
session_start();
require_once 'config.php';

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: log-in.php");
    exit();
}

$user_id = intval($_SESSION['user_id']);

// Handle actions: Add, Update, Delete
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    verify_csrf_token($_POST['csrf_token'] ?? '');
    
    $action = $_POST['action'];
    
    if ($action === 'add') {
        $product_id = intval($_POST['product_id']);
        
        // Check if item already exists in cart
        $stmt_check = mysqli_prepare($conn, "SELECT id, quantity FROM cart_items WHERE user_id = ? AND product_id = ?");
        mysqli_stmt_bind_param($stmt_check, "ii", $user_id, $product_id);
        mysqli_stmt_execute($stmt_check);
        $check_res = mysqli_stmt_get_result($stmt_check);
        
        if (mysqli_num_rows($check_res) > 0) {
            $row = mysqli_fetch_assoc($check_res);
            $new_qty = $row['quantity'] + 1;
            
            $stmt_update = mysqli_prepare($conn, "UPDATE cart_items SET quantity = ? WHERE id = ?");
            mysqli_stmt_bind_param($stmt_update, "ii", $new_qty, $row['id']);
            mysqli_stmt_execute($stmt_update);
        } else {
            $stmt_insert = mysqli_prepare($conn, "INSERT INTO cart_items (user_id, product_id, quantity) VALUES (?, ?, 1)");
            mysqli_stmt_bind_param($stmt_insert, "ii", $user_id, $product_id);
            mysqli_stmt_execute($stmt_insert);
        }
        
        // Redirect to avoid form resubmission on refresh
        header("Location: cart.php");
        exit();
    }
    elseif ($action === 'update') {
        $cart_id = intval($_POST['cart_id']);
        $new_qty = intval($_POST['quantity']);
        if ($new_qty > 0) {
            $stmt_update = mysqli_prepare($conn, "UPDATE cart_items SET quantity = ? WHERE id = ? AND user_id = ?");
            mysqli_stmt_bind_param($stmt_update, "iii", $new_qty, $cart_id, $user_id);
            mysqli_stmt_execute($stmt_update);
        }
        header("Location: cart.php");
        exit();
    }
    elseif ($action === 'delete') {
        $cart_id = intval($_POST['cart_id']);
        $stmt_del = mysqli_prepare($conn, "DELETE FROM cart_items WHERE id = ? AND user_id = ?");
        mysqli_stmt_bind_param($stmt_del, "ii", $cart_id, $user_id);
        mysqli_stmt_execute($stmt_del);
        header("Location: cart.php");
        exit();
    }
}

// Fetch Cart Items
$stmt_fetch = mysqli_prepare($conn, "
    SELECT c.id as cart_id, c.quantity, p.id as product_id, p.name, p.price, p.image, p.stock
    FROM cart_items c
    JOIN products p ON c.product_id = p.id
    WHERE c.user_id = ?
    ORDER BY c.id DESC
");
mysqli_stmt_bind_param($stmt_fetch, "i", $user_id);
mysqli_stmt_execute($stmt_fetch);
$cart_items = mysqli_stmt_get_result($stmt_fetch);

$total_price = 0;
$total_items = 0;

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Shopping Cart - O'Cos</title>
  <link rel="stylesheet" href="./styles/global.css"/>
  <style>
    .cart-page { padding-top: 100px; padding-bottom: 80px; }
    .cart-container {
      display: flex;
      gap: 30px;
      align-items: flex-start;
    }
    .cart-items {
      flex: 2;
      background: #fff;
      border-radius: 16px;
      padding: 30px;
      box-shadow: var(--shadow-sm);
      border: 1px solid var(--border-color);
    }
    .cart-summary {
      flex: 1;
      background: #fff;
      border-radius: 16px;
      padding: 30px;
      box-shadow: var(--shadow-sm);
      border: 1px solid var(--border-color);
      position: sticky;
      top: 100px;
    }
    .cart-item {
      display: flex;
      gap: 20px;
      padding-bottom: 20px;
      margin-bottom: 20px;
      border-bottom: 1px solid var(--border-color);
    }
    .cart-item:last-child {
      border-bottom: none;
      margin-bottom: 0;
      padding-bottom: 0;
    }
    .item-img {
      width: 100px;
      height: 100px;
      border-radius: 12px;
      object-fit: cover;
      background: var(--accent);
    }
    .item-details { flex: 1; }
    .item-title {
      font-size: 1.2rem;
      color: var(--primary-dark);
      margin-bottom: 5px;
    }
    .item-price {
      font-weight: bold;
      color: var(--secondary);
      font-size: 1.1rem;
      margin-bottom: 15px;
    }
    .qty-controls {
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .qty-input {
      width: 60px;
      padding: 5px;
      text-align: center;
      border: 1px solid var(--border-color);
      border-radius: 6px;
    }
    .btn-sm {
      padding: 5px 10px;
      font-size: 0.85rem;
    }
    @media (max-width: 768px) {
      .cart-container { flex-direction: column; }
      .cart-summary { position: static; }
    }
  </style>
</head>
<body class="cart-page">

  <!-- Navigation -->
  <nav class="navbar">
    <div class="container">
      <a href="index.php" class="nav-logo">O'Cos</a>
      <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="about-us.php">About Us</a>
        <a href="katalog-products.php">Catalog</a>
        <a href="favorites.php" style="color: var(--primary-dark); font-weight: 600;">❤️ Favorites</a>
        <a href="my-orders.php" style="color: var(--primary-dark); font-weight: 600;">📦 My Orders</a>
        <a href="cart.php" class="active" style="color: var(--primary-dark); font-weight: 600;">🛒 Cart</a>
        <?php if($_SESSION['role'] === 'admin'): ?>
          <a href="dashboard.php" style="color: var(--primary); font-weight: 600;">Dashboard</a>
        <?php endif; ?>
        <a href="logout.php" class="btn btn-outline" style="padding: 8px 20px;">Log Out</a>
      </div>
    </div>
  </nav>

  <div class="container">
    <h1 style="color: var(--primary-dark); margin-bottom: 30px;">Your Shopping Cart</h1>
    
    <div class="cart-container">
      
      <!-- Cart Items Section -->
      <div class="cart-items">
        <?php if(mysqli_num_rows($cart_items) > 0): ?>
          <?php while($item = mysqli_fetch_assoc($cart_items)): 
            $subtotal = $item['price'] * $item['quantity'];
            $total_price += $subtotal;
            $total_items += $item['quantity'];
          ?>
            <div class="cart-item">
              <img src="<?php echo htmlspecialchars($item['image']); ?>" alt="product" class="item-img" onerror="this.src='https://via.placeholder.com/100'" />
              <div class="item-details">
                <h3 class="item-title"><?php echo htmlspecialchars($item['name']); ?></h3>
                <div class="item-price">Rp. <?php echo number_format($item['price'], 0, ',', '.'); ?></div>
                
                <div style="display: flex; align-items: center; justify-content: space-between;">
                  <!-- Update Quantity Form -->
                  <form action="cart.php" method="POST" class="qty-controls">
                    <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                    <input type="number" name="quantity" class="qty-input" value="<?php echo $item['quantity']; ?>" min="1" max="<?php echo $item['stock']; ?>">
                    <button type="submit" class="btn btn-outline btn-sm">Update</button>
                    <?php if($item['stock'] < $item['quantity']): ?>
                      <span style="color: red; font-size: 0.8rem;">Stock: <?php echo $item['stock']; ?></span>
                    <?php endif; ?>
                  </form>
                  
                  <!-- Delete Item Form -->
                  <form action="cart.php" method="POST">
                    <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="cart_id" value="<?php echo $item['cart_id']; ?>">
                    <button type="submit" class="btn btn-sm" style="background: #ffebee; color: #c62828; border: none; cursor: pointer;">Remove</button>
                  </form>
                </div>
              </div>
            </div>
          <?php endwhile; ?>
        <?php else: ?>
          <div style="text-align: center; padding: 40px; color: var(--text-muted);">
            <h3>Your cart is empty!</h3>
            <p>Looks like you haven't added any cosmetics yet.</p>
            <br>
            <a href="katalog-products.php" class="btn btn-primary">Start Shopping</a>
          </div>
        <?php endif; ?>
      </div>
      
      <!-- Order Summary Section -->
      <div class="cart-summary">
        <h3 style="color: var(--primary-dark); margin-top: 0; border-bottom: 1px solid var(--border-color); padding-bottom: 15px; margin-bottom: 20px;">Order Summary</h3>
        
        <div style="display: flex; justify-content: space-between; margin-bottom: 15px;">
          <span style="color: var(--text-muted);">Total Items</span>
          <span style="font-weight: 600;"><?php echo $total_items; ?></span>
        </div>
        
        <div style="display: flex; justify-content: space-between; margin-bottom: 20px;">
          <span style="color: var(--text-muted);">Subtotal</span>
          <span style="font-weight: bold; color: var(--primary-dark); font-size: 1.2rem;">
            Rp. <?php echo number_format($total_price, 0, ',', '.'); ?>
          </span>
        </div>
        
        <hr style="border: 0; border-top: 1px solid var(--border-color); margin-bottom: 20px;">
        
        <?php if($total_items > 0): ?>
          <a href="checkout.php" class="btn btn-primary" style="width: 100%; font-size: 1.1rem; padding: 15px; text-align: center; text-decoration: none; display: block;">Checkout Now</a>
        <?php else: ?>
          <button class="btn btn-outline" style="width: 100%;" disabled>Cart Empty</button>
        <?php endif; ?>
      </div>
      
    </div>
  </div>

</body>
</html>
