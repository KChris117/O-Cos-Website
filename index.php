<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
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
        <a href="index.php" class="active">Beranda</a>
        <a href="#about">About Us</a>
        <a href="katalog-products.php">Katalog</a>
        <?php if(isset($_SESSION['user_id'])): ?>
          <?php if($_SESSION['role'] === 'admin'): ?>
            <a href="dashboard.php" style="color: var(--primary); font-weight: 600;">Dashboard</a>
          <?php endif; ?>
          <span style="font-weight: 500;">Halo, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
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
        <h1 class="hero-title">Pancarkan Pesona Cantikmu</h1>
        <p class="hero-subtitle">
          Berbagai alat kosmetik premium untuk menunjang penampilan Anda agar selalu tampil cantik, menarik, dan menawan dengan O’Cos.
        </p>
        <a href="katalog-products.php" class="btn btn-primary">Belanja Sekarang</a>
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
        <img src="./assets/image-22-RxD.png" alt="Tentang O'Cos" onerror="this.src='https://images.unsplash.com/photo-1571781926291-c477eb3af723?ixlib=rb-1.2.1&auto=format&fit=crop&w=800&q=80'" />
      </div>
      <div class="about-text">
        <h2>Apa sih O’Cos itu?</h2>
        <p>
          O’Cos adalah platform e-commerce eksklusif yang bergerak di bidang kosmetik. Kami menyediakan semua kebutuhan produk kecantikan premium Anda mulai dari skincare, lipstik, make-up, dan masih banyak lagi. Temukan versi terbaik dari diri Anda bersama O'Cos.
        </p>
      </div>
    </div>
  </section>

  <!-- Benefits Section -->
  <section class="benefits">
    <div class="container">
      <h2 class="section-title">Kenapa Memilih Kami?</h2>
      <p class="section-subtitle">Dari membeli produk di website kami, Anda akan mendapatkan berbagai benefit utama.</p>
      
      <div class="benefits-grid">
        <div class="benefit-card">
          <div class="benefit-icon">
            <img src="./assets/radio.png" alt="Mitra Terbaik" onerror="this.style.display='none'; this.parentNode.innerHTML='🤝';" />
          </div>
          <h3>Mitra Terbaik & Terpercaya</h3>
          <p style="color: var(--text-muted); font-size: 0.95rem;">Kami bekerja sama dengan brand ternama dan terjamin keasliannya.</p>
        </div>
        
        <div class="benefit-card">
          <div class="benefit-icon">
            <img src="./assets/package.png" alt="Produk Aman" onerror="this.style.display='none'; this.parentNode.innerHTML='✨';" />
          </div>
          <h3>Produk Aman & Berkualitas</h3>
          <p style="color: var(--text-muted); font-size: 0.95rem;">Setiap produk melewati uji kualitas ketat demi keamanan kulit Anda.</p>
        </div>
        
        <div class="benefit-card">
          <div class="benefit-icon">
            <img src="./assets/group-1-FJy.png" alt="Harga Terjangkau" onerror="this.style.display='none'; this.parentNode.innerHTML='💎';" />
          </div>
          <h3>Harga Terjangkau</h3>
          <p style="color: var(--text-muted); font-size: 0.95rem;">Dapatkan produk kosmetik premium dengan harga yang bersahabat.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Categories Section -->
  <section class="categories">
    <div class="container">
      <h2 class="section-title">Kategori Produk</h2>
      <p class="section-subtitle">Temukan berbagai macam kategori produk sesuai kebutuhan kecantikan Anda di O’Cos.</p>
      
      <div class="categories-grid">
        <div class="category-card" onclick="window.location.href='katalog-products.php'">
          <img src="./assets/face.png" alt="Wajah" onerror="this.src='https://images.unsplash.com/photo-1616683693504-3ea7e9ad6fec?auto=format&fit=crop&w=400&q=80'" />
          <div class="category-overlay">
            <h3>WAJAH</h3>
          </div>
        </div>
        
        <div class="category-card" onclick="window.location.href='katalog-products.php'">
          <img src="./assets/hair.webp" alt="Rambut" onerror="this.src='https://images.unsplash.com/photo-1522337660859-02fbefca4702?auto=format&fit=crop&w=400&q=80'" />
          <div class="category-overlay">
            <h3>RAMBUT</h3>
          </div>
        </div>
        
        <div class="category-card" onclick="window.location.href='katalog-products.php'">
          <img src="./assets/lips.webp" alt="Bibir" onerror="this.src='https://images.unsplash.com/photo-1586495777744-4413f21062fa?auto=format&fit=crop&w=400&q=80'" />
          <div class="category-overlay">
            <h3>BIBIR</h3>
          </div>
        </div>
        
        <div class="category-card" onclick="window.location.href='katalog-products.php'">
          <img src="./assets/nail.png" alt="Kuku" onerror="this.src='https://images.unsplash.com/photo-1519014816548-bf5fe059e98b?auto=format&fit=crop&w=400&q=80'" />
          <div class="category-overlay">
            <h3>KUKU</h3>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Brands/Jenis Produk Section -->
  <section class="brands">
    <div class="container">
      <h2 class="section-title">Jenis Produk</h2>
      <p class="section-subtitle">Terdiri dari berbagai jenis brand ternama luar negeri dan lokal pilihan.</p>
      
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
            Platform kosmetik premium yang dipercaya untuk mempercantik harimu.
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
    // Simple script to handle navbar scroll effect
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
  </script>
</body>
</html>
