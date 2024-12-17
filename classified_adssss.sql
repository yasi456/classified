-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 08, 2024 at 06:05 PM
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
-- Database: `classified_adssss`
--

-- --------------------------------------------------------

--
-- Table structure for table `ads`
--

CREATE TABLE `ads` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `subcategory_id` int(11) DEFAULT NULL,
  `location` varchar(100) NOT NULL,
  `condition` enum('new','used') NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `phone1` varchar(20) NOT NULL,
  `phone2` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `ads`
--

INSERT INTO `ads` (`id`, `user_id`, `title`, `description`, `category_id`, `subcategory_id`, `location`, `condition`, `price`, `phone1`, `phone2`, `created_at`, `updated_at`) VALUES
(1, 1, 'rerrr', 'rrr', 7, 36, 'Puttalam', 'new', 0.07, '33333', '433', '2024-09-04 19:22:33', '2024-09-04 19:22:33'),
(3, 15, 'jj', 'ss', 1, 5, 'Los Angeles', 'used', 0.03, '33333', '433', '2024-09-05 07:33:36', '2024-09-05 07:33:36'),
(4, 15, 'ee', 'ee', 11, 52, 'Los Angeles', 'used', 0.02, '44', '433', '2024-09-05 07:34:14', '2024-09-05 07:34:14'),
(6, 11, 'ss', 'ss', 7, 36, 'New York', 'new', 0.04, '33333', '433', '2024-09-05 08:27:49', '2024-09-05 08:27:49'),
(7, 11, 'eee', 'frr', 4, 23, '0', '', 0.00, '33333', '433', '2024-09-05 15:09:41', '2024-09-07 18:09:43'),
(8, 11, 'edde', 'ededed', 7, 36, '0', '', 0.00, '33333', '433', '2024-09-05 15:15:43', '2024-09-07 17:40:08'),
(10, 11, 'ee', 'eee', 4, 23, 'Los Angeles', 'new', 0.03, 'ee', 'ee', '2024-09-07 17:48:23', '2024-09-07 17:48:23'),
(11, 11, 'ewe', 'ddd', 11, 47, 'Los Angeles', 'used', 0.02, '33333', '433', '2024-09-08 09:24:03', '2024-09-08 09:24:03'),
(12, 11, '44', '44', 7, 36, 'Los Angeles', 'new', 44.00, '44', '44', '2024-09-08 09:24:28', '2024-09-08 09:24:28'),
(13, 11, 'kk', 'gygyg', 7, 36, 'Houston', 'new', 2.00, '33333', '433', '2024-09-08 09:47:53', '2024-09-08 09:47:53'),
(14, 11, 'cdc', 'cddc', 12, 56, 'New York', 'new', 0.02, '33333', '433', '2024-09-08 09:50:03', '2024-09-08 09:50:03'),
(15, 11, 'ece', '333', 7, 36, 'New York', 'new', 0.02, '[33', '333', '2024-09-08 09:57:36', '2024-09-08 09:57:36'),
(16, 11, 'xe', 'ex', 7, 36, 'New York', 'new', 0.03, 'e', 'exe', '2024-09-08 10:00:49', '2024-09-08 10:00:49'),
(17, 11, 'wsw', 'swws', 7, 37, 'New York', 'new', 0.03, '33333', '433', '2024-09-08 10:02:33', '2024-09-08 10:02:33'),
(18, 11, 'ded', 'dee', 7, 37, 'New York', 'new', 0.02, '33333', '433', '2024-09-08 11:00:08', '2024-09-08 11:00:08'),
(19, 11, 'sws', 'wswsw', 12, 56, 'New York', 'new', 0.03, '33333', '433', '2024-09-08 14:40:08', '2024-09-08 14:40:08'),
(20, 11, 'sde', 'exe', 7, 36, 'Los Angeles', 'new', 0.03, '33333', '433', '2024-09-08 14:48:56', '2024-09-08 14:48:56');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'FURNITURE'),
(2, 'JOBS'),
(3, 'MOBILES'),
(4, 'SERVICES'),
(5, 'VEHICLE'),
(6, 'REAL ESTATE'),
(7, 'BIKES'),
(8, 'PETS'),
(9, 'KIDS'),
(10, 'FASHION'),
(11, 'ELECTRONICS AND APPLIANCES'),
(12, 'BOOKS SPORTS AND HOBBIES');

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ad_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `favorites`
--

INSERT INTO `favorites` (`id`, `user_id`, `ad_id`, `created_at`) VALUES
(5, 11, 8, '2024-09-06 08:29:57'),
(7, 11, 7, '2024-09-07 07:06:02'),
(10, 11, 1, '2024-09-07 08:24:53'),
(12, 11, 15, '2024-09-08 10:02:06'),
(14, 11, 18, '2024-09-08 16:02:08');

