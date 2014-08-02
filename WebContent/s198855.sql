-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 18, 2013 at 11:42 AM
-- Server version: 5.5.31
-- PHP Version: 5.3.10-1ubuntu3.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `s198855`
--

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_U` int(11) NOT NULL,
  `Message` varchar(1024) DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `ID_U` (`ID_U`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `stations`
--

DROP TABLE IF EXISTS `stations`;
CREATE TABLE IF NOT EXISTS `stations` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Station` varchar(64) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `stations`
--

INSERT INTO `stations` (`ID`, `Station`) VALUES
(1, 'Bologna Centrale'),
(2, 'Firenze S. M. Novella'),
(3, 'Milano Centrale'),
(4, 'Roma Termini'),
(5, 'Torino Porta Nuova');

-- --------------------------------------------------------

--
-- Table structure for table `trains`
--

DROP TABLE IF EXISTS `trains`;
CREATE TABLE IF NOT EXISTS `trains` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `TrainNumber` int(4) NOT NULL,
  `IdDepartureStation` int(11) NOT NULL,
  `IdArrivalStation` int(11) NOT NULL,
  `DepartureTime` time NOT NULL,
  `ArrivalTime` time NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `IdDepartureStation` (`IdDepartureStation`),
  KEY `IdArrivalStation` (`IdArrivalStation`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=87 ;

--
-- Dumping data for table `trains`
--

INSERT INTO `trains` (`ID`, `TrainNumber`, `IdDepartureStation`, `IdArrivalStation`, `DepartureTime`, `ArrivalTime`) VALUES
(1, 9517, 5, 4, '08:02:00', '12:35:00'),
(2, 9565, 5, 4, '08:23:00', '12:50:00'),
(3, 9623, 5, 4, '09:40:00', '13:55:00'),
(4, 9723, 5, 3, '11:10:00', '12:50:00'),
(5, 9533, 3, 4, '13:15:00', '16:35:00'),
(6, 9729, 5, 3, '13:10:00', '14:50:00'),
(7, 9541, 3, 4, '15:15:00', '18:35:00'),
(8, 9807, 5, 1, '08:32:00', '11:38:00'),
(9, 9419, 1, 4, '11:53:00', '14:10:00'),
(10, 9807, 5, 1, '08:32:00', '11:38:00'),
(11, 9631, 5, 3, '11:40:00', '12:40:00'),
(12, 9533, 3, 1, '13:15:00', '14:17:00'),
(13, 9513, 3, 2, '08:15:00', '09:55:00'),
(14, 9529, 3, 2, '12:15:00', '13:55:00'),
(15, 9815, 3, 1, '13:35:00', '15:38:00'),
(16, 9435, 1, 2, '15:53:00', '16:30:00'),
(17, 9567, 5, 2, '13:23:00', '16:10:00'),
(18, 9569, 5, 2, '14:23:00', '17:10:00'),
(19, 9561, 2, 4, '09:19:00', '10:50:00'),
(20, 9407, 2, 4, '09:38:00', '11:10:00'),
(21, 9533, 2, 4, '15:04:00', '16:35:00'),
(22, 9451, 2, 4, '20:38:00', '22:05:00'),
(23, 9557, 2, 4, '21:04:00', '22:30:00'),
(24, 9500, 2, 1, '07:00:00', '07:37:00'),
(25, 9502, 2, 1, '07:30:00', '08:07:00'),
(26, 9568, 2, 1, '10:45:00', '11:22:00'),
(27, 9574, 2, 5, '17:45:00', '20:35:00'),
(28, 9576, 2, 5, '19:45:00', '22:35:00'),
(29, 9540, 2, 3, '17:00:00', '18:40:00'),
(30, 9438, 2, 1, '17:30:00', '18:07:00'),
(31, 9644, 1, 3, '18:28:00', '19:37:00'),
(32, 6544, 2, 3, '18:00:00', '19:40:00'),
(33, 9550, 2, 3, '19:00:00', '20:40:00'),
(34, 9550, 4, 2, '17:20:00', '18:51:00'),
(35, 9446, 4, 2, '17:50:00', '19:22:00'),
(36, 9576, 4, 2, '18:05:00', '19:36:00'),
(37, 9552, 4, 2, '18:20:00', '19:51:00'),
(38, 9646, 4, 3, '17:00:00', '20:00:00'),
(39, 9550, 4, 3, '17:20:00', '20:40:00'),
(40, 9650, 4, 3, '18:00:00', '20:55:00'),
(41, 9552, 4, 3, '18:20:00', '21:40:00'),
(42, 9646, 4, 5, '17:00:00', '21:10:00'),
(43, 9576, 4, 5, '18:05:00', '22:35:00'),
(44, 9654, 4, 5, '19:00:00', '23:05:00'),
(45, 9450, 4, 1, '18:50:00', '21:07:00'),
(46, 9558, 4, 1, '19:20:00', '21:35:00'),
(47, 9492, 4, 1, '19:35:00', '21:52:00'),
(48, 9544, 1, 3, '18:38:00', '19:40:00'),
(49, 9826, 1, 3, '19:18:00', '21:25:00'),
(50, 9550, 1, 3, '19:38:00', '20:40:00'),
(51, 9572, 1, 5, '17:23:00', '19:35:00'),
(52, 9824, 1, 5, '18:18:00', '21:47:00'),
(53, 9574, 1, 5, '18:23:00', '20:35:00'),
(54, 9732, 3, 5, '18:10:00', '19:50:00'),
(55, 9740, 3, 5, '20:08:00', '21:50:00'),
(56, 9646, 3, 5, '20:13:00', '21:10:00'),
(57, 9746, 3, 5, '21:10:00', '22:50:00'),
(58, 9654, 3, 5, '22:05:00', '23:05:00'),
(59, 9713, 5, 3, '07:10:00', '08:52:00'),
(60, 9615, 5, 3, '07:40:00', '08:42:00'),
(61, 9517, 5, 3, '08:02:00', '09:02:00'),
(63, 9623, 5, 3, '09:40:00', '10:40:00'),
(65, 9733, 5, 3, '14:10:00', '15:50:00'),
(66, 9737, 5, 3, '15:10:00', '16:50:00'),
(67, 9745, 5, 3, '17:10:00', '18:52:00'),
(69, 9655, 5, 3, '17:40:00', '18:42:00'),
(70, 9567, 5, 3, '19:10:00', '20:50:00'),
(71, 9596, 3, 5, '08:00:00', '09:00:00'),
(72, 9702, 3, 5, '09:10:00', '10:50:00'),
(73, 9610, 3, 5, '11:05:00', '12:05:00'),
(74, 9622, 3, 5, '11:40:00', '13:20:00'),
(75, 9630, 3, 5, '16:05:00', '17:05:00'),
(76, 9728, 3, 5, '17:10:00', '18:50:00'),
(77, 9517, 3, 2, '09:15:00', '10:55:00'),
(78, 9521, 3, 2, '10:15:00', '11:55:00'),
(79, 9525, 3, 2, '11:15:00', '12:55:00'),
(80, 9533, 3, 2, '13:15:00', '14:55:00'),
(81, 9573, 3, 2, '14:15:00', '15:55:00'),
(82, 9541, 3, 2, '15:15:00', '16:55:00'),
(83, 9546, 3, 2, '16:15:00', '17:55:00'),
(84, 9555, 3, 2, '18:30:00', '20:10:00'),
(85, 9557, 3, 2, '19:15:00', '20:10:00'),
(86, 9559, 3, 2, '20:15:00', '21:55:00');

-- --------------------------------------------------------

--
-- Table structure for table `trips`
--

DROP TABLE IF EXISTS `trips`;
CREATE TABLE IF NOT EXISTS `trips` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `ID_T` int(11) NOT NULL,
  `ID_U` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `ID_T` (`ID_T`),
  KEY `ID_U` (`ID_U`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `FirstName` varchar(62) NOT NULL,
  `LastName` varchar(128) NOT NULL,
  `MailAddress` varchar(256) NOT NULL,
  `Password` varchar(32) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `FirstName`, `LastName`, `MailAddress`, `Password`) VALUES
(1, 'Admin', 'Admin', 'admin', '73acd9a5972130b75066c82595a1fae3');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_2` FOREIGN KEY (`ID_U`) REFERENCES `users` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `trains`
--
ALTER TABLE `trains`
  ADD CONSTRAINT `trains_ibfk_1` FOREIGN KEY (`IdDepartureStation`) REFERENCES `stations` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `trains_ibfk_2` FOREIGN KEY (`IdArrivalStation`) REFERENCES `stations` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `trips`
--
ALTER TABLE `trips`
  ADD CONSTRAINT `trips_ibfk_1` FOREIGN KEY (`ID_T`) REFERENCES `trains` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `trips_ibfk_2` FOREIGN KEY (`ID_U`) REFERENCES `users` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
