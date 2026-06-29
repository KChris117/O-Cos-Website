<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit();
}

$user_id = intval($_SESSION['user_id']);
$address = mysqli_real_escape_string($conn, $_POST['address']);

// Fetch Cart Items
$query = "
    SELECT c.product_id, c.quantity, p.price, p.stock
    FROM cart_items c
    JOIN products p ON c.product_id = p.id
    WHERE c.user_id = $user_id
";
$cart_items = mysqli_query($conn, $query);

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
    $insert_trx = "INSERT INTO transactions (id, user_id, address, total_amount, status) VALUES ('$trx_id', $user_id, '$address', $total_amount, 'Pending')";
    mysqli_query($conn, $insert_trx);
    
    // 2. Insert into transaction_details & Update Stock
    foreach($items as $item) {
        $p_id = $item['product_id'];
        $qty = $item['quantity'];
        $price = $item['price'];
        
        // Detail
        mysqli_query($conn, "INSERT INTO transaction_details (transaction_id, product_id, quantity, price) VALUES ('$trx_id', $p_id, $qty, $price)");
        
        // Stock Deduction
        mysqli_query($conn, "UPDATE products SET stock = stock - $qty WHERE id = $p_id");
    }
    
    // 3. Clear Cart
    mysqli_query($conn, "DELETE FROM cart_items WHERE user_id = $user_id");
    
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
