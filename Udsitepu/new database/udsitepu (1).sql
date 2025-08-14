-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 14, 2025 at 01:45 PM
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
-- Database: `udsitepu`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(30) DEFAULT NULL,
  `password_hash` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password_hash`) VALUES
(1, 'admin', '$2y$10$JHGwGvJRSsSL9E2nA/OPquD7TUVBWz3YZnTAjK8pTeJTZTfVcm9gK'),
(3, 'sitepu', '$2y$10$KN4NeC.T9YIqLz3RzfpaCO/Y4FzgSShhJBs1nzq5AAnv8MF1ohB9S');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_name` varchar(100) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total` int(11) DEFAULT NULL,
  `status` enum('menunggu','dibayar','dikemas','dikirim','selesai') DEFAULT 'menunggu',
  `delivery_method` varchar(20) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `order_date` datetime DEFAULT current_timestamp(),
  `address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `customer_name`, `user_id`, `total`, `status`, `delivery_method`, `created_at`, `order_date`, `address`) VALUES
(1, 'risa', NULL, 35000, 'selesai', NULL, '2025-07-17 03:34:07', '2025-08-01 03:42:25', NULL),
(2, 'yola', NULL, 100000, 'selesai', NULL, '2025-07-17 13:46:11', '2025-08-01 03:42:25', NULL),
(3, 'risa', NULL, 20000, 'selesai', NULL, '2025-07-17 15:35:21', '2025-08-01 03:42:25', NULL),
(4, 'risa', NULL, 35000, 'selesai', NULL, '2025-07-26 00:09:08', '2025-08-01 03:42:25', NULL),
(5, 'risa', NULL, 20000, 'selesai', NULL, '2025-07-26 01:21:10', '2025-08-01 03:42:25', NULL),
(6, 'risa perbina', NULL, 20000, 'selesai', NULL, '2025-07-26 01:27:29', '2025-08-01 03:42:25', NULL),
(7, 'risa perbina', NULL, 15000, 'selesai', NULL, '2025-07-26 01:31:01', '2025-08-01 03:42:25', NULL),
(8, 'yola', NULL, 35000, 'selesai', NULL, '2025-07-26 01:45:57', '2025-08-01 03:42:25', NULL),
(9, 'rehna', NULL, 20000, 'selesai', NULL, '2025-07-26 01:50:48', '2025-08-01 03:42:25', NULL),
(10, 'roni', 1, 20000, 'selesai', NULL, '2025-07-28 02:21:07', '2025-08-01 03:42:25', NULL),
(11, 'Risa Perbina', 1, 25000, 'selesai', 'Diantar', '2025-07-28 05:30:11', '2025-08-01 03:42:25', NULL),
(12, 'Risa Perbina', 1, 25000, 'menunggu', 'Diantar', '2025-07-28 05:34:47', '2025-08-01 03:42:25', NULL),
(13, 'Risa Perbina', 1, 20000, 'dibayar', 'Diantar', '2025-07-30 05:46:06', '2025-08-01 03:42:25', NULL),
(14, 'Risa Perbina', 1, 175000, 'dikirim', 'Diantar', '2025-07-30 12:01:18', '2025-08-01 03:42:25', NULL),
(15, 'asyh', 2, 35000, 'menunggu', 'Ambil Sendiri', '2025-08-05 02:46:19', '2025-08-05 02:46:19', NULL),
(16, 'asyh', 2, 35000, 'menunggu', 'Ambil Sendiri', '2025-08-05 02:47:18', '2025-08-05 02:47:18', NULL),
(17, 'asyh', 2, 35000, 'menunggu', 'Ambil Sendiri', '2025-08-05 02:50:04', '2025-08-05 02:50:04', NULL),
(18, 'asyh', 2, 35000, 'menunggu', 'Ambil Sendiri', '2025-08-05 10:05:41', '2025-08-05 10:05:41', NULL),
(19, 'asyh', 2, 35000, 'menunggu', 'Diantar', '2025-08-05 10:22:41', '2025-08-05 10:22:41', NULL),
(20, 'asyh', 2, 35000, 'menunggu', 'Diantar', '2025-08-05 10:22:52', '2025-08-05 10:22:52', NULL),
(21, 'asyh', 2, 20000, 'menunggu', 'Diantar', '2025-08-05 10:23:35', '2025-08-05 10:23:35', NULL),
(22, 'asyh', 2, 20000, 'menunggu', 'Ambil Sendiri', '2025-08-05 15:05:51', '2025-08-05 15:05:51', NULL),
(23, 'asyh', 2, 20000, 'menunggu', 'Diantar', '2025-08-05 15:06:05', '2025-08-05 15:06:05', NULL),
(24, 'asyh', 2, 20000, 'menunggu', 'Diantar', '2025-08-05 15:08:13', '2025-08-05 15:08:13', NULL),
(25, 'asyh', 2, 20000, 'menunggu', 'Diantar', '2025-08-05 15:08:25', '2025-08-05 15:08:25', NULL),
(26, 'asyh', 2, 20000, 'menunggu', 'Ambil Sendiri', '2025-08-05 15:08:44', '2025-08-05 15:08:44', NULL),
(27, 'asyh', 2, 20000, 'menunggu', 'Ambil Sendiri', '2025-08-05 15:12:28', '2025-08-05 15:12:28', NULL),
(28, 'asyh', 2, 20000, 'menunggu', 'Diantar', '2025-08-05 15:12:53', '2025-08-05 15:12:53', NULL),
(29, 'asyh', 2, 20000, 'menunggu', 'Ambil Sendiri', '2025-08-05 15:16:55', '2025-08-05 15:16:55', NULL),
(30, 'asyh', 2, 20000, 'menunggu', 'Ambil Sendiri', '2025-08-05 15:20:55', '2025-08-05 15:20:55', NULL),
(31, 'asyh', 2, 20000, 'menunggu', 'Ambil Sendiri', '2025-08-05 15:21:23', '2025-08-05 15:21:23', NULL),
(32, 'asyh', 2, 20000, 'menunggu', 'Ambil Sendiri', '2025-08-05 15:25:20', '2025-08-05 15:25:20', NULL),
(33, 'asyh', 2, 20000, 'menunggu', 'Ambil Sendiri', '2025-08-05 15:30:14', '2025-08-05 15:30:14', NULL),
(34, 'asyh', 2, 20000, 'menunggu', 'Ambil Sendiri', '2025-08-05 15:31:16', '2025-08-05 15:31:16', NULL),
(35, 'asyhhh', 2, 0, 'menunggu', 'Ambil Sendiri', '2025-08-05 17:38:31', '2025-08-05 17:38:31', NULL),
(36, 'asyh', 2, 0, 'menunggu', 'Ambil Sendiri', '2025-08-05 17:49:32', '2025-08-05 17:49:32', NULL),
(37, 'bubul', 2, 0, 'menunggu', 'Ambil Sendiri', '2025-08-05 17:49:40', '2025-08-05 17:49:40', NULL),
(38, 'bubul', 2, 0, 'menunggu', 'Ambil Sendiri', '2025-08-05 17:50:11', '2025-08-05 17:50:11', NULL),
(39, 'bubul', 2, 0, 'menunggu', 'Ambil Sendiri', '2025-08-05 17:52:07', '2025-08-05 17:52:07', NULL),
(40, 'bubul', 2, 0, 'menunggu', 'Ambil Sendiri', '2025-08-05 17:52:15', '2025-08-05 17:52:15', NULL),
(41, 'bubul', 2, 0, '', 'Ambil Sendiri', '2025-08-05 17:53:34', '2025-08-05 17:53:34', NULL),
(42, 'pingpong', 2, 0, 'menunggu', 'Diantar', '2025-08-05 18:00:25', '2025-08-05 18:00:25', NULL),
(43, 'siti', 2, 105000, 'menunggu', 'Diantar', '2025-08-05 18:26:58', '2025-08-05 18:26:58', 'gvkd'),
(44, 'nana', 2, 55000, 'menunggu', 'Ambil Sendiri', '2025-08-05 19:56:56', '2025-08-05 19:56:56', 'tutuuu'),
(45, 'tutu', 2, 55000, 'menunggu', 'Ambil Sendiri', '2025-08-05 20:03:23', '2025-08-05 20:03:23', 'fdf'),
(46, 'asyh', 2, 75000, 'menunggu', 'Diantar', '2025-08-05 20:05:03', '2025-08-05 20:05:03', 'fvd'),
(47, 'asyh', 2, 135000, 'menunggu', 'Ambil Sendiri', '2025-08-05 20:58:19', '2025-08-05 20:58:19', 'jj'),
(48, 'bunga', 2, 80000, 'menunggu', 'Ambil Sendiri', '2025-08-05 21:01:19', '2025-08-05 21:01:19', 'jakarta\r\n\r\n'),
(49, 'asyh', 2, 55000, 'menunggu', 'Ambil Sendiri', '2025-08-05 21:02:20', '2025-08-05 21:02:20', 'tutu'),
(50, 'asyh', 2, 20000, 'menunggu', 'Ambil Sendiri', '2025-08-05 21:03:26', '2025-08-05 21:03:26', 'yyth'),
(51, 'asyh', 2, 530000, 'menunggu', 'Diantar', '2025-08-05 21:12:44', '2025-08-05 21:12:44', 'jjnj'),
(52, 'gita', 2, 1565000, 'menunggu', 'Ambil Sendiri', '2025-08-05 21:20:57', '2025-08-05 21:20:57', 'depok'),
(53, 'asyh', 2, 40000, 'menunggu', 'Ambil Sendiri', '2025-08-05 21:26:47', '2025-08-05 21:26:47', NULL),
(54, 'asyh', 2, 40000, 'menunggu', 'Ambil Sendiri', '2025-08-05 21:26:57', '2025-08-05 21:26:57', NULL),
(55, 'asyh', 2, 20000, 'menunggu', 'Ambil Sendiri', '2025-08-05 21:27:16', '2025-08-05 21:27:16', NULL),
(56, 'asyh', 2, 20000, 'menunggu', 'Ambil Sendiri', '2025-08-05 21:32:23', '2025-08-05 21:32:23', NULL),
(57, 'asyh', 2, 60000, 'menunggu', 'Diantar', '2025-08-05 21:47:29', '2025-08-05 21:47:29', NULL),
(58, 'bintng', 2, 110000, 'menunggu', 'Diantar', '2025-08-05 21:49:40', '2025-08-05 21:49:40', 'jkrt'),
(59, 'asyh', 2, 125000, 'menunggu', 'Diantar', '2025-08-05 21:59:35', '2025-08-05 21:59:35', 'hhjhjh'),
(60, 'asyh', 2, 125000, 'menunggu', 'Diantar', '2025-08-05 22:00:11', '2025-08-05 22:00:11', 'hhjhjh'),
(61, 'asyh', 2, 40000, 'menunggu', 'Diantar', '2025-08-05 22:02:02', '2025-08-05 22:02:02', 'hhh'),
(62, 'bina', 2, 35000, 'menunggu', 'Ambil Sendiri', '2025-08-05 22:20:49', '2025-08-05 22:20:49', 'bali'),
(63, 'siti', 2, 55000, 'menunggu', 'Ambil Sendiri', '2025-08-05 22:22:32', '2025-08-05 22:22:32', 'surabaya'),
(64, 'asyh', 2, 40000, 'menunggu', 'Diantar', '2025-08-05 22:53:32', '2025-08-05 22:53:32', 'jjkjkjk'),
(65, 'asyh', 2, 45000, 'menunggu', 'Diantar', '2025-08-05 23:08:30', '2025-08-05 23:08:30', 'bnbnb'),
(66, 'asyh', 2, 20000, 'menunggu', 'Ambil Sendiri', '2025-08-05 23:40:03', '2025-08-05 23:40:03', NULL),
(67, 'asyh', 2, 40000, 'menunggu', 'Ambil Sendiri', '2025-08-13 14:08:18', '2025-08-13 14:08:18', 'jnjnj'),
(68, 'asyh', 2, 20000, 'menunggu', 'Diantar', '2025-08-13 14:08:55', '2025-08-13 14:08:55', 'nknk'),
(69, 'asyh', 2, 20000, 'menunggu', 'Ambil Sendiri', '2025-08-14 17:38:36', '2025-08-14 17:38:36', NULL),
(70, 'asyh', 2, 20000, 'menunggu', 'Ambil Sendiri', '2025-08-14 17:38:45', '2025-08-14 17:38:45', NULL),
(71, 'asyh', 2, 35000, 'menunggu', 'Ambil Sendiri', '2025-08-14 17:38:59', '2025-08-14 17:38:59', NULL),
(72, 'asyh', 2, 35000, 'menunggu', 'Diantar', '2025-08-14 17:51:22', '2025-08-14 17:51:22', NULL),
(73, 'asyh', 2, 35000, 'menunggu', 'Ambil Sendiri', '2025-08-14 18:14:58', '2025-08-14 18:14:58', NULL),
(74, 'asyh', 2, 35000, 'menunggu', 'Ambil Sendiri', '2025-08-14 18:15:13', '2025-08-14 18:15:13', NULL),
(75, 'asyh', 2, 35000, 'menunggu', 'Ambil Sendiri', '2025-08-14 18:15:24', '2025-08-14 18:15:24', 'ugj'),
(76, 'asyh', 2, 20000, 'menunggu', 'Ambil Sendiri', '2025-08-14 18:19:08', '2025-08-14 18:19:08', NULL),
(77, 'asyh', 2, 35000, 'menunggu', 'Ambil Sendiri', '2025-08-14 18:32:46', '2025-08-14 18:32:46', NULL),
(78, 'asyh', 2, 35000, 'menunggu', 'Ambil Sendiri', '2025-08-14 18:33:04', '2025-08-14 18:33:04', NULL),
(79, 'asyh', 2, 35000, 'menunggu', 'Ambil Sendiri', '2025-08-14 18:40:51', '2025-08-14 18:40:51', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `orders_temp`
--

CREATE TABLE `orders_temp` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `status` enum('menunggu','batal','selesai') DEFAULT 'menunggu',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders_temp`
--

INSERT INTO `orders_temp` (`id`, `user_id`, `customer_name`, `total`, `status`, `created_at`) VALUES
(1, 2, 'asyh', 35000.00, 'batal', '2025-08-14 18:14:58'),
(2, 2, 'asyh', 20000.00, 'batal', '2025-08-14 18:24:55'),
(3, 2, 'asyh', 70000.00, 'batal', '2025-08-14 18:40:14');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `qty` int(11) DEFAULT NULL,
  `price` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `qty`, `price`) VALUES
(1, 1, 3, 1, NULL),
(2, 2, 8, 5, NULL),
(3, 3, 8, 1, NULL),
(4, 4, 3, 1, NULL),
(5, 5, 8, 1, NULL),
(6, 6, 10, 1, NULL),
(7, 7, 7, 1, NULL),
(8, 8, 3, 1, NULL),
(9, 9, 10, 1, NULL),
(10, NULL, 9, 1, NULL),
(11, NULL, 9, 1, NULL),
(12, 10, 12, 1, NULL),
(13, 11, 11, 1, NULL),
(14, 12, 11, 1, NULL),
(15, 13, 10, 1, NULL),
(16, 14, 3, 5, NULL),
(17, 15, 3, 1, NULL),
(18, 16, 3, 1, NULL),
(19, 17, 3, 1, NULL),
(20, 18, 3, 1, NULL),
(21, 19, 3, 1, NULL),
(22, 20, 3, 1, NULL),
(23, 21, 8, 1, NULL),
(24, 22, 8, 1, NULL),
(25, 23, 8, 1, NULL),
(26, 24, 8, 1, NULL),
(27, 25, 8, 1, NULL),
(28, 26, 8, 1, NULL),
(29, 27, 8, 1, NULL),
(30, 28, 8, 1, NULL),
(31, 29, 8, 1, NULL),
(32, 30, 8, 1, NULL),
(33, 31, 8, 1, NULL),
(34, 32, 8, 1, NULL),
(35, 33, 8, 1, NULL),
(36, 34, 8, 1, NULL),
(37, 35, 8, 3, NULL),
(38, 35, 12, 1, NULL),
(39, 36, 8, 1, NULL),
(40, 36, 9, 3, NULL),
(41, 42, 3, 1, NULL),
(42, 42, 12, 1, NULL),
(43, 43, 8, 1, NULL),
(44, 43, 3, 1, NULL),
(45, 43, 4, 1, NULL),
(46, 44, 3, 1, NULL),
(47, 44, 12, 1, NULL),
(48, 45, 8, 1, 20000),
(49, 45, 3, 1, 35000),
(50, 46, 8, 2, 20000),
(51, 46, 3, 1, 35000),
(52, 47, 8, 5, 20000),
(53, 47, 3, 1, 35000),
(54, 48, 8, 4, 20000),
(55, 49, 8, 1, 20000),
(56, 49, 3, 1, 35000),
(57, 50, 8, 1, 20000),
(58, 52, 8, 70, 1400000),
(59, 52, 12, 3, 60000),
(60, 52, 3, 3, 105000),
(61, 53, 8, 2, NULL),
(62, 54, 8, 2, NULL),
(63, 55, 8, 1, NULL),
(64, 56, 8, 1, NULL),
(65, 57, 8, 3, 60000),
(66, 58, 3, 2, 70000),
(67, 58, 12, 2, 40000),
(68, 60, 3, 3, 105000),
(69, 60, 12, 1, 20000),
(70, 61, 12, 2, 40000),
(71, 62, 3, 1, 35000),
(72, 63, 3, 1, 35000),
(73, 63, 12, 1, 20000),
(74, 64, 8, 1, 20000),
(75, 64, 12, 1, 20000),
(76, 65, 9, 1, 25000),
(77, 65, 5, 1, 20000),
(78, 66, 8, 1, 20000),
(79, 67, 12, 2, 40000),
(80, 68, 12, 1, 20000),
(81, 69, 12, 1, 20000),
(82, 70, 12, 1, 20000),
(83, 71, 3, 1, 35000),
(84, 72, 3, 1, 35000),
(85, 73, 3, 1, 35000),
(86, 74, 3, 1, 35000),
(87, 75, 3, 1, 35000),
(88, 76, 12, 1, 20000),
(89, 77, 3, 1, 35000),
(90, 78, 3, 1, 35000),
(91, 79, 3, 1, 35000);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `uploaded_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `order_id`, `file_path`, `uploaded_at`) VALUES
(1, 1752679315, 'uploads/1752679315_Screenshot 2025-07-11 021420.png', '2025-07-16 22:21:55'),
(2, 1752691094, '../uploads/bukti/1752691094_Screenshot 2025-07-11 015240.png', '2025-07-17 01:38:14'),
(3, 1752696953, '../uploads/bukti/1752696953_666.png', '2025-07-17 03:15:53'),
(4, 1752697228, '../uploads/bukti/1752697228_Screenshot 2025-07-01 190539.png', '2025-07-17 03:20:28'),
(5, 1, '../uploads/bukti/1752698047_Screenshot 2025-07-01 224310.png', '2025-07-17 03:34:07'),
(6, 2, '../uploads/bukti/1752734771_Screenshot 2025-07-03 011029.png', '2025-07-17 13:46:11'),
(7, 3, '../uploads/bukti/1752741321_Screenshot 2025-07-03 003305.png', '2025-07-17 15:35:21'),
(8, 4, '../uploads/bukti/1753463349_Screenshot 2025-07-01 224310.png', '2025-07-26 00:09:09'),
(9, 5, '../uploads/bukti/1753467670_Screenshot 2025-07-03 003305.png', '2025-07-26 01:21:10'),
(10, 6, '../uploads/bukti/1753468049_Screenshot 2025-07-03 222034.png', '2025-07-26 01:27:29'),
(11, 7, '../uploads/bukti/1753468261_Screenshot 2025-07-04 011023.png', '2025-07-26 01:31:01'),
(12, 8, '../uploads/bukti/1753469157_Screenshot 2025-07-05 214438.png', '2025-07-26 01:45:57'),
(13, 9, '../uploads/bukti/1753469448_Screenshot 2025-07-10 194336.png', '2025-07-26 01:50:48'),
(14, NULL, '../uploads/bukti/1753643390_Screenshot 2025-07-10 185010.png', '2025-07-28 02:09:50'),
(15, NULL, '../uploads/bukti/1753643437_Screenshot 2025-07-10 185010.png', '2025-07-28 02:10:37'),
(16, 10, '../uploads/bukti/1753644067_Screenshot 2025-07-10 185205.png', '2025-07-28 02:21:07'),
(17, 12, '../uploads/bukti/1753655741_Screenshot 2025-07-04 012648.png', '2025-07-28 05:35:41'),
(18, 13, '1753829189_Screenshot 2025-07-05 210207.png', '2025-07-30 05:46:29'),
(19, 15, '1754336789_image2.jpg', '2025-08-05 02:46:29'),
(20, 16, '1754336845_image2.jpg', '2025-08-05 02:47:25'),
(21, 16, '1754336852_image2.jpg', '2025-08-05 02:47:32'),
(22, 20, '1754364181_background-rak-buku-zoom-7.jpg', '2025-08-05 10:23:01'),
(23, 21, '1754364221_background-rak-buku-zoom-7.jpg', '2025-08-05 10:23:41'),
(24, 38, '1754391067_image2.jpg', '2025-08-05 17:51:07'),
(25, 40, '1754391157_image1.jpg', '2025-08-05 17:52:37'),
(26, 41, '1754391483_image3.jpg', '2025-08-05 17:58:03'),
(27, 43, '1754393218_image2.jpg', '2025-08-05 18:26:58'),
(28, 44, '1754398616_image2.jpg', '2025-08-05 19:56:56'),
(29, 45, '1754399003_image3.jpg', '2025-08-05 20:03:23'),
(30, 46, '1754399103_image2.jpg', '2025-08-05 20:05:03'),
(31, 47, '1754402299_image2.jpg', '2025-08-05 20:58:19'),
(32, 48, '1754402479_background-rak-buku-zoom-7.jpg', '2025-08-05 21:01:19'),
(33, 49, '1754402540_image2.jpg', '2025-08-05 21:02:20'),
(34, 50, '1754402606_background-rak-buku-zoom-7.jpg', '2025-08-05 21:03:26'),
(35, 52, '1754403657_image2.jpg', '2025-08-05 21:20:57'),
(36, 58, '1754405380_background-rak-buku-zoom-7.jpg', '2025-08-05 21:49:40'),
(37, 60, '1754406011_background-rak-buku-zoom-7.jpg', '2025-08-05 22:00:11'),
(38, 61, '1754406122_background-rak-buku-zoom-7.jpg', '2025-08-05 22:02:02'),
(39, 62, '1754407249_background-rak-buku-zoom-7.jpg', '2025-08-05 22:20:49'),
(40, 63, '1754407352_background-rak-buku-zoom-7.jpg', '2025-08-05 22:22:32'),
(41, 64, '1754409212_background-rak-buku-zoom-7.jpg', '2025-08-05 22:53:32'),
(42, 65, '1754410110_background-rak-buku-zoom-7.jpg', '2025-08-05 23:08:30'),
(43, 67, '1755068898_navigasi user.png', '2025-08-13 14:08:18'),
(44, 68, '1755068935_navigasi user.png', '2025-08-13 14:08:55'),
(45, 75, '1755170124_navigasi user.png', '2025-08-14 18:15:24');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `price` int(11) DEFAULT NULL,
  `stock` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `stock`, `image`) VALUES
