<?php
// config.php
// Konfigurasi koneksi ke database MySQL XAMPP

$db_host = "127.0.0.1";
$db_user = "root";       // Default username XAMPP
$db_pass = "";           // Default password XAMPP (kosong)
$db_name = "db_o_cos";   // Nama database sesuai kesepakatan

// Membuat koneksi
$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

// Mengecek koneksi
if (!$conn) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}

require_once 'includes/security.php';
?>
