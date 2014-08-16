-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 15, 2014 at 05:17 PM
-- Server version: 5.5.38-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `controlcenter`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(6) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `facebookid` varchar(20) DEFAULT NULL,
  `password` varchar(128) NOT NULL,
  `salt` varchar(128) NOT NULL,
  `passwordchangerequired` enum('1','0') NOT NULL DEFAULT '0',
  `userconfirmation` enum('1','0') NOT NULL DEFAULT '0',
  `adminconfirmation` enum('1','0') NOT NULL DEFAULT '0',
  `realname` varchar(30) NOT NULL,
  `realfirstname` varchar(30) NOT NULL,
  `mailadress` varchar(50) NOT NULL,
  `website` varchar(50) DEFAULT NULL,
  `country` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `search_by_username` (`username`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=75 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `facebookid`, `password`, `salt`, `passwordchangerequired`, `userconfirmation`, `adminconfirmation`, `realname`, `realfirstname`, `mailadress`, `website`, `country`) VALUES
(000001, 'Matt', '', 'c469a4d67bcacc1f5e504f7ea5dbf9d01bd8f7474f150af51e55789fa46db6a663ec2c6863ba70bf8fd467fea9d902e5792f7dcc9f36d10d2580115f2a117e0d', 'a9ef9d9a4799b3d1e706e0a5d5a3637241bb7821252265f9f52889e95411d37b7f3f258b751512b35a78605a42d5743dfbb42f7871e25e0b04031d3cd2ab79ba', '', '1', '1', 'Bauw', 'Matthias', 'matthias.bauw@gmail.com', NULL, NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
