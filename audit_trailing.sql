-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 07, 2021 at 07:20 AM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 8.0.7

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
-- Table structure for table `logs`
--

CREATE TABLE `logs` (
  `id` int(30) NOT NULL,
  `user_id` int(30) NOT NULL,
  `action_made` text NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `logs`
--

INSERT INTO `logs` (`id`, `user_id`, `action_made`, `date_created`) VALUES
(1, 1, 'Logged out.', '2021-10-07 10:43:44'),
(2, 1, 'Logged in the system.', '2021-10-07 10:44:33'),
(4, 1, 'Logged in the system.', '2021-10-07 10:50:07'),
(5, 1, ' added [id=0] Claire Blake into the member list.', '2021-10-07 11:51:27'),
(6, 1, ' added [id=2] John Smith into the member list.', '2021-10-07 11:55:00'),
(7, 1, ' updated the details of [id=1] member.', '2021-10-07 12:50:55'),
(8, 1, ' added [id=3] test test into the member list.', '2021-10-07 12:51:25'),
(9, 1, ' added [id=4] test test into the member list.', '2021-10-07 12:55:57'),
(10, 1, ' deleted [id=4] test test from member list.', '2021-10-07 12:56:04'),
(11, 1, ' viewed the data of [id=1]Clairy Blake', '2021-10-07 13:13:09'),
(12, 1, ' viewed the data of [id=2]John Smith', '2021-10-07 13:13:32'),
(13, 1, ' viewed the data of [id=1]Clairy Blake', '2021-10-07 13:16:02'),
(14, 1, 'Logged out.', '2021-10-07 13:16:11'),
(15, 2, 'Logged in the system.', '2021-10-07 13:16:15'),
(16, 2, ' added [id=5] Mike Williams into the member list.', '2021-10-07 13:17:46'),
(17, 2, ' viewed the data of [id=5]Mike Williams', '2021-10-07 13:17:58'),
(18, 2, ' updated the details of [id=5] member.', '2021-10-07 13:18:15');

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `id` int(30) NOT NULL,
  `firstname` varchar(250) NOT NULL,
  `lastname` varchar(250) NOT NULL,
  `contact` varchar(50) NOT NULL,
  `address` text NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`id`, `firstname`, `lastname`, `contact`, `address`, `date_created`) VALUES
(1, 'Clairy', 'Blake', '09123456789', 'Sample Address', '2021-10-07 11:51:27'),
(2, 'John', 'Smith', '09123456789', 'Sample Address', '2021-10-07 11:55:00'),
(5, 'Mikee', 'Williams', '09112655889', '23rd St. Here City', '2021-10-07 13:17:46');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `date_created` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`, `date_created`) VALUES
(1, 'Administrator', 'admin', '0192023a7bbd73250516f069df18b500', '2021-10-07 03:59:25'),
(2, 'john Smitn', 'jsmith', '1254737c076cf867dc53d60a0364f38e', '2021-10-07 03:59:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `logs`
--
ALTER TABLE `logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
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
-- AUTO_INCREMENT for table `logs`
--
ALTER TABLE `logs`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
