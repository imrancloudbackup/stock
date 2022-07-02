-- phpMyAdmin SQL Dump
-- version 4.9.7
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 02, 2022 at 12:49 AM
-- Server version: 5.7.23-23
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ravelet6_test_market`
--

-- --------------------------------------------------------

--
-- Table structure for table `table_purchase_stocks`
--

CREATE TABLE `table_purchase_stocks` (
  `id` bigint(8) NOT NULL,
  `user_id` bigint(8) DEFAULT NULL,
  `stock_name` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `stock_price` decimal(10,2) DEFAULT NULL,
  `stock_volume` bigint(8) DEFAULT NULL,
  `stock_total_price` decimal(10,2) DEFAULT NULL,
  `purchased_date` date DEFAULT NULL,
  `purchased_ip` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `purchased_location` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `stock_status` int(4) DEFAULT NULL,
  `stock_created_source` int(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `table_sell_stocks`
--

CREATE TABLE `table_sell_stocks` (
  `id` bigint(8) NOT NULL,
  `user_id` bigint(8) DEFAULT NULL,
  `stock_name` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `stock_price` decimal(10,2) DEFAULT NULL,
  `stock_volume` bigint(8) DEFAULT NULL,
  `stock_total_price` decimal(10,2) DEFAULT NULL,
  `sell_date` date DEFAULT NULL,
  `sell_ip` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sell_location` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `sell_status` int(4) DEFAULT NULL,
  `sell_created_source` int(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `table_stocks`
--

CREATE TABLE `table_stocks` (
  `id` bigint(8) NOT NULL,
  `stock_date` date DEFAULT NULL,
  `stock_name` varchar(250) COLLATE utf8_unicode_ci DEFAULT NULL,
  `stock_price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `table_stocks`
--

INSERT INTO `table_stocks` (`id`, `stock_date`, `stock_name`, `stock_price`) VALUES
(1, '2020-02-11', 'AAPL', '320.00'),
(2, '2020-02-11', 'GOOGL', '1510.00'),
(3, '2020-02-11', 'MSFT', '185.00'),
(4, '2020-02-12', 'GOOGL', '1518.00'),
(5, '2020-02-12', 'MSFT', '184.00'),
(6, '2020-02-13', 'AAPL', '324.00'),
(7, '2020-02-14', 'GOOGL', '1520.00'),
(8, '2020-02-15', 'AAPL', '319.00'),
(9, '2020-02-15', 'GOOGL', '1523.00'),
(10, '2020-02-15', 'MSFT', '189.00'),
(11, '2020-02-16', 'GOOGL', '1530.00'),
(12, '2020-02-18', 'AAPL', '319.00'),
(13, '2020-02-18', 'MSFT', '187.00'),
(14, '2020-02-19', 'AAPL', '323.00'),
(15, '2020-02-21', 'AAPL', '313.00'),
(16, '2020-02-21', 'GOOGL', '1483.00'),
(17, '2020-02-21', 'MSFT', '178.00'),
(18, '2020-02-22', 'GOOGL', '1485.00'),
(19, '2020-02-22', 'MSFT', '180.00'),
(20, '2020-02-23', 'AAPL', '320.00');

-- --------------------------------------------------------

--
-- Table structure for table `table_users`
--

CREATE TABLE `table_users` (
  `id` bigint(8) NOT NULL,
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
  `today_purchase` int(4) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `table_users`
--

INSERT INTO `table_users` (`id`, `user_name`, `user_email`, `user_password`, `user_role`, `user_gender`, `created_by`, `created_date`, `created_ip`, `created_location`, `modified_by`, `modified_date`, `modified_ip`, `modified_location`, `delete_by`, `delete_date`, `delete_ip`, `delete_location`, `user_status`, `security_token`, `expiry_time`, `user_created_source`, `user_wallet_amount`, `today_purchase`) VALUES
(1, 'Imran1', 'imran98.jmc@gmail.com', '098f6bcd4621d373cade4e832627b4f6', 1, 1, 'Imran', '16/06/2022 11:42:18 am', '69.94.36.222', ', Male, Maldives', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, '98855.00', 0),
(2, 'test', 'test@gmail.com', 'ad0234829205b9033196ba818f7a872b', NULL, NULL, 'test', '16/06/2022 11:57:44 am', '69.94.36.222', ', Male, Maldives', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, NULL, 0),
(5, 'senthil', 'senthil@gmail.com', '5a105e8b9d40e1329780d62ea2265d8a', NULL, NULL, 'senthil', '16/06/2022 12:07:32 pm', '69.94.36.222', ', Male, Maldives', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, NULL, 0),
(6, 'testing', 'testing@gmail.com', '098f6bcd4621d373cade4e832627b4f6', NULL, NULL, 'testing', '19/06/2022 11:14:18 am', '69.94.36.222', ', Male, Maldives', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 0, NULL, 0),
(7, 'kamil', 'kamil@gmail.com', '5a105e8b9d40e1329780d62ea2265d8a', 1, 2, 'kamil', '22/06/2022 06:04:35 pm', '69.94.36.222', ', Male, Maldives', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, 1, '10032.00', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `table_purchase_stocks`
--
ALTER TABLE `table_purchase_stocks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `table_sell_stocks`
--
ALTER TABLE `table_sell_stocks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `table_stocks`
--
ALTER TABLE `table_stocks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `table_users`
--
ALTER TABLE `table_users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `table_purchase_stocks`
--
ALTER TABLE `table_purchase_stocks`
  MODIFY `id` bigint(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `table_sell_stocks`
--
ALTER TABLE `table_sell_stocks`
  MODIFY `id` bigint(8) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `table_stocks`
--
ALTER TABLE `table_stocks`
  MODIFY `id` bigint(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `table_users`
--
ALTER TABLE `table_users`
  MODIFY `id` bigint(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
