-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Dec 21, 2024 at 06:01 AM
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
-- Database: `child_vaccination_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `child`
--

CREATE TABLE `child` (
  `id` int(11) NOT NULL,
  `c_name` varchar(255) NOT NULL,
  `c_gender` varchar(50) DEFAULT NULL,
  `c_city` varchar(255) DEFAULT NULL,
  `c_birth` date DEFAULT NULL,
  `c_age` int(11) DEFAULT NULL,
  `c_weight` int(11) DEFAULT NULL,
  `c_height` int(11) DEFAULT NULL,
  `c_vaccine` varchar(255) DEFAULT NULL,
  `p_username` varchar(255) DEFAULT NULL,
  `status` enum('true','false') NOT NULL DEFAULT 'false',
  `p_email` varchar(255) DEFAULT NULL,
  `scheduled_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `child`
--

INSERT INTO `child` (`id`, `c_name`, `c_gender`, `c_city`, `c_birth`, `c_age`, `c_weight`, `c_height`, `c_vaccine`, `p_username`, `status`, `p_email`, `scheduled_date`) VALUES
(51, 'Sagar Adhikari', 'Male', 'birtamode', '2024-12-17', 1, 1, 1, 'DTP', 'Srijan ', 'true', 'sapkotasrijan7@gmail.com', '2025-01-01');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `gender` varchar(50) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `birth` date DEFAULT NULL,
  `role` enum('admin','parent') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `gender`, `city`, `birth`, `role`) VALUES
(3, 'Srijan', '$2y$10$jD5QTOJ1O9ZjbTOty50Ilutxs1zZY6UG2ZKAUnHyauLLcTq4Ju7VW', 'sapkotasrijan7@gmail.com', 'M', 'Birtamode', '2024-02-08', 'parent');

-- --------------------------------------------------------

--
-- Table structure for table `vaccine_dates`
--

CREATE TABLE `vaccine_dates` (
  `id` int(11) NOT NULL,
  `c_name` varchar(255) NOT NULL,
  `p_username` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `v_date` date DEFAULT NULL,
  `timing` time DEFAULT NULL,
  `status` enum('pending','completed','cancelled') NOT NULL DEFAULT 'pending',
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vaccine_dates`
--

INSERT INTO `vaccine_dates` (`id`, `c_name`, `p_username`, `name`, `v_date`, `timing`, `status`, `last_updated`) VALUES
(92, 'Sagar Adhikari', 'Srijan ', 'DTP', '2025-01-01', '09:00:00', 'pending', '2024-12-17 14:14:05');

-- --------------------------------------------------------

--
-- Table structure for table `vaccine_stock`
--

CREATE TABLE `vaccine_stock` (
  `id` int(11) NOT NULL,
  `vaccine_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `vaccine_stock`
--

INSERT INTO `vaccine_stock` (`id`, `vaccine_name`, `quantity`) VALUES
(1, 'Hepatitis B', 100),
(2, 'BCG', 53),
(3, 'Polio', 75),
(4, 'DTP', 60);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `child`
--
ALTER TABLE `child`
  ADD PRIMARY KEY (`id`),
  ADD KEY `p_username` (`p_username`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `vaccine_dates`
--
ALTER TABLE `vaccine_dates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `p_username` (`p_username`);

--
-- Indexes for table `vaccine_stock`
--
ALTER TABLE `vaccine_stock`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `child`
--
ALTER TABLE `child`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `vaccine_dates`
--
ALTER TABLE `vaccine_dates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT for table `vaccine_stock`
--
ALTER TABLE `vaccine_stock`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `child`
--
ALTER TABLE `child`
  ADD CONSTRAINT `child_ibfk_1` FOREIGN KEY (`p_username`) REFERENCES `users` (`username`);

--
-- Constraints for table `vaccine_dates`
--
ALTER TABLE `vaccine_dates`
  ADD CONSTRAINT `vaccine_dates_ibfk_1` FOREIGN KEY (`p_username`) REFERENCES `users` (`username`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
