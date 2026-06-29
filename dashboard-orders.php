<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: log-in.php");
    exit();
}

// Handle Status Change
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'update_status') {
    $trx_id = mysqli_real_escape_string($conn, $_POST['trx_id']);
    $new_status = mysqli_real_escape_string($conn, $_POST['status']);
    
    // Check current status
    $trx_res = mysqli_query($conn, "SELECT status FROM transactions WHERE id = '$trx_id'");
    if(mysqli_num_rows($trx_res) > 0) {
        $current = mysqli_fetch_assoc($trx_res)['status'];
        
        // If changing to canceled/rejected from a non-canceled state, return stock
        if ($new_status === 'Canceled' && $current !== 'Canceled') {
            mysqli_begin_transaction($conn);
            try {
                mysqli_query($conn, "UPDATE transactions SET status = 'Canceled' WHERE id = '$trx_id'");
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
        } else {
            // Normal update
            mysqli_query($conn, "UPDATE transactions SET status = '$new_status' WHERE id = '$trx_id'");
        }
    }
    header("Location: dashboard-orders.php");
    exit();
}

// Fetch all transactions
$query = "
    SELECT t.*, u.username 
    FROM transactions t 
    JOIN users u ON t.user_id = u.id 
    ORDER BY t.created_at DESC
";
$transactions = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Manage Orders - Admin O'Cos</title>
  <link rel="stylesheet" href="./styles/global.css"/>
  <link rel="stylesheet" href="./styles/dashboard.css"/>
  <style>
    .status-badge {
      padding: 4px 10px;
      border-radius: 12px;
      font-size: 0.8rem;
      font-weight: bold;
    }
    .badge-Pending { background: #fff3e0; color: #e65100; }
    .badge-OnPacking { background: #e3f2fd; color: #1565c0; }
    .badge-OnDelivery { background: #f3e5f5; color: #6a1b9a; }
    .badge-Completed { background: #e8f5e9; color: #2e7d32; }
    .badge-Canceled { background: #ffebee; color: #c62828; }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <aside class="sidebar">
    <div class="sidebar-header">
      <h2>O'Cos Admin</h2>
    </div>
    
    <div style="padding: 20px; text-align: center; border-bottom: 1px solid var(--border-color);">
      <div style="width: 80px; height: 80px; background: var(--primary-light); border-radius: 50%; margin: 0 auto 10px; display: flex; align-items: center; justify-content: center; font-size: 2rem; color: var(--primary-dark);">
        👑
      </div>
      <h3 style="margin: 0; color: var(--primary-dark);"><?php echo htmlspecialchars($_SESSION['username']); ?></h3>
      <span style="font-size: 0.8rem; color: var(--text-muted); text-transform: uppercase;">Administrator</span>
    </div>

    <nav class="sidebar-nav">
      <a href="dashboard.php">📊 Overview</a>
      <a href="dashboard-orders.php" class="active">📦 Manage Orders</a>
      <a href="dashboard-items.php">🛍️ Manage Items</a>
      <a href="dashboard-users.php">👥 Manage Users</a>
    </nav>

    <div class="sidebar-footer">
      <a href="index.php" class="btn btn-outline" style="margin-bottom: 10px; text-align: center;">View Website</a>
      <a href="logout.php" class="btn btn-primary" style="text-align: center;">Log Out</a>
    </div>
  </aside>

  <!-- Main Content -->
  <main class="main-content">
    <div class="dashboard-header">
      <h1 class="dashboard-title">Manage Customer Orders (COD)</h1>
    </div>

    <!-- Data Table -->
    <div class="table-container">
      <div class="table-header">
        <h3>Transaction History</h3>
      </div>
      <div style="overflow-x: auto;">
        <table class="data-table">
          <thead>
            <tr>
              <th>TRX ID</th>
              <th>Customer</th>
              <th>Date</th>
              <th>Total Amount</th>
              <th>Status</th>
              <th>Action (Update Status)</th>
            </tr>
          </thead>
          <tbody>
            <?php if(mysqli_num_rows($transactions) > 0): ?>
              <?php while($row = mysqli_fetch_assoc($transactions)): 
                  $css_status = str_replace(' ', '', $row['status']);
              ?>
              <tr>
                <td style="font-weight: 500; color: var(--primary-dark);">
                  <?php echo $row['id']; ?><br>
                  <button type="button" class="btn btn-outline" style="padding: 2px 8px; font-size: 0.75rem; margin-top: 5px;" 
                    onclick="openAdminModal('<?php echo $row['id']; ?>')">View Details</button>
                    
                  <div id="details-<?php echo $row['id']; ?>" style="display: none;">
                    <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 5px;">Shipping Address</p>
                    <div style="background: #f1f3f4; padding: 10px; border-radius: 6px; font-size: 0.9rem; margin-bottom: 15px;">
                      <?php echo nl2br(htmlspecialchars($row['address'])); ?>
                    </div>
                    
                    <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 5px;">Items Ordered</p>
                    <ul style="padding-left: 20px; margin: 0; font-size: 0.9rem; color: var(--primary-dark);">
                    <?php 
                      $trx_id = $row['id'];
                      $details = mysqli_query($conn, "SELECT td.quantity, p.name FROM transaction_details td JOIN products p ON td.product_id = p.id WHERE td.transaction_id = '$trx_id'");
                      while($item = mysqli_fetch_assoc($details)) {
                        echo "<li><strong>{$item['quantity']}x</strong> " . htmlspecialchars($item['name']) . "</li>";
                      }
                    ?>
                    </ul>
                  </div>
                </td>
                <td><?php echo htmlspecialchars($row['username']); ?></td>
                <td><?php echo date('d M Y, H:i', strtotime($row['created_at'])); ?></td>
                <td style="font-weight: bold;">Rp. <?php echo number_format($row['total_amount'], 0, ',', '.'); ?></td>
                <td><span class="status-badge badge-<?php echo $css_status; ?>"><?php echo $row['status']; ?></span></td>
                <td>
                  <?php if($row['status'] !== 'Canceled' && $row['status'] !== 'Completed'): ?>
                  <form action="dashboard-orders.php" method="POST" style="display: flex; gap: 5px;">
                    <input type="hidden" name="action" value="update_status">
                    <input type="hidden" name="trx_id" value="<?php echo $row['id']; ?>">
                    <select name="status" style="padding: 5px; border-radius: 4px; border: 1px solid var(--border-color);">
                      <option value="Pending" <?php echo ($row['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                      <option value="On Packing" <?php echo ($row['status'] == 'On Packing') ? 'selected' : ''; ?>>On Packing</option>
                      <option value="On Delivery" <?php echo ($row['status'] == 'On Delivery') ? 'selected' : ''; ?>>On Delivery</option>
                      <option value="Completed" <?php echo ($row['status'] == 'Completed') ? 'selected' : ''; ?>>Completed</option>
                      <option value="Canceled">Reject/Cancel</option>
                    </select>
                    <button type="submit" class="btn btn-primary" style="padding: 5px 10px; font-size: 0.8rem;">Save</button>
                  </form>
                  <?php else: ?>
                    <span style="color: var(--text-muted); font-size: 0.85rem; font-style: italic;">Transaction Closed</span>
                  <?php endif; ?>
                </td>
              </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr>
                <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 30px;">No transactions found.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </main>

  <!-- Admin Details Modal -->
  <div class="modal-overlay" id="adminDetailsModal">
    <div class="modal-content">
      <span class="modal-close" onclick="closeAdminModal()">&times;</span>
      <h3 style="margin-top: 0; color: var(--primary-dark); border-bottom: 1px solid var(--border-color); padding-bottom: 10px;">Transaction Details</h3>
      
      <div style="margin-top: 20px;">
        <p style="font-size: 0.9rem; color: var(--text-muted); margin-bottom: 5px;">Transaction ID</p>
        <p style="font-weight: bold; margin-bottom: 15px;" id="admin-modal-trx-id"></p>
        
        <div id="admin-modal-body"></div>
      </div>
    </div>
  </div>

  <script>
    const adminModal = document.getElementById('adminDetailsModal');
    
    function openAdminModal(trxId) {
      document.getElementById('admin-modal-trx-id').innerText = trxId;
      document.getElementById('admin-modal-body').innerHTML = document.getElementById('details-' + trxId).innerHTML;
      adminModal.classList.add('active');
    }
    
    function closeAdminModal() {
      adminModal.classList.add('closing');
      setTimeout(() => {
        adminModal.classList.remove('active', 'closing');
      }, 250);
    }
    
    adminModal.addEventListener('click', function(e) {
      if (e.target === adminModal) closeAdminModal();
    });
  </script>
</body>
</html>
