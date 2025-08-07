-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 07, 2025 at 07:29 AM
-- Server version: 9.1.0
-- PHP Version: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `student_analysis`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
CREATE TABLE IF NOT EXISTS `admins` (
  `admin_id` int NOT NULL AUTO_INCREMENT,
  `id` int NOT NULL,
  `username` varchar(45) NOT NULL,
  `name` varchar(25) NOT NULL,
  `email` varchar(55) DEFAULT NULL,
  `phone` varchar(13) NOT NULL,
  `address` varchar(150) NOT NULL,
  `profile_image` varchar(255) NOT NULL,
  PRIMARY KEY (`admin_id`),
  KEY `fkad` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `marks`
--

DROP TABLE IF EXISTS `marks`;
CREATE TABLE IF NOT EXISTS `marks` (
  `id` int NOT NULL AUTO_INCREMENT,
  `std_id` int NOT NULL,
  `class` int NOT NULL,
  `first_language` int NOT NULL,
  `second_language` int NOT NULL,
  `third_language` int NOT NULL,
  `maths` int NOT NULL,
  `science` int NOT NULL,
  `social_science` int NOT NULL,
  `max_marks` int NOT NULL,
  `total_marks` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unq` (`std_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `results`
--

DROP TABLE IF EXISTS `results`;
CREATE TABLE IF NOT EXISTS `results` (
  `id` int NOT NULL AUTO_INCREMENT,
  `std_id` int NOT NULL,
  `class` int NOT NULL,
  `max_marks` int NOT NULL,
  `total_marks` int NOT NULL,
  `percentage` float NOT NULL,
  `grade` varchar(2) NOT NULL,
  `status` varchar(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `std_id` (`std_id`,`class`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `security`
--

DROP TABLE IF EXISTS `security`;
CREATE TABLE IF NOT EXISTS `security` (
  `sec_id` int NOT NULL AUTO_INCREMENT,
  `id` int NOT NULL,
  `username` varchar(25) NOT NULL,
  `password` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `role` enum('admin','staff','student') NOT NULL,
  PRIMARY KEY (`sec_id`),
  KEY `fks` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

DROP TABLE IF EXISTS `staff`;
CREATE TABLE IF NOT EXISTS `staff` (
  `staff_id` int NOT NULL AUTO_INCREMENT,
  `id` int NOT NULL,
  `name` varchar(25) NOT NULL,
  `phone` varchar(13) NOT NULL,
  `email` varchar(55) DEFAULT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `address` text,
  PRIMARY KEY (`staff_id`),
  KEY `fkst` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
CREATE TABLE IF NOT EXISTS `students` (
  `std_id` int NOT NULL AUTO_INCREMENT,
  `id` int NOT NULL,
  `username` varchar(25) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `dob` date DEFAULT NULL,
  `class` int DEFAULT NULL,
  `year` int DEFAULT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `phone` varchar(13) NOT NULL,
  `email` varchar(50) NOT NULL,
  `address` text,
  `parent_name` varchar(255) DEFAULT NULL,
  `parent_phone_number` varchar(15) DEFAULT NULL,
  `hint_question` varchar(255) DEFAULT NULL,
  `hint_answer` varchar(255) DEFAULT NULL,
  `profile_image` varchar(255) NOT NULL,
  PRIMARY KEY (`std_id`),
  KEY `fks` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

DROP TABLE IF EXISTS `subjects`;
CREATE TABLE IF NOT EXISTS `subjects` (
  `sub_id` int NOT NULL AUTO_INCREMENT,
  `sub_name` varchar(100) NOT NULL,
  `max_marks` int NOT NULL,
  `passing_marks` int NOT NULL,
  `staff_id` int NOT NULL,
  `class` int NOT NULL,
  PRIMARY KEY (`sub_id`),
  KEY `fkstf` (`staff_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(25) NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `phno` varchar(13) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email` varchar(45) DEFAULT NULL,
  `role` enum('admin','staff','student') NOT NULL,
  `school_id` int NOT NULL,
  `hintQn` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `hintAns` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `password` (`password`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admins`
--
ALTER TABLE `admins`
  ADD CONSTRAINT `fkad` FOREIGN KEY (`id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `marks`
--
ALTER TABLE `marks`
  ADD CONSTRAINT `fkresult` FOREIGN KEY (`std_id`) REFERENCES `students` (`std_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `results`
--
ALTER TABLE `results`
  ADD CONSTRAINT `fkrs` FOREIGN KEY (`std_id`) REFERENCES `students` (`std_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `staff`
--
ALTER TABLE `staff`
  ADD CONSTRAINT `fkst` FOREIGN KEY (`id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `fks` FOREIGN KEY (`id`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `subjects`
--
ALTER TABLE `subjects`
  ADD CONSTRAINT `fkstf` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`staff_id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
