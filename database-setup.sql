-- Script untuk membuat database dan tabel O'Cos (Versi 2 - Dengan Fitur Dashboard & Stok)
-- Silakan jalankan ini di phpMyAdmin (localhost:8080/phpmyadmin)
-- Jika database sebelumnya sudah ada, Anda bisa menghapusnya (Drop) terlebih dahulu, atau jalankan kode di bawah ini:



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
-- Menggunakan DELETE alih-alih TRUNCATE karena ada relasi Foreign Key (Cart/Favorites)
DELETE FROM `products`;
ALTER TABLE `products` AUTO_INCREMENT = 1;

-- Masukkan beberapa data awal (dummy) ke dalam database untuk diuji coba
INSERT INTO `products` (`name`, `category`, `price`, `stock`, `description`, `image`) VALUES
('Facial Foam by Sari Ayu', 'FACE', 20000.00, 50, 'Facial cleansing foam containing Langsat fruit extract.', './assets/image-16-w2R.png'),
('Nail Polish by Maybelline', 'NAILS', 32000.00, 15, 'Quality nail polish that is long-lasting and shiny.', './assets/image-17-F3X.png'),
('Facial Foam by Wardah', 'FACE', 27000.00, 100, 'Facial cleanser with a gentle pH balance formula.', './assets/image-18-uTX.png'),
('Shampoo Keratin Smooth by TRESemme', 'HAIR', 100000.00, 20, 'Soft and manageable hair for 48 hours.', './assets/image-19-acH.png'),
('Micellar Water by Garnier', 'FACE', 35000.00, 85, 'Cleanses makeup and dirt gently.', './assets/image-20.png'),
('Facial Scrub by Wardah', 'FACE', 25000.00, 0, 'Gentle scrub to remove dead skin cells.', './assets/image-21.png'),
('Matte Liquid Lipstick by MAC', 'LIPS', 150000.00, 30, 'Long-lasting matte liquid lipstick.', './assets/dummy-1.png'),
('Lip Tint by Emina', 'LIPS', 35000.00, 80, 'Lightweight lip tint for everyday use.', './assets/dummy-2.png'),
('Lip Gloss by Dior', 'LIPS', 450000.00, 10, 'High-shine premium lip gloss.', './assets/dummy-3.png'),
('Lip Balm by Nivea', 'LIPS', 20000.00, 150, 'Moisturizing lip balm with strawberry flavor.', './assets/dummy-4.png'),
('Lip Liner by NYX', 'LIPS', 85000.00, 45, 'Smooth and creamy lip liner.', './assets/dummy-5.png'),
('Lip Mask by Laneige', 'LIPS', 250000.00, 25, 'Overnight lip mask for soft lips.', './assets/dummy-6.png'),
('Lip Scrub by Emina', 'LIPS', 40000.00, 60, 'Gentle sugar scrub for lips.', './assets/dummy-7.png'),
('Lip Plumper by Too Faced', 'LIPS', 350000.00, 15, 'Extreme lip plumping gloss.', './assets/dummy-8.png'),
('Lip Oil by Clarins', 'LIPS', 320000.00, 20, 'Nourishing lip oil with a hint of color.', './assets/dummy-9.png'),
('Liquid Lipstick by Maybelline', 'LIPS', 110000.00, 90, 'Superstay matte ink liquid lipstick.', './assets/dummy-10.png'),
('Foundation by Make Over', 'FACE', 125000.00, 40, 'Full coverage liquid foundation.', './assets/dummy-11.png'),
('Concealer by Maybelline', 'FACE', 95000.00, 55, 'Fit Me concealer for flawless look.', './assets/dummy-12.png'),
('Blush On by Emina', 'FACE', 38000.00, 120, 'Cheek lit pressed blush.', './assets/dummy-13.png'),
('Highlighter by Fenty Beauty', 'FACE', 550000.00, 8, 'Killawatt freestyle highlighter.', './assets/dummy-14.png'),
('Face Powder by Marks', 'FACE', 15000.00, 200, 'Classic loose powder for oily skin.', './assets/dummy-15.png'),
('Face Primer by Pixy', 'FACE', 55000.00, 70, 'Make it glow beauty skin primer.', './assets/dummy-16.png'),
('Setting Spray by NYX', 'FACE', 150000.00, 35, 'Matte finish setting spray.', './assets/dummy-17.png'),
('Hydrating Toner by Hada Labo', 'FACE', 45000.00, 85, 'Ultimate moisturizing lotion.', './assets/dummy-18.png'),
('Vitamin C Serum by Somethinc', 'FACE', 135000.00, 50, 'Brightening serum with Vitamin C.', './assets/dummy-19.png'),
('Day Cream Moisturizer by Olay', 'FACE', 110000.00, 45, 'Total effects 7 in 1 day cream.', './assets/dummy-20.png'),
('Hair Mask by Makarizo', 'HAIR', 65000.00, 60, 'Hair energy fibertherapy hair & scalp creambath.', './assets/dummy-21.png'),
('Conditioner by Pantene', 'HAIR', 25000.00, 100, 'Pro-V hair fall control conditioner.', './assets/dummy-22.png'),
('Hair Serum by LOreal', 'HAIR', 125000.00, 30, 'Extraordinary oil hair serum.', './assets/dummy-23.png'),
('Hair Oil by Natur', 'HAIR', 35000.00, 75, 'Natural hair oil with aloe vera.', './assets/dummy-24.png'),
('Dry Shampoo by Batiste', 'HAIR', 85000.00, 40, 'Original classic fresh dry shampoo.', './assets/dummy-25.png'),
('Hair Color by Garnier', 'HAIR', 45000.00, 55, 'Color naturals ultra color.', './assets/dummy-26.png'),
('Hair Spray by TRESemme', 'HAIR', 70000.00, 25, 'Extra hold hair spray.', './assets/dummy-27.png'),
('Hair Pomade by Gatsby', 'HAIR', 30000.00, 80, 'Water base pomade.', './assets/dummy-28.png'),
('Scalp Scrub by The Body Shop', 'HAIR', 250000.00, 15, 'Fuji green tea cleansing hair scrub.', './assets/dummy-29.png'),
('Hair Vitamins by Ellips', 'HAIR', 12000.00, 150, 'Hair treatment vitamins with Moroccan oil.', './assets/dummy-30.png'),
('Base Coat by Revlon', 'NAILS', 45000.00, 40, 'Quick dry base coat.', './assets/dummy-31.png'),
('Top Coat by OPI', 'NAILS', 150000.00, 20, 'High shine top coat.', './assets/dummy-32.png'),
('Cuticle Oil by The Body Shop', 'NAILS', 95000.00, 25, 'Almond nail and cuticle oil.', './assets/dummy-33.png'),
('Gel Nail Polish by Innisfree', 'NAILS', 55000.00, 60, 'Real color nail polish.', './assets/dummy-34.png'),
('Nail Strengthener by Sally Hansen', 'NAILS', 120000.00, 15, 'Hard as nails vitamin strength.', './assets/dummy-35.png'),
('Matte Top Coat by Golden Rose', 'NAILS', 35000.00, 50, 'Matte finish top coat.', './assets/dummy-36.png'),
('Nail Polish Remover by Acetone', 'NAILS', 15000.00, 100, 'Fast acting nail polish remover.', './assets/dummy-37.png'),
('Sheet Mask by Mediheal', 'FACE', 25000.00, 80, 'Hydrating tea tree essence mask.', './assets/dummy-38.png'),
('Lip Tint by Somethinc', 'LIPS', 60000.00, 130, 'Ombrell lip totem tint.', './assets/dummy-39.png'),
('Hair Mousse by Makarizo', 'HAIR', 45000.00, 60, 'Styling mousse for curly hair.', './assets/dummy-40.png'),
('Nail File Set by Sephora', 'NAILS', 75000.00, 40, 'Professional nail file block.', './assets/dummy-41.png'),
('Eyeliner by Wardah', 'FACE', 48000.00, 95, 'Optimum hi-black liquid eyeliner.', './assets/dummy-42.png'),
('Mascara by Maybelline', 'FACE', 85000.00, 110, 'Lash sensational sky high mascara.', './assets/dummy-43.png'),
('Eyebrow Pencil by Viva', 'FACE', 38000.00, 200, 'Classic waterproof eyebrow pencil.', './assets/dummy-44.png');

-- (Opsional) Jika tabel products Anda sudah ada sebelumnya tapi belum ada kolom stock, jalankan query ini saja:
-- ALTER TABLE `products` ADD `stock` INT(11) NOT NULL DEFAULT 0 AFTER `price`;
