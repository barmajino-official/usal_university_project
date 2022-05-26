-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 19, 2022 at 07:57 PM
-- Server version: 5.7.33
-- PHP Version: 7.4.19

CREATE Database usal_university;
USE usal_university;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `usal_university`
--

-- --------------------------------------------------------

--
-- Table structure for table `authentication`
--

CREATE TABLE `authentication` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `type` char(1) NOT NULL,
  `created_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `authentication`
--

INSERT INTO `authentication` (`id`, `email`, `password`, `type`, `created_time`) VALUES
(42, 'mohammadsaad@gmail.com', '123456', 'D', '2022-05-13 00:55:22'),
(43, 'hassanjaafar@gmail.com', '123456', 'S', '2022-05-13 00:56:14'),
(44, 'alijaafar.barmajino@gmail.com', '123456', 'D', '2022-05-13 00:56:51');

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE `course` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `major_id` int(11) NOT NULL,
  `created_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`id`, `name`, `major_id`, `created_time`) VALUES
(1, 'EENG304', 1, '2022-05-10 14:50:36'),
(2, 'EENG300', 1, '2022-05-10 14:50:36'),
(3, 'EENG301L', 1, '2022-05-10 14:50:36'),
(4, 'CENG352L', 1, '2022-05-10 14:50:36'),
(5, 'EDU495', 2, '2022-05-10 14:51:37'),
(6, 'EDU435', 2, '2022-05-10 14:51:37'),
(7, 'EDU415', 2, '2022-05-10 14:51:37'),
(8, 'EDU400', 2, '2022-05-10 14:51:37'),
(9, 'EENG495', 3, '2022-05-10 14:52:28'),
(10, 'CENG400L', 3, '2022-05-10 14:52:28'),
(11, 'EENG360', 3, '2022-05-10 14:52:28'),
(12, 'EENG435L', 3, '2022-05-10 14:52:28');

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `id` int(11) NOT NULL,
  `fname` varchar(20) NOT NULL,
  `lname` varchar(20) NOT NULL,
  `authentication_id` int(11) NOT NULL,
  `created_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`id`, `fname`, `lname`, `authentication_id`, `created_time`) VALUES
(13, 'Mohammad', 'Saad', 42, '2022-05-13 00:55:22'),
(14, 'Ali', 'jaafar', 44, '2022-05-13 00:56:51');

-- --------------------------------------------------------

--
-- Table structure for table `doctors_multy_major`
--

CREATE TABLE `doctors_multy_major` (
  `id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `major_id` int(11) NOT NULL,
  `created_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `doctors_multy_major`
--

INSERT INTO `doctors_multy_major` (`id`, `doctor_id`, `major_id`, `created_time`) VALUES
(31, 13, 1, '2022-05-13 00:55:22'),
(33, 14, 2, '2022-05-13 00:56:51');

-- --------------------------------------------------------

--
-- Table structure for table `floor`
--

CREATE TABLE `floor` (
  `id` int(11) NOT NULL,
  `name` varchar(60) NOT NULL,
  `created_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `floor`
--

INSERT INTO `floor` (`id`, `name`, `created_time`) VALUES
(1, '-1', '2022-05-10 10:12:18'),
(2, '-2', '2022-05-10 10:12:18'),
(3, '1', '2022-05-10 10:12:18'),
(4, '2', '2022-05-10 10:12:18');

-- --------------------------------------------------------

--
-- Table structure for table `major`
--

CREATE TABLE `major` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `created_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `major`
--

INSERT INTO `major` (`id`, `name`, `created_time`) VALUES
(1, 'Faculty of Management', '2022-05-10 14:40:40'),
(2, 'Faculty of Education', '2022-05-10 14:40:40'),
(3, 'Faculty of Arts and Sciences', '2022-05-10 14:40:40');

-- --------------------------------------------------------

--
-- Table structure for table `reservation`
--

CREATE TABLE `reservation` (
  `id` int(11) NOT NULL,
  `doctor_id` int(11) NOT NULL,
  `room` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `checkin` time NOT NULL,
  `checkout` time NOT NULL,
  `created_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `reservation`
--

