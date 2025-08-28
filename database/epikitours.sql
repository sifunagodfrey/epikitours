-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 28, 2025 at 04:52 PM
-- Server version: 8.0.37
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `epikitours`
--

-- --------------------------------------------------------

--
-- Table structure for table `epi_activity_logs`
--

CREATE TABLE `epi_activity_logs` (
  `id` bigint NOT NULL,
  `user_id` bigint DEFAULT NULL,
  `action` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ip_address` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `epi_activity_logs`
--

INSERT INTO `epi_activity_logs` (`id`, `user_id`, `action`, `ip_address`, `created_at`) VALUES
(1, NULL, 'User Registered', '::1', '2025-08-25 09:21:06'),
(2, NULL, 'User Logged In', '::1', '2025-08-25 09:29:21'),
(3, NULL, 'User Logged In', '::1', '2025-08-25 09:32:05'),
(4, NULL, 'User Logged In', '::1', '2025-08-25 09:34:23'),
(5, NULL, 'User Logged In', '::1', '2025-08-25 09:35:32'),
(6, NULL, 'User Logged In', '::1', '2025-08-25 09:36:09'),
(7, NULL, 'User Logged In', '::1', '2025-08-25 09:39:38'),
(8, NULL, 'User Logged In', '::1', '2025-08-25 09:42:25'),
(9, NULL, 'User Logged In', '::1', '2025-08-25 09:46:50'),
(10, 4, 'User Registered', '::1', '2025-08-25 09:49:23'),
(11, 4, 'User Logged In', '::1', '2025-08-25 09:49:26'),
(12, 4, 'User Logged In', '::1', '2025-08-25 09:50:33'),
(13, 4, 'User Logged In', '::1', '2025-08-25 09:51:20'),
(14, 4, 'User Logged In', '::1', '2025-08-25 09:55:46'),
(15, 4, 'User Logged In', '::1', '2025-08-25 09:56:16'),
(16, 4, 'User Logged In', '::1', '2025-08-25 09:56:23'),
(17, 4, 'User Logged In', '::1', '2025-08-25 09:59:06'),
(18, 4, 'User Logged In', '::1', '2025-08-25 09:59:09'),
(19, 4, 'User Logged In', '::1', '2025-08-25 10:01:23'),
(20, 4, 'User Logged In', '::1', '2025-08-25 10:05:05'),
(21, 4, 'User Logged In', '::1', '2025-08-25 10:18:07'),
(22, 4, 'User Logged In', '::1', '2025-08-25 10:22:23'),
(23, 4, 'User Logged In', '::1', '2025-08-25 10:29:04'),
(24, 4, 'User Logged In', '::1', '2025-08-25 10:58:30'),
(25, 4, 'User Logged In', '::1', '2025-08-25 11:14:02'),
(26, 4, 'User Logged In', '::1', '2025-08-25 11:15:29'),
(27, 4, 'User Logged In', '::1', '2025-08-26 05:01:55'),
(28, 4, 'User Logged In', '::1', '2025-08-26 07:06:08'),
(29, 4, 'User Logged In', '::1', '2025-08-26 15:11:18'),
(30, 4, 'User Logged In', '::1', '2025-08-27 06:56:51'),
(31, 5, 'User Logged In', '::1', '2025-08-28 05:57:11'),
(32, 5, 'User Logged In', '::1', '2025-08-28 05:58:11'),
(33, NULL, 'User Registered', '::1', '2025-08-28 14:06:45'),
(34, NULL, 'Admin Created User', '::1', '2025-08-28 14:26:40'),
(35, NULL, 'Admin Created User', '::1', '2025-08-28 14:27:41'),
(36, NULL, 'Admin Created User', '::1', '2025-08-28 14:32:54'),
(37, NULL, 'Admin Created User', '::1', '2025-08-28 14:36:19'),
(38, NULL, 'Admin Created User', '::1', '2025-08-28 14:50:10');

-- --------------------------------------------------------

--
-- Table structure for table `epi_bookings`
--

CREATE TABLE `epi_bookings` (
  `id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `tour_id` bigint NOT NULL,
  `booking_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('pending','confirmed','canceled') COLLATE utf8mb4_general_ci DEFAULT 'pending',
  `confirmation_code` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `epi_notifications`
--

CREATE TABLE `epi_notifications` (
  `id` bigint NOT NULL,
  `user_id` bigint NOT NULL,
  `tour_id` bigint DEFAULT NULL,
  `type` enum('email','system','sms') COLLATE utf8mb4_general_ci DEFAULT 'email',
  `message` text COLLATE utf8mb4_general_ci,
  `status` enum('sent','pending','failed') COLLATE utf8mb4_general_ci DEFAULT 'pending',
  `sent_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `epi_notifications`
--

INSERT INTO `epi_notifications` (`id`, `user_id`, `tour_id`, `type`, `message`, `status`, `sent_at`) VALUES
(1, 1, NULL, 'email', 'Your safari booking is confirmed!', 'sent', '2025-08-25 09:46:10');

-- --------------------------------------------------------

--
-- Table structure for table `epi_payments`
--

CREATE TABLE `epi_payments` (
  `id` int NOT NULL,
  `booking_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(10) COLLATE utf8mb4_general_ci DEFAULT 'KES',
  `payment_method` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `transaction_id` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('pending','completed','failed','refunded') COLLATE utf8mb4_general_ci DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `epi_sessions`
--

CREATE TABLE `epi_sessions` (
  `id` bigint NOT NULL,
  `tour_id` bigint NOT NULL,
  `session_link` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `status` enum('scheduled','live','ended') COLLATE utf8mb4_general_ci DEFAULT 'scheduled',
  `assigned_agent_id` bigint DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `epi_tours`
--

CREATE TABLE `epi_tours` (
  `id` bigint NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `location` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `youtube_link` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `marzipano_path` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jitsi_link` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `preview_thumbnail` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `agent_id` bigint DEFAULT NULL,
  `status` enum('upcoming','ongoing','completed','canceled') COLLATE utf8mb4_general_ci DEFAULT 'upcoming',
  `created_by` bigint NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `epi_tour_agents`
--

CREATE TABLE `epi_tour_agents` (
  `id` bigint NOT NULL,
  `tour_id` bigint NOT NULL,
  `agent_id` bigint NOT NULL,
  `assigned_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `epi_tour_jitsi`
--

CREATE TABLE `epi_tour_jitsi` (
  `id` bigint NOT NULL,
  `api_key` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `api_secret` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `base_url` varchar(255) COLLATE utf8mb4_general_ci DEFAULT 'https://meet.jit.si',
  `config_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ;

-- --------------------------------------------------------

--
-- Table structure for table `epi_tour_media`
--

CREATE TABLE `epi_tour_media` (
  `id` bigint NOT NULL,
  `tour_id` bigint NOT NULL,
  `media_type` enum('image','video','panorama') COLLATE utf8mb4_general_ci NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `epi_users`
--

CREATE TABLE `epi_users` (
  `id` bigint NOT NULL,
  `first_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `last_name` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `user_role` enum('admin','guide','visitor') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'visitor',
  `profile_picture` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email_verified` tinyint(1) DEFAULT '0',
  `verification_token` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status` enum('dormant','active') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'active',
  `password_reset_token` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password_reset_expires` datetime DEFAULT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `epi_users`
--

INSERT INTO `epi_users` (`id`, `first_name`, `last_name`, `email`, `password`, `phone`, `user_role`, `profile_picture`, `email_verified`, `verification_token`, `status`, `password_reset_token`, `password_reset_expires`, `last_login`, `created_at`, `updated_at`) VALUES
(2, 'Alice', 'Wanjiku', 'guide@example.com', '*EF53AC6FDCE36A16350904509C4D00B826AB5D99', '254700111222', 'visitor', NULL, 1, NULL, 'active', NULL, NULL, NULL, '2025-08-25 09:46:10', '2025-08-28 08:32:48'),
(4, 'Godfrey', 'Sifuna', 'sifuna.godfreyw@gmail.com', '$2y$10$BF.L6FaUqd5DorBqsgnf1euWcoAHqlduFn55OEjKm1Ff0YqojhvBa', '0706006230', 'admin', NULL, 0, NULL, 'active', NULL, NULL, '2025-08-27 06:56:51', '2025-08-25 09:49:23', '2025-08-28 08:32:48'),
(5, 'Dana', 'Katisya', 'danakatisya@gmail.com', '$2y$10$pbZc2IBwKUog3iSS9ko.lu7/HyrjIy1BwJLUWc388.Wq3TYDrGdR6', '0758926969', 'admin', NULL, 0, NULL, 'active', NULL, NULL, '2025-08-28 05:58:11', '2025-08-28 05:56:34', '2025-08-28 12:39:34');

-- --------------------------------------------------------

--
-- Table structure for table `isk_system_logs`
--

CREATE TABLE `isk_system_logs` (
  `id` int NOT NULL,
  `message` text COLLATE utf8mb4_general_ci,
  `status` enum('unread','read') COLLATE utf8mb4_general_ci DEFAULT 'unread',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `epi_activity_logs`
--
ALTER TABLE `epi_activity_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `epi_bookings`
--
ALTER TABLE `epi_bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `confirmation_code` (`confirmation_code`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `tour_id` (`tour_id`);

--
-- Indexes for table `epi_notifications`
--
ALTER TABLE `epi_notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `tour_id` (`tour_id`);

--
-- Indexes for table `epi_payments`
--
ALTER TABLE `epi_payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transaction_id` (`transaction_id`);

--
-- Indexes for table `epi_sessions`
--
ALTER TABLE `epi_sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_id` (`tour_id`),
  ADD KEY `assigned_agent_id` (`assigned_agent_id`);

--
-- Indexes for table `epi_tours`
--
ALTER TABLE `epi_tours`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug_unique` (`slug`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `epi_tour_agents`
--
ALTER TABLE `epi_tour_agents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_id` (`tour_id`),
  ADD KEY `agent_id` (`agent_id`);

--
-- Indexes for table `epi_tour_jitsi`
--
ALTER TABLE `epi_tour_jitsi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `epi_tour_media`
--
ALTER TABLE `epi_tour_media`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tour_id` (`tour_id`);

--
-- Indexes for table `epi_users`
--
ALTER TABLE `epi_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `isk_system_logs`
--
ALTER TABLE `isk_system_logs`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `epi_activity_logs`
--
ALTER TABLE `epi_activity_logs`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `epi_bookings`
--
ALTER TABLE `epi_bookings`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `epi_notifications`
--
ALTER TABLE `epi_notifications`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `epi_payments`
--
ALTER TABLE `epi_payments`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `epi_sessions`
--
ALTER TABLE `epi_sessions`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `epi_tours`
--
ALTER TABLE `epi_tours`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `epi_tour_agents`
--
ALTER TABLE `epi_tour_agents`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `epi_tour_jitsi`
--
ALTER TABLE `epi_tour_jitsi`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `epi_tour_media`
--
ALTER TABLE `epi_tour_media`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `epi_users`
--
ALTER TABLE `epi_users`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `isk_system_logs`
--
ALTER TABLE `isk_system_logs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `epi_activity_logs`
--
ALTER TABLE `epi_activity_logs`
  ADD CONSTRAINT `epi_activity_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `epi_users` (`id`) ON DELETE SET NULL ON UPDATE RESTRICT;

--
-- Constraints for table `epi_bookings`
--
ALTER TABLE `epi_bookings`
  ADD CONSTRAINT `epi_bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `epi_users` (`id`),
  ADD CONSTRAINT `epi_bookings_ibfk_2` FOREIGN KEY (`tour_id`) REFERENCES `epi_tours` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `epi_notifications`
--
ALTER TABLE `epi_notifications`
  ADD CONSTRAINT `epi_notifications_ibfk_2` FOREIGN KEY (`tour_id`) REFERENCES `epi_tours` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `epi_sessions`
--
ALTER TABLE `epi_sessions`
  ADD CONSTRAINT `epi_sessions_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `epi_tours` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `epi_sessions_ibfk_2` FOREIGN KEY (`assigned_agent_id`) REFERENCES `epi_users` (`id`);

--
-- Constraints for table `epi_tours`
--
ALTER TABLE `epi_tours`
  ADD CONSTRAINT `epi_tours_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `epi_users` (`id`);

--
-- Constraints for table `epi_tour_agents`
--
ALTER TABLE `epi_tour_agents`
  ADD CONSTRAINT `epi_tour_agents_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `epi_tours` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `epi_tour_agents_ibfk_2` FOREIGN KEY (`agent_id`) REFERENCES `epi_users` (`id`);

--
-- Constraints for table `epi_tour_media`
--
ALTER TABLE `epi_tour_media`
  ADD CONSTRAINT `epi_tour_media_ibfk_1` FOREIGN KEY (`tour_id`) REFERENCES `epi_tours` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
