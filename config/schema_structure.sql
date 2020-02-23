-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 23, 2020 at 11:58 PM
-- Server version: 5.7.22-0ubuntu18.04.1
-- PHP Version: 7.2.5-0ubuntu0.18.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `api_gulkinnos_com`
--

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

CREATE TABLE IF NOT EXISTS `contacts` (
                                          `id` int(11) NOT NULL AUTO_INCREMENT,
                                          `fname` varchar(150) NOT NULL,
                                          `lname` varchar(150) NOT NULL,
                                          `email` varchar(175) NOT NULL,
                                          `phone1` varchar(50) NOT NULL,
                                          `phone2` varchar(50) NOT NULL,
                                          `phone3` varchar(50) NOT NULL,
                                          `adress` varchar(265) NOT NULL,
                                          `adress2` varchar(256) NOT NULL,
                                          `city` varchar(256) NOT NULL,
                                          `state` varchar(150) NOT NULL,
                                          `zip_code` varchar(50) NOT NULL,
                                          `country` varchar(155) NOT NULL,
                                          `user_id` int(11) NOT NULL,
                                          PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
                                       `id` int(11) NOT NULL AUTO_INCREMENT,
                                       `username` varchar(150) NOT NULL,
                                       `email` varchar(150) NOT NULL,
                                       `password` varchar(150) NOT NULL,
                                       `fname` varchar(150) NOT NULL,
                                       `lastname` varchar(150) NOT NULL,
                                       `acl` text,
                                       `deleted` tinyint(4) DEFAULT '0',
                                       PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `user_sessions`
--

CREATE TABLE IF NOT EXISTS `user_sessions` (
                                               `id` int(11) NOT NULL AUTO_INCREMENT,
                                               `user_id` int(11) NOT NULL,
                                               `session` varchar(255) NOT NULL,
                                               `user_agent` varchar(255) NOT NULL,
                                               PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
