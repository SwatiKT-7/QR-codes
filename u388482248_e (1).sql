-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 21, 2025 at 06:56 AM
-- Server version: 10.11.10-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u388482248_e`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `host_id` int(11) NOT NULL,
  `preferred_time` datetime NOT NULL,
  `appointment_time` datetime DEFAULT NULL,
  `status` enum('pending','accepted','rejected','completed') DEFAULT 'pending',
  `verification_code` varchar(50) NOT NULL,
  `qr_code` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `check_in` datetime DEFAULT NULL,
  `check_out` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `user_id`, `host_id`, `preferred_time`, `appointment_time`, `status`, `verification_code`, `qr_code`, `created_at`, `check_in`, `check_out`) VALUES
(5, 9, 8, '0000-00-00 00:00:00', '2025-02-09 14:05:00', 'accepted', '317796', '', '2025-02-09 08:33:54', '2025-02-09 09:34:20', NULL),
(6, 9, 8, '0000-00-00 00:00:00', '2025-02-09 15:06:00', 'accepted', '957397', '', '2025-02-09 09:36:09', '2025-02-09 09:38:48', '2025-02-09 09:38:50'),
(7, 9, 8, '0000-00-00 00:00:00', '2025-02-09 15:09:00', 'accepted', '424259', '', '2025-02-09 09:39:21', NULL, NULL),
(8, 9, 8, '0000-00-00 00:00:00', '2025-02-09 22:55:00', 'accepted', '611615', '', '2025-02-09 17:25:08', '2025-02-09 17:26:38', '2025-02-09 17:26:41'),
(9, 9, 8, '0000-00-00 00:00:00', '2025-02-09 02:57:00', 'accepted', '760094', '', '2025-02-09 17:27:07', NULL, NULL),
(10, 9, 8, '0000-00-00 00:00:00', '2025-02-11 14:57:00', 'accepted', '470992', '', '2025-02-11 09:27:20', NULL, NULL),
(11, 9, 8, '0000-00-00 00:00:00', '2025-02-11 15:52:00', 'accepted', '351890', '', '2025-02-11 10:22:43', '2025-02-11 10:24:15', '2025-02-11 10:24:17'),
(12, 10, 8, '0000-00-00 00:00:00', '2025-02-12 00:01:00', 'accepted', '731684', '', '2025-02-11 14:44:45', NULL, NULL),
(13, 9, 8, '0000-00-00 00:00:00', '2025-02-12 00:40:00', 'accepted', '508560', '', '2025-02-11 19:09:19', NULL, NULL),
(14, 9, 8, '0000-00-00 00:00:00', '2025-02-12 00:45:00', 'accepted', '264243', '', '2025-02-11 19:15:06', NULL, NULL),
(15, 9, 8, '0000-00-00 00:00:00', '2025-02-12 00:48:00', 'accepted', '496449', '', '2025-02-11 19:18:32', NULL, NULL),
(16, 9, 8, '0000-00-00 00:00:00', '2025-02-12 00:51:00', 'accepted', '549044', '', '2025-02-11 19:21:19', NULL, NULL),
(17, 9, 8, '0000-00-00 00:00:00', '2025-02-12 00:55:00', 'accepted', '745647', '', '2025-02-11 19:24:57', NULL, NULL),
(18, 9, 8, '0000-00-00 00:00:00', '2025-02-12 00:59:00', 'accepted', '727810', '', '2025-02-11 19:28:23', NULL, NULL),
(19, 9, 8, '0000-00-00 00:00:00', '2025-02-12 01:06:00', 'accepted', '252442', '', '2025-02-11 19:36:06', '2025-02-11 19:37:08', '2025-02-11 19:37:19'),
(20, 11, 8, '0000-00-00 00:00:00', '2025-02-14 13:17:00', 'accepted', '510652', '', '2025-02-12 06:24:28', NULL, NULL),
(21, 9, 8, '0000-00-00 00:00:00', '2025-02-14 13:17:00', 'accepted', '719963', '', '2025-02-14 07:47:16', '2025-02-14 07:48:27', '2025-02-14 07:48:29'),
(22, 9, 8, '0000-00-00 00:00:00', '2023-02-18 20:07:00', 'accepted', '506738', '', '2025-02-18 14:37:28', '2025-02-18 14:41:20', '2025-02-18 14:41:24');

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE `login` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `phone_no` varchar(15) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `type` enum('host','user') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`id`, `username`, `phone_no`, `photo`, `email`, `password`, `type`, `created_at`) VALUES
(8, 'Vipranshu123', '6387634038', 'IMG-20250209-WA0002.jpg', 'vipranshu39@gmail.com', '$2y$10$tRnNbFWGGJd3a29wfa4A0.XFWHM8wo5JX8QfASTwHsIqLUJnGUA/i', 'host', '2025-02-09 08:31:10'),
(9, 'dr.ranveer', '6387634038', 'IMG-20250116-WA0015.jpg', 'vipranshusachan@gmail.com', '$2y$10$hGKQyBZ86cXas0RHEFujte7ZVOuvyjFIli2LvIr3VnsIZrngMnT2.', 'user', '2025-02-09 08:33:34'),
(10, 'Ranjeet', '8299478153', 'IMG-20250210-WA0032.jpg', 'ranjeet.axissoft@gmail.com', '$2y$10$gigdzbiFndv9.dLwtWlsaOgJSmC7d/itRRuMlCVourUB4mLPiQKjS', 'user', '2025-02-11 14:44:22'),
(11, 'Sand', '8299478153', 'IMG-20250210-WA0014.jpg', 'ranjeet.prinavgis@gmail.com', '$2y$10$DhyJFqJzQ7Ez2qDIhsow7emCBUukdVvkJXwi7khfpO0GBqj74zdkC', 'user', '2025-02-12 06:23:41'),
(12, 'Ranj', '8299478153', 'IMG-20250212-WA0004.jpg', 'ranjeet.kumar574@gmail.com', '$2y$10$W00EIJDGHqQC6f.ddALuGuB3YlVm/K4dPifIHzdzSyVuslvP1/Ycu', 'host', '2025-02-12 07:01:17');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `host_id` (`host_id`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `login` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`host_id`) REFERENCES `login` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
