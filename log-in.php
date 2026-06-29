<?php
session_start();
require_once 'config.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username_email = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE username = '$username_email' OR email = '$username_email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        if (password_verify($password, $user['password'])) {
            // Berhasil login
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            if ($user['role'] == 'admin') {
                header("Location: dashboard.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username atau Email tidak ditemukan!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <link rel="icon" href="/favicon.ico" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="theme-color" content="#d4a398" />
  <title>Log In - O'Cos</title>
  <link rel="stylesheet" href="./styles/global.css"/>
  <link rel="stylesheet" href="./styles/auth.css"/>
</head>
<body class="auth-page">

  <div class="container" style="display: flex; justify-content: center;">
    <div class="auth-card">
      <a href="index.php" class="back-home">
        <span>← Kembali ke Beranda</span>
      </a>
      
      <div class="auth-header">
        <h1 class="auth-title">Log In</h1>
        <p class="auth-subtitle">Selamat datang kembali! Silakan masuk ke akun Anda.</p>
      </div>

      <?php if($error): ?>
        <div style="background: #ffcccc; color: #cc0000; padding: 10px; border-radius: 8px; margin-bottom: 20px; text-align: center;">
          <?php echo $error; ?>
        </div>
      <?php endif; ?>
      
      <form class="auth-form" action="log-in.php" method="POST">
        <div class="form-group">
          <label for="username">Username / Email</label>
          <input type="text" id="username" name="username" placeholder="Masukkan username Anda" required />
        </div>
        
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" placeholder="Masukkan password Anda" required />
        </div>
        
        <button type="submit" class="btn btn-primary auth-btn">Log In</button>
      </form>
      
      <div class="auth-footer">
        <p>Belum punya akun? <a href="sign-in.php">Daftar sekarang (Sign In)</a></p>
      </div>
    </div>
  </div>

</body>
</html>
