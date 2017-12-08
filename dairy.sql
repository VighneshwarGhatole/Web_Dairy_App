-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 08, 2017 at 09:10 AM
-- Server version: 5.5.57-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `dairy`
--

-- --------------------------------------------------------

--
-- Table structure for table `api_auth_logs`
--

CREATE TABLE IF NOT EXISTS `api_auth_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `token` char(32) NOT NULL,
  `device_id` varchar(50) NOT NULL,
  `device_type` tinyint(4) NOT NULL COMMENT '0=>Android,1=>ios,2=>web',
  `token_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0=>In active,1=>Active',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `api_auth_logs`
--

INSERT INTO `api_auth_logs` (`id`, `user_id`, `token`, `device_id`, `device_type`, `token_status`, `created_at`) VALUES
(1, 3, '7b6d2c507cbe80d05e8eead897ed465d', '', 0, 1, '2017-06-08 05:31:20'),
(2, 16, '477550d867dd517b65e56eac0dc0f158', '123456789', 1, 1, '2017-06-12 06:09:58'),
(3, 17, 'ba3c69f57889efe521a33e5f9e2ea67a', '123456789', 0, 1, '2017-06-12 06:32:13'),
(4, 15, '6f2ef9eba7d5912aff317c578a2d8e1d', '123456789', 0, 0, '2017-06-09 08:56:56'),
(5, 30, 'b5248314bbfb8a310eb31eb95b7132cc', '123456789', 0, 0, '2017-06-02 07:12:01'),
(6, 33, '762d7305b364aeb98530a28b4443a3dd', '123456789', 0, 0, '2017-05-31 12:56:01'),
(7, 32, '43e80dc8826d527d5c0e2dd3b5d9e7ae', '123456789', 0, 1, '2017-06-03 08:50:04'),
(8, 36, '74ffc8d565858d4316417ebaef95ebe5', '', 0, 1, '2017-06-12 05:21:08'),
(9, 41, 'b230841bba3d4d0d45ab94751fcd4f30', '1234567890', 0, 0, '2017-06-10 11:19:10'),
(10, 52, 'c3e9691eb1a46ffcf6f167af5b2bf2f1', '', 0, 1, '2017-06-11 04:15:52'),
(11, 59, 'ef7484a55302751dd3d18cecdc37b2e4', '', 0, 1, '2017-07-04 07:41:30'),
(12, 58, '344eba2ac5b7ad319100d0e0e43e58db', '', 0, 1, '2017-07-07 05:01:14');

-- --------------------------------------------------------

--
-- Table structure for table `codes`
--

CREATE TABLE IF NOT EXISTS `codes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` tinyint(1) NOT NULL COMMENT '0=cow, 1=buffalow',
  `mobile_no` varchar(10) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `codes`
--

INSERT INTO `codes` (`id`, `user_id`, `name`, `type`, `mobile_no`, `status`, `is_deleted`, `created`) VALUES
(1, 1, 'sanjay bakry', 1, '9643818275', 1, 0, '2017-09-01 23:42:12'),
(2, 2, 'shanti dairy', 1, '9999877663', 1, 0, '2017-08-29 23:44:00'),
(3, 1, 'goury diry', 0, '8765432145', 1, 0, '2017-08-31 00:47:00'),
(10, 2, 'wgaha', 0, '4546578788', 1, 0, '2017-09-10 07:53:55'),
(11, 2, 'wgaha', 0, '4546578788', 1, 0, '2017-09-10 10:50:17'),
(12, 7, 'wgaha', 0, '4546578788', 1, 0, '2017-09-26 17:00:08');

-- --------------------------------------------------------

--
-- Table structure for table `entries`
--