-- --------------------------------------------------------

--
-- Table structure for table `photos`
--

CREATE TABLE `photos` (
  `id` int(11) NOT NULL,
  `ad_id` int(11) DEFAULT NULL,
  `file_path` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `photos`
--

INSERT INTO `photos` (`id`, `ad_id`, `file_path`) VALUES
(1, 1, 'uploads/ad_1_photo1.jpg'),
(2, 1, 'uploads/ad_1_photo2.jpg'),
(3, 1, 'uploads/ad_1_photo3.jpg'),
(4, 1, 'uploads/ad_1_photo4.jpg'),
(5, 1, 'uploads/ad_1_photo5.png'),
(11, 3, 'uploads/ad_3_photo1.jpeg'),
(12, 3, 'uploads/ad_3_photo2.jpg'),
(13, 3, 'uploads/ad_3_photo3.png'),
(14, 3, 'uploads/ad_3_photo4.jpeg'),
(15, 3, 'uploads/ad_3_photo5.jpeg'),
(16, 4, 'uploads/ad_4_photo1.jpeg'),
(17, 4, 'uploads/ad_4_photo2.png'),
(18, 4, 'uploads/ad_4_photo3.jpeg'),
(19, 4, 'uploads/ad_4_photo4.jpeg'),
(20, 4, 'uploads/ad_4_photo5.png'),
(25, 6, 'uploads/ad_6_photo1.png'),
(26, 6, 'uploads/ad_6_photo2.png'),
(27, 6, 'uploads/ad_6_photo3.png'),
(28, 6, 'uploads/ad_6_photo4.png'),
(29, 6, 'uploads/ad_6_photo5.png'),
(30, 7, 'uploads/66d9c9b5cb3e2_klipartz.com.png'),
(31, 7, 'uploads/66d9c9b61b9e5_438119303_1752706621884598_2640737104970855809_n.jpg'),
(32, 7, 'uploads/66d9c9b623c9d_colorful_wave_4k_5k_hd_abstract.jpg'),
(33, 7, 'uploads/66d9c9b63192c_istockphoto-1215741500-170667a.jpg'),
(34, 8, 'uploads/66d9cb1fa0e07_images (1).jpeg'),
(36, 7, 'uploads/66db24c218d61_images (1).jpeg'),
(37, 7, 'uploads/66dc7c5ac56d9_images (1).jpeg'),
(38, 10, 'uploads/66dc91e79b31b_istockphoto-1002977928-612x612.jpg'),
(39, 12, 'uploads/66dd6d4cdcc29_images (1).jpeg'),
(40, 13, 'uploads/66dd72c9c17bb_istockphoto-1002977928-612x612.jpg'),
(41, 14, 'uploads/66dd734b7a173_istockphoto-1002977928-612x612.jpg'),
(42, 15, 'uploads/66dd751028737_istockphoto-1002977928-612x612.jpg'),
(43, 16, 'uploads/66dd75d1d09a9_images (1).jpeg'),
(44, 17, 'uploads/66dd76392b289_images (1).jpeg'),
(45, 18, 'uploads/66dd83b8f34bd_images (1).jpeg'),
(46, 19, 'uploads/66ddb7487f64d_images (1).jpeg'),
(47, 20, 'uploads/66ddb95861834_images (1).jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `subcategories`
--

CREATE TABLE `subcategories` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `subcategories`
--

INSERT INTO `subcategories` (`id`, `category_id`, `name`) VALUES
(1, 1, 'Other Household Items'),
(2, 1, 'Beds And Wardrobes'),
(3, 1, 'Home Decor And Garden'),
(4, 1, 'Kitchen And Other Appliances'),
(5, 1, 'Fridge - AC - Washing Machine'),
(6, 1, 'Other Services'),
(7, 2, 'Other Jobs'),
(8, 2, 'Customer Service'),
(9, 2, 'IT'),
(10, 2, 'Marketing'),
(11, 2, 'Sales'),
(12, 2, 'Manufacturing'),
(13, 2, 'Clerical And Administration'),
(14, 2, 'Hotels And Tourism'),
(15, 2, 'Accounting And Finance'),
(16, 2, 'Advertising And PR'),
(17, 2, 'Human Resources'),
(18, 2, 'Education'),
(19, 3, 'Mobile Phones'),
(20, 3, 'Accessories'),
(21, 3, 'Tablets'),
(22, 4, 'Education and Classes'),
(23, 4, 'Drivers And Taxi'),
(24, 4, 'Web Development'),
(25, 4, 'Electronics And Computer Repair'),
(26, 5, 'Cars'),
(27, 5, 'Spare Parts'),
(28, 5, 'Commercial Vehicles'),
(29, 5, 'Other Vehicles'),
(30, 6, 'Land And Plots'),
(31, 6, 'Apartments'),
(32, 6, 'House'),
(33, 6, 'Shops - Offices - Commercial Space'),
(34, 6, 'PG And Roommates'),
(35, 6, 'Vacation Rentals - Guest Houses'),
(36, 7, 'Motorcycles'),
(37, 7, 'Spare Parts'),
(38, 8, 'Dogs'),
(39, 8, 'Other Pets'),
(40, 8, 'Aquariums'),
(41, 9, 'Furniture And Toys'),
(42, 9, 'Accessories'),
(43, 9, 'Prams And Walkers'),
(44, 10, 'Accessories'),
(45, 10, 'Clothes'),
(46, 10, 'Footwear'),
(47, 11, 'Computers And Accessories'),
(48, 11, 'Kitchen And Other Appliances'),
(49, 11, 'TV - Video - Audio'),
(50, 11, 'Cameras & Accessories'),
(51, 11, 'Games And Entertainment'),
(52, 11, 'Fridge - AC - Washing Machine'),
(53, 12, 'Gym And Fitness'),
(54, 12, 'Other Hobbies'),
(55, 12, 'Musical Instruments'),
(56, 12, 'Books And Magazines'),
(57, 12, 'Sports Equipment');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'banuka', '', 'kgkulatunga61@gmail.com', '$2y$10$AH7d9qx7/L0ceMxuISsQoupnwU.dCo7YbQ2rcaaoghecwk2JDV9yS', '2024-09-04 16:24:15'),
(8, 'reff', NULL, 'ref444r@gmail.com', '$2y$10$UZE6c9xhVf.LWAdtJBuSBuzkzc99ZgjW6cNY8TFMOR27uCIaio.nG', '2024-09-04 16:33:12'),
(10, 'Qwe', NULL, 'ref444rrr@gmail.com', '$2y$10$upxIBSwHHnjDcehKvddtHOVXqLxNgsYGDq4ocui3zegEo3io3NmLi', '2024-09-04 16:33:48'),
(11, 'reffd', NULL, 'yasikulatunga@outlook.com', '$2y$10$mNlzcMe4UCZw1TlqgeGr.enC/kRSirUtV/UIavd7gT.yEu1wA/Vvi', '2024-09-04 16:40:46'),
(12, 'reff', NULL, 'kkgkulatunga61@gmail.com', '$2y$10$FTeM52EpE4WbyvAnnGzM/.RyPoq3yyhXuhL8Xxw5h85b1WahHazFa', '2024-09-04 17:10:46'),
(13, 'efe', NULL, 'rr@d.com', '$2y$10$pzppfgCFAQJ6LG6f0uZ7LO9y8mVikXcSazfOcfce3LFdaWPpBDTAS', '2024-09-04 18:48:17'),
(14, 'banuka', NULL, 'rrr@123', '$2y$10$7rQoTo7IRS4uZQVZ/HfLaeWWfHRzVUW0Dgja7KFJqoRGd57geT9WG', '2024-09-05 05:49:44'),
(15, 'efe', NULL, 'ddddddwddd@ee', '$2y$10$45cPKbon9HcyRV2GQuC19.zaAby0T/r5IbmLD20gsheMVo4/94jtS', '2024-09-05 06:32:22');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ads`
--
ALTER TABLE `ads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `subcategory_id` (`subcategory_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`ad_id`),
  ADD KEY `ad_id` (`ad_id`);

--
-- Indexes for table `photos`
--
ALTER TABLE `photos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ad_id` (`ad_id`);

--
-- Indexes for table `subcategories`
--
ALTER TABLE `subcategories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

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
-- AUTO_INCREMENT for table `ads`
--
ALTER TABLE `ads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `photos`
--
ALTER TABLE `photos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `subcategories`
--
ALTER TABLE `subcategories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ads`
--
ALTER TABLE `ads`
  ADD CONSTRAINT `ads_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ads_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `ads_ibfk_3` FOREIGN KEY (`subcategory_id`) REFERENCES `subcategories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`ad_id`) REFERENCES `ads` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `photos`
--
ALTER TABLE `photos`
  ADD CONSTRAINT `photos_ibfk_1` FOREIGN KEY (`ad_id`) REFERENCES `ads` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subcategories`
--
ALTER TABLE `subcategories`
  ADD CONSTRAINT `subcategories_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
