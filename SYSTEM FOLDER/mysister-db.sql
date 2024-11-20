-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 20, 2024 at 11:01 AM
-- Server version: 8.2.0
-- PHP Version: 8.3.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mysister`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `cnum` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `pass` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `fname`, `lname`, `cnum`, `email`, `pass`) VALUES
(1, 'Nurul', 'Izzah', '01111397703', 'izah0130@gmail.com', '$2y$10$pQf2rjoIWXdNcROBbHfF3.rHafrtEj.ObC/7pMXKOd.2DFM60kKyK');

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `booking_id` int UNSIGNED NOT NULL,
  `cust_id` int UNSIGNED NOT NULL,
  `service_id` int UNSIGNED NOT NULL,
  `maid_id` int UNSIGNED DEFAULT NULL,
  `booking_date` date NOT NULL,
  `booking_slot` enum('8:00 a.m','10:00 a.m','12:00 p.m','2:00 p.m') NOT NULL,
  `booking_status` enum('completed','ongoing') DEFAULT 'ongoing',
  `total_price` decimal(10,2) NOT NULL,
  `payment_status` enum('pending','completed','failed','canceled') NOT NULL DEFAULT 'pending',
  `payment_method` enum('paypal','credit_card','debit_card') NOT NULL,
  `payment_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `amount` decimal(10,2) DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`booking_id`, `cust_id`, `service_id`, `maid_id`, `booking_date`, `booking_slot`, `booking_status`, `total_price`, `payment_status`, `payment_method`, `payment_date`, `amount`) VALUES
(1, 44, 4, 6, '2024-11-21', '12:00 p.m', 'completed', 40.00, 'completed', 'paypal', '2024-11-19 07:53:35', 40.00),
(2, 50, 2, 6, '2024-11-20', '12:00 p.m', 'completed', 60.00, 'completed', 'paypal', '2024-11-19 08:58:05', 60.00),
(3, 53, 3, 6, '2024-11-21', '8:00 a.m', 'ongoing', 80.00, 'completed', 'paypal', '2024-11-19 13:15:43', 80.00);

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `id` int UNSIGNED NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `cnum` varchar(15) NOT NULL,
  `address` text NOT NULL,
  `email` varchar(100) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `verified` tinyint(1) DEFAULT '0',
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`id`, `fname`, `lname`, `cnum`, `address`, `email`, `pass`, `verified`, `status`) VALUES
(28, 'Nurul', 'Izzah', '0199902518', 'Kolej Siswa Jaya', 'neyligo65@gmail.com', '$2y$10$xraVreOaWEWsaQb/HGXpfer8C3aAxeLBzFXupOKhqhxm0rPoBmR1e', 1, 'active'),
(44, 'Ahmad', 'Thaqif', '0196945846', 'Bukit Perdana', 'guwuhzzz@gmail.com', '$2y$10$/IsNENqxEZ5IH38gMwMd7eSQ34GJ4.eUA0YegbwEPJ9KIWrk5vXD.', 1, 'active'),
(49, 'Uzairi', 'Taib', '0178855514', 'Sri Rampai', 'benosow417@bulatox.com', '$2y$10$JSrcLgXUPCleCpQFtVjNFeENQivD9OMrIfxHqJO7LeBkATPWOW9Rm', 1, 'active'),
(50, 'Nur', 'Liyana', '01160578733', 'Jalan Rejang 4, Taman Setapak Jaya, 54100 Kuala Lumpur, Wilayah Persekutuan Kuala Lumpur', 'sefewo1501@lineacr.com', '$2y$10$.NXWX7ikQnw4R7eLDf3wp.bBOP/lSA.ZryuaEJzyquGzQdUlKzS3a', 1, 'active'),
(53, 'Zafira', 'Qistina', '0196595337', 'Jalan Bukit Utama', 'zafiraqistinaa@gmail.com', '$2y$10$z9FXDowmtDRA2rK3pmVV1ePJC6wjJPOrZkJubUgl9.mVofmqQAdBC', 1, 'active'),
(54, 'Amyra', 'Khadijah', '0187924599', 'No 3 Jalan Kosas 3/10, Taman Kosas', 'xojote7581@merotx.com', '$2y$10$tY6JIgSr5gqgGvQxjHRNDeOKRr1CYPQURK7aYrxwScDnA6xkeNBba', 1, 'active');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int NOT NULL,
  `customer_email` varchar(100) NOT NULL,
  `service_name` varchar(255) NOT NULL,
  `comments` text NOT NULL,
  `date_submitted` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `customer_email`, `service_name`, `comments`, `date_submitted`) VALUES
