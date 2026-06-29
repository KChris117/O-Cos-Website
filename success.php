<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: log-in.php");
    exit();
}

$trx_id = isset($_GET['trx_id']) ? htmlspecialchars($_GET['trx_id']) : 'UNKNOWN';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Order Success - O'Cos</title>
  <link rel="stylesheet" href="./styles/global.css"/>
  <link rel="stylesheet" href="./styles/auth.css"/>
</head>
<body class="auth-page" style="padding-top: 100px;">

  <div class="container" style="display: flex; justify-content: center;">
    <div class="auth-card" style="text-align: center; padding: 50px 30px;">
      
      <div style="font-size: 4rem; margin-bottom: 20px;">🎉</div>
      <h1 class="auth-title" style="color: #2e7d32; margin-bottom: 10px;">Order Placed Successfully!</h1>
      <p class="auth-subtitle" style="margin-bottom: 30px;">
        Thank you for shopping at O'Cos. Your Cash On Delivery (COD) order has been received and is currently in <strong>Pending</strong> status waiting for admin confirmation.
      </p>
      
      <div style="background: #f8f9fa; padding: 20px; border-radius: 12px; border: 1px dashed var(--border-color); margin-bottom: 30px;">
        <span style="color: var(--text-muted); font-size: 0.9rem;">Transaction ID</span><br>
        <span style="font-size: 1.5rem; font-weight: bold; color: var(--primary-dark);"><?php echo $trx_id; ?></span>
      </div>
      
      <div style="display: flex; gap: 15px; justify-content: center;">
        <a href="my-orders.php" class="btn btn-primary">Track My Order</a>
        <a href="katalog-products.php" class="btn btn-outline">Continue Shopping</a>
      </div>
      
    </div>
  </div>

</body>
</html>
