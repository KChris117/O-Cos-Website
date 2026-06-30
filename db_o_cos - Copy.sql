-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 30, 2026 at 05:56 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_o_cos`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `description` text NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `category`, `price`, `stock`, `description`, `image`) VALUES
(1, 'Facial Foam by Sari Ayu', 'FACE', 20000.00, 50, 'Facial cleansing foam containing Langsat fruit extract.', './assets/image-16.png'),
(2, 'Nail Polish by Maybelline', 'NAILS', 32000.00, 15, 'Quality nail polish that is long-lasting and shiny.', './assets/image-19-GMo.png'),
(3, 'Facial Foam by Wardah', 'FACE', 27000.00, 100, 'Facial cleanser with a gentle pH balance formula.', './assets/image-18-uTX.png'),
(4, 'Shampoo Keratin Smooth by TRESemme', 'HAIR', 100000.00, 20, 'Soft and manageable hair for 48 hours.', './assets/image-19.png'),
(5, 'Micellar Water by Garnier', 'FACE', 35000.00, 85, 'Cleanses makeup and dirt gently.', './assets/image-20.png'),
(6, 'Facial Scrub by Wardah', 'FACE', 25000.00, 0, 'Gentle scrub to remove dead skin cells.', './assets/image-21.png'),
(9, 'Lip Gloss by Dior', 'LIPS', 450000.00, 10, 'High-shine premium lip gloss.', './assets/LipGloss_Dior.jpg'),
(10, 'Lip Balm by Nivea', 'LIPS', 20000.00, 150, 'Moisturizing lip balm with strawberry flavor.', './assets/LipBalm_Nivea.jpg'),
(13, 'Lip Scrub by Emina', 'LIPS', 40000.00, 60, 'Gentle sugar scrub for lips.', './assets/LipScrub_Emina.jpg'),
(15, 'Lip Oil by Clarins', 'LIPS', 320000.00, 20, 'Nourishing lip oil with a hint of color.', './assets/LipOil_Clarins.webp'),
(16, 'Liquid Lipstick by Maybelline', 'LIPS', 110000.00, 90, 'Superstay matte ink liquid lipstick.', './assets/LiquidLipstick_Maybelline.jpg'),
(32, 'Hair Color by Garnier', 'HAIR', 45000.00, 55, 'Color naturals ultra color.', './assets/HairColor_Garnier.avif'),
(33, 'Hair Spray by TRESemme', 'HAIR', 70000.00, 25, 'Extra hold hair spray.', './assets/HairSpray_TRESemme.jpg'),
(34, 'Hair Pomade by Gatsby', 'HAIR', 30000.00, 80, 'Water base pomade.', './assets/HairPomade_Gatsby.avif'),
(35, 'Scalp Scrub by The Body Shop', 'HAIR', 250000.00, 15, 'Fuji green tea cleansing hair scrub.', './assets/ScalpScrub_TheBodyShop.png'),
(36, 'Hair Vitamins by Ellips', 'HAIR', 12000.00, 150, 'Hair treatment vitamins with Moroccan oil.', './assets/HairVitamins_Ellips.avif'),
(41, 'Nail Strengthener by Sally Hansen', 'NAILS', 120000.00, 15, 'Hard as nails vitamin strength.', './assets/Nail_Strengthener_SallyHansen.jpg'),
(42, 'Matte Top Coat by Golden Rose', 'NAILS', 35000.00, 50, 'Matte finish top coat.', './assets/Matte_TopCoat_GoldenRose.jpg'),
(43, 'Nail Polish Remover by Acetone', 'NAILS', 15000.00, 100, 'Fast acting nail polish remover.', './assets/Nail_Polish_Remover_Acetone.avif'),
(44, 'Sheet Mask by Mediheal', 'FACE', 25000.00, 80, 'Hydrating tea tree essence mask.', './assets/SheetMask_Mediheal.webp'),
(45, 'Lip Tint by Somethinc', 'LIPS', 60000.00, 130, 'Ombrell lip totem tint.', './assets/LipTint_Somethinc.jpg'),
(46, 'Hair Mousse by Makarizo', 'HAIR', 45000.00, 60, 'Styling mousse for curly hair.', './assets/Hair_Mousse_Makarizo.webp'),
(47, 'Nail File Set by Sephora', 'NAILS', 75000.00, 40, 'Professional nail file block.', './assets/nail_fileset_sephora.jpg'),
(48, 'Eyeliner by Wardah', 'FACE', 48000.00, 95, 'Optimum hi-black liquid eyeliner.', './assets/eyeliner_wardah.jpg'),
(49, 'Mascara by Maybelline', 'FACE', 85000.00, 110, 'Lash sensational sky high mascara.', './assets/mascara_maybeline.webp'),
(50, 'Eyebrow Pencil by Viva', 'FACE', 38000.00, 200, 'Classic waterproof eyebrow pencil.', './assets/eyebrow_viva.png');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` varchar(50) NOT NULL,
  `user_id` int(11) NOT NULL,
  `address` text NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('Pending','On Packing','On Delivery','Completed','Canceled') NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transaction_details`
--

CREATE TABLE `transaction_details` (
  `id` int(11) NOT NULL,
  `transaction_id` varchar(50) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`) VALUES
(1, 'Chris Aristo', 'offelmarbun@gmail.com', '$2y$10$VMSpDsuhjPdfxPEYKF9F9u0FfBLtAn.BOQrzyMsMO35N6U.ClX3lq', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_product` (`user_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `transaction_details`
--
ALTER TABLE `transaction_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaction_id` (`transaction_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `transaction_details`
--
ALTER TABLE `transaction_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transaction_details`
--
ALTER TABLE `transaction_details`
  ADD CONSTRAINT `transaction_details_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `transaction_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