(1, 'guwuhzzz@gmail.com', 'House Cleaning', 'The service was excellent!', '2024-11-14 10:23:45'),
(2, 'benosow417@bulatox.com', 'Ironing & Folding Cloth', 'Lipatan yang sangat kemas', '2024-11-14 10:30:13'),
(3, 'kiviba1920@hraifi.com', 'Move In/Out Cleaning', 'Bungkusan barangan sebelum diangkat untuk dipindahkan sangatlaa kemas dan teliti', '2024-11-14 10:34:15'),
(4, 'sefewo1501@lineacr.com', 'Office Cleaning', 'Dah tak nampak debu langsung. Respect !', '2024-11-14 10:35:06'),
(5, 'sefewo1501@lineacr.com', 'Ironing & Folding Cloth', 'Pergh lipatan yang sangat kemas sampai rasa sayang nak hancurkan lipatan tu', '2024-11-14 10:41:20'),
(6, 'sefewo1501@lineacr.com', 'House Cleaning', 'Bersih sangat', '2024-11-14 10:48:14'),
(7, 'sefewo1501@lineacr.com', 'Window Cleaning', 'Cermin berkilat sampai bersinar mata.', '2024-11-18 08:20:27'),
(8, 'sefewo1501@lineacr.com', 'Move In/Out Cleaning', 'Terbaik. Gempak gila proses pemindahan.', '2024-11-18 19:02:23'),
(9, 'guwuhzzz@gmail.com', 'Ironing & Folding Cloth', 'Kemas', '2024-11-19 07:58:04'),
(10, 'sefewo1501@lineacr.com', 'Office Cleaning', 'Office terus bersih ', '2024-11-19 09:03:16'),
(11, 'zafiraqistinaa@gmail.com', 'Restroom Cleaning', 'My restroom smell so nice and the workers are so friendly! ', '2024-11-19 13:28:02'),
(12, 'zafiraqistinaa@gmail.com', 'Spring Cleaning', '100/100!!', '2024-11-19 13:30:17');

-- --------------------------------------------------------

--
-- Table structure for table `maid`
--

