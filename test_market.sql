-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 23, 2022 at 05:57 AM
-- Server version: 5.7.31
-- PHP Version: 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `test_market`
--

-- --------------------------------------------------------

--
-- Table structure for table `table_purchase_stocks`
--

DROP TABLE IF EXISTS `table_purchase_stocks`;
CREATE TABLE IF NOT EXISTS `table_purchase_stocks` (
  `id` bigint(8) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(8) DEFAULT NULL,
  `stock_name` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `stock_price` decimal(10,2) DEFAULT NULL,
  `stock_volume` bigint(8) DEFAULT NULL,
  `stock_total_price` decimal(10,2) DEFAULT NULL,
  `purchased_date` date DEFAULT NULL,
  `purchased_ip` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `purchased_location` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `stock_status` int(4) DEFAULT NULL,
  `stock_created_source` int(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `table_sell_stocks`
--

DROP TABLE IF EXISTS `table_sell_stocks`;
CREATE TABLE IF NOT EXISTS `table_sell_stocks` (
  `id` bigint(8) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(8) DEFAULT NULL,
  `stock_name` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `stock_price` decimal(10,2) DEFAULT NULL,
  `stock_volume` bigint(8) DEFAULT NULL,
  `stock_total_price` decimal(10,2) DEFAULT NULL,
  `sell_date` date DEFAULT NULL,
  `sell_ip` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sell_location` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sell_status` int(4) DEFAULT NULL,
  `sell_created_source` int(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `table_stocks`
--

DROP TABLE IF EXISTS `table_stocks`;
CREATE TABLE IF NOT EXISTS `table_stocks` (
  `id` bigint(8) NOT NULL AUTO_INCREMENT,
  `stock_date` date DEFAULT NULL,
  `stock_name` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `stock_price` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `table_users`
--

DROP TABLE IF EXISTS `table_users`;
CREATE TABLE IF NOT EXISTS `table_users` (
  `id` bigint(8) NOT NULL AUTO_INCREMENT,
  `user_name` varchar(500) DEFAULT NULL,
  `user_email` varchar(250) DEFAULT NULL,
  `user_password` varchar(250) DEFAULT NULL,
  `user_role` int(4) DEFAULT NULL,
  `user_gender` int(4) DEFAULT NULL,
  `created_by` varchar(250) DEFAULT NULL,
  `created_date` varchar(250) DEFAULT NULL,
  `created_ip` varchar(250) DEFAULT NULL,
  `created_location` varchar(250) DEFAULT NULL,
  `modified_by` varchar(100) DEFAULT NULL,
  `modified_date` varchar(100) DEFAULT NULL,
  `modified_ip` varchar(100) DEFAULT NULL,
  `modified_location` varchar(100) DEFAULT NULL,
  `delete_by` varchar(100) DEFAULT NULL,
  `delete_date` varchar(100) DEFAULT NULL,
  `delete_ip` varchar(100) DEFAULT NULL,
  `delete_location` varchar(100) DEFAULT NULL,
  `user_status` int(4) DEFAULT NULL,
  `security_token` varchar(250) DEFAULT NULL,
  `expiry_time` datetime DEFAULT NULL,
  `user_created_source` int(4) DEFAULT NULL,
  `user_wallet_amount` decimal(10,2) DEFAULT NULL,
  `today_purchase` int(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
