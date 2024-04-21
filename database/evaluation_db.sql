-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 21, 2024 at 06:22 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `evaluation_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `academic_list`
--

CREATE TABLE `academic_list` (
  `id` int(30) NOT NULL,
  `academic_id` int(30) NOT NULL,
  `year` text NOT NULL,
  `semester` text NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `status` int(1) NOT NULL DEFAULT 0 COMMENT '0=Pending,1=Start,2=Closed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `academic_list`
--

INSERT INTO `academic_list` (`id`, `academic_id`, `year`, `semester`, `is_default`, `status`) VALUES
(1, 1, '2023-2024', 'Odd', 0, 1),
(2, 2, '2023-2024', 'Even', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `class_list`
--

CREATE TABLE `class_list` (
  `id` int(30) NOT NULL,
  `curriculum` text NOT NULL,
  `level` text NOT NULL,
  `section` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class_list`
--

INSERT INTO `class_list` (`id`, `curriculum`, `level`, `section`) VALUES
(1, 'IT', '2', 'A'),
(2, 'IT', '2', 'B'),
(3, 'IT', '3', 'A'),
(4, 'IT', '3', 'B'),
(5, 'IT', '4', 'A'),
(6, 'IT', '4', 'B');

-- --------------------------------------------------------

--
-- Table structure for table `criteria_list`
--