CREATE TABLE `maid` (
  `id` int UNSIGNED NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `cnum` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `start_date` date DEFAULT NULL,
  `salary` decimal(10,2) NOT NULL,
  `profile_picture` blob
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `maid`
--

INSERT INTO `maid` (`id`, `fname`, `lname`, `cnum`, `email`, `pass`, `start_date`, `salary`, `profile_picture`) VALUES
(2, 'Lina', 'Nizam', '0123458866', 'waredi5391@craftapk.com', '$2y$10$/zTaXqr5MO6XPgHnV/z6vOld75QnIN4MbPOp0QqM2/yZ7bx/0mHI6', '2024-08-25', 2500.00, NULL),
(3, 'Fatin', 'Allysha', '01139940520', 'fatinallyshar@gmail.com', '$2y$10$HAEdHq5s3I8JNUR40qneCe.fW.ML1DB2J6dzuaxxmoOuyK86iUV5O', '2024-10-10', 10000.00, NULL),
(4, 'Nurr', 'Zulaikha', '01133212573', 'zulaikhakhairee@gmail.com', '$2y$10$8Oob0/boCNZ9ogxrMMfBU.UIpTYufnlmR.p0bNIwFLAcm/5/u5ft6', '2024-06-16', 2500.00, NULL),
(6, 'Nurul', 'Afiqah Sherin', '0193905337', 'afiqahsherin04@gmail.com', '$2y$10$P9Zir8UILy2MEaw3j.owkOQNXWWjkPeXSTOV/jJtuEccD7AhFgl96', '2024-11-13', 100.00, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `otp_codes`
--

CREATE TABLE `otp_codes` (
  `id` int NOT NULL,
  `email` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `otp_codes`
--

INSERT INTO `otp_codes` (`id`, `email`, `code`, `created_at`) VALUES
(27, 'fatin@gmail.com', '3452', '2024-09-11 07:45:46'),
(29, 'fatinallyshar@gmail', '6600', '2024-09-11 08:30:12'),
(34, 'fovidi9917@exweme.com', '3106', '2024-09-24 03:38:11'),
(35, 'fovidi9917@exweme.com', '1363', '2024-09-24 03:45:30'),
(38, 'zulaikhakhairee@gmail.com', '6486', '2024-10-07 15:06:09'),
(44, 'zafiraqistinaa@gmail.com', '6349', '2024-11-19 10:27:24');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `email`, `token`, `expires_at`, `created_at`) VALUES
(5, 'guwuhzzz@gmail.com', 'ded01cc5d4e4f9c18022d13d7a146252', '2024-09-30 10:41:00', '2024-09-30 08:41:00'),
(8, 'guwuhzzz@gmail.com', 'e6bd577bf3b0db5420fddcb38dbbd321', '2024-09-30 18:44:40', '2024-09-30 09:44:40'),
(9, 'guwuhzzz@gmail.com', 'adeefacdc5fe33d00a9bdf4116e2cbb8', '2024-09-30 18:48:20', '2024-09-30 09:48:20'),
(10, 'guwuhzzz@gmail.com', 'c37772a48c027d03a954495c3a3f45ff', '2024-09-30 18:53:10', '2024-09-30 09:53:10'),
(11, 'guwuhzzz@gmail.com', '1b48a87338ad08c3543b538126234279', '2024-09-30 18:56:42', '2024-09-30 09:56:42'),
(12, 'guwuhzzz@gmail.com', '8db3c888fabedc073a80b614f16359fb', '2024-09-30 18:58:28', '2024-09-30 09:58:28'),
(18, 'guwuhzzz@gmail.com', '08f1169f0c9a073fe97598df7d236e48', '2024-10-10 14:50:35', '2024-10-10 05:50:35'),
(20, 'guwuhzzz@gmail.com', '75bf23c3e77023cf422f8c695e5892dc', '2024-10-20 03:39:13', '2024-10-19 18:39:13'),
(21, 'izah0130@gmail.com', '3a86af8668efa3c5e1d60172a750a678', '2024-10-29 00:09:43', '2024-10-28 15:09:43'),
(23, 'zafiraqistinaa@gmail.com', '7ffb4ebf45a5b3169effc7343bd4b5ac', '2024-11-19 20:29:34', '2024-11-19 11:29:34'),
(25, 'afiqahsherin04@gmail.com', 'c2239a1db477e9f1bf5676cd8ff54157', '2024-11-20 00:16:18', '2024-11-19 15:16:18'),
(26, 'xojote7581@merotx.com', 'c4d036c313d4c5e077b61759f8028e14', '2024-11-20 03:14:08', '2024-11-19 18:14:08');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'false = visible, true = hidden',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `description`, `price`, `image`, `status`, `created_at`, `updated_at`) VALUES
(1, 'House Cleaning', 'Perfect for light cleaning and quick touch-ups for your house. Get your sparkle on!', 60.00, '../uploads/1731421390_s1.jpg', 1, '2024-11-11 07:48:01', '2024-11-19 20:12:42'),
(2, 'Office Cleaning', 'A spotless and organized workspace, enhancing productivity and making a lasting impression.', 60.00, '../uploads/1731421409_s2.jpg', 0, '2024-11-11 07:48:01', '2024-11-12 14:23:29'),
(3, 'Deep Cleaning', 'A thorough cleaning that leaves no corner untouched. Feel the difference!', 80.00, '../uploads/1731421434_s4.png', 0, '2024-11-11 07:48:01', '2024-11-12 14:23:54'),
(4, 'Ironing & Folding Cloth', 'Enjoy crisp, wrinkle-free clothes, neatly folded laundry, and dishwashing services—making your life easier, one task at a time!', 40.00, '../uploads/1731421453_s3.png', 0, '2024-11-11 07:48:01', '2024-11-12 14:24:13'),
(5, 'Move In/Out Cleaning', 'Ensure a smooth transition with our service, leaving your old home spotless and your new one ready to welcome you.', 100.00, '../uploads/1731421472_s5.png', 0, '2024-11-11 07:48:01', '2024-11-12 14:24:32'),
(6, 'Spring Cleaning', 'Our Spring Cleaning service tackles every chore, leaving your entire home refreshed and spotless inside and out.', 120.00, '../uploads/1731421498_s6.png', 0, '2024-11-11 07:48:01', '2024-11-12 14:24:58'),
(18, 'Window Cleaning', 'Bring the sunshine back into your home! Our professional window cleaning service removes streaks and smudges, ensuring crystal-clear views inside and out.', 150.00, '../uploads/1731663751_window.png', 0, '2024-11-15 09:42:31', '2024-11-15 09:42:31'),
(19, 'Restroom Cleaning', 'Maintain a hygienic and pleasant restroom experience for everyone! Our meticulous restroom cleaning service ensures sparkling surfaces, fresh scents, and fully stocked essentials.', 100.00, '../uploads/1731663886_toilet.png', 0, '2024-11-15 09:44:46', '2024-11-15 09:44:46');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `email` varchar(100) NOT NULL,
  `pass` varchar(255) NOT NULL,
  `user_type` enum('admin','maid','customer') NOT NULL,
  `last_login` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `pass`, `user_type`, `last_login`) VALUES
