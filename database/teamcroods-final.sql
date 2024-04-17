-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 12, 2023 at 09:31 PM
-- Server version: 8.0.31
-- PHP Version: 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ojt_monitoring`
--
CREATE DATABASE ojt_monitoring;
USE ojt_monitoring;

-- --------------------------------------------------------

--
-- Table structure for table `advisor`
--

DROP TABLE IF EXISTS `advisor`;
CREATE TABLE IF NOT EXISTS `advisor` (
  `advisorId` int NOT NULL AUTO_INCREMENT,
  `firstName` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastName` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`advisorId`)
) ENGINE=InnoDB AUTO_INCREMENT=333006 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `advisor`
--

INSERT INTO `advisor` (`advisorId`, `firstName`, `lastName`, `email`, `contact`) VALUES
(333001, 'Adriano', 'Ramos', 'advisor1@gmail.com', '123-456-7890'),
(333002, 'Lorena', 'Ackerman', 'advisor2@gmail.com', '234-567-8901'),
(333003, 'Gabriel', 'Silva', 'advisor3@gmail.com', '345-678-9012'),
(333004, 'Daniela', 'Ortega', 'advisor4@gmail.com', '456-789-0123'),
(333005, 'Mateo', 'Yaeger', 'advisor5@gmail.com', '567-890-1234');

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

DROP TABLE IF EXISTS `announcements`;
CREATE TABLE IF NOT EXISTS `announcements` (
  `announcementId` int NOT NULL AUTO_INCREMENT,
  `teacherId` int NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `datePosted` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`announcementId`),
  KEY `announcements_advisorId` (`teacherId`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`announcementId`, `teacherId`, `title`, `message`, `datePosted`) VALUES
(3, 111011, 'Teacher Transfer', 'To all students under me, transfer to Mrs. Elena Perez', '2023-12-12 21:55:45'),
(4, 111015, 'Urgent Notification ', 'Mr. Reyes, please see me after work. We have important business matters to discuss.', '2023-12-11 22:05:31'),
(5, 111012, 'HEALTH NOTICE', 'With the ongoing COVID situation, remember: No mask, no entry. Prioritize safety. Thank you.', '2023-12-06 22:05:31'),
(6, 111013, 'Missing Requirements', 'Those with incomplete/under review requirements, please come to the office ASAP. Let\'s address and resolve any outstanding issues. Thank you.', '2023-12-11 22:05:31'),
(7, 111014, 'Promotion Chance', 'Promotion chances are favorable for those with zero demerits. Keep up the good work and maintain a clean record. Best of luck!', '2023-12-11 22:05:31');

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

