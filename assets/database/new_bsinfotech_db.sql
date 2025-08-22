-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 10, 2024 at 10:26 AM
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
-- Database: `bsinfotech_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `account_verification`
--

CREATE TABLE `account_verification` (
  `verification_id` bigint(250) NOT NULL,
  `user_id` bigint(250) NOT NULL,
  `verification_code` text NOT NULL,
  `is_verified` varchar(150) NOT NULL DEFAULT 'false',
  `datetime_added` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account_verification`
--

INSERT INTO `account_verification` (`verification_id`, `user_id`, `verification_code`, `is_verified`, `datetime_added`) VALUES
(204519, 623558587, '245324', 'true', '2024-11-10 16:51:26'),
(801706, 623558587, '424866', 'true', '2024-11-08 18:23:00'),
(973717, 623558587, '692705', 'true', '2024-11-09 01:04:24');

-- --------------------------------------------------------

--
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
  `schedule_id` bigint(250) NOT NULL,
  `subject_id` bigint(250) NOT NULL,
  `section` varchar(200) NOT NULL,
  `room` varchar(200) NOT NULL,
  `week_days` text NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `is_archived` tinyint(1) NOT NULL DEFAULT 0,
  `datetime_added` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subject_informations`
--

CREATE TABLE `subject_informations` (
  `subject_id` bigint(250) NOT NULL,
  `subject_code` varchar(150) NOT NULL,
  `subject_name` text NOT NULL,
  `school_year` varchar(150) NOT NULL,
  `school_semester` varchar(150) NOT NULL,
  `instructor_name` text NOT NULL,
  `has_lab` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subject_informations`
--

