<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit();
}

verify_csrf_token($_POST['csrf_token'] ?? '');

$user_id = intval($_SESSION['user_id']);
$address = $_POST['address'];

// Fetch Cart Items
$stmt_fetch = mysqli_prepare($conn, "
    SELECT c.product_id, c.quantity, p.price, p.stock
    FROM cart_items c
    JOIN products p ON c.product_id = p.id
    WHERE c.user_id = ?
");
mysqli_stmt_bind_param($stmt_fetch, "i", $user_id);
mysqli_stmt_execute($stmt_fetch);
$cart_items = mysqli_stmt_get_result($stmt_fetch);

if(mysqli_num_rows($cart_items) == 0) {
    header("Location: cart.php");
    exit();
}

// Calculate Total
$total_amount = 0;
$items = [];
while($item = mysqli_fetch_assoc($cart_items)) {
    // Basic stock check validation
    if($item['stock'] < $item['quantity']) {
        // If someone bought it right before them
        die("Error: Insufficient stock for one of your items. Please go back to cart and update.");
    }
    
    $total_amount += ($item['price'] * $item['quantity']);
    $items[] = $item;
}

// Generate Transaction ID
$trx_id = 'TRX-' . time() . '-' . rand(100, 999);

// Begin Transaction Simulation
mysqli_begin_transaction($conn);

try {
    // 1. Insert into transactions
    $status = 'Pending';
    $stmt_trx = mysqli_prepare($conn, "INSERT INTO transactions (id, user_id, address, total_amount, status) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt_trx, "sisds", $trx_id, $user_id, $address, $total_amount, $status);
    mysqli_stmt_execute($stmt_trx);
    
    // 2. Insert into transaction_details & Update Stock
    $stmt_det = mysqli_prepare($conn, "INSERT INTO transaction_details (transaction_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
    $stmt_stock = mysqli_prepare($conn, "UPDATE products SET stock = stock - ? WHERE id = ?");
    
    foreach($items as $item) {
        $p_id = $item['product_id'];
        $qty = $item['quantity'];
        $price = $item['price'];
        
        // Detail
        mysqli_stmt_bind_param($stmt_det, "siid", $trx_id, $p_id, $qty, $price);
        mysqli_stmt_execute($stmt_det);
        
        // Stock Deduction
        mysqli_stmt_bind_param($stmt_stock, "ii", $qty, $p_id);
        mysqli_stmt_execute($stmt_stock);
    }
    
    // 3. Clear Cart
    $stmt_clear = mysqli_prepare($conn, "DELETE FROM cart_items WHERE user_id = ?");
    mysqli_stmt_bind_param($stmt_clear, "i", $user_id);
    mysqli_stmt_execute($stmt_clear);
    
    // Commit all changes
    mysqli_commit($conn);
    
    // Redirect to Success page
    header("Location: success.php?trx_id=$trx_id");
    exit();

} catch (Exception $e) {
    mysqli_rollback($conn);
    die("Transaction Failed: " . $e->getMessage());
}
?>
