-- Script untuk membuat database dan tabel O'Cos (Versi 2 - Dengan Fitur Dashboard & Stok)
-- Silakan jalankan ini di phpMyAdmin (localhost:8080/phpmyadmin)
-- Jika database sebelumnya sudah ada, Anda bisa menghapusnya (Drop) terlebih dahulu, atau jalankan kode di bawah ini:

CREATE DATABASE IF NOT EXISTS `db_o_cos`;
USE `db_o_cos`;

-- Tabel Produk (Ditambah kolom stock)
CREATE TABLE IF NOT EXISTS `products` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `category` VARCHAR(100) NOT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  `stock` INT(11) NOT NULL DEFAULT 0,
  `description` TEXT NOT NULL,
  `image` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel Users
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('admin', 'user') NOT NULL DEFAULT 'user',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel Favorites
CREATE TABLE IF NOT EXISTS `favorites` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `product_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_product` (`user_id`, `product_id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel Transactions
CREATE TABLE IF NOT EXISTS `transactions` (
  `id` VARCHAR(50) NOT NULL,
  `user_id` INT(11) NOT NULL,
  `address` TEXT NOT NULL,
  `total_amount` DECIMAL(10,2) NOT NULL,
  `status` ENUM('Pending', 'On Packing', 'On Delivery', 'Completed', 'Canceled') NOT NULL DEFAULT 'Pending',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel Transaction Details
CREATE TABLE IF NOT EXISTS `transaction_details` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `transaction_id` VARCHAR(50) NOT NULL,
  `product_id` INT(11) NOT NULL,
  `quantity` INT(11) NOT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`transaction_id`) REFERENCES `transactions`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabel Cart Items (Keranjang Belanja)
CREATE TABLE IF NOT EXISTS `cart_items` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `user_id` INT(11) NOT NULL,
  `product_id` INT(11) NOT NULL,
  `quantity` INT(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Hapus data lama agar bersih sebelum disisipkan yang baru (opsional jika sudah ada)
TRUNCATE TABLE `products`;

-- Masukkan beberapa data awal (dummy) ke dalam database untuk diuji coba
INSERT INTO `products` (`name`, `category`, `price`, `stock`, `description`, `image`) VALUES
('Facial Foam by Sari Ayu', 'FACE', 20000.00, 50, 'Facial cleansing foam containing Langsat fruit extract.', './assets/image-16-w2R.png'),
('Nail Polish by Maybelline', 'NAILS', 32000.00, 15, 'Quality nail polish that is long-lasting and shiny.', './assets/image-17-F3X.png'),
('Facial Foam by Wardah', 'FACE', 27000.00, 100, 'Facial cleanser with a gentle pH balance formula.', './assets/image-18-uTX.png'),
('Shampoo Keratin Smooth by TRESemme', 'HAIR', 100000.00, 20, 'Soft and manageable hair for 48 hours.', './assets/image-19-acH.png'),
('Body Lotion by Nivea', 'BODY', 21000.00, 35, 'Moisturizing lotion that nourishes the skin for up to 24 hours.', './assets/image-20.png'),
('Facial Scrub by Wardah', 'FACE', 25000.00, 0, 'Gentle scrub to remove dead skin cells.', './assets/image-21.png');

-- (Opsional) Jika tabel products Anda sudah ada sebelumnya tapi belum ada kolom stock, jalankan query ini saja:
-- ALTER TABLE `products` ADD `stock` INT(11) NOT NULL DEFAULT 0 AFTER `price`;