INSERT INTO `subject_informations` (`subject_id`, `subject_code`, `subject_name`, `school_year`, `school_semester`, `instructor_name`, `has_lab`) VALUES
(1, 'PLF 101', 'Program Logic Formulation', '1st Year', '1st Semester', '', 0),
(2, 'CC 101', 'Introduction to Computing', '1st Year', '1st Semester', '', 1),
(3, 'FILN 1', 'Kontekstwalisadong Komunikasyon sa Filipino', '1st Year', '1st Semester', '', 0),
(4, 'GEC 5', 'Purposive Communication', '1st Year', '1st Semester', '', 0),
(5, 'GEC 4', 'Mathematics in the Modern World', '1st Year', '1st Semester', '', 0),
(6, 'PEN 1', 'Fitness and Recreational Outdoor Activities', '1st Year', '1st Semester', '', 0),
(7, 'GEC 8', 'Ethics', '1st Year', '1st Semester', '', 0),
(8, 'NSTP 1', 'National Service Training Program I', '1st Year', '1st Semester', '', 0),
(9, 'NSTP 1R', 'Reserve Officers\' Training Corps I', '1st Year', '1st Semester', '', 0),
(10, 'NSTP 1L', 'Literacy Training Service I', '1st Year', '1st Semester', '', 0),
(11, 'NSTP 1C', 'Civic Welfare Training Service I', '1st Year', '1st Semester', '', 0),
(12, 'NSTP 2A', 'National Service Training Program 2', '1st Year', '2nd Semester', '', 0),
(13, 'ITES 102', 'Computer Hardware System', '1st Year', '2nd Semester', '', 1),
(14, 'CC 102', 'Computer Programming I', '1st Year', '2nd Semester', '', 0),
(15, 'FILN 2', 'Sosyedad at Literatura / Panitikang Panlipunan', '1st Year', '2nd Semester', '', 0),
(16, 'HCI 101', 'Introduction to Human Computer Interaction', '1st Year', '2nd Semester', '', 1),
(17, 'MS 101', 'Discrete Mathematics', '1st Year', '2nd Semester', '', 0),
(18, 'GEC 1', 'Understanding the Self', '1st Year', '2nd Semester', '', 0),
(19, 'PEN 2', 'Philippine Folkdances', '1st Year', '2nd Semester', '', 0),
(20, 'NSTP 2C', 'Civic Welfare Training Service II', '1st Year', '2nd Semester', '', 0),
(21, 'NSTP 2L', 'Literacy Training Service II', '1st Year', '2nd Semester', '', 0),
(22, 'NSTP 2R', 'Reserve Officers\' Training Corps II', '1st Year', '2nd Semester', '', 0),
(23, 'PEN 3', 'Individual and Dual Sports', '2nd Year', '1st Semester', '', 0),
(24, 'GEE 1', 'Environmental Science', '2nd Year', '1st Semester', '', 0),
(25, 'GEC 3', 'The Contemporary World', '2nd Year', '1st Semester', '', 0),
(26, 'GEC 2', 'Reading in the Philippines History', '2nd Year', '1st Semester', '', 0),
(27, 'GEC 7', 'Science, Technology and Society', '2nd Year', '1st Semester', '', 0),
(28, 'GEM', 'The Life and Works of Rizal', '2nd Year', '1st Semester', '', 0),
(29, 'CC 103', 'Computer Programming II', '2nd Year', '1st Semester', '', 1),
(30, 'CC 105', 'Information Management', '2nd Year', '2nd Semester', '', 1),
(31, 'PEN 4', 'Team Sports', '2nd Year', '2nd Semester', '', 0),
(32, 'GEC 6', 'Art Appreciation', '2nd Year', '2nd Semester', '', 0),
(33, 'GEE 7', 'Gender and Society', '2nd Year', '2nd Semester', '', 0),
(34, 'GEE 14', 'TechnoEntrepreneurship', '2nd Year', '2nd Semester', '', 0),
(35, 'PF 101', 'Object Oriented Programming', '2nd Year', '2nd Semester', '', 1),
(36, 'CC 104', 'Data Structures and Algorithms', '2nd Year', '2nd Semester', '', 1),
(37, 'FILN 3', 'SineSosyedad/Pelikulang Panlipunan', '3rd Year', '1st Semester', '', 0),
(38, 'PF 102', 'Event Driven Programming', '3rd Year', '1st Semester', '', 1),
(39, 'PT 101', 'Platform Technologies', '3rd Year', '1st Semester', '', 1),
(40, 'ENN 1', 'Scientific and Technical Writing', '3rd Year', '1st Semester', '', 0),
(41, 'GDDAT', 'Game Development and Digital Animation Technology', '3rd Year', '1st Semester', '', 1),
(42, 'IM 101', 'Advance Database Systems', '3rd Year', '1st Semester', '', 1),
(43, 'IAS 101', 'Information Assurance and Security', '3rd Year', '2nd Semester', '', 1),
(44, 'CAP 101', 'Capstone Project and Research I', '3rd Year', '2nd Semester', '', 0),
(45, 'NET 101', 'Networking I', '3rd Year', '2nd Semester', '', 1),
(46, 'CC 106', 'Applications Development and Emerging Technologies', '3rd Year', '2nd Semester', '', 1),
(47, 'SIA 101', 'Systems Integration and Architecture I', '3rd Year', '2nd Semester', '', 0),
(48, 'MS 102', 'Quantitative Methods', '3rd Year', 'Summer/Mid-Yr', '', 0),
(49, 'NET 102', 'Networking II', '4th Year', '1st Semester', '', 1),
(50, 'SP 101', 'Social and Professional Issues', '4th Year', '1st Semester', '', 0),
(51, 'FLNG', 'Foreign Language', '4th Year', '1st Semester', '', 0),
(52, 'CAP 102', 'Capstone Project and Research II', '4th Year', '1st Semester', '', 0),
(53, 'SA 101', 'Systems Administration and Maintenance', '4th Year', '1st Semester', '', 1),
(54, 'PRAC 101', 'Practicum (600 hours)', '4th Year', '2nd Semester', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users_accounts`
--

CREATE TABLE `users_accounts` (
  `user_id` bigint(250) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `user_email` varchar(200) NOT NULL,
  `user_password` varchar(200) NOT NULL,
  `user_type` varchar(100) NOT NULL DEFAULT 'user',
  `user_status` varchar(100) NOT NULL DEFAULT 'active',
  `date_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_accounts`
--

INSERT INTO `users_accounts` (`user_id`, `user_name`, `user_email`, `user_password`, `user_type`, `user_status`, `date_created`) VALUES
(623558587, 'Jose David', 'socmed.joseph00@gmail.com', 'YWRtaW4=', 'user', 'active', '2024-07-26 19:45:56');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account_verification`
--
ALTER TABLE `account_verification`
  ADD PRIMARY KEY (`verification_id`);

--
-- Indexes for table `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`schedule_id`);

--
-- Indexes for table `subject_informations`
--
ALTER TABLE `subject_informations`
  ADD PRIMARY KEY (`subject_id`);

--
-- Indexes for table `users_accounts`
--
ALTER TABLE `users_accounts`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account_verification`
--
ALTER TABLE `account_verification`
  MODIFY `verification_id` bigint(250) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=988337;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `schedule_id` bigint(250) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=999077;

--
-- AUTO_INCREMENT for table `subject_informations`
--
ALTER TABLE `subject_informations`
  MODIFY `subject_id` bigint(250) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `users_accounts`
--
ALTER TABLE `users_accounts`
  MODIFY `user_id` bigint(250) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=623558588;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