DROP TABLE IF EXISTS `company`;
CREATE TABLE IF NOT EXISTS `company` (
  `companyID` int NOT NULL AUTO_INCREMENT,
  `companyName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `companyAddress` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `website` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`companyID`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `company`
--

INSERT INTO `company` (`companyID`, `companyName`, `companyAddress`, `website`, `contact`) VALUES
(1, 'Accenture Philippines', '6750 Ayala Avenue, Makati City', 'www.accenture.com/ph-en', '02-988-7000'),
(2, 'IBM Philippines', '32nd Street, Bonifacio Global City, Taguig', 'www.ibm.com/ph-en', '02-995-6400'),
(3, 'Tata Consultancy Services (TCS)', '28th Street, Bonifacio Global City, Taguig', 'www.tcs.com/ph-en', '02-886-9999'),
(4, 'Tech Mahindra Philippines', '9th Avenue, Bonifacio Global City, Taguig', 'www.techmahindra.com/ph-en', '02-792-9000'),
(5, 'Infosys BPM Philippines', 'Uptown Parade, Bonifacio Global City, Taguig', 'www.infosysbpm.com/ph', '02-883-7000');

-- --------------------------------------------------------

--
-- Table structure for table `internship`
--

DROP TABLE IF EXISTS `internship`;
CREATE TABLE IF NOT EXISTS `internship` (
  `internshipId` int NOT NULL AUTO_INCREMENT,
  `studentId` int NOT NULL,
  `companyId` int NOT NULL,
  `teacherId` int NOT NULL,
  `advisorId` int NOT NULL,
  `dateStarted` date NOT NULL,
  `dateEnded` date NOT NULL,
  PRIMARY KEY (`internshipId`),
  KEY `internship_companyId` (`companyId`),
  KEY `internship_advisorId` (`advisorId`),
  KEY `internship_teacherId` (`teacherId`),
  KEY `studentId` (`studentId`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `internship`
--

INSERT INTO `internship` (`internshipId`, `studentId`, `companyId`, `teacherId`, `advisorId`, `dateStarted`, `dateEnded`) VALUES
(1, 222001, 1, 111011, 333001, '2023-12-05', '2024-05-08'),
(2, 222002, 2, 111012, 333002, '2023-11-23', '2024-04-24'),
(3, 222003, 3, 111013, 333003, '2023-11-17', '2024-04-15'),
(4, 222004, 4, 111014, 333004, '2023-12-06', '2024-05-17'),
(5, 222005, 5, 111015, 333005, '2023-11-24', '2024-04-24'),
(6, 222006, 5, 111015, 333005, '2023-11-30', '2024-05-01'),
(7, 222007, 4, 111014, 333004, '2023-12-02', '2024-05-16'),
(8, 222008, 3, 111013, 333003, '2023-12-06', '2024-05-06'),
(9, 222009, 2, 111012, 333002, '2023-12-04', '2024-05-08'),
(10, 222010, 1, 111011, 333001, '2023-12-02', '2024-05-13');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

DROP TABLE IF EXISTS `reports`;
CREATE TABLE IF NOT EXISTS `reports` (
  `reportId` int NOT NULL AUTO_INCREMENT,
  `studentId` int NOT NULL,
  `weekNum` int NOT NULL,
  `hoursWorked` int NOT NULL,
  `demerit` int DEFAULT NULL,
  `reportFile` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `submittedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`reportId`),
  KEY `userId_reports` (`studentId`),
  KEY `report_status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`reportId`, `studentId`, `weekNum`, `hoursWorked`, `demerit`, `reportFile`, `submittedAt`, `comment`, `status`) VALUES
(7, 222006, 2, 36, NULL, '../public/uploads/sofia_torres_w2_report.pdf', '2023-12-07 05:43:19', 'Can use a little bit of improvement but good work nonetheless', 2),
(8, 222003, 1, 37, NULL, '../public/uploads/jose_lopez_w1_report.pdf', '2023-11-12 11:15:39', 'Good Job!', 2),
(9, 222003, 2, 43, NULL, '../public/uploads/jose_lopez_w2_report.pdf', '2023-11-20 07:30:00', '', 1),
(10, 222002, 1, 30, 1, '../public/uploads/maria_gonzales_w1_report.pdf', '2023-11-30 16:50:00', 'Keep up the good work but there is a need for increased commitment to meeting the required work hours.', 2),
(11, 222001, 1, 36, NULL, '../public/uploads/juan_santos_w1_report.pdf', '2023-12-10 00:00:00', 'Great job on completing week 1!', 2),
(12, 222002, 2, 35, NULL, '../public/uploads/maria_gonzales_w2_report.pdf', '2023-12-11 17:48:00', 'Meeting expectations—well done!', 2),
(13, 222003, 3, 35, NULL, '../public/uploads/jose_lopez_w3_report.pdf', '2023-12-01 02:45:00', 'Appreciate the honesty in Week 3.', 2),
(14, 222004, 1, 18, 1, '../public/uploads/ana_cruz_w1_report.pdf', '2023-12-09 03:15:00', 'Let\'s aim for more hours next week. Keep it up!', 2),
(15, 222005, 3, 36, NULL, '../public/uploads/ramon_reyes_w3_report.pdf', '2023-12-13 04:00:00', 'Great job on submitting the report for Week 3! Could you also provide updates on your activities for the first two weeks? ', 2),
(16, 222006, 3, 20, NULL, '../public/uploads/sofia_torres_w3_report.pdf', '2023-12-13 12:23:34', '', 1),
(17, 222007, 1, 29, 1, '../public/uploads/miguel_delacruz_w1_report.pdf', '2023-12-07 22:00:00', 'Fair work. Could do some improvement.', 2),
(18, 222008, 1, 13, NULL, '../public/uploads/carmen_rivera_w1_report.pdf', '2023-12-11 02:15:53', '', 1),
(19, 222009, 1, 38, NULL, '../public/uploads/pedro_santiago_w1_report.pdf', '2023-05-17 12:23:34', 'Good work!', 2),
(20, 222010, 1, 16, NULL, '../public/uploads/bella_fernando_w1_reports.pdf', '2023-12-11 13:27:50', '', 1),
(23, 222005, 2, 33, NULL, '', '2023-12-07 02:30:00', '', 0),
(24, 222005, 1, 26, NULL, '', '2023-12-01 03:32:00', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `requirements`
--

DROP TABLE IF EXISTS `requirements`;
CREATE TABLE IF NOT EXISTS `requirements` (
  `requirementId` int NOT NULL AUTO_INCREMENT,
  `studentId` int NOT NULL,
  `jobResume` tinyint NOT NULL DEFAULT '0',
  `curriVitae` tinyint NOT NULL DEFAULT '0',
  `coverLetter` tinyint NOT NULL DEFAULT '0',
  `moa` tinyint(1) NOT NULL DEFAULT '0',
  `medCert` tinyint(1) NOT NULL DEFAULT '0',
  `waiver` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`requirementId`),
  KEY `studentId_requirements` (`studentId`),
  KEY `moc_status` (`moa`),
  KEY `medCert_status` (`medCert`),
  KEY `waiver_status` (`waiver`),
  KEY `jobResume_status` (`jobResume`),
  KEY `curriVitae_status` (`curriVitae`),
  KEY `coverLetter_status` (`coverLetter`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `requirements`
--

INSERT INTO `requirements` (`requirementId`, `studentId`, `jobResume`, `curriVitae`, `coverLetter`, `moa`, `medCert`, `waiver`) VALUES
(1, 222001, 2, 2, 2, 2, 1, 2),
(11, 222002, 2, 2, 2, 1, 1, 2),
(12, 222003, 2, 2, 2, 1, 0, 2),
(13, 222004, 2, 1, 1, 0, 1, 1),
(14, 222005, 2, 2, 2, 2, 2, 2),
(15, 222006, 2, 2, 2, 1, 2, 2),
(16, 222007, 2, 1, 1, 1, 2, 2),
(17, 222008, 1, 2, 0, 0, 0, 0),
(18, 222009, 2, 2, 1, 0, 0, 1),
(19, 222010, 0, 1, 1, 2, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `status`
--

DROP TABLE IF EXISTS `status`;
CREATE TABLE IF NOT EXISTS `status` (
  `code` tinyint(1) NOT NULL,
  `status` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `status`
--

INSERT INTO `status` (`code`, `status`) VALUES
(0, 'MISSING'),
(1, 'REVIEWING'),
(2, 'APPROVED');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

DROP TABLE IF EXISTS `student`;
CREATE TABLE IF NOT EXISTS `student` (
  `studentId` int NOT NULL AUTO_INCREMENT,
  `userId` int NOT NULL,
  `firstName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `course` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `classCode` int NOT NULL,
  PRIMARY KEY (`studentId`),
  KEY `userId_student` (`userId`)
) ENGINE=InnoDB AUTO_INCREMENT=222011 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`studentId`, `userId`, `firstName`, `lastName`, `course`, `classCode`) VALUES
(222001, 1, 'Juan', 'Santos', 'BSCS', 9373),
(222002, 2, 'Maria', 'Gonzales', 'BSCS', 9373),
(222003, 3, 'Jose', 'Lopez', 'BSCS', 9374),
(222004, 4, 'Ana', 'Cruz', 'BSCS', 9374),
(222005, 5, 'Ramon', 'Reyes', 'BSCS', 9373),
(222006, 6, 'Sofia', 'Torres', 'BSCS', 9375),
(222007, 7, 'Miguel', 'Dela Cruz', 'BSCS', 9373),
(222008, 8, 'Carmen', 'Rivera', 'BSCS', 9375),
(222009, 9, 'Pedro', 'Santiago', 'BSCS', 9375),
(222010, 10, 'Bella', 'Fernando', 'BSCS', 9374);

