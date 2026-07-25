<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: log-in.php");
    exit();
}

// Ambil semua pengguna dan hitung total item (quantity) yang pernah dibeli
$stmt_users = mysqli_prepare($conn, "
    SELECT u.id, u.username, u.email, u.role,
           COALESCE(SUM(td.quantity), 0) AS total_items_bought,
           COUNT(DISTINCT t.id) AS total_orders
    FROM users u
    LEFT JOIN transactions t ON u.id = t.user_id
    LEFT JOIN transaction_details td ON t.id = td.transaction_id
    GROUP BY u.id
    ORDER BY u.role ASC, u.id DESC
");
mysqli_stmt_execute($stmt_users);
$users = mysqli_stmt_get_result($stmt_users);

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Manage Users - Admin O'Cos</title>
  <link rel="stylesheet" href="./styles/global.css"/>
  <link rel="stylesheet" href="./styles/dashboard.css"/>
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
      <a href="dashboard-orders.php">📦 Manage Orders</a>
      <a href="dashboard-items.php">🛍️ Manage Items</a>
      <a href="dashboard-users.php" class="active">👥 Manage Users</a>
      <div style="height: 1px; background: var(--border-color); margin: 15px 20px;"></div>
      <a href="index.php">🌐 View Website</a>
      <a href="logout.php" style="color: #c62828;">🚪 Log Out</a>
    </nav>
  </aside>

  <!-- Main Content -->
  <main class="main-content">
    <div class="dashboard-header">
      <h1 class="dashboard-title">User Management</h1>
    </div>

    <!-- Data Table -->
    <div class="table-container">
      <div class="table-header">
        <h3>O'Cos Users List</h3>
      </div>
      <div style="overflow-x: auto;">
        <table class="data-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Username</th>
              <th>Email</th>
              <th>Role</th>
              <th>Total Items Bought</th>
              <th>Total Orders</th>
            </tr>
          </thead>
          <tbody>
            <?php if(mysqli_num_rows($users) > 0): ?>
              <?php while($row = mysqli_fetch_assoc($users)): ?>
              <tr>
                <td>#<?php echo $row['id']; ?></td>
                <td style="font-weight: 500; color: var(--primary-dark);">
                  <?php echo htmlspecialchars($row['username']); ?>
                </td>
                <td><?php echo htmlspecialchars($row['email']); ?></td>
                <td>
                  <?php if($row['role'] === 'admin'): ?>
                    <span style="background: #e3f2fd; color: #1565c0; padding: 4px 10px; border-radius: 20px; font-size: 0.8rem; font-weight: 600;">Admin</span>
                  <?php else: ?>
                    <span style="background: #f1f3f4; color: #5f6368; padding: 4px 10px; border-radius: 20px; font-size: 0.8rem; font-weight: 600;">User</span>
                  <?php endif; ?>
                </td>
                <td>
                  <span style="font-weight: bold; color: var(--secondary);"><?php echo $row['total_items_bought']; ?> pcs</span>
                </td>
                <td><?php echo $row['total_orders']; ?> Times</td>
              </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr>
                <td colspan="6" style="text-align: center; color: var(--text-muted); padding: 30px;">No registered users yet.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </main>

</body>
</html>
