<?php
session_start();
require_once 'config.php';

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Check if username or email already exists
    $check_query = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
    $check_result = mysqli_query($conn, $check_query);
    
    if (mysqli_num_rows($check_result) > 0) {
        $error = "Username or Email is already registered!";
    } else {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Cek apakah tabel users masih kosong
        $role_query = "SELECT COUNT(*) as count FROM users";
        $role_result = mysqli_query($conn, $role_query);
        $row = mysqli_fetch_assoc($role_result);
        
        $role = ($row['count'] == 0) ? 'admin' : 'user';

        // Insert new user
        $insert_query = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$hashed_password', '$role')";
        
        if (mysqli_query($conn, $insert_query)) {
            $success = "Registration successful! Please Log In.";
        } else {
            $error = "System error occurred: " . mysqli_error($conn);
        }
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
  <title>Sign Up - O'Cos</title>
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
        <h1 class="auth-title">Sign Up</h1>
        <p class="auth-subtitle">Create a new account and enjoy a premium cosmetics shopping experience.</p>
      </div>
      
      <?php if($error): ?>
        <div style="background: #ffcccc; color: #cc0000; padding: 10px; border-radius: 8px; margin-bottom: 20px; text-align: center;">
          <?php echo $error; ?>
        </div>
      <?php endif; ?>

      <?php if($success): ?>
        <div style="background: #ccffcc; color: #006600; padding: 10px; border-radius: 8px; margin-bottom: 20px; text-align: center;">
          <?php echo $success; ?>
        </div>
      <?php endif; ?>
      
      <form class="auth-form" action="sign-in.php" method="POST">
        <div class="form-group">
          <label for="username">Username</label>
          <input type="text" id="username" name="username" placeholder="Choose a username" required />
        </div>
        
        <div class="form-group">
          <label for="email">Email Address</label>
          <input type="email" id="email" name="email" placeholder="example@email.com" required />
        </div>
        
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" placeholder="Create a new password" required minlength="6" />
        </div>
        
        <button type="submit" class="btn btn-primary auth-btn">Create An Account</button>
      </form>
      
      <div class="auth-footer">
        <p>Already have an account? <a href="log-in.php">Log In</a></p>
      </div>
    </div>
  </div>

</body>
</html>
