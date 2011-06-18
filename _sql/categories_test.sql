-- phpMyAdmin SQL Dump
-- version 3.2.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 18, 2011 at 07:10 PM
-- Server version: 5.1.40
-- PHP Version: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `joostina_2_0_dev`
--

-- --------------------------------------------------------

--
-- Table structure for table `jos_categories`
--

CREATE TABLE IF NOT EXISTS `jos_categories` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `lft` mediumint(8) unsigned NOT NULL,
  `rgt` mediumint(8) unsigned NOT NULL,
  `level` mediumint(8) NOT NULL,
  `parent_id` int(11) NOT NULL,
  `moved` tinyint(1) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `group` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `slug` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `state` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `lft` (`lft`,`rgt`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=64 ;

--
-- Dumping data for table `jos_categories`
--

INSERT INTO `jos_categories` (`id`, `lft`, `rgt`, `level`, `parent_id`, `moved`, `name`, `group`, `slug`, `state`) VALUES
(33, 50, 51, 3, 29, 0, '2.1.2', 'content', '2-1-2', 1),
(32, 48, 49, 3, 29, 0, '2.1.1', 'content', '2-1-1', 1),
(31, 55, 56, 2, 28, 0, '2.3', 'content', '2-3', 1),
(30, 53, 54, 2, 28, 0, '2.2', 'content', '2-2', 1),
(29, 47, 52, 2, 28, 0, '2.1', 'content', '2-1', 1),
(28, 46, 59, 1, 1, 0, '2', 'content', '2', 1),
(27, 28, 29, 3, 24, 0, '1.2.2', 'content', '1-2-2', 1),
(26, 6, 27, 3, 24, 0, '1.2.1', 'content', '1-2-1', 1),
(25, 31, 32, 2, 22, 0, '1.3', 'content', '1-3', 1),
(24, 5, 30, 2, 22, 0, '1.2', 'content', '1-2', 1),
(23, 3, 4, 2, 22, 0, '1.1', 'content', '1-1', 1),
(1, 1, 84, 0, 0, 0, 'root', '', 'root', 0),
(22, 2, 45, 1, 1, 0, '1', 'content', '1', 1),
(34, 57, 58, 2, 28, 0, '2.2', 'content', '2-2', 1),
(35, 60, 67, 1, 1, 0, '3', 'content', '3', 1),
(36, 68, 69, 1, 1, 0, '4', 'content', '4', 1),
(37, 70, 71, 1, 1, 0, '5', 'content', '5', 1),
(38, 72, 75, 1, 1, 0, '6', 'content', '6', 1),
(39, 76, 77, 1, 1, 0, '7', 'content', '7', 1),
(40, 78, 79, 1, 1, 0, '8', 'content', '8', 1),
(41, 80, 81, 1, 1, 0, '9', 'content', '9', 1),
(42, 82, 83, 1, 1, 0, '10', 'content', '10', 1),
(43, 7, 8, 4, 26, 0, '1.2.1.1', 'content', '1-2-1-1', 1),
(44, 9, 10, 4, 26, 0, '1.2.1.2', 'content', '1-2-1-2', 1),
(45, 11, 22, 4, 26, 0, '1.2.1.3', 'content', '1-2-1-3', 1),
(46, 23, 24, 4, 26, 0, '1.2.1.4', 'content', '1-2-1-4', 1),
(47, 25, 26, 4, 26, 0, '1.2.1.5', 'content', '1-2-1-5', 1),
(48, 61, 62, 2, 35, 0, '3.1', 'content', '3-1', 1),
(49, 63, 64, 2, 35, 0, '3.2', 'content', '3-2', 1),
(50, 65, 66, 2, 35, 0, '3.3', 'content', '3-3', 1),
(51, 12, 13, 5, 45, 0, '1.2.1.3.1', 'content', '1-2-1-3-1', 1),
(52, 14, 15, 5, 45, 0, '1.2.1.3.2', 'content', '1-2-1-3-2', 1),
(53, 16, 17, 5, 45, 0, '1.2.1.3.3', 'content', '1-2-1-3-3', 1),
(54, 18, 19, 5, 45, 0, '1.2.1.3.4', 'content', '1-2-1-3-4', 1),
(55, 20, 21, 5, 45, 0, '1.2.1.3.5', 'content', '1-2-1-3-5', 1),
(56, 33, 34, 2, 22, 0, '1.4', 'content', '1-4', 0),
(57, 35, 42, 2, 22, 0, '1.5', 'content', '1-5', 0),
(60, 73, 74, 2, 38, 0, '6.1', 'content', '6-1', 1),
(59, 43, 44, 2, 22, 0, '1.6', 'content', '1-6', 1),
(61, 36, 37, 3, 57, 0, '1.5.1', 'content', '1-5-1', 1),
(62, 38, 39, 3, 57, 0, '1.5.2', 'content', '1-5-2', 1),
(63, 40, 41, 3, 57, 0, '1.5.3', 'content', '1-5-3', 1);

-- --------------------------------------------------------

--
-- Table structure for table `jos_categories_details`
--

CREATE TABLE IF NOT EXISTS `jos_categories_details` (
  `cat_id` int(11) NOT NULL,
  `desc_short` mediumtext NOT NULL,
  `desc_full` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `user_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `attachments` text NOT NULL,
  `video` varchar(300) NOT NULL,
  PRIMARY KEY (`cat_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `jos_categories_details`
--

INSERT INTO `jos_categories_details` (`cat_id`, `desc_short`, `desc_full`, `created_at`, `user_id`, `image`, `attachments`, `video`) VALUES
(30, '', '&nbsp;', '2011-06-18 19:01:10', 1, '', '{"images":[]}', ''),
(31, '', '&nbsp;', '2011-06-18 19:01:20', 1, '', '{"images":[]}', ''),
(32, '', '&nbsp;', '2011-06-18 19:01:33', 1, '', '{"images":[]}', ''),
(29, '', '&nbsp;', '2011-06-18 19:01:01', 1, '', '{"images":[]}', ''),
(28, '', '&nbsp;', '2011-06-18 19:00:47', 1, '', '{"images":[]}', ''),
(27, '', '&nbsp;', '2011-06-18 19:00:35', 1, '', '{"images":[]}', ''),
(26, '', '&nbsp;', '2011-06-18 19:00:16', 1, '', '{"images":[]}', ''),
(25, '', '&nbsp;', '2011-06-18 19:00:06', 1, '', '{"images":[]}', ''),
(24, '', '&nbsp;', '2011-06-18 18:59:58', 1, '', '{"images":[]}', ''),
(23, '', '&nbsp;', '2011-06-18 18:59:36', 1, '', '{"images":[]}', ''),
(22, '', '&nbsp;', '2011-06-18 18:59:24', 1, '', '{"images":[]}', ''),
(33, '', '&nbsp;', '2011-06-18 19:01:42', 1, '', '{"images":[]}', ''),
(34, '', '&nbsp;', '2011-06-18 19:01:56', 1, '', '{"images":[]}', ''),
(35, '', '&nbsp;', '2011-06-18 19:02:04', 1, '', '{"images":[]}', ''),
(36, '', '&nbsp;', '2011-06-18 19:02:13', 1, '', '{"images":[]}', ''),
(37, '', '&nbsp;', '2011-06-18 19:02:21', 1, '', '{"images":[]}', ''),
(38, '', '&nbsp;', '2011-06-18 19:02:30', 1, '', '{"images":[]}', ''),
(39, '', '&nbsp;', '2011-06-18 19:02:36', 1, '', '{"images":[]}', ''),
(40, '', '&nbsp;', '2011-06-18 19:02:42', 1, '', '{"images":[]}', ''),
(41, '', '&nbsp;', '2011-06-18 19:02:50', 1, '', '{"images":[]}', ''),
(42, '', '&nbsp;', '2011-06-18 19:02:57', 1, '', '{"images":[]}', ''),
(43, '', '&nbsp;', '2011-06-18 19:03:11', 1, '', '{"images":[]}', ''),
(44, '', '&nbsp;', '2011-06-18 19:03:20', 1, '', '{"images":[]}', ''),
(45, '', '&nbsp;', '2011-06-18 19:03:30', 1, '', '{"images":[]}', ''),
(46, '', '&nbsp;', '2011-06-18 19:03:40', 1, '', '{"images":[]}', ''),
(47, '', '&nbsp;', '2011-06-18 19:03:51', 1, '', '{"images":[]}', ''),
(48, '', '&nbsp;', '2011-06-18 19:04:08', 1, '', '{"images":[]}', ''),
(49, '', '&nbsp;', '2011-06-18 19:04:19', 1, '', '{"images":[]}', ''),
(50, '', '&nbsp;', '2011-06-18 19:04:31', 1, '', '{"images":[]}', ''),
(51, '', '&nbsp;', '2011-06-18 19:04:55', 1, '', '{"images":[]}', ''),
(52, '', '&nbsp;', '2011-06-18 19:05:05', 1, '', '{"images":[]}', ''),
(53, '', '&nbsp;', '2011-06-18 19:05:16', 1, '', '{"images":[]}', ''),
(54, '', '&nbsp;', '2011-06-18 19:05:26', 1, '', '{"images":[]}', ''),
(55, '', '&nbsp;', '2011-06-18 19:05:44', 1, '', '{"images":[]}', ''),
(56, '', '&nbsp;', '2011-06-18 19:06:10', 1, '', '{"images":[]}', ''),
(57, '', '&nbsp;', '2011-06-18 19:06:17', 1, '', '{"images":[]}', ''),
(58, '', ' ', '2011-06-18 19:06:23', 1, '', '{"images":[]}', ''),
(59, '', '&nbsp;', '2011-06-18 19:09:00', 1, '', '{"images":[]}', ''),
(60, '', '&nbsp;', '2011-06-18 19:09:18', 1, '', '{"images":[]}', ''),
(61, '', '&nbsp;', '2011-06-18 19:09:42', 1, '', '{"images":[]}', ''),
(62, '', '&nbsp;', '2011-06-18 19:09:54', 1, '', '{"images":[]}', ''),
(63, '', '&nbsp;', '2011-06-18 19:10:04', 1, '', '{"images":[]}', '');
