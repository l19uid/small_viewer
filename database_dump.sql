-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 01, 2023 at 03:45 PM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ip_3`
--

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `employee_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `job` varchar(255) NOT NULL,
  `wage` int(11) NOT NULL,
  `room` int(11) NOT NULL,
  `username` text NOT NULL,
  `password` text NOT NULL,
  `admin` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`employee_id`, `name`, `surname`, `job`, `wage`, `room`, `username`, `password`, `admin`) VALUES
(1, 'Františka', 'Netěsná', 'ředitel', 650000, 1, 'admin', '8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918', 1),
(3, 'Alena', 'Netěsná', 'ekonomka', 42000, 5, 'user', '04f8996da763b7a969b1028ee3007569eaf3a635486ddab211d512c85b9df8fb', 0),
(4, 'Jiřina', 'Hamáčková', 'ekonomka', 32000, 5, '', '', 0),
(5, 'Stanislav', 'Lorenc', 'skladník', 14000, 8, '', '', 0),
(6, 'Martina', 'Marková', 'skladnice', 14500, 8, '', '', 0),
(7, 'Tomáš', 'Kalousek', 'technik', 23000, 7, '', '', 0),
(8, 'Jindřich', 'Holzer', 'technik', 22000, 7, '', '', 0),
(9, 'Alena', 'Krátká', 'technik', 24000, 7, '', '', 0),
(10, 'Stanislav', 'Janovič', 'technik', 22000, 7, '', '', 0),
(11, 'Milan', 'Steiner', 'mistr', 29000, 7, '', '', 0),
(13, 'daw', 'awd', 'daw', 123, 5, '', '', 0),
(14, 'daw', 'awd', 'daw', 123, 5, '', '', 0),
(15, 'WDASDDAWD', 'AWDASDA', 'WAD', 222222, 1, 'one', 'one', 0),
(16, 'WDASDDAWD', 'AWDASDA', 'WAD', 222222, 1, 'one', 'one', 0),
(17, 'karel', 'glott', 'awda', 123123, 5, 'karel', 'gott', 0);

-- --------------------------------------------------------

--
-- Table structure for table `key`
--

CREATE TABLE `key` (
  `key_id` int(11) NOT NULL,
  `employee` int(11) NOT NULL,
  `room` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

--
-- Dumping data for table `key`
--

INSERT INTO `key` (`key_id`, `employee`, `room`) VALUES
(1, 1, 1),
(19, 1, 2),
(20, 1, 3),
(21, 1, 4),
(22, 1, 5),
(23, 1, 6),
(16, 1, 7),
(17, 1, 8),
(18, 1, 11),
(46, 3, 1),
(47, 3, 2),
(6, 3, 5),
(35, 3, 6),
(48, 4, 2),
(7, 4, 5),
(36, 4, 6),
(38, 5, 6),
(9, 5, 8),
(50, 5, 11),
(39, 6, 6),
(10, 6, 8),
(51, 6, 11),
(37, 7, 6),
(8, 7, 7),
(52, 7, 11),
(31, 8, 6),
(2, 8, 7),
(53, 8, 11),
(32, 9, 6),
(3, 9, 7),
(54, 9, 11),
(33, 10, 6),
(4, 10, 7),
(55, 10, 11),
(49, 11, 2),
(34, 11, 6),
(5, 11, 7),
(56, 11, 11);

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE `room` (
  `room_id` int(11) NOT NULL,
  `no` varchar(15) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_czech_ci;

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`room_id`, `no`, `name`, `phone`) VALUES
(1, '101', 'Říditelna', '229221'),
(2, '102', 'Kuchyňka', '2293'),
(3, '104', 'Zasedací místnost', '2294'),
(4, '201', 'Xerox', '2296'),
(5, '202', 'Ekonomické', '2295'),
(6, '203', 'Toalety', NULL),
(7, '001', 'Dílna', '2241'),
(8, '002', 'Sklad', '2243'),
(11, '003', 'Šatna', NULL),
(12, '021', 'Sál', '155'),
(13, '022', 'Záchody', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`employee_id`),
  ADD KEY `room` (`room`);

--
-- Indexes for table `key`
--
ALTER TABLE `key`
  ADD PRIMARY KEY (`key_id`),
  ADD UNIQUE KEY `employee_room` (`employee`,`room`),
  ADD KEY `room` (`room`);

--
-- Indexes for table `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`room_id`),
  ADD UNIQUE KEY `no` (`no`),
  ADD UNIQUE KEY `phone` (`phone`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `employee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `key`
--
ALTER TABLE `key`
  MODIFY `key_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `room`
--
ALTER TABLE `room`
  MODIFY `room_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `employee`
--
ALTER TABLE `employee`
  ADD CONSTRAINT `employee_ibfk_1` FOREIGN KEY (`room`) REFERENCES `room` (`room_id`);

--
-- Constraints for table `key`
--
ALTER TABLE `key`
  ADD CONSTRAINT `key_ibfk_1` FOREIGN KEY (`employee`) REFERENCES `employee` (`employee_id`),
  ADD CONSTRAINT `key_ibfk_2` FOREIGN KEY (`room`) REFERENCES `room` (`room_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