(2, 'Saprodap', 20000, 80, 'saprodap.jpg'),
(3, 'Knock', 35000, 43, 'knock.jpg'),
(4, 'Novatec', 50000, 39, 'novatecc.jpg'),
(5, 'Nitrophoska', 20000, 34, 'nitrophoska.jpg'),
(6, 'Yara Liva Tropicote', 25000, 20, 'yara liva tropicote.jpg'),
(7, 'Meroke SOP', 15000, 50, 'merokesop.jpg'),
(8, 'Kamas', 20000, 4, 'kamas.jpg'),
(9, 'Magnum', 25000, 69, 'magnum.jpg'),
(10, 'Vigo Amino', 20000, 75, 'vigoamino.jpg'),
(11, 'Mahkota', 25000, 85, 'mahkota.jpg'),
(12, 'Laoying', 20000, 51, 'lao ying.jpg'),
(14, 'npk 16.16', 25000, 16, 'npk 16.16.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`) VALUES
(1, 'Risa Perbina', 'risasembirirng700@gmail.com', '$2y$10$PtD0uu7izWgFXejeGbGawueXvfPcpyhr3Eii5GZv7mDqgK3.Kpm4q'),
(2, 'asyh', 'asyh@gmail.com', '$2y$10$ipS3V7D.szDUiYidkmmd7u0fsjTvE40oLREN6/GGniw4oSI.YKvIS'),
(3, 'hh', 'hh@nn.com', '$2y$10$3LLeQuUKIhOIqn84WmY.1uyqc9vkz8varmWJDxNlJGPD1yQiVYdWe'),
(4, 'd', 'd@cmd.com', '$2y$10$il8T.INGb1R0xepCIgBcauf0LY2W3SEVdj0q3/IrTjQbzRn7c8eoG');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders_temp`
--
ALTER TABLE `orders_temp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `orders_temp`
--
ALTER TABLE `orders_temp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
