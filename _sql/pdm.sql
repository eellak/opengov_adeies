-- phpMyAdmin SQL Dump
-- version 4.2.12deb2+deb8u1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 13, 2016 at 07:03 PM
-- Server version: 5.5.46-0+deb8u1
-- PHP Version: 5.6.14-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `pdm`
--

-- --------------------------------------------------------

--
-- Table structure for table `leaves`
--

CREATE TABLE IF NOT EXISTS `leaves` (
`id` int(11) NOT NULL,
  `num_leaves` int(11) NOT NULL,
  `past_leaves` int(11) NOT NULL,
  `remaining_leaves` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=576 DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `leaves_submissions`
--

CREATE TABLE IF NOT EXISTS `leaves_submissions` (
`leave_id` int(11) NOT NULL,
  `employee_afm` varchar(11) DEFAULT NULL,
  `type_id` int(11) DEFAULT NULL,
  `date_submitted` datetime DEFAULT NULL,
  `submitted_by` varchar(25) NOT NULL,
  `date_starts` date DEFAULT NULL,
  `date_ends` date DEFAULT NULL,
  `num_leaves` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0',
  `signature_by` varchar(11) DEFAULT '0',
  `signature_date` datetime NOT NULL,
  `canceled` tinyint(1) NOT NULL DEFAULT '0',
  `canceled_by` varchar(20) NOT NULL,
  `canceled_days` int(20) NOT NULL,
  `canceled_date` datetime NOT NULL,
  `ip_canceled` varchar(255) NOT NULL,
  `comments` text,
  `ip_submitted` varchar(255) NOT NULL,
  `ip_approved` varchar(255) NOT NULL,
  `remaining_leaves` int(11) NOT NULL,
  `migrated` tinyint(1) NOT NULL DEFAULT '0',
  `filename` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `main_users`
--

CREATE TABLE IF NOT EXISTS `main_users` (
`id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `first_name` varchar(512) NOT NULL,
  `last_name` varchar(512) NOT NULL,
  `amka` varchar(20) NOT NULL,
  `afm` varchar(20) NOT NULL,
  `type` varchar(20) NOT NULL,
  `auto_id` int(11) NOT NULL,
  `usage_code` varchar(55) NOT NULL,
  `date_hired` date NOT NULL,
  `date_permanent` date NOT NULL,
  `unit_p` varchar(20) NOT NULL,
  `unit_t` varchar(20) NOT NULL,
  `unit_g` varchar(20) NOT NULL,
  `unit_gd` varchar(20) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=576 DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `leaves`
--
ALTER TABLE `leaves`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `leaves_submissions`
--
ALTER TABLE `leaves_submissions`
 ADD PRIMARY KEY (`leave_id`);

--
-- Indexes for table `main_users`
--
ALTER TABLE `main_users`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `leaves`
--
ALTER TABLE `leaves`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=576;
--
-- AUTO_INCREMENT for table `leaves_submissions`
--
ALTER TABLE `leaves_submissions`
MODIFY `leave_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=35;
--
-- AUTO_INCREMENT for table `main_users`
--
ALTER TABLE `main_users`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=576;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
