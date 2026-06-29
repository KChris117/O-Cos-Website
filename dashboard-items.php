<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: log-in.php");
    exit();
}

// Hapus Item (Delete)
if(isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    mysqli_query($conn, "DELETE FROM products WHERE id = $id");
    header("Location: dashboard-items.php");
    exit();
}

// Tambah (Create) atau Edit (Update) Item
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $image = mysqli_real_escape_string($conn, $_POST['image']);
    
    if(isset($_POST['id']) && !empty($_POST['id'])) {
        // UPDATE
        $id = intval($_POST['id']);
        mysqli_query($conn, "UPDATE products SET name='$name', category='$category', price='$price', stock='$stock', description='$description', image='$image' WHERE id=$id");
    } else {
        // CREATE
        mysqli_query($conn, "INSERT INTO products (name, category, price, stock, description, image) VALUES ('$name', '$category', '$price', '$stock', '$description', '$image')");
    }
    header("Location: dashboard-items.php");
    exit();
}

// Ambil semua produk
$products = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Manage Items - Admin O'Cos</title>
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
      <a href="dashboard-items.php" class="active">🛍️ Manage Items</a>
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
      <h1 class="dashboard-title">Manage Items (Products)</h1>
    </div>

    <!-- Form Create ONLY -->
    <div class="form-card" style="margin-bottom: 40px; margin-left: 0; max-width: 100%;">
      <h3 style="margin-top: 0; color: var(--primary-dark);">Add New Item</h3>
      
      <form action="dashboard-items.php" method="POST">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
          <div class="form-group">
            <label>Product Name</label>
            <input type="text" name="name" required placeholder="e.g., Matte Lipstick" />
          </div>
          
          <div class="form-group">
            <label>Category</label>
            <select name="category" required>
              <option value="FACE">Face</option>
              <option value="LIPS">Lips</option>
              <option value="EYES">Eyes</option>
              <option value="NAILS">Nails</option>
              <option value="BODY">Body</option>
              <option value="HAIR">Hair</option>
            </select>
          </div>
          
          <div class="form-group">
            <label>Price (Rp)</label>
            <input type="number" name="price" required min="0" placeholder="100000" />
          </div>
          
          <div class="form-group">
            <label>Available Stock</label>
            <input type="number" name="stock" required min="0" placeholder="50" value="0" />
          </div>
        </div>
        
        <div class="form-group">
          <label>Image URL (e.g., ./assets/image.png or https://...)</label>
          <input type="text" name="image" required value="./assets/image-placeholder.png" />
        </div>
        
        <div class="form-group">
          <label>Product Description</label>
          <textarea name="description" required placeholder="Write product details here..."></textarea>
        </div>
        
        <button type="submit" class="btn btn-primary">Save New Product</button>
      </form>
    </div>

    <!-- Data Table -->
    <div class="table-container">
      <div class="table-header">
        <h3>Cosmetic Products List</h3>
      </div>
      <div style="overflow-x: auto;">
        <table class="data-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Image</th>
              <th>Item Name</th>
              <th>Category</th>
              <th>Price</th>
              <th>Stock</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if(mysqli_num_rows($products) > 0): ?>
              <?php while($row = mysqli_fetch_assoc($products)): ?>
              <tr>
                <td>#<?php echo $row['id']; ?></td>
                <td>
                  <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="img" style="width: 40px; height: 40px; border-radius: 8px; object-fit: cover;" onerror="this.src='https://via.placeholder.com/40'" />
                </td>
                <td style="font-weight: 500; color: var(--primary-dark);"><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['category']); ?></td>
                <td>Rp. <?php echo number_format($row['price'], 0, ',', '.'); ?></td>
                <td>
                  <?php if($row['stock'] <= 5): ?>
                    <span style="color: #c62828; font-weight: 600;"><?php echo $row['stock']; ?> (Critical)</span>
                  <?php else: ?>
                    <span style="color: #2e7d32; font-weight: 600;"><?php echo $row['stock']; ?></span>
                  <?php endif; ?>
                </td>
                <td>
                  <!-- Edit Button triggering Modal -->
                  <button type="button" class="action-btn btn-edit" 
                    data-id="<?php echo $row['id']; ?>"
                    data-name="<?php echo htmlspecialchars($row['name']); ?>"
                    data-category="<?php echo htmlspecialchars($row['category']); ?>"
                    data-price="<?php echo $row['price']; ?>"
                    data-stock="<?php echo $row['stock']; ?>"
                    data-image="<?php echo htmlspecialchars($row['image']); ?>"
                    data-description="<?php echo htmlspecialchars($row['description']); ?>"
                    onclick="openEditModal(this)">
                    Edit
                  </button>
                  <a href="dashboard-items.php?delete=<?php echo $row['id']; ?>" class="action-btn btn-delete" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                </td>
              </tr>
              <?php endwhile; ?>
            <?php else: ?>
              <tr>
                <td colspan="7" style="text-align: center; color: var(--text-muted); padding: 30px;">No items in the database yet.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </main>

  <!-- Edit Modal Overlay -->
  <div class="modal-overlay" id="editModalOverlay">
    <div class="modal-content">
      <span class="modal-close" onclick="closeEditModal()">&times;</span>
      <h3 style="margin-top: 0; color: var(--primary-dark);">Edit Item Details</h3>
      
      <form action="dashboard-items.php" method="POST" id="editForm">
        <!-- Hidden ID for update -->
        <input type="hidden" name="id" id="edit-id" value="" />
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
          <div class="form-group">
            <label>Product Name</label>
            <input type="text" name="name" id="edit-name" required />
          </div>
          
          <div class="form-group">
            <label>Category</label>
            <select name="category" id="edit-category" required>
              <option value="FACE">Face</option>
              <option value="LIPS">Lips</option>
              <option value="EYES">Eyes</option>
              <option value="NAILS">Nails</option>
              <option value="BODY">Body</option>
              <option value="HAIR">Hair</option>
            </select>
          </div>
          
          <div class="form-group">
            <label>Price (Rp)</label>
            <input type="number" name="price" id="edit-price" required min="0" />
          </div>
          
          <div class="form-group">
            <label>Available Stock</label>
            <input type="number" name="stock" id="edit-stock" required min="0" />
          </div>
        </div>
        
        <div class="form-group">
          <label>Image URL</label>
          <input type="text" name="image" id="edit-image" required />
        </div>
        
        <div class="form-group">
          <label>Product Description</label>
          <textarea name="description" id="edit-description" required></textarea>
        </div>
        
        <button type="submit" class="btn btn-primary" style="width: 100%;">Save Changes</button>
      </form>
    </div>
  </div>

  <script>
    const modalOverlay = document.getElementById('editModalOverlay');

    function openEditModal(buttonElement) {
      // Get data from button attributes
      const id = buttonElement.getAttribute('data-id');
      const name = buttonElement.getAttribute('data-name');
      const category = buttonElement.getAttribute('data-category');
      const price = buttonElement.getAttribute('data-price');
      const stock = buttonElement.getAttribute('data-stock');
      const image = buttonElement.getAttribute('data-image');
      const description = buttonElement.getAttribute('data-description');

      // Populate Modal Fields
      document.getElementById('edit-id').value = id;
      document.getElementById('edit-name').value = name;
      document.getElementById('edit-category').value = category;
      document.getElementById('edit-price').value = price;
      document.getElementById('edit-stock').value = stock;
      document.getElementById('edit-image').value = image;
      document.getElementById('edit-description').value = description;

      // Show Modal
      modalOverlay.classList.add('active');
    }

    function closeEditModal() {
      modalOverlay.classList.remove('active');
    }

    // Close modal when clicking outside the modal box
    modalOverlay.addEventListener('click', function(e) {
      if (e.target === modalOverlay) {
        closeEditModal();
      }
    });
  </script>

</body>
</html>
