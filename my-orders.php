<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: log-in.php");
    exit();
}

$user_id = intval($_SESSION['user_id']);

// Handle Cancellation by User
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'cancel') {
    $trx_id = mysqli_real_escape_string($conn, $_POST['trx_id']);
    
    // Verify it belongs to user and is Pending
    $check_q = "SELECT status FROM transactions WHERE id = '$trx_id' AND user_id = $user_id";
    $check_res = mysqli_query($conn, $check_q);
    
    if (mysqli_num_rows($check_res) > 0) {
        $trx = mysqli_fetch_assoc($check_res);
        if ($trx['status'] === 'Pending') {
            mysqli_begin_transaction($conn);
            try {
                // Update status to Canceled
                mysqli_query($conn, "UPDATE transactions SET status = 'Canceled' WHERE id = '$trx_id'");
                
                // Return Stock
                $details = mysqli_query($conn, "SELECT product_id, quantity FROM transaction_details WHERE transaction_id = '$trx_id'");
                while($d = mysqli_fetch_assoc($details)) {
                    $pid = $d['product_id'];
                    $qty = $d['quantity'];
                    mysqli_query($conn, "UPDATE products SET stock = stock + $qty WHERE id = $pid");
                }
                
                mysqli_commit($conn);
            } catch (Exception $e) {
                mysqli_rollback($conn);
            }
        }
    }
    header("Location: my-orders.php");
    exit();
}

