-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 09, 2019 at 09:16 AM
-- Server version: 10.1.36-MariaDB
-- PHP Version: 7.2.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `connectyou`
--

-- --------------------------------------------------------

--
-- Table structure for table `university`
--

CREATE TABLE `university` (
  `ID` int(11) NOT NULL,
  `name` varchar(256) NOT NULL,
  `logo` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `university`
--

INSERT INTO `university` (`ID`, `name`, `logo`) VALUES
(1, 'University of Bath', 'university_of_bath.png'),
(2, 'University of Cambridge', 'university_of_cambridge.png'),
(3, 'University of Oxford', 'university_of_oxford.png'),
(4, 'University College London', 'university_college_london.png'),
(5, 'Imperial College London', 'imperial_college_london.png'),
(6, 'University of Birmingham', 'university_of_birmingham.png'),
(7, 'University of Bristol', 'university_of_bristol.png'),
(8, 'University of Aberdeen', 'university_of_aberdeen.png'),
(9, 'Abertay University', 'abertay_university.png'),
(10, 'Aberystwyth University', 'aberystwyth_university.png'),
(11, 'Anglia Ruskin University', 'anglia_ruskin_university.png'),
(12, 'Arden University', 'arden_university.png'),
(13, 'Aston University', 'aston_university.png'),
(14, 'Bangor University', 'bangor_university.png'),
(15, 'Bath Spa University', 'bath_spa_university.png'),
(16, 'University of Bedfordshire', 'university_of_bedfordshire.png\r\n'),
(17, 'Birmingham City University\r\n', 'birmingham_city_university.png'),
(18, 'University College Birmingham', 'university_college_birmingham.png'),
(19, 'Bishop Grosseteste University', 'bishop_grosseteste_university.png\r\n'),
(20, 'University of Bolton\r\n', 'university_of_bolton.png'),
(21, 'Arts University Bournemouth', 'arts_university_bournemouth.png'),
(22, 'Bournemouth University\r\n', 'bournemouth_university.png'),
(23, 'BPP University\r\n', 'BPP_university.png\r\n'),
(24, 'University of Bradford\r\n', 'university_of_bradford.png\r\n'),
(25, 'University of Brighton', 'university_of_brighton.png\r\n'),
(26, 'Brunel University London\r\n', 'brunel_university_london.png\r\n'),
(27, 'University of Buckingham\r\n', 'university_of_buckingham.png\r\n'),
(28, 'Buckinghamshire New University\r\n', 'buckinghamshire_new_university.png'),
(29, 'Canterbury Christ Church University', 'canterbury_christ_church_university.png'),
(30, 'Cardiff Metropolitan University\r\n', 'cardiff_metropolitan_university.png\r\n'),
(31, 'Cardiff University', 'cardiff_university.png'),
(32, 'University of Chester', 'university_of_chester.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `university`
--
ALTER TABLE `university`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `university`
--
ALTER TABLE `university`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
