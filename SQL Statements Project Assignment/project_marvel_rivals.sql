-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 03, 2025 at 01:25 AM
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
-- Database: `mysql`
--

-- --------------------------------------------------------

--
-- Table structure for table `project_marvel_rivals`
--

CREATE TABLE `project_marvel_rivals` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `bio` varchar(255) NOT NULL,
  `role` enum('User','Coach','Admin') NOT NULL DEFAULT 'User',
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `project_marvel_rivals`
--

INSERT INTO `project_marvel_rivals` (`id`, `username`, `password`, `profile_picture`, `bio`, `role`, `email`) VALUES
(4, 'test123', '$2y$10$0kL8gJQLW1HcBVC/OQHNjel0uO5gKd.Gf0BhsLd7QVVnzwmtVfF.e', NULL, 'I am noob', 'User', ''),
(5, 'DeDeDe', '$2y$10$FtH2ePr5KrkG4c1HVSEOvOAQiaDNnUePqV0YNT5rjDcPcx9kN3NQu', NULL, 'Here to help', 'Coach', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `project_marvel_rivals`
--
ALTER TABLE `project_marvel_rivals`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `project_marvel_rivals`
--
ALTER TABLE `project_marvel_rivals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
