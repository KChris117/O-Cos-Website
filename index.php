<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <link rel="icon" href="/favicon.ico" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="theme-color" content="#d4a398" />
  <title>O'Cos - Premium Cosmetics</title>
  
  <!-- Global Styles & Home Specific Styles -->
  <link rel="stylesheet" href="./styles/global.css"/>
  <link rel="stylesheet" href="./styles/home.css"/>
</head>
<body>

  <!-- Navigation -->
  <nav class="navbar">
    <div class="container">
      <a href="index.php" class="nav-logo">O'Cos</a>
      <div class="nav-links">
        <a href="index.php" class="active">Home</a>
        <a href="#about">About Us</a>
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

  <!-- Hero Section -->
  <header class="hero">
    <div class="hero-bg-shape"></div>
    <div class="container" style="display: flex; align-items: center; position: relative; z-index: 1;">
      <div class="hero-content">
        <?php if(isset($_SESSION['user_id'])): ?>
          <p style="color: var(--primary-dark); font-weight: 600; font-size: 1.1rem; margin-bottom: 10px; letter-spacing: 1px; text-transform: uppercase;">
            Welcome to O'Cos, <?php echo htmlspecialchars($_SESSION['username']); ?>! ✨
          </p>
        <?php endif; ?>
        <h1 class="hero-title">Radiate Your Beauty</h1>
        <p class="hero-subtitle">
          Various premium cosmetics to support your appearance so you always look beautiful, attractive, and stunning with O’Cos.
        </p>
        <a href="katalog-products.php" class="btn btn-primary">Shop Now</a>
      </div>
      <div class="hero-image">
        <!-- Using image-4.png as the hero banner -->
        <img src="./assets/image-4.png" alt="O'Cos Cosmetics Showcase" onerror="this.src='https://images.unsplash.com/photo-1596462502278-27bfdc403348?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80'" />
      </div>
    </div>
  </header>

  <!-- About Section -->
  <section id="about" class="about">
    <div class="container">
      <div class="about-image">
        <img src="./assets/image-22-RxD.png" alt="About O'Cos" onerror="this.src='https://images.unsplash.com/photo-1571781926291-c477eb3af723?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80'" />
      </div>
      <div class="about-text">
        <h2>What is O’Cos?</h2>
        <p>
          O’Cos is an exclusive e-commerce platform operating in the cosmetics sector. We provide all your premium beauty product needs from skincare, lipsticks, makeup, and much more. Discover the best version of yourself with O'Cos.
        </p>
      </div>
    </div>
  </section>

  <!-- Benefits Section -->
  <section class="benefits">
    <div class="container">
      <h2 class="section-title">Why Choose Us?</h2>
      <p class="section-subtitle">By purchasing products on our website, you will get various key benefits.</p>
      
      <div class="benefits-grid">
        <div class="benefit-card">
          <div class="benefit-icon">
            <img src="./assets/radio.png" alt="Best Partner" onerror="this.style.display='none'; this.parentNode.innerHTML='🤝';" />
          </div>
          <h3>Best & Trusted Partner</h3>
          <p style="color: var(--text-muted); font-size: 0.95rem;">We partner with top brands and guarantee their authenticity.</p>
        </div>
        
        <div class="benefit-card">
          <div class="benefit-icon">
            <img src="./assets/package.png" alt="Safe Products" onerror="this.style.display='none'; this.parentNode.innerHTML='✨';" />
          </div>
          <h3>Safe & Quality Products</h3>
          <p style="color: var(--text-muted); font-size: 0.95rem;">Every product passes strict quality tests for your skin's safety.</p>
        </div>
        
        <div class="benefit-card">
          <div class="benefit-icon">
            <img src="./assets/group-1-FJy.png" alt="Affordable Prices" onerror="this.style.display='none'; this.parentNode.innerHTML='💎';" />
          </div>
          <h3>Affordable Prices</h3>
          <p style="color: var(--text-muted); font-size: 0.95rem;">Get premium cosmetics at friendly and affordable prices.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Categories Section -->
  <section class="categories">
    <div class="container">
      <h2 class="section-title">Product Categories</h2>
      <p class="section-subtitle">Find various product categories according to your beauty needs at O’Cos.</p>
      
      <div class="categories-grid">
        <div class="category-card" onclick="window.location.href='katalog-products.php'">
          <img src="./assets/face.png" alt="Face" onerror="this.src='https://images.unsplash.com/photo-1616683693504-3ea7e9ad6fec?auto=format&fit=crop&w=400&q=80'" />
          <div class="category-overlay">
            <h3>FACE</h3>
          </div>
        </div>
        
        <div class="category-card" onclick="window.location.href='katalog-products.php'">
          <img src="./assets/hair.webp" alt="Hair" onerror="this.src='https://images.unsplash.com/photo-1522337660859-02fbefca4702?auto=format&fit=crop&w=400&q=80'" />
          <div class="category-overlay">
            <h3>HAIR</h3>
          </div>
        </div>
        
        <div class="category-card" onclick="window.location.href='katalog-products.php'">
          <img src="./assets/lips.webp" alt="Lips" onerror="this.src='https://images.unsplash.com/photo-1586495777744-4413f21062fa?auto=format&fit=crop&w=400&q=80'" />
          <div class="category-overlay">
            <h3>LIPS</h3>
          </div>
        </div>
        
        <div class="category-card" onclick="window.location.href='katalog-products.php'">
          <img src="./assets/nail.png" alt="Nails" onerror="this.src='https://images.unsplash.com/photo-1519014816548-bf5fe059e98b?auto=format&fit=crop&w=400&q=80'" />
          <div class="category-overlay">
            <h3>NAILS</h3>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Brands Section -->
  <section class="brands">
    <div class="container">
      <h2 class="section-title">Our Brands</h2>
      <p class="section-subtitle">Consists of various top international and selected local brands.</p>
      
      <div class="brands-slider">
        <img src="./assets/image-10.png" alt="Brand 1" onerror="this.style.display='none'" />
        <img src="./assets/image-12-sEH.png" alt="Brand 2" onerror="this.style.display='none'" />
        <img src="./assets/image-14-Dpd.png" alt="Brand 3" onerror="this.style.display='none'" />
        <img src="./assets/image-13.png" alt="Brand 4" onerror="this.style.display='none'" />
      </div>
    </div>
  </section>

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
        nav.style.padding = '20px 0';
      }
    });

    // Navbar active state logic for About Us
    document.querySelectorAll('.nav-links a').forEach(link => {
      link.addEventListener('click', function() {
        if(!this.classList.contains('btn')) {
          document.querySelectorAll('.nav-links a:not(.btn)').forEach(l => l.classList.remove('active'));
          this.classList.add('active');
        }
      });
    });
  </script>
</body>
</html>
