<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: log-in.php");
    exit();
}

$user_id = intval($_SESSION['user_id']);

// Fetch Cart Items
$stmt = mysqli_prepare($conn, "
    SELECT c.quantity, p.name, p.price, p.stock
    FROM cart_items c
    JOIN products p ON c.product_id = p.id
    WHERE c.user_id = ?
");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$cart_items = mysqli_stmt_get_result($stmt);

if(mysqli_num_rows($cart_items) == 0) {
    // If cart is empty, redirect back
    header("Location: cart.php");
    exit();
}

$total_price = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Checkout (COD) - O'Cos</title>
  <link rel="stylesheet" href="./styles/global.css"/>
  <link rel="stylesheet" href="./styles/auth.css"/>
</head>
<body class="auth-page" style="padding-top: 50px;">

  <div class="container" style="display: flex; justify-content: center; gap: 30px; flex-wrap: wrap;">
    
    <!-- Checkout Form -->
    <div class="auth-card" style="flex: 2; max-width: 600px; margin: 0;">
      <a href="cart.php" class="back-home">
        <span>← Back to Cart</span>
      </a>
      
      <div class="auth-header" style="margin-bottom: 20px;">
        <h1 class="auth-title">Checkout</h1>
        <p class="auth-subtitle">Please provide your shipping details for Cash On Delivery (COD).</p>
      </div>
      
      <form class="auth-form" action="checkout-process.php" method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
        <div class="form-group">
          <label>Full Name</label>
          <input type="text" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" readonly style="background: #f1f3f4; cursor: not-allowed;" />
        </div>
        
        <div class="form-group">
          <label>Shipping Address (Required for COD)</label>
          <textarea name="address" required placeholder="e.g., Jl. Jend. Sudirman No. 12, Jakarta..." style="height: 100px;"></textarea>
        </div>
        
        <div class="form-group">
          <label>Payment Method</label>
          <input type="text" value="Cash On Delivery (COD)" readonly style="background: #e3f2fd; color: #1565c0; font-weight: bold; border: 1px solid #90caf9; cursor: not-allowed;" />
          <input type="hidden" name="payment_method" value="COD" />
        </div>
        
        <button type="submit" class="btn btn-primary auth-btn" style="margin-top: 20px;">Place Order (Confirm)</button>
      </form>
    </div>

    <!-- Order Summary -->
    <div class="auth-card" style="flex: 1; max-width: 400px; margin: 0; background: #fafafa; border: 1px solid var(--border-color);">
      <h3 style="color: var(--primary-dark); margin-top: 0; border-bottom: 1px solid var(--border-color); padding-bottom: 15px;">Order Summary</h3>
      
      <div style="margin-top: 15px; margin-bottom: 20px;">
        <?php while($item = mysqli_fetch_assoc($cart_items)): 
          $subtotal = $item['price'] * $item['quantity'];
          $total_price += $subtotal;
        ?>
          <div style="display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 0.95rem;">
            <span><?php echo $item['quantity']; ?>x <?php echo htmlspecialchars($item['name']); ?></span>
            <span style="font-weight: 600;">Rp. <?php echo number_format($subtotal, 0, ',', '.'); ?></span>
          </div>
        <?php endwhile; ?>
      </div>
      
      <hr style="border: 0; border-top: 1px dashed var(--border-color); margin-bottom: 20px;">
      
      <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
        <span style="color: var(--text-muted);">Shipping</span>
        <span style="font-weight: 600; color: #2e7d32;">Free (Promo)</span>
      </div>
      
      <div style="display: flex; justify-content: space-between;">
        <span style="color: var(--text-muted); font-size: 1.2rem;">Total</span>
        <span style="font-weight: bold; color: var(--primary-dark); font-size: 1.4rem;">
          Rp. <?php echo number_format($total_price, 0, ',', '.'); ?>
        </span>
      </div>
    </div>

  </div>
</body>
</html>
