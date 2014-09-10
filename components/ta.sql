-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 29, 2014 at 03:02 PM
-- Server version: 5.5.38
-- PHP Version: 5.3.10-1ubuntu3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ta`
--

-- --------------------------------------------------------

--
-- Table structure for table `artiste`
--

CREATE TABLE IF NOT EXISTS `artiste` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_update` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `name` varchar(255) NOT NULL,
  `path_pics` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `url` varchar(512) NOT NULL,
  `itw` varchar(512) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `artiste`
--

-- --------------------------------------------------------

--
-- Table structure for table `quartier`
--

CREATE TABLE IF NOT EXISTS `quartier` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_update` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `name` varchar(255) NOT NULL,
  `path_pics` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `nb_videos` int(11) NOT NULL DEFAULT '0',
  `url` varchar(512) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `quartier`
--


--
-- Table structure for table `text`
--

CREATE TABLE IF NOT EXISTS `text` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL,
  `date_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `text` longtext NOT NULL,
  `name_admin` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `text`
--

INSERT INTO `text` (`id`, `name`, `date_update`, `text`, `name_admin`) VALUES
(3, 'about', '2014-08-29 12:58:03', 'about', 'root'),
(4, 'short_about', '2014-08-29 12:58:33', 'short about', 'root'),
(5, 'team', '2014-08-29 12:59:01', 'ici la team!', 'root'),
(6, 'contact', '2014-08-29 12:59:52', 'contact text', 'root');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_update` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_last_visit` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ta_login` varchar(255) NOT NULL,
  `ta_password` text NOT NULL,
  `mail` varchar(255) NOT NULL,
  `rights` int(11) NOT NULL,
  `nb_visits` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `date_creation`, `date_update`, `date_last_visit`, `ta_login`, `ta_password`, `mail`, `rights`, `nb_visits`) VALUES
(1, '2014-08-28 07:49:04', '2014-08-29 10:18:03', '2014-08-29 12:12:19', 'smio', 'b9be3c1558ec0a007212388ff055bf4a958769279832dedee15c04f8c8e514f33bda053348f69887b9304191aa1d914533c23ffa581c7720582c27d0d063df73', 'kiz', 0, 127);

-- --------------------------------------------------------

--
-- Table structure for table `video`
--

CREATE TABLE IF NOT EXISTS `video` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_update` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `category` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `url` varchar(512) NOT NULL,
  `id_artiste` int(11) NOT NULL,
  `id_quartier` int(11) NOT NULL,
  `text` text NOT NULL,
  `weekly` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=26 ;

--
-- Dumping data for table `video`
--

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