CREATE TABLE IF NOT EXISTS `entries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `code_id` int(11) NOT NULL,
  `crl` int(11) NOT NULL,
  `fat` float(4,1) NOT NULL,
  `snf` float(4,1) NOT NULL,
  `ltr` int(4) NOT NULL,
  `price` float(4,1) NOT NULL,
  `more` tinyint(4) NOT NULL,
  `total` float NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=active, 0=in-active',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1=deleted, 0=not deleted',
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `entries`
--

INSERT INTO `entries` (`id`, `user_id`, `code_id`, `crl`, `fat`, `snf`, `ltr`, `price`, `more`, `total`, `status`, `is_deleted`, `created`) VALUES
(5, 1, 1, 44, 3.2, 2.0, 0, 0.0, 0, 14, 1, 0, '2017-07-30 09:09:27'),
(6, 1, 1, 44, 999.9, 999.9, 0, 0.0, 0, 14, 1, 0, '2017-07-30 09:11:31'),
(7, 2, 2, 44, 999.9, 999.9, 0, 0.0, 0, 14, 1, 0, '2017-07-30 12:19:16'),
(8, 2, 2, 44, 999.9, 999.9, 0, 0.0, 0, 14, 1, 0, '2017-07-30 13:01:24'),
(9, 1, 1, 10, 2.8, 3.4, 12, 121.0, 1, 1452, 1, 0, '2017-07-30 16:41:49'),
(10, 1, 2, 10, 2.8, 3.4, 12, 121.0, 1, 1452, 1, 0, '2017-07-30 16:55:00'),
(11, 7, 1, 10, 2.8, 3.4, 12, 121.0, 1, 1452, 1, 0, '2017-09-17 17:02:28'),
(12, 2, 1, 10, 2.5, 3.4, 12, 13.2, 1, 158.4, 1, 0, '2017-09-22 22:14:25');

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE IF NOT EXISTS `expenses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `code_id` int(11) NOT NULL,
  `expense` float NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1=active,0=in-active',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1=deleted, 0=not deleted',
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`id`, `user_id`, `code_id`, `expense`, `status`, `is_deleted`, `created`) VALUES
(4, 1, 1, 200, 1, 0, '2017-07-30 13:07:27'),
(5, 2, 1, -12, 1, 0, '2017-09-10 17:00:58'),
(6, 2, 1, -1222, 1, 0, '2017-09-10 17:00:58'),
(7, 2, 1, -12, 1, 0, '2017-09-10 17:01:44'),
(8, 2, 1, -1222, 1, 0, '2017-09-10 17:01:44'),
(9, 2, 1, -12, 1, 0, '2017-09-10 17:02:28'),
(10, 2, 1, -1222, 1, 0, '2017-09-10 17:02:28'),
(11, 2, 1, -12, 1, 0, '2017-09-17 16:17:51'),
(12, 2, 1, -1222, 1, 0, '2017-09-17 16:17:51'),
(13, 2, 1, -12, 1, 0, '2017-09-17 16:19:16'),
(14, 2, 1, -1222, 1, 0, '2017-09-17 16:19:16'),
(15, 2, 1, 12, 1, 0, '2017-09-22 22:14:25'),
(16, 2, 1, -12, 1, 0, '2017-09-23 00:04:04'),
(17, 2, 1, -1222, 1, 0, '2017-09-23 00:04:04'),
(18, 7, 1, -12, 1, 0, '2017-09-26 22:30:09'),
(19, 7, 1, -1222, 1, 0, '2017-09-26 22:30:09');

-- --------------------------------------------------------

--
-- Table structure for table `prices`
--

