<?php
session_start();
require_once 'config.php';

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    verify_csrf_token($_POST['csrf_token'] ?? '');

    $username_email = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE username = ? OR email = ?");
    mysqli_stmt_bind_param($stmt, "ss", $username_email, $username_email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

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
            $error = "Incorrect password!";
        }
    } else {
        $error = "Username or Email not found!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
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
        <span>← Back to Home</span>
      </a>
      
      <div class="auth-header">
        <h1 class="auth-title">Log In</h1>
        <p class="auth-subtitle">Welcome back! Please log in to your account.</p>
      </div>

      <?php if($error): ?>
        <div style="background: #ffcccc; color: #cc0000; padding: 10px; border-radius: 8px; margin-bottom: 20px; text-align: center;">
          <?php echo $error; ?>
        </div>
      <?php endif; ?>
      
      <form class="auth-form" action="log-in.php" method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo generate_csrf_token(); ?>">
        <div class="form-group">
          <label for="username">Username / Email</label>
          <input type="text" id="username" name="username" placeholder="Enter your username" required />
        </div>
        
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" placeholder="Enter your password" required />
        </div>
        
        <button type="submit" class="btn btn-primary auth-btn">Log In</button>
      </form>
      
      <div class="auth-footer">
        <p>Don't have an account? <a href="sign-in.php">Sign Up now</a></p>
      </div>
    </div>
  </div>

</body>
</html>
