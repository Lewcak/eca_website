-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 30, 2025 at 04:48 PM
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
-- Database: `eca_project`
--

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `seller_id` int(11) NOT NULL,
  `condition` varchar(50) NOT NULL DEFAULT 'Used'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `image`, `description`, `seller_id`, `condition`) VALUES
(3, 'The Mind', 150.00, 'themind.jpg', 'teamwork silent card game', 1, 'Used'),
(4, 'Ark Nova', 1200.00, 'arknova.jpg', 'A game about building the best zoo', 3, 'Like New'),
(6, 'Ark Nova Marine Expansion', 450.00, 'marineexpansion.jpg', 'The First Expansion for Ark Nova, Marine Worlds\r\n\r\nAll cards items and cards included.', 3, 'Used'),
(7, 'King Of Tokyo: Dark Edition', 1400.00, 'kotdark.jpg', 'King Of Tokyo: Dark Edtion\r\n\r\nDecent condition, all parts and cards \r\n\r\nprice not negotiable', 10, 'Like New'),
(8, 'Risk', 300.00, 'risk.jpg', 'Risk strategy game odl\r\n\r\nsome pieces', 11, 'Worn'),
(9, 'Bounty', 450.00, 'bounty.jpg', 'ARGHHHH ME MATEYYY\r\n\r\nDONT ASK IV AVAIBLE I WILL KILLLL KILLL KILLL \r\n\r\nSTRAND', 12, 'Worn'),
(13, 'Arcs', 1400.00, 'arcs,jpg.jpg', 'arcs space war', 1, 'New'),
(14, 'Dune Imperium Uprising', 1400.00, 'duneup.jpg', 'The Movie, Book and now game', 1, 'New');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `number` varchar(13) DEFAULT NULL,
  `admin` tinyint(1) DEFAULT 0,
  `role` varchar(20) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `number`, `admin`, `role`) VALUES
(1, 'HomeOfTheBoards', 'HomeOfTheBoards@email.com', '$2y$10$18dvEB3yMx2ospENvp8Gzu3.IYPF3yEOXnayabd71LWYjWF9yKqfa', '1234567890', 1, 'admin'),
(3, 'user1', 'user1@Email.com', '$2y$10$/.6PRe0qcRHEtzu2ccgvN.YVhNw09ZJNheD0vVWC5Q3JLuH9s/LHS', '0847752134', 0, 'user'),
(10, 'Luca', 'luca@gmail.com', '$2y$10$wxG.s9VDpw4N5M0yGY79y.RvvfkWV85fZu0kQWd9n4JrIly/lULQa', '0857765467', 0, 'user'),
(11, 'johan', 'JOHAN@EMAIL.COM', '$2y$10$/OGbJ7uWB47TZTsZKx.KHOzSaHPXgIqFi/yZvDCyHmWBvvpVHQo/2', '0837364230', 0, 'user'),
(12, 'Huisman', 'Huisman@email.com', '$2y$10$Wp9p7aBv8qbgj2SGA64Q5e.qbaZ5rsFop..xcBJUgz8ytMc1ewn/S', '0845563470', 0, 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `seller_id` (`seller_id`);

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
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`seller_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