CREATE TABLE `criteria_list` (
  `id` int(30) NOT NULL,
  `criteria` text NOT NULL,
  `order_by` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `criteria_list`
--

INSERT INTO `criteria_list` (`id`, `criteria`, `order_by`) VALUES
(1, 'Course', 0),
(2, 'Test', 1);

-- --------------------------------------------------------

--
-- Table structure for table `evaluation_answers`
--

CREATE TABLE `evaluation_answers` (
  `evaluation_id` int(30) NOT NULL,
  `question_id` int(30) NOT NULL,
  `rate` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `evaluation_answers`
--

INSERT INTO `evaluation_answers` (`evaluation_id`, `question_id`, `rate`) VALUES
(3, 1, 5),
(3, 2, 5),
(3, 3, 5),
(3, 4, 5),
(3, 5, 5),
(3, 6, 5),
(3, 7, 5),
(3, 8, 5),
(3, 9, 5),
(3, 10, 5),
(1, 1, 1),
(1, 2, 2),
(1, 3, 3),
(1, 4, 4),
(1, 5, 5),
(2, 1, 1),
(2, 2, 2),
(2, 3, 3),
(2, 4, 4),
(2, 5, 5),
(2, 6, 1),
(2, 7, 2),
(2, 8, 3),
(2, 9, 4),
(2, 10, 5),
(3, 1, 1),
(3, 2, 2),
(3, 3, 3),
(3, 4, 4),
(3, 5, 5),
(3, 6, 1),
(3, 7, 2),
(3, 8, 3),
(3, 9, 4),
(3, 10, 5),
(4, 1, 1),
(4, 2, 2),
(4, 3, 3),
(4, 4, 4),
(4, 5, 5),
(4, 6, 1),
(4, 7, 2),
(4, 8, 3),
(4, 9, 4),
(4, 10, 5);

-- --------------------------------------------------------

--
-- Table structure for table `evaluation_list`
--

CREATE TABLE `evaluation_list` (
  `evaluation_id` int(30) NOT NULL,
  `academic_id` int(30) NOT NULL,
  `class_id` int(30) NOT NULL,
  `student_id` int(30) NOT NULL,
  `subject_id` int(30) NOT NULL,
  `faculty_id` int(30) NOT NULL,
  `restriction_id` int(30) NOT NULL,
  `date_taken` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `evaluation_list`
--

INSERT INTO `evaluation_list` (`evaluation_id`, `academic_id`, `class_id`, `student_id`, `subject_id`, `faculty_id`, `restriction_id`, `date_taken`) VALUES
(4, 2, 5, 1, 1, 1, 9, '2024-04-21 21:47:38');

-- --------------------------------------------------------

--
-- Table structure for table `faculty_list`
--

CREATE TABLE `faculty_list` (
  `id` int(30) NOT NULL,
  `school_id` varchar(100) NOT NULL,
  `firstname` varchar(200) NOT NULL,
  `lastname` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` text NOT NULL,
  `avatar` text NOT NULL DEFAULT 'no-image-available.png',
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faculty_list`
--

INSERT INTO `faculty_list` (`id`, `school_id`, `firstname`, `lastname`, `email`, `password`, `avatar`, `date_created`) VALUES
(1, '101', 'T', '1', '101@medicaps.ac.in', '634555a8c3b6790128207bf0e5f231e7', 'no-image-available.png', '2024-02-21 14:21:36'),
(2, '102', 'T', '2', '102@medicaps.ac.in', '634555a8c3b6790128207bf0e5f231e7', 'no-image-available.png', '2024-02-21 14:22:11'),
(3, '103', 'T', '3', '103@medicaps.ac.in', '6974ce5ac660610b44d9b9fed0ff9548', '', '0000-00-00 00:00:00'),
(4, '104', 'T', '4', '104@medicaps.ac.in', 'c9e1074f5b3f9fc8ea15d152add07294', '', '0000-00-00 00:00:00'),
(5, '105', 'T', '5', '105@medicaps.ac.in', '65b9eea6e1cc6bb9f0cd2a47751a186f', '', '0000-00-00 00:00:00'),
(6, '106', 'T', '6', '106@medicaps.ac.in', 'f0935e4cd5920aa6c7c996a5ee53a70f', '', '0000-00-00 00:00:00'),
(7, '107', 'T', '7', '107@medicaps.ac.in', 'a97da629b098b75c294dffdc3e463904', '', '0000-00-00 00:00:00'),
(8, '108', 'T', '8', '108@medicaps.ac.in', 'a3c65c2974270fd093ee8a9bf8ae7d0b', '', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `question_list`
--

CREATE TABLE `question_list` (
  `id` int(30) NOT NULL,
  `academic_id` int(30) NOT NULL,
  `question` text NOT NULL,
  `order_by` int(30) NOT NULL,
  `criteria_id` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `question_list`
--

INSERT INTO `question_list` (`id`, `academic_id`, `question`, `order_by`, `criteria_id`) VALUES
(1, 2, 'Que 1', 0, 1),
(2, 2, 'Que 2', 1, 1),
(3, 2, 'Que 3', 2, 1),
(4, 2, 'Que 4', 3, 1),
(5, 2, 'Que 5', 4, 1),
(6, 2, 'Que 1', 5, 2),
(7, 2, 'Que 2', 6, 2),
(8, 2, 'Que 3', 7, 2),
(9, 2, 'Que 4', 8, 2),
(10, 2, 'Que 5', 9, 2),
(11, 1, 'Que 1', 0, 1),
(12, 1, 'Que 2', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `restriction_list`
--

CREATE TABLE `restriction_list` (
  `id` int(30) NOT NULL,
  `academic_id` int(30) NOT NULL,
  `faculty_id` int(30) NOT NULL,
  `class_id` int(30) NOT NULL,
  `subject_id` int(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `restriction_list`
--

INSERT INTO `restriction_list` (`id`, `academic_id`, `faculty_id`, `class_id`, `subject_id`) VALUES
(1, 3, 2, 7, 4),
(2, 3, 2, 8, 5),
(3, 3, 3, 7, 5),
(4, 3, 3, 8, 7),
(9, 2, 1, 5, 1),
(10, 2, 1, 6, 4),
(11, 2, 2, 5, 4),
(12, 2, 2, 6, 1),
(14, 2, 3, 5, 9),
(15, 1, 1, 5, 1);

-- --------------------------------------------------------

--
-- Table structure for table `student_list`
--

CREATE TABLE `student_list` (
  `id` int(30) NOT NULL,
  `school_id` varchar(100) NOT NULL,
  `firstname` varchar(200) NOT NULL,
  `lastname` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` text NOT NULL,
  `class_id` int(30) NOT NULL,
  `avatar` text NOT NULL DEFAULT 'no-image-available.png',
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_list`
--

INSERT INTO `student_list` (`id`, `school_id`, `firstname`, `lastname`, `email`, `password`, `class_id`, `avatar`, `date_created`) VALUES
(1, 'EN20IT301003', 'Aastha Raj', 'Singh', 'EN20IT301003@medicaps.ac.in', '634555a8c3b6790128207bf0e5f231e7', 5, '1713556920_breno-machado-in9-n0JwgZ0-unsplash.jpg', '2024-02-25 14:11:50'),
(2, 'EN20IT301001', 'Aaditya', 'Kumar', 'EN20IT301001@medicaps.ac.in', '634555a8c3b6790128207bf0e5f231e7', 5, 'no-image-available.png', '2024-02-25 15:49:49'),
(3, 'EN21IT301001', 'Demo', 'Demo', 'EN21IT301001@medicaps.ac.in', '36396b644975f471b04d0a5f0027a94b', 3, 'no-image-available.png', '2024-02-26 15:32:22'),
(4, 'EN20IT301004', 'Aayush', 'Arora', 'EN20IT301004@medicaps.ac.in', '634555a8c3b6790128207bf0e5f231e7', 5, 'no-image-available.png', '2024-02-26 15:33:25'),
(5, 'EN20IT301017', 'Arish', 'sahu', 'EN20T301017@medicaps.ac.in', '634555a8c3b6790128207bf0e5f231e7', 5, 'no-image-available.png', '2024-03-12 10:06:04'),
(7, 'EN20IT301007', 'Abhishek', 'Kushwaha', 'EN20IT301007@medicaps.ac.in', '634555a8c3b6790128207bf0e5f231e7', 5, 'no-image-available.png', '2024-04-21 12:51:20');

-- --------------------------------------------------------

--
-- Table structure for table `subject_list`
--

CREATE TABLE `subject_list` (
  `id` int(30) NOT NULL,
  `code` varchar(50) NOT NULL,
  `subject` text NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subject_list`
--

INSERT INTO `subject_list` (`id`, `code`, `subject`, `description`) VALUES
(1, 'IT02', 'Subject 02', ''),
(2, 'IT09', 'Subject 09', ''),
(3, 'IT04', 'Subject 04', ''),
(4, 'IT01', 'Subject 01', ''),
(5, 'IT08', 'Subject 08', ''),
(6, 'IT10', 'Subject 10', ''),
(7, 'IT07', 'Subject 07', ''),
(8, 'IT05', 'Subject 05', ''),
(9, 'IT03', 'Subject 03', ''),
(10, 'IT06', 'Subject 06', '');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` int(30) NOT NULL,
  `name` text NOT NULL,
  `email` varchar(200) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `cover_img` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`id`, `name`, `email`, `contact`, `address`, `cover_img`) VALUES
(1, 'Course Evaluation System', 'Akshaybillore92@gmail.com', '+91 8462069215', 'Indore, Madhya Pradesh, India', '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(30) NOT NULL,
  `firstname` varchar(200) NOT NULL,
  `lastname` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` text NOT NULL,
  `avatar` text NOT NULL DEFAULT 'no-image-available.png',
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `remember_token` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `email`, `password`, `avatar`, `date_created`, `remember_token`) VALUES
(1, 'Administrator', 'IT', 'ITAdmin@medicaps.ac.in', '634555a8c3b6790128207bf0e5f231e7', '1607135820_avatar.jpg', '2020-11-26 10:57:04', NULL),
(2, 'Akshay', 'Billore', 'Akshaybillore92@gmail.com', '634555a8c3b6790128207bf0e5f231e7', 'no-image-available.png', '2024-02-20 18:54:42', NULL),
(3, 'Dr. Prashant', 'Panse', 'HodIT@medicaps.ac.in', '634555a8c3b6790128207bf0e5f231e7', 'no-image-available.png', '2024-02-22 16:38:51', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `academic_list`
--
ALTER TABLE `academic_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `class_list`
--
ALTER TABLE `class_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `criteria_list`
--
ALTER TABLE `criteria_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `evaluation_list`
--
ALTER TABLE `evaluation_list`
  ADD PRIMARY KEY (`evaluation_id`);

--
-- Indexes for table `faculty_list`
--
ALTER TABLE `faculty_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `question_list`
--
ALTER TABLE `question_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `restriction_list`
--
ALTER TABLE `restriction_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_list`
--
ALTER TABLE `student_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subject_list`
--
ALTER TABLE `subject_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
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
-- AUTO_INCREMENT for table `academic_list`
--
ALTER TABLE `academic_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `class_list`
--
ALTER TABLE `class_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `criteria_list`
--
ALTER TABLE `criteria_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `evaluation_list`
--
ALTER TABLE `evaluation_list`
  MODIFY `evaluation_id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `faculty_list`
--
ALTER TABLE `faculty_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `question_list`
--
ALTER TABLE `question_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `restriction_list`
--
ALTER TABLE `restriction_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `student_list`
--
ALTER TABLE `student_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `subject_list`
--
ALTER TABLE `subject_list`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
