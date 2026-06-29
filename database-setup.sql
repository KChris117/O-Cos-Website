-- Script untuk membuat database dan tabel O'Cos
-- Silakan jalankan ini di phpMyAdmin (localhost:8080/phpmyadmin)

CREATE DATABASE IF NOT EXISTS `db_o_cos`;
USE `db_o_cos`;

CREATE TABLE IF NOT EXISTS `products` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `category` VARCHAR(100) NOT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  `description` TEXT NOT NULL,
  `image` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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

-- Masukkan beberapa data awal (dummy) ke dalam database untuk diuji coba
INSERT INTO `products` (`name`, `category`, `price`, `description`, `image`) VALUES
('Facial Foam by Sari Ayu', 'WAJAH', 20000.00, 'Busa pembersih wajah yang mengandung ekstrak buah Langsat.', './assets/image-16-w2R.png'),
('Nail Polish (Kutek) by Maybelline', 'KUKU', 32000.00, 'Pewarna kuku berkualitas yang tahan lama dan mengkilap.', './assets/image-17-F3X.png'),
('Facial Foam by Wardah', 'WAJAH', 27000.00, 'Pembersih wajah dengan formula pH balance yang lembut.', './assets/image-18-uTX.png'),
('Shampoo Keratin Smooth by TRESemme', 'RAMBUT', 100000.00, 'Rambut lembut dan mudah diatur selama 48 jam.', './assets/image-19-acH.png'),
('Body Lotion by Nivea', 'TUBUH', 21000.00, 'Lotion pelembab yang menutrisi kulit hingga 24 jam.', './assets/image-20.png'),
('Facial Scrub by Wardah', 'WAJAH', 25000.00, 'Scrub lembut untuk mengangkat sel kulit mati.', './assets/image-21.png');
