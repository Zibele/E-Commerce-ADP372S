-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3308
-- Generation Time: Aug 18, 2018 at 07:04 PM
-- Server version: 5.7.19
-- PHP Version: 5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ecommerce`
--

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

DROP TABLE IF EXISTS `address`;
CREATE TABLE IF NOT EXISTS `address` (
  `AddressID` varchar(255) NOT NULL,
  `Street` text,
  `City` text,
  `Province` text,
  `ZipCode` text,
  PRIMARY KEY (`AddressID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

DROP TABLE IF EXISTS `customer`;
CREATE TABLE IF NOT EXISTS `customer` (
  `CustomerID` varchar(255) NOT NULL,
  `Name` text,
  `Email` text,
  `Password` text,
  `IsSubscribed` text,
  `AddressID` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`CustomerID`),
  KEY `AddressID` (`AddressID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

DROP TABLE IF EXISTS `item`;
CREATE TABLE IF NOT EXISTS `item` (
  `CategoryID` varchar(10) DEFAULT NULL,
  `ItemID` varchar(11) NOT NULL,
  `Name` text,
  `Description` text,
  `Price` double DEFAULT NULL,
  `ImagePath` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ItemID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`CategoryID`, `ItemID`, `Name`, `Description`, `Price`, `ImagePath`) VALUES
('FIGURE', 'FIG001', 'Draven-Figurine', 'A Figurine of the league of legends character Draven', 399, 'items/Draven-Fig.jpg\r'),
('FIGURE', 'FIG002', 'Jhin-Figurine', 'A Figurine of the league of legends character Jhin', 299, 'items/Jhin-Fig.jpg\r'),
('FIGURE', 'FIG003', 'URF-Figurine', 'A Figurine of the league of legends character URF', 299, 'items/URF-Fig.jpg\r'),
('FIGURE', 'FIG004', 'Vayne-Figurine', 'A Figurine of the league of legends character Vayne', 399, 'items/Vayne-Fig.jpg\r'),
('FIGURE', 'FIG005', 'Taric-Figurine', 'A Figurine of the league of legends character Taric', 299, 'items/Taric-Fig.jpg\r'),
('FIGURE', 'FIG006', 'Lunar-Figurine', 'A Figurine of the league of legends character Lunar', 299, 'items/Lunar-Fig.jpg\r'),
('FIGURE', 'FIG007', 'Ziggs-Figurine', 'A Figurine of the league of legends character Zigs', 399, 'items/Ziggs-Fig.jpg'),
('STATUE', 'STAT001', 'Ashe', 'A statue of Ashe', 449, 'items/Ashe-Statue.jpg\r'),
('STATUE', 'STAT002', 'Braum-Statue', 'A Statue of the League of legends character Braum', 449, 'items/Braum-Statue.jpg\r'),
('STATUE', 'STAT004', 'Yasuo-Statue', 'A Statue of the League of legends character Yasuo', 799, 'items/Yasuo-Statue.jpg\r'),
('STATUE', 'STAT005', 'Zed-Statue', 'A Statue of the League of legends character Ashe', 799, 'items/Zed-Statue.jpg\r'),
('STATUE', 'STAT006', 'Twisted Fate-Statue', 'A Statue of the League of legends character Twisted Fate', 449, 'items/TF-Statue.jpg\r\n'),
('STATUE', 'STAT007', 'Katarina-Statue', 'A Statue of the League of legends character Ashe', 449, 'items/Katarina-Statue.jpg\r'),
('STATUE', 'STAT008', 'Teemo-Statue', 'A Statue of the League of legends character Ashe', 549, 'items/Teemo-Statue.jpg\r'),
('STATUE', 'STAT011', 'Ekko-Statue', 'A Statue of the League of legends character Ashe', 549, 'items/Ekko-Statue.jpg\r'),
('STATUE', 'STAT012', 'Vi-Statue', 'A Statue of the League of legends character Ashe', 549, 'items/Vi-Statue.jpg\r'),
('STATUE', 'STAT013', 'Thresh-Statue', 'A Statue of the League of legends character Thresh', 799, 'items/Thresh-Statue.jpg\r\n'),
('STATUE', 'STATG003', 'Poppy-Statue', 'A Statue of the League of legends character Poppy', 549, 'items/Poppy-Statue.jpg\r');

-- --------------------------------------------------------

--
-- Table structure for table `orderline`
--

DROP TABLE IF EXISTS `orderline`;
CREATE TABLE IF NOT EXISTS `orderline` (
  `OrderLineID` varchar(255) NOT NULL,
  `OrderID` varchar(255) DEFAULT NULL,
  `ItemID` varchar(11) DEFAULT NULL,
  `Quantity` int(11) DEFAULT NULL,
  `QPrice` double DEFAULT NULL,
  PRIMARY KEY (`OrderLineID`),
  KEY `OrderID` (`OrderID`),
  KEY `ItemID` (`ItemID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `OrderID` varchar(255) NOT NULL,
  `OrderNumber` varchar(255) DEFAULT NULL,
  `ShippingAddress` text,
  `DeliveryCost` double DEFAULT NULL,
  `CustomerID` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`OrderID`),
  KEY `CustomerID` (`CustomerID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `customer`
--
ALTER TABLE `customer`
  ADD CONSTRAINT `customer_ibfk_1` FOREIGN KEY (`AddressID`) REFERENCES `address` (`AddressID`);

--
-- Constraints for table `orderline`
--
ALTER TABLE `orderline`
  ADD CONSTRAINT `orderline_ibfk_1` FOREIGN KEY (`OrderID`) REFERENCES `orders` (`OrderID`),
  ADD CONSTRAINT `orderline_ibfk_2` FOREIGN KEY (`ItemID`) REFERENCES `item` (`ItemID`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`CustomerID`) REFERENCES `customer` (`CustomerID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
