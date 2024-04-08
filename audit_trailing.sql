-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 08, 2024 at 11:55 AM
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
-- Database: `audit_trailing`
--

-- --------------------------------------------------------

--
-- Table structure for table `data_registration`
--

CREATE TABLE `data_registration` (
  `id` int(11) NOT NULL,
  `date_reg` datetime DEFAULT NULL,
  `username_reg` varchar(255) NOT NULL,
  `rekening` bigint(20) DEFAULT 0,
  `email` varchar(255) DEFAULT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `status_sms` tinyint(4) DEFAULT NULL,
  `status_email` tinyint(4) DEFAULT NULL,
  `status_wa` tinyint(4) DEFAULT 0,
  `username_update` varchar(255) DEFAULT '"',
  `date_update` datetime DEFAULT NULL,
  `channel` varchar(255) DEFAULT '"',
  `amount_sms` decimal(21,0) DEFAULT NULL,
  `amount_email` decimal(21,0) DEFAULT NULL,
  `amount_wa` decimal(21,0) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `data_registration`
--

INSERT INTO `data_registration` (`id`, `date_reg`, `username_reg`, `rekening`, `email`, `phone_number`, `status_sms`, `status_email`, `status_wa`, `username_update`, `date_update`, `channel`, `amount_sms`, `amount_email`, `amount_wa`) VALUES
(1, '2024-03-01 00:00:00', 'admin_1', 16601020870538, 'princealvinyusuf@gmail.com', '082392042422', 0, 1, 1, '111', '2024-04-08 11:29:49', '', NULL, NULL, 0),
(2, '2024-03-01 00:00:00', 'admin_2', 16601020870555, 'abdul@gmail.com', '081234982349', 1, 1, 1, '', '2024-04-05 14:21:30', '', NULL, NULL, 0),
(3, '2024-03-01 00:00:00', 'admin_2', 16601020870598, 'princealvinyusuf@gmail.com', '082392042422', 0, 0, 1, 'eForm-xxx1', '2024-04-08 16:21:17', 'Email', NULL, NULL, 0),
(4, '2024-03-01 00:00:00', 'admin_2', 166010208705285, 'princealvinyusuf@gmail.com', '082392042422', 1, 0, 0, 'eForm-123456', '2024-04-08 16:19:40', 'WA', NULL, NULL, 0),
(5, '2024-03-01 00:00:00', 'admin_1', 166010208705758, 'princealvinyusuf@gmail.com', '082392042422', 1, 0, 1, 'eForm-123', '2024-04-08 16:16:11', 'WA', NULL, NULL, 0),
(6, '2024-03-25 15:34:52', 'admin_3', 166077765449991, 'abdul@gmail.com', '081234982349', 1, 1, 1, '', NULL, '', NULL, NULL, 0),
(7, '2024-03-25 15:34:52', 'admin_1', 16637463847362, 'abdul@gmail.com', '081234445445', 1, 1, 1, '', NULL, '', NULL, NULL, 0),
(9, '2024-04-02 15:08:50', 'admin_5', 519522344983742, 'rafi@gmail.com', '081364407052', 1, 1, 1, '', '2024-04-05 10:44:00', '', NULL, NULL, 0),
(10, '2024-04-02 15:08:50', 'user123', 123456789012345, 'user123@example.com', '081364407052', 1, 1, 1, '', NULL, '', NULL, NULL, 0),
(11, '2024-04-02 15:08:50', 'john_doe', 987654321098765, 'john_doe@hotmail.com', '081364407052', 1, 1, 1, '', NULL, '', NULL, NULL, 0),
(12, '2024-04-02 15:08:50', 'alice_smith', 456789012345678, 'alice_smith@gmail.com', '081364407052', 1, 1, 1, '', NULL, '', NULL, NULL, 0),
(13, '2024-04-02 15:08:50', 'test_user', 345678901234567, 'test_user@yahoo.com', '081364407052', 1, 1, 1, '', NULL, '', NULL, NULL, 0),
(14, '2024-04-02 15:08:50', 'jane_doe', 876543210987654, 'jane_doe@example.com', '081364407052', 1, 1, 1, '', NULL, '', NULL, NULL, 0),
(15, '2024-04-02 15:08:50', 'user456', 234567890123456, 'user456@gmail.com', '081364407052', 1, 1, 1, '', NULL, '', NULL, NULL, 0),
(16, '2024-04-02 15:08:50', 'samuel_jackson', 654321098765432, 'samuel_jackson@outlook.com', '081364407052', 1, 1, 1, '', NULL, '', NULL, NULL, 0),
(17, '2024-04-02 15:08:50', 'jenny_89', 789012345678901, 'jenny_89@gmail.com', '081364407052', 1, 1, 1, '', NULL, '', NULL, NULL, 0),
(18, '2024-04-02 15:08:50', 'admin007', 543210987654321, 'admin007@yahoo.com', '081364407052', 1, 1, 1, '', NULL, '', NULL, NULL, 0),
(19, '2024-04-02 15:08:50', 'mary_doe', 12345678901234, 'mary_doe@example.com', '081364407052', 1, 1, 1, '', NULL, '', NULL, NULL, 0),
(20, '2024-04-02 15:08:50', 'new_user', 432109876543210, 'new_user@hotmail.com', '081364407052', 1, 1, 1, '', NULL, '', NULL, NULL, 0),
(21, '2024-04-02 15:08:50', 'sara_smith', 901234567890123, 'sara_smith@gmail.com', '081364407052', 1, 1, 1, '', NULL, '', NULL, NULL, 0),
(27, '2024-04-02 15:08:50', 'julia_87', 876543210987654, 'julia_87@gmail.com', '081364407052', 1, 1, 1, '', NULL, '', NULL, NULL, 0),
(28, '2024-04-02 15:08:50', 'admin_test', 567890123456789, 'admin_test@yahoo.com', '081364407052', 1, 1, 1, '', NULL, '', NULL, NULL, 0),
(29, '2024-04-02 15:08:50', 'david_robinson', 234567890123456, 'david_robinson@example.com', '081364407052', 1, 1, 1, '', NULL, '', NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int(30) NOT NULL,
  `user_id` int(30) NOT NULL,
  `action_made` text NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `ip_address` varchar(50) NOT NULL,
  `user_agent` varchar(255) NOT NULL,
  `query` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`id`, `user_id`, `action_made`, `date_created`, `ip_address`, `user_agent`, `query`) VALUES
(1281, 1, 'Logged out.', '2024-04-08 16:27:22', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:124.0) Gecko/20100101 Firefox/124.0', ''),
(1282, 1, 'Logged in the system.', '2024-04-08 16:32:33', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:124.0) Gecko/20100101 Firefox/124.0', ''),
(1283, 1, 'Added new user: Engineer into the user list.', '2024-04-08 16:32:58', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:124.0) Gecko/20100101 Firefox/124.0', ''),
(1284, 1, 'Updated the details of: Engineer account.', '2024-04-08 16:33:14', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:124.0) Gecko/20100101 Firefox/124.0', ''),
(1285, 1, 'Logged out.', '2024-04-08 16:33:26', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:124.0) Gecko/20100101 Firefox/124.0', ''),
(1286, 24, 'Logged in the system.', '2024-04-08 16:33:32', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:124.0) Gecko/20100101 Firefox/124.0', '');

-- --------------------------------------------------------

--
-- Table structure for table `query`
--

CREATE TABLE `query` (
  `id` int(11) NOT NULL,
  `query_name` varchar(100) DEFAULT NULL,
  `query_text` varchar(255) DEFAULT NULL,
  `database_name` varchar(80) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `query`
--

INSERT INTO `query` (`id`, `query_name`, `query_text`, `database_name`, `description`, `created_at`) VALUES
(1, 'Execute ABC', 'INSERT INTO Customers (CustomerName, City, Country)\r\nVALUES (\'Cardinal\', \'Stavanger\', \'Norway\');', 'PROD BRILink', 'This function will execute...', '2024-03-19 04:36:26'),
(2, 'Execute DEF', 'INSERT INTO Marketers (CustomerName, City, Country)\r\nVALUES (\'Cardinal\', \'Stavanger\', \'Norway\');', 'PROD BRILife', 'This function will execute...', '2024-03-19 04:36:26');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `contact` varchar(50) NOT NULL,
  `address` text NOT NULL,
  `date_created` datetime DEFAULT NULL,
  `access_level` varchar(80) NOT NULL,
  `last_active` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `contact`, `address`, `date_created`, `access_level`, `last_active`) VALUES
(1, 'Administrator Core', 'admin', '0192023a7bbd73250516f069df18b500', '082392042422', 'blegoh@test.bri.co.id', '2021-10-07 03:59:25', 'Administrator', '2024-04-08 09:32:36'),
(23, 'Operator', 'operator', '0192023a7bbd73250516f069df18b500', '0812345678', 'jihan@gmail.com', NULL, 'Operator', '2024-04-08 08:48:26'),
(24, 'Engineer', 'engineer', '0192023a7bbd73250516f069df18b500', '0808080808', 'engineer_sdk@gmail.com', NULL, 'Engineer', '2024-04-08 09:33:35');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `data_registration`
--
ALTER TABLE `data_registration`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `query`
--
ALTER TABLE `query`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `data_registration`
--
ALTER TABLE `data_registration`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1287;

--
-- AUTO_INCREMENT for table `query`
--
ALTER TABLE `query`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `logs`
--
ALTER TABLE `logs`
  ADD CONSTRAINT `logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
