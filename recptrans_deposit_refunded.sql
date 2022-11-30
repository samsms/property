-- phpMyAdmin SQL Dump
-- version 5.1.1deb5ubuntu1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 30, 2022 at 04:49 PM
-- Server version: 10.6.7-MariaDB-2ubuntu1.1
-- PHP Version: 8.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `techsava_rivercourt`
--

-- --------------------------------------------------------

--
-- Table structure for table `recptrans_deposit_refunded`
--

CREATE TABLE `recptrans_deposit_refunded` (
  `id` int(211) NOT NULL,
  `deposit_payed` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reason` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reciept_no` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `recptrans_deposit_refunded`
--

INSERT INTO `recptrans_deposit_refunded` (`id`, `deposit_payed`, `reason`, `reciept_no`) VALUES
(16, '2300', 'to cater for damages', '105387');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `recptrans_deposit_refunded`
--
ALTER TABLE `recptrans_deposit_refunded`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `recptrans_deposit_refunded`
--
ALTER TABLE `recptrans_deposit_refunded`
  MODIFY `id` int(211) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