-- --------------------------------------------------------

--
-- Table structure for table `teacher`
--

DROP TABLE IF EXISTS `teacher`;
CREATE TABLE IF NOT EXISTS `teacher` (
  `teacherId` int NOT NULL,
  `userId` int NOT NULL,
  `firstName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`teacherId`),
  KEY `userId_advisor` (`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `teacher`
--

INSERT INTO `teacher` (`teacherId`, `userId`, `firstName`, `lastName`) VALUES
(111011, 11, ' Carlos', 'Gomez'),
(111012, 12, 'Elena', 'Perez'),
(111013, 13, 'Luis', 'Ramirez'),
(111014, 14, 'Isabel', 'Gutirrez'),
(111015, 15, 'Antonio', 'Diaz');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `userId` int NOT NULL AUTO_INCREMENT,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` int NOT NULL,
  PRIMARY KEY (`userId`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userId`, `email`, `password`, `type`) VALUES
(1, 'student1@slu.edu.ph', 'student1pass', 1),
(2, 'student2@slu.edu.ph', 'student2pass', 1),
(3, 'student3@slu.edu.ph', 'student3pass', 1),
(4, 'student4@slu.edu.ph', 'student4pass', 1),
(5, 'student5@slu.edu.ph', 'student5pass', 1),
(6, 'student6@slu.edu.ph', 'student6pass', 1),
(7, 'student7@slu.edu.ph', '1', 1),
(8, 'student8@slu.edu.ph', 'student8pass', 1),
(9, 'student9@slu.edu.ph', 'student9pass', 1),
(10, 'student10@slu.edu.ph', '1', 1),
(11, 'teacher1@slu.edu.ph', 'teacher1pass', 2),
(12, 'teacher2@slu.edu.ph', 'teacher2pass', 2),
(13, 'teacher3@slu.edu.ph', ' teacher3pass', 2),
(14, 'teacher4@slu.edu.ph', 'teacher4pass', 2),
(15, 'teacher5@slu.edu.ph', 'teacher5pass', 2),
(16, 'advisor1@gmail.com', 'advisor1pass', 3),
(17, 'advisor2@gmail.com', 'advisor2pass', 3),
(18, 'advisor3@gmail.com', 'advisor3pass', 3),
(19, 'advisor4@gmail.com', 'advisor4pass', 3),
(20, 'advisor5@gmail.com', 'advisor5pass', 3);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `announcements`
--
ALTER TABLE `announcements`
  ADD CONSTRAINT `announcements_teacherId` FOREIGN KEY (`teacherId`) REFERENCES `teacher` (`teacherId`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `internship`
--
ALTER TABLE `internship`
  ADD CONSTRAINT `internship_advisorId` FOREIGN KEY (`advisorId`) REFERENCES `advisor` (`advisorId`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `internship_companyId` FOREIGN KEY (`companyId`) REFERENCES `company` (`companyID`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `internship_studentId` FOREIGN KEY (`studentId`) REFERENCES `student` (`studentId`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `internship_teacherId` FOREIGN KEY (`teacherId`) REFERENCES `teacher` (`teacherId`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `report_status` FOREIGN KEY (`status`) REFERENCES `status` (`code`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `userId_reports` FOREIGN KEY (`studentId`) REFERENCES `student` (`studentId`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `requirements`
--
ALTER TABLE `requirements`
  ADD CONSTRAINT `coverLetter_status` FOREIGN KEY (`coverLetter`) REFERENCES `status` (`code`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `curriVitae_status` FOREIGN KEY (`curriVitae`) REFERENCES `status` (`code`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `jobResume_status` FOREIGN KEY (`jobResume`) REFERENCES `status` (`code`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `medCert_status` FOREIGN KEY (`medCert`) REFERENCES `status` (`code`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `moa_status` FOREIGN KEY (`moa`) REFERENCES `status` (`code`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `studentId_requirements` FOREIGN KEY (`studentId`) REFERENCES `student` (`studentId`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  ADD CONSTRAINT `waiver_status` FOREIGN KEY (`waiver`) REFERENCES `status` (`code`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `userId_student` FOREIGN KEY (`userId`) REFERENCES `user` (`userId`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Constraints for table `teacher`
--
ALTER TABLE `teacher`
  ADD CONSTRAINT `userId_teacherId` FOREIGN KEY (`userId`) REFERENCES `user` (`userId`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
