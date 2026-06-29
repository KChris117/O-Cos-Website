<?php
session_start();

// Cek apakah user sudah login dan role-nya adalah admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    // Jika bukan admin, lempar ke halaman login
    header("Location: log-in.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Dashboard Admin - O'Cos</title>
  <link rel="stylesheet" href="./styles/global.css"/>
  <style>
    .dashboard-container {
      padding: 120px 20px 50px;
      text-align: center;
      min-height: 80vh;
    }
    .welcome-card {
      background: var(--bg-card);
      max-width: 600px;
      margin: 0 auto;
      padding: 50px;
      border-radius: 20px;
      box-shadow: var(--shadow-md);
      border: 1px solid var(--border-color);
    }
    .welcome-card h1 {
      color: var(--primary-dark);
      margin-bottom: 20px;
    }
    .welcome-card p {
      color: var(--text-muted);
      margin-bottom: 30px;
    }
  </style>
</head>
<body>

  <!-- Navigation -->
  <nav class="navbar">
    <div class="container">
      <a href="dashboard.php" class="nav-logo">O'Cos Admin</a>
      <div class="nav-links">
        <span style="font-weight: 500; color: var(--primary);">Halo, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
        <a href="index.php">Lihat Web</a>
        <a href="logout.php" class="btn btn-outline" style="padding: 8px 20px;">Log Out</a>
      </div>
    </div>
  </nav>

  <div class="dashboard-container">
    <div class="welcome-card">
      <h1>Selamat Datang, Admin!</h1>
      <p>Ini adalah halaman Dashboard khusus Admin. Di masa depan, Anda dapat menambahkan fitur kelola produk (CRUD), melihat pesanan pelanggan, dan mengatur pengguna dari halaman ini.</p>
      
      <a href="index.php" class="btn btn-primary">Kembali ke Beranda Utama</a>
    </div>
  </div>

</body>
</html>