INSERT INTO `reservation` (`id`, `doctor_id`, `room`, `course_id`, `date`, `checkin`, `checkout`, `created_time`) VALUES
(15, 14, 23, 5, '7788-07-07', '17:55:00', '16:52:00', '2022-05-13 00:59:54');

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE `room` (
  `id` int(11) NOT NULL,
  `name` varchar(60) NOT NULL,
  `floor` int(11) NOT NULL,
  `seatnb` int(11) NOT NULL,
  `created_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`id`, `name`, `floor`, `seatnb`, `created_time`) VALUES
(1, 'Lab1', 1, 20, '2022-05-10 10:21:15'),
(2, 'Lab2', 1, 17, '2022-05-10 10:21:15'),
(3, 'Lab3', 1, 25, '2022-05-10 10:21:15'),
(4, 'B1-4', 1, 50, '2022-05-10 10:21:15'),
(5, 'B1-5', 1, 50, '2022-05-10 10:21:15'),
(6, 'B1-6', 1, 50, '2022-05-10 10:21:15'),
(7, 'B2-1', 2, 25, '2022-05-10 10:21:15'),
(8, 'B2-2', 2, 25, '2022-05-10 10:21:15'),
(9, 'B2-3', 2, 30, '2022-05-10 10:21:15'),
(10, 'B2-5', 2, 40, '2022-05-10 10:21:15'),
(11, 'B2-6', 2, 50, '2022-05-10 10:21:15'),
(12, 'B2-7', 2, 40, '2022-05-10 10:21:15'),
(13, 'CR1-1', 3, 20, '2022-05-10 10:21:15'),
(14, 'CR1-2', 3, 30, '2022-05-10 10:21:15'),
(15, 'CR1-3', 3, 25, '2022-05-10 10:21:15'),
(16, 'CR1-4', 3, 40, '2022-05-10 10:21:15'),
(17, 'CR1-5', 3, 40, '2022-05-10 10:21:15'),
(18, 'CR1-6', 3, 50, '2022-05-10 10:21:15'),
(19, 'CR2-1', 4, 35, '2022-05-10 10:21:15'),
(20, 'CR2-2', 4, 50, '2022-05-10 10:21:15'),
(21, 'CR2-3', 4, 25, '2022-05-10 10:21:15'),
(22, 'CR2-4', 4, 30, '2022-05-10 10:21:15'),
(23, 'CR2-5', 4, 40, '2022-05-10 10:21:15'),
(24, 'CR2-6', 4, 30, '2022-05-10 10:21:15');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `fname` varchar(20) NOT NULL,
  `lname` varchar(20) NOT NULL,
  `major` int(11) NOT NULL,
  `authentication_id` int(11) NOT NULL,
  `created_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `fname`, `lname`, `major`, `authentication_id`, `created_time`) VALUES
(8, 'Hassan', 'jaafar', 1, 43, '2022-05-13 00:56:14');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `authentication`
--
ALTER TABLE `authentication`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`id`),
  ADD KEY `major_course` (`major_id`) USING BTREE;

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dactor_authentication` (`authentication_id`);

--
-- Indexes for table `doctors_multy_major`
--
ALTER TABLE `doctors_multy_major`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doctor_major_doctors` (`doctor_id`),
  ADD KEY `doctor_major_magors` (`major_id`);

--
-- Indexes for table `floor`
--
ALTER TABLE `floor`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `major`
--
ALTER TABLE `major`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `room_reservation` (`room`),
  ADD KEY `dactor_reservation` (`doctor_id`),
  ADD KEY `course_reservation` (`course_id`);

--
-- Indexes for table `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`id`),
  ADD KEY `floor_room` (`floor`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD KEY `students_authentication` (`authentication_id`),
  ADD KEY `major_students` (`major`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `authentication`
--
ALTER TABLE `authentication`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `course`
--
ALTER TABLE `course`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `doctors_multy_major`
--
ALTER TABLE `doctors_multy_major`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `floor`
--
ALTER TABLE `floor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `major`
--
ALTER TABLE `major`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `room`
--
ALTER TABLE `room`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `course`
--
ALTER TABLE `course`
  ADD CONSTRAINT `major_course` FOREIGN KEY (`major_id`) REFERENCES `major` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `doctors`
--
ALTER TABLE `doctors`
  ADD CONSTRAINT `dactor_authentication` FOREIGN KEY (`authentication_id`) REFERENCES `authentication` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `doctors_multy_major`
--
ALTER TABLE `doctors_multy_major`
  ADD CONSTRAINT `doctor_major_doctors` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `doctor_major_magors` FOREIGN KEY (`major_id`) REFERENCES `major` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `course_reservation` FOREIGN KEY (`course_id`) REFERENCES `course` (`id`),
  ADD CONSTRAINT `dactor_reservation` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`),
  ADD CONSTRAINT `room_reservation` FOREIGN KEY (`room`) REFERENCES `room` (`id`);

--
-- Constraints for table `room`
--
ALTER TABLE `room`
  ADD CONSTRAINT `floor_room` FOREIGN KEY (`floor`) REFERENCES `floor` (`id`);

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `major_students` FOREIGN KEY (`major`) REFERENCES `major` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `students_authentication` FOREIGN KEY (`authentication_id`) REFERENCES `authentication` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