(1, 'benosow417@bulatox.com', '$2y$10$JSrcLgXUPCleCpQFtVjNFeENQivD9OMrIfxHqJO7LeBkATPWOW9Rm', 'customer', '2024-10-27 03:02:51'),
(2, 'fatinallyshar@gmail.com', '$2y$10$HAEdHq5s3I8JNUR40qneCe.fW.ML1DB2J6dzuaxxmoOuyK86iUV5O', 'maid', '2024-10-09 22:43:48'),
(3, 'guwuhzzz@gmail.com', '$2y$10$goXWfssF.lrfNSMVH79onu3evIYK3Qe1WK30/vWa/HAMEWJDvym2G', 'customer', '2024-11-19 15:50:55'),
(4, 'izah0130@gmail.com', '$2y$10$pQf2rjoIWXdNcROBbHfF3.rHafrtEj.ObC/7pMXKOd.2DFM60kKyK', 'admin', '2024-11-20 13:16:30'),
(5, 'kiviba1920@hraifi.com', '$2y$10$rEHu0xO6y587IE5mF5sjw./fAzF7nmkWuLRyRfa0LHxfpvpJ10co6', 'customer', '2024-10-10 10:05:12'),
(6, 'neyligo65@gmail.com', '$2y$10$xraVreOaWEWsaQb/HGXpfer8C3aAxeLBzFXupOKhqhxm0rPoBmR1e', 'customer', '2024-10-09 22:12:03'),
(7, 'waredi5391@craftapk.com', '$2y$10$kkUR2II7j7NOQLSfSvA7reRzuBvGoNC7QhVh2AVmRNNVY3U4yVmCi', 'maid', '2024-10-10 13:52:02'),
(8, 'zulaikhakhairee@gmail.com', '$2y$10$8Oob0/boCNZ9ogxrMMfBU.UIpTYufnlmR.p0bNIwFLAcm/5/u5ft6', 'maid', '2024-11-13 13:52:42'),
(9, 'afiqahsherin04@gmail.com', '$2y$10$P9Zir8UILy2MEaw3j.owkOQNXWWjkPeXSTOV/jJtuEccD7AhFgl96', 'maid', '2024-11-20 11:49:50'),
(10, 'sefewo1501@lineacr.com', '$2y$10$.NXWX7ikQnw4R7eLDf3wp.bBOP/lSA.ZryuaEJzyquGzQdUlKzS3a', 'customer', '2024-11-20 01:32:00'),
(12, 'zafiraqistinaa@gmail.com', '$2y$10$z9FXDowmtDRA2rK3pmVV1ePJC6wjJPOrZkJubUgl9.mVofmqQAdBC', 'customer', '2024-11-19 19:48:35'),
(13, 'xojote7581@merotx.com', '$2y$10$tY6JIgSr5gqgGvQxjHRNDeOKRr1CYPQURK7aYrxwScDnA6xkeNBba', 'customer', '2024-11-20 15:06:15');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `fk_booking_customer` (`cust_id`),
  ADD KEY `fk_booking_service` (`service_id`),
  ADD KEY `fk_booking_maid` (`maid_id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `feedback_ibfk_1_new` (`customer_email`),
  ADD KEY `feedback_ibfk_2_new` (`service_name`);

--
-- Indexes for table `maid`
--
ALTER TABLE `maid`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `otp_codes`
--
ALTER TABLE `otp_codes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `booking`
--
ALTER TABLE `booking`
  MODIFY `booking_id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `maid`
--
ALTER TABLE `maid`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `otp_codes`
--
ALTER TABLE `otp_codes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`email`) REFERENCES `users` (`email`);

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`cust_id`) REFERENCES `customer` (`id`),
  ADD CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`),
  ADD CONSTRAINT `fk_booking_maid` FOREIGN KEY (`maid_id`) REFERENCES `maid` (`id`);

--
-- Constraints for table `customer`
--
ALTER TABLE `customer`
  ADD CONSTRAINT `customer_ibfk_1` FOREIGN KEY (`email`) REFERENCES `users` (`email`);

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1_new` FOREIGN KEY (`customer_email`) REFERENCES `users` (`email`),
  ADD CONSTRAINT `feedback_ibfk_2_new` FOREIGN KEY (`service_name`) REFERENCES `services` (`name`);

--
-- Constraints for table `maid`
--
ALTER TABLE `maid`
  ADD CONSTRAINT `maid_ibfk_1` FOREIGN KEY (`email`) REFERENCES `users` (`email`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
