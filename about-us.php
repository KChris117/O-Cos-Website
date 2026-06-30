<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <link rel="icon" href="/favicon.ico" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="theme-color" content="#d4a398" />
  <title>About Us - O'Cos Cosmetics</title>
  
  <link rel="stylesheet" href="./styles/global.css"/>
  <link rel="stylesheet" href="./styles/about.css"/>
</head>
<body class="about-page">

  <!-- Navigation -->
  <nav class="navbar">
    <div class="container">
      <a href="index.php" class="nav-logo">O'Cos</a>
      <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="about-us.php" class="active">About Us</a>
        <a href="katalog-products.php">Catalog</a>
        <?php if(isset($_SESSION['user_id'])): ?>
          <a href="favorites.php" style="color: var(--primary-dark); font-weight: 600;">❤️ Favorites</a>
          <a href="my-orders.php" style="color: var(--primary-dark); font-weight: 600;">📦 My Orders</a>
          <a href="cart.php" style="color: var(--primary-dark); font-weight: 600;">🛒 Cart</a>
          <?php if($_SESSION['role'] === 'admin'): ?>
            <a href="dashboard.php" style="color: var(--primary); font-weight: 600;">Dashboard</a>
          <?php endif; ?>
          <a href="logout.php" class="btn btn-outline" style="padding: 8px 20px;">Log Out</a>
        <?php else: ?>
          <a href="log-in.php" class="btn btn-outline" style="padding: 8px 20px;">Log In</a>
        <?php endif; ?>
      </div>
    </div>
  </nav>

  <div class="container">
    <div class="about-hero">
      <h1>The Story Behind O'Cos</h1>
    </div>

    <div class="about-story">
      <p>
        O'Cos was born out of a challenge during the pandemic. We observed that many cosmetic businesses were struggling to sell their products offline due to COVID-19 restrictions. To overcome this hurdle and provide a modern solution, we built this exclusive e-commerce platform. O'Cos bridges the gap between premium cosmetic brands and beauty enthusiasts, ensuring that self-care and beauty can still thrive from the safety of home.
      </p>
    </div>

    <h2 class="team-section-title">Meet The Minds Behind O'Cos</h2>
    <p class="team-section-subtitle">Proudly developed by Semester 1 Informatics Engineering students from Poltek Batam.</p>

    <div class="team-grid">
      <!-- Team Member 1 -->
      <div class="team-card">
        <div class="team-avatar">👨🏻‍💻</div>
        <h3 class="team-name">Christoffel Aristo Marbun</h3>
        <div class="team-badge">Team Leader</div>
        <p class="team-role">Full Stack Programmer</p>
      </div>

      <!-- Team Member 2 -->
      <div class="team-card">
        <div class="team-avatar">👩🏻‍💻</div>
        <h3 class="team-name">Tarissa Magdalena</h3>
        <div class="team-badge">Core Backend</div>
        <p class="team-role">Backend Programmer</p>
      </div>

      <!-- Team Member 3 -->
      <div class="team-card">
        <div class="team-avatar">👨🏻‍💻</div>
        <h3 class="team-name">Jonathan Opel Nainggolan</h3>
        <div class="team-badge">UI/UX & Code</div>
        <p class="team-role">Frontend Programmer</p>
      </div>

      <!-- Team Member 4 -->
      <div class="team-card">
        <div class="team-avatar">👨🏻‍💻</div>
        <h3 class="team-name">Muhammad Hasan</h3>
        <div class="team-badge">UI/UX & Code</div>
        <p class="team-role">Frontend Programmer</p>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer class="footer">
    <div class="container">
      <div class="footer-content">
        <div class="footer-brand">
          <h2>O'Cos</h2>
          <p style="color: #bbb; max-width: 300px; font-weight: 300;">
            The trusted premium cosmetics platform to beautify your day.
          </p>
          <div class="footer-social">
            <a href="#" aria-label="Facebook">F</a>
            <a href="#" aria-label="Twitter">T</a>
            <a href="#" aria-label="Instagram">I</a>
          </div>
        </div>
        
        <div class="footer-contact">
          <h3>Contact Us</h3>
          <p>📧 O'Cos@company.id</p>
          <p>📍 Jl. Batu Besar No 101/Blok C</p>
          <p>📞 +62 812 3456 7890</p>
        </div>
      </div>
      <div class="footer-bottom">
        <p>&copy; 2026 O'Cos Cosmetics. All rights reserved.</p>
      </div>
    </div>
  </footer>

  <script>
    // Navbar scroll effect
    window.addEventListener('scroll', () => {
      const nav = document.querySelector('.navbar');
      if (window.scrollY > 50) {
        nav.style.boxShadow = '0 2px 10px rgba(0,0,0,0.1)';
        nav.style.padding = '15px 0';
      } else {
        nav.style.boxShadow = 'none';
        nav.style.padding = window.innerWidth > 768 ? '20px 0' : '15px 0';
      }
    });
  </script>
</body>
</html>
