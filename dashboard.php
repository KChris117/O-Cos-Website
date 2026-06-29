<?php
session_start();
require_once 'config.php';

// Cek apakah user sudah login dan role-nya admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: log-in.php");
    exit();
}

// Ambil statistik sederhana
$user_count_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM users WHERE role = 'user'");
$user_count = mysqli_fetch_assoc($user_count_query)['total'];

$item_count_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM products");
$item_count = mysqli_fetch_assoc($item_count_query)['total'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Admin Dashboard - O'Cos</title>
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
      <a href="dashboard.php" class="active">📊 Overview</a>
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
      <h1 class="dashboard-title">Dashboard Overview</h1>
    </div>

    <div class="stat-grid">
      <div class="stat-card">
        <h3>Total Users</h3>
        <div class="value"><?php echo $user_count; ?></div>
      </div>
      <div class="stat-card">
        <h3>Total Products</h3>
        <div class="value"><?php echo $item_count; ?></div>
      </div>
      <div class="stat-card">
        <h3>Successful Orders</h3>
        <div class="value">0</div>
      </div>
    </div>
    
    <div class="table-container" style="padding: 30px; text-align: center;">
      <h2 style="color: var(--primary-dark); margin-bottom: 15px;">Welcome to the Admin Panel!</h2>
      <p style="color: var(--text-muted);">Use the menu on the left to manage products and view user activity.</p>
    </div>
  </main>

</body>
</html>
