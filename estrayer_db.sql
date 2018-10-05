-- phpMyAdmin SQL Dump
-- version 4.0.10.20
-- https://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 05, 2018 at 04:06 PM
-- Server version: 5.1.73
-- PHP Version: 5.3.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `estrayer_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `Account`
--

CREATE TABLE IF NOT EXISTS `Account` (
  `account_ID` int(6) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `password` varchar(50) NOT NULL,
  `type` int(1) NOT NULL COMMENT 'user, admin, or coordinator',
  `active` bit(1) NOT NULL,
  PRIMARY KEY (`account_ID`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `Addresses`
--

CREATE TABLE IF NOT EXISTS `Addresses` (
  `address_ID` int(10) NOT NULL AUTO_INCREMENT,
  `country_ID` int(3) NOT NULL,
  `state/province` varchar(25) NOT NULL,
  `city` varchar(25) NOT NULL,
  `post_code` int(10) NOT NULL,
  `street_address` varchar(50) NOT NULL,
  PRIMARY KEY (`address_ID`),
  KEY `country_ID` (`country_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `Address History`
--

CREATE TABLE IF NOT EXISTS `Address History` (
  `address_ID` int(10) NOT NULL,
  `account_ID` int(6) NOT NULL,
  `start` date NOT NULL,
  `end` date DEFAULT NULL,
  PRIMARY KEY (`address_ID`,`account_ID`,`start`),
  KEY `Address History_ibfk_1` (`account_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Countries`
--

CREATE TABLE IF NOT EXISTS `Countries` (
  `country_ID` int(3) NOT NULL AUTO_INCREMENT,
  `country` varchar(50) NOT NULL,
  PRIMARY KEY (`country_ID`),
  UNIQUE KEY `country` (`country`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `Degrees`
--

CREATE TABLE IF NOT EXISTS `Degrees` (
  `account_ID` int(6) NOT NULL,
  `degree_type_ID` int(1) NOT NULL,
  `school` varchar(25) NOT NULL,
  `major` varchar(30) NOT NULL,
  `graduation_year` year(4) NOT NULL,
  PRIMARY KEY (`account_ID`,`degree_type_ID`,`school`,`major`),
  KEY `PK_FK_degree_type_ID` (`degree_type_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Degree Types`
--

CREATE TABLE IF NOT EXISTS `Degree Types` (
  `degree_type_ID` int(1) NOT NULL AUTO_INCREMENT,
  `degree` varchar(20) NOT NULL,
  PRIMARY KEY (`degree_type_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `Information`
--

CREATE TABLE IF NOT EXISTS `Information` (
  `account_ID` int(6) NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `middle_name` varchar(20) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `gender` int(1) NOT NULL,
  `email_address` varchar(30) NOT NULL,
  PRIMARY KEY (`account_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Job`
--

CREATE TABLE IF NOT EXISTS `Job` (
  `job_ID` int(10) NOT NULL AUTO_INCREMENT,
  `employer` varchar(25) NOT NULL,
  `profession_field` varchar(25) NOT NULL,
  PRIMARY KEY (`job_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `Job History`
--

CREATE TABLE IF NOT EXISTS `Job History` (
  `job_ID` int(10) NOT NULL,
  `account_ID` int(6) NOT NULL,
  `start` date NOT NULL,
  `end` date DEFAULT NULL,
  PRIMARY KEY (`job_ID`,`account_ID`,`start`),
  KEY `Job History_ibfk_2` (`account_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Mentorship`
--

CREATE TABLE IF NOT EXISTS `Mentorship` (
  `mentor_ID` int(6) NOT NULL,
  `mentee_ID` int(6) NOT NULL,
  `start` date NOT NULL,
  `end` date DEFAULT NULL,
  PRIMARY KEY (`mentor_ID`,`mentee_ID`,`start`),
  KEY `mentee_ID` (`mentee_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Pending Mentorship`
--

CREATE TABLE IF NOT EXISTS `Pending Mentorship` (
  `mentor_ID` int(6) NOT NULL,
  `mentee_ID` int(6) NOT NULL,
  `accept_code` varchar(50) NOT NULL,
  `decline_code` varchar(50) NOT NULL,
  `mentor_status` tinyint(1) NOT NULL,
  `mentee_status` tinyint(1) NOT NULL,
  PRIMARY KEY (`accept_code`,`decline_code`),
  UNIQUE KEY `accept_code` (`accept_code`),
  UNIQUE KEY `decline_code` (`decline_code`),
  KEY `mentor_ID` (`mentor_ID`,`mentee_ID`),
  KEY `mentee_ID` (`mentee_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Phone Numbers`
--

CREATE TABLE IF NOT EXISTS `Phone Numbers` (
  `account_ID` int(6) NOT NULL,
  `phone_type_ID` int(1) NOT NULL,
  `phone_number` int(11) NOT NULL,
  PRIMARY KEY (`account_ID`,`phone_number`),
  KEY `phone_type_ID` (`phone_type_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `Phone Types`
--

CREATE TABLE IF NOT EXISTS `Phone Types` (
  `phone_type_ID` int(1) NOT NULL AUTO_INCREMENT,
  `phone_type` varchar(10) NOT NULL COMMENT 'cell phone, work phone, home phone',
  PRIMARY KEY (`phone_type_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `Pictures`
--

CREATE TABLE IF NOT EXISTS `Pictures` (
  `picture_ID` int(10) NOT NULL AUTO_INCREMENT,
  `account_ID` int(6) NOT NULL,
  `date_uploaded` date NOT NULL,
  `picture` varchar(260) NOT NULL COMMENT 'file address',
  PRIMARY KEY (`picture_ID`),
  KEY `account_ID` (`account_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `Resumes`
--

CREATE TABLE IF NOT EXISTS `Resumes` (
  `account_ID` int(6) NOT NULL,
  `resume_file` varchar(260) NOT NULL,
  PRIMARY KEY (`account_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Addresses`
--
ALTER TABLE `Addresses`
  ADD CONSTRAINT `Addresses_ibfk_1` FOREIGN KEY (`country_ID`) REFERENCES `Countries` (`country_ID`) ON UPDATE CASCADE;

--
-- Constraints for table `Address History`
--
ALTER TABLE `Address History`
  ADD CONSTRAINT `Address History_ibfk_1` FOREIGN KEY (`account_ID`) REFERENCES `Account` (`account_ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `address_ID` FOREIGN KEY (`address_ID`) REFERENCES `Addresses` (`address_ID`) ON UPDATE CASCADE;

--
-- Constraints for table `Degrees`
--
ALTER TABLE `Degrees`
  ADD CONSTRAINT `Degrees_ibfk_1` FOREIGN KEY (`account_ID`) REFERENCES `Account` (`account_ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `PK_FK_degree_type_ID` FOREIGN KEY (`degree_type_ID`) REFERENCES `Degree Types` (`degree_type_ID`) ON UPDATE CASCADE;

--
-- Constraints for table `Information`
--
ALTER TABLE `Information`
  ADD CONSTRAINT `PK_FK_account_ID` FOREIGN KEY (`account_ID`) REFERENCES `Account` (`account_ID`) ON UPDATE CASCADE;

--
-- Constraints for table `Job History`
--
ALTER TABLE `Job History`
  ADD CONSTRAINT `Job History_ibfk_1` FOREIGN KEY (`job_ID`) REFERENCES `Job` (`job_ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `Job History_ibfk_2` FOREIGN KEY (`account_ID`) REFERENCES `Account` (`account_ID`) ON UPDATE CASCADE;

--
-- Constraints for table `Mentorship`
--
ALTER TABLE `Mentorship`
  ADD CONSTRAINT `Mentorship_ibfk_1` FOREIGN KEY (`mentor_ID`) REFERENCES `Account` (`account_ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `Mentorship_ibfk_2` FOREIGN KEY (`mentee_ID`) REFERENCES `Account` (`account_ID`) ON UPDATE CASCADE;

--
-- Constraints for table `Pending Mentorship`
--
ALTER TABLE `Pending Mentorship`
  ADD CONSTRAINT `Pending Mentorship_ibfk_2` FOREIGN KEY (`mentee_ID`) REFERENCES `Account` (`account_ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `Pending Mentorship_ibfk_1` FOREIGN KEY (`mentor_ID`) REFERENCES `Account` (`account_ID`) ON UPDATE CASCADE;

--
-- Constraints for table `Phone Numbers`
--
ALTER TABLE `Phone Numbers`
  ADD CONSTRAINT `phone_type_ID` FOREIGN KEY (`phone_type_ID`) REFERENCES `Phone Types` (`phone_type_ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `Phone Numbers_ibfk_1` FOREIGN KEY (`account_ID`) REFERENCES `Account` (`account_ID`) ON UPDATE CASCADE;

--
-- Constraints for table `Pictures`
--
ALTER TABLE `Pictures`
  ADD CONSTRAINT `Pictures_ibfk_1` FOREIGN KEY (`account_ID`) REFERENCES `Account` (`account_ID`) ON UPDATE CASCADE;

--
-- Constraints for table `Resumes`
--
ALTER TABLE `Resumes`
  ADD CONSTRAINT `Resumes_ibfk_1` FOREIGN KEY (`account_ID`) REFERENCES `Account` (`account_ID`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