CREATE TABLE IF NOT EXISTS `prices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `lfat` float(4,1) NOT NULL,
  `hfat` float(4,1) NOT NULL,
  `lsnf` float(4,1) NOT NULL,
  `hsnf` float(4,1) NOT NULL,
  `start_price` int(5) NOT NULL,
  `fat_intetval` float(4,1) NOT NULL,
  `snf_intetval` float(4,1) NOT NULL,
  `type` tinyint(4) NOT NULL COMMENT '0=cow, 1=buffalow',
  `time` tinyint(4) NOT NULL COMMENT '0=morning, 1=evening',
  `status` tinyint(4) NOT NULL DEFAULT '1' COMMENT '1=active, 0=inactive',
  `is_deleted` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1=deleted',
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `prices`
--

INSERT INTO `prices` (`id`, `user_id`, `lfat`, `hfat`, `lsnf`, `hsnf`, `start_price`, `fat_intetval`, `snf_intetval`, `type`, `time`, `status`, `is_deleted`, `created`) VALUES
(1, 1, 4.2, 8.3, 4.2, 8.3, 10, 0.6, 0.0, 0, 1, 1, 0, '2017-09-03 07:16:29'),
(2, 1, 2.2, 9.3, 4.2, 3.3, 10, 0.6, 0.0, 1, 0, 1, 0, '2017-09-03 07:16:29'),
(3, 2, 4.2, 8.3, 4.2, 8.3, 10, 0.6, 0.0, 0, 0, 1, 0, '2017-09-10 10:43:30'),
(4, 2, 2.2, 4.2, 2.2, 4.2, 10, 0.2, 0.0, 1, 1, 1, 0, '2017-09-22 16:44:25'),
(5, 7, 4.2, 8.3, 4.2, 8.3, 10, 0.6, 0.6, 0, 0, 1, 0, '2017-09-26 17:00:08');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL,
  `fname` varchar(100) NOT NULL,
  `lname` varchar(100) DEFAULT NULL,
  `address` varchar(255) NOT NULL,
  `pincode` varchar(20) NOT NULL,
  `gender` char(1) NOT NULL,
  `dob` date NOT NULL,
  `agentId` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobile_no` varchar(100) NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `pic` varchar(255) DEFAULT NULL,
  `qualification` varchar(255) DEFAULT NULL,
  `role` smallint(6) NOT NULL DEFAULT '0' COMMENT '1=admin, 0=agent, 2=manager',
  `status` tinyint(1) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0=> Not deleted, 1=> Deleted',
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `parent_id`, `fname`, `lname`, `address`, `pincode`, `gender`, `dob`, `agentId`, `password`, `email`, `mobile_no`, `token`, `pic`, `qualification`, `role`, `status`, `is_deleted`, `created`, `modified`) VALUES
(1, 6, 'dasarath sahooeee', NULL, 'sector - 62, noida up-201301', '201301', 'm', '1990-01-11', 'U2Fob28xMjM=', 'c2Fob28xMjM=', 'sahoo@gmail.com', '96438082723', 'YifvYByqP8T4c3sfS8L41oqtVatflMX4', NULL, 'mca', 0, 1, 0, '2017-07-14 00:00:00', '2017-09-16 20:49:19'),
(2, 7, 'mukesh kumar', NULL, 'sector-50', '', 'm', '0000-00-00', 'TXVrZXNoMTIz', 'bXVrZXNoMTIz', 'mukesh@gmail.com', '9643808388', 'A2dCgt4Y4NmA2kLV6wbOe5eyZLThbinj', NULL, NULL, 0, 1, 0, '2017-07-14 23:19:02', '2017-09-23 19:09:23'),
(3, 0, 'Admin', NULL, 'sector - 62, noida up-201301', '201301', 'm', '1990-01-11', 'QWRtaW4=', 'YWRtaW4xMjM=', 'admindairy@gmail.com', '9643808272', '2hGt8NVQutavV7nPpCuhaqrgmzHnKZzY', NULL, 'mca', 1, 1, 0, '2017-07-14 00:00:00', '2017-07-30 12:51:46'),
(5, 6, 'kuldeep jain', NULL, '', '', '', '0000-00-00', 'S3VsZGVlcDEyMw==', 'a3VsZGVlcDEyMw==', 'kuldeep@gmail.com', '23412414143', NULL, NULL, NULL, 0, 1, 0, '2017-08-06 21:12:28', '2017-09-16 20:43:03'),
(6, 0, 'param', NULL, '', '', '', '0000-00-00', 'UGFyYW0xMjM=', 'cGFyYW0xMjM=', 'param@gmail.com', '23412414143', NULL, NULL, NULL, 2, 1, 0, '2017-08-06 21:12:28', '2017-09-16 19:35:16'),
(7, 0, 'rohan mishra', NULL, '', '', '', '0000-00-00', 'Um9oYW4xMjM=', 'cm9oYW4xMjM=', 'rohan@gmail.com', '234523523523', '5938WExEdnrnpyGexUrcjRbUNRpH1yBs', NULL, NULL, 2, 1, 0, '2017-09-16 18:16:20', '2017-09-23 20:06:44'),
(8, 7, 'rohit rana', NULL, '', '', '', '0000-00-00', 'Um9oaXRyYW5h', 'cm9oaXRyYW5h', 'rohit@gmail.com', '234523542354', NULL, NULL, NULL, 0, 1, 0, '2017-09-16 19:42:27', '2017-09-17 12:14:17'),
(9, 6, 'himani', NULL, 'noida', '', '', '0000-00-00', 'dGVzdGhpbWk=', 'aGltYW5pMTIz', 'arya.himani22@gmail.com', '908747327843', NULL, NULL, NULL, 0, 1, 0, '2017-09-26 21:05:51', '2017-09-26 21:05:51'),
(10, 6, 'fdafasfd', NULL, '', '', '', '0000-00-00', 'ZGFzYXJhdGg=', 'cGFzc3dvcmQ=', 'dfasdfasfd@gmail.com', '2345235235', NULL, NULL, NULL, 0, 1, 0, '2017-09-26 21:21:41', '2017-09-26 21:21:41');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