// Fetch all transactions for user
$query = "SELECT * FROM transactions WHERE user_id = $user_id ORDER BY created_at DESC";
$transactions = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>My Orders - O'Cos</title>
  <link rel="stylesheet" href="./styles/global.css"/>
  <style>
    .order-card {
      background: #fff;
      border-radius: 12px;
      box-shadow: var(--shadow-sm);
      border: 1px solid var(--border-color);
      padding: 20px;
      margin-bottom: 20px;
    }
    .order-header {
      display: flex;
      justify-content: space-between;
      border-bottom: 1px solid var(--border-color);
      padding-bottom: 15px;
      margin-bottom: 15px;
    }
    .order-status {
      padding: 5px 12px;
      border-radius: 20px;
      font-size: 0.85rem;
      font-weight: 600;
    }
    .status-Pending { background: #fff3e0; color: #e65100; }
    .status-OnPacking { background: #e3f2fd; color: #1565c0; }
    .status-OnDelivery { background: #f3e5f5; color: #6a1b9a; }
    .status-Completed { background: #e8f5e9; color: #2e7d32; }
    .status-Canceled { background: #ffebee; color: #c62828; }
    
    .item-row {
      display: flex;
      justify-content: space-between;
      margin-bottom: 10px;
      font-size: 0.95rem;
    }
    
    /* Modal Styling */
    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }
    @keyframes slideDown {
      from { transform: translateY(-50px); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
    }
    @keyframes fadeOut {
      from { opacity: 1; }
      to { opacity: 0; }
    }
    @keyframes slideUp {
      from { transform: translateY(0); opacity: 1; }
      to { transform: translateY(-50px); opacity: 0; }
    }

    .modal-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      display: none;
      justify-content: center;
      align-items: center;
      z-index: 1000;
      backdrop-filter: blur(3px);
    }
    .modal-overlay.active { 
      display: flex; 
      animation: fadeIn 0.3s ease forwards;
    }
    .modal-content {
      background: #fff;
      padding: 30px;
      border-radius: 16px;
      width: 90%;
      max-width: 500px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
      position: relative;
    }
    .modal-overlay.active .modal-content {
      animation: slideDown 0.3s ease forwards;
    }
    .modal-overlay.closing {
      animation: fadeOut 0.3s ease forwards;
    }
    .modal-overlay.closing .modal-content {
      animation: slideUp 0.3s ease forwards;
    }
    .modal-close {
      position: absolute;
      top: 20px;
      right: 25px;
      font-size: 1.5rem;
      cursor: pointer;
      color: var(--text-muted);
    }
  </style>
</head>
<body style="padding-top: 80px; background: #f8f9fa;">

  <!-- Navigation -->
  <nav class="navbar">
    <div class="container">
      <a href="index.php" class="nav-logo">O'Cos</a>
      <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="index.php#about">About Us</a>
        <a href="katalog-products.php">Catalog</a>
        <a href="favorites.php" style="color: var(--primary-dark); font-weight: 600;">❤️ Favorites</a>
        <a href="my-orders.php" class="active" style="color: var(--primary-dark); font-weight: 600;">📦 My Orders</a>
        <a href="cart.php" style="color: var(--primary-dark); font-weight: 600;">🛒 Cart</a>
        <?php if($_SESSION['role'] === 'admin'): ?>
          <a href="dashboard.php" style="color: var(--primary); font-weight: 600;">Dashboard</a>
        <?php endif; ?>
        <a href="logout.php" class="btn btn-outline" style="padding: 8px 20px;">Log Out</a>
      </div>
    </div>
  </nav>

  <div class="container" style="max-width: 800px; padding: 40px 20px;">
    <h1 style="color: var(--primary-dark); margin-bottom: 30px;">My Orders (Transaction History)</h1>
    
    <?php if(mysqli_num_rows($transactions) > 0): ?>
      <?php while($trx = mysqli_fetch_assoc($transactions)): 
          $trx_id = $trx['id'];
          // Convert enum "On Packing" to CSS friendly class "OnPacking"
          $css_status = str_replace(' ', '', $trx['status']); 
      ?>
      <div class="order-card">
        <div class="order-header">
          <div>
            <div style="font-weight: bold; color: var(--primary-dark); font-size: 1.1rem;"><?php echo $trx_id; ?></div>
            <div style="font-size: 0.85rem; color: var(--text-muted);"><?php echo date('d M Y, H:i', strtotime($trx['created_at'])); ?></div>
          </div>
          <div>
            <span class="order-status status-<?php echo $css_status; ?>">
              <?php echo $trx['status']; ?>
            </span>
          </div>
        </div>
        
        <div style="margin-bottom: 20px;">
          <?php 
            $details = mysqli_query($conn, "SELECT td.quantity, p.name FROM transaction_details td JOIN products p ON td.product_id = p.id WHERE td.transaction_id = '$trx_id'");
            while($item = mysqli_fetch_assoc($details)):
          ?>
            <div class="item-row">
              <span style="color: #555;"><?php echo $item['quantity']; ?>x <?php echo htmlspecialchars($item['name']); ?></span>
            </div>
          <?php endwhile; ?>
        </div>
        
        <div style="display: flex; justify-content: space-between; align-items: center; border-top: 1px dashed var(--border-color); padding-top: 15px;">
          <div>
            <div style="font-size: 0.85rem; color: var(--text-muted);">Total Amount</div>
            <div style="font-weight: bold; font-size: 1.2rem; color: var(--primary-dark);">Rp. <?php echo number_format($trx['total_amount'], 0, ',', '.'); ?></div>
          </div>
          
          <div style="display: flex; gap: 10px;">
            <!-- View Details Button -->
            <button class="btn btn-primary" style="padding: 8px 15px; font-size: 0.85rem;" 
              data-id="<?php echo $trx_id; ?>"
              data-address="<?php echo htmlspecialchars($trx['address']); ?>"
              onclick="openDetailsModal(this)">View Details</button>
          
          <?php if($trx['status'] === 'Pending'): ?>
            <form action="my-orders.php" method="POST" onsubmit="return confirm('Are you sure you want to cancel this order?');">
              <input type="hidden" name="action" value="cancel" />
              <input type="hidden" name="trx_id" value="<?php echo $trx_id; ?>" />
              <button type="submit" class="btn btn-outline" style="padding: 8px 15px; font-size: 0.85rem; background: #ffebee; color: #c62828; border-color: #ffcdd2;">Cancel Order</button>
            </form>
          <?php elseif($trx['status'] === 'On Packing'): ?>
            <span style="font-size: 0.85rem; color: #1565c0; font-style: italic;">Being Packed - Cannot be canceled</span>
          <?php endif; ?>
          </div>
        </div>
      </div>
      <?php endwhile; ?>
    <?php else: ?>
      <div style="text-align: center; padding: 50px; background: #fff; border-radius: 12px; border: 1px solid var(--border-color);">
        <h3>No orders yet.</h3>
        <p style="color: var(--text-muted); margin-bottom: 20px;">You haven't made any purchases.</p>
        <a href="katalog-products.php" class="btn btn-primary">Go Shopping</a>
      </div>
    <?php endif; ?>
    
  </div>

  <!-- Details Modal -->
  <div class="modal-overlay" id="detailsModal">
    <div class="modal-content">
      <span class="modal-close" onclick="closeDetailsModal()">&times;</span>
      <h3 style="margin-top: 0; color: var(--primary-dark); border-bottom: 1px solid var(--border-color); padding-bottom: 10px;">Order Details</h3>
      
      <div style="margin-top: 20px;">
        <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 5px;">Transaction ID</p>
        <p style="font-weight: bold; margin-bottom: 15px;" id="modal-trx-id"></p>
        
        <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 5px;">Shipping Address</p>
        <div style="background: #f1f3f4; padding: 15px; border-radius: 8px; font-size: 0.95rem; line-height: 1.5;" id="modal-address"></div>
      </div>
    </div>
  </div>

  <script>
    const modal = document.getElementById('detailsModal');
    
    function openDetailsModal(btn) {
      document.getElementById('modal-trx-id').innerText = btn.getAttribute('data-id');
      document.getElementById('modal-address').innerText = btn.getAttribute('data-address');
      modal.classList.add('active');
    }
    
    function closeDetailsModal() {
      modal.classList.add('closing');
      setTimeout(() => {
        modal.classList.remove('active', 'closing');
      }, 250);
    }
    
    modal.addEventListener('click', function(e) {
      if (e.target === modal) closeDetailsModal();
    });
  </script>
</body>
</html>
