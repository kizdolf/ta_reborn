-- phpMyAdmin SQL Dump
-- version 4.2.12deb2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 19, 2015 at 05:31 PM
-- Server version: 5.6.24-0ubuntu2
-- PHP Version: 5.6.4-4ubuntu6.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `toulouse_acoustics`
--

-- --------------------------------------------------------

--
-- Table structure for table `artiste`
--

CREATE TABLE IF NOT EXISTS `artiste` (
`id` int(11) NOT NULL,
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_update` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `name` varchar(255) NOT NULL,
  `path_pics` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `url` varchar(512) NOT NULL,
  `itw` varchar(512) NOT NULL,
  `id_style` int(11) NOT NULL,
  `path_vignette` varchar(512) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `artiste`
--

INSERT INTO `artiste` (`id`, `date_creation`, `date_update`, `name`, `path_pics`, `text`, `url`, `itw`, `id_style`, `path_vignette`) VALUES
(1, '2015-07-19 13:08:07', '2015-07-19 14:33:46', 'artiste1', '../img/uniques/artiste/1', '<p>coucou</p>\r\n', 'url2015', 'https://soundcloud.com/lowtemp-music/07-gramatik-swucca-chust', 1, 'img/uniques/artiste/1.png');

-- --------------------------------------------------------

--
-- Table structure for table `draft`
--

CREATE TABLE IF NOT EXISTS `draft` (
  `user` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `text` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `partner`
--

CREATE TABLE IF NOT EXISTS `partner` (
`id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `desc` text NOT NULL,
  `url` varchar(255) NOT NULL,
  `path_logo` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `partner`
--

INSERT INTO `partner` (`id`, `name`, `desc`, `url`, `path_logo`) VALUES
(1, 'test', '<p>coucou</p>\r\n', 'https://www.facebook.com/', 'img/uniques/logo/test.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `quartier`
--

CREATE TABLE IF NOT EXISTS `quartier` (
`id` int(11) NOT NULL,
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_update` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `name` varchar(255) NOT NULL,
  `path_pics` varchar(255) NOT NULL,
  `text` text NOT NULL,
  `nb_videos` int(11) NOT NULL DEFAULT '0',
  `url` varchar(512) NOT NULL,
  `path_vignette` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `quartier`
--

INSERT INTO `quartier` (`id`, `date_creation`, `date_update`, `name`, `path_pics`, `text`, `nb_videos`, `url`, `path_vignette`) VALUES
(2, '2015-07-19 13:06:24', '2015-07-19 14:49:19', 'lieu 2015!', '../img/uniques/quartier/2', '<p>coucou</p>\r\n', 1, 'aze', 'img/uniques/quartier/2.png');

-- --------------------------------------------------------

--
-- Table structure for table `style`
--

CREATE TABLE IF NOT EXISTS `style` (
`id` int(11) NOT NULL,
  `name` varchar(248) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `style`
--

INSERT INTO `style` (`id`, `name`) VALUES
(2, '2015'),
(1, '2016');

-- --------------------------------------------------------

--
-- Table structure for table `text`
--

CREATE TABLE IF NOT EXISTS `text` (
`id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL,
  `date_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `text` longtext NOT NULL,
  `name_admin` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `text`
--

INSERT INTO `text` (`id`, `name`, `date_update`, `text`, `name_admin`) VALUES
(3, 'about', '2015-07-19 14:13:49', '<p>Ceci et le A propos.&nbsp;</p>\r\n\r\n<p>Et pi c&#39;est tout.&nbsp;</p>\r\n\r\n<p>faut &eacute;crire un truc bien par l&agrave;!</p>\r\n', ''),
(4, 'short_about', '2014-08-29 12:58:33', 'short about', 'root'),
(5, 'team', '2014-08-29 12:59:01', 'ici la team!', 'root'),
(6, 'contact', '2014-08-29 12:59:52', 'contact text', 'root');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
`id` int(11) NOT NULL,
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_update` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `date_last_visit` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `ta_login` varchar(255) NOT NULL,
  `ta_password` text NOT NULL,
  `mail` varchar(255) NOT NULL,
  `rights` int(11) NOT NULL,
  `nb_visits` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `date_creation`, `date_update`, `date_last_visit`, `ta_login`, `ta_password`, `mail`, `rights`, `nb_visits`) VALUES
(1, '2014-08-28 07:49:04', '2014-08-29 10:18:03', '2015-07-19 11:49:36', 'smio', 'b9be3c1558ec0a007212388ff055bf4a958769279832dedee15c04f8c8e514f33bda053348f69887b9304191aa1d914533c23ffa581c7720582c27d0d063df73', 'kiz', 0, 35),
(2, '2014-08-29 10:06:26', '2014-08-29 12:35:59', '0000-00-00 00:00:00', 're', '344907e89b981caf221d05f597eb57a6af408f15f4dd7895bbd1b96a2938ec24a7dcf23acb94ece0b6d7b0640358bc56bdb448194b9305311aff038a834a079f', 'bof', 1, 0),
(3, '2014-08-29 10:11:29', '2014-08-29 15:52:36', '2014-08-29 17:34:19', 'test2', '344907e89b981caf221d05f597eb57a6af408f15f4dd7895bbd1b96a2938ec24a7dcf23acb94ece0b6d7b0640358bc56bdb448194b9305311aff038a834a079f', 'nonon', 3, 9),
(4, '2014-08-29 10:13:32', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'rofl', '344907e89b981caf221d05f597eb57a6af408f15f4dd7895bbd1b96a2938ec24a7dcf23acb94ece0b6d7b0640358bc56bdb448194b9305311aff038a834a079f', 'coucou', 0, 0),
(5, '2015-07-19 13:12:12', '0000-00-00 00:00:00', '2015-07-19 13:12:26', 'test', '344907e89b981caf221d05f597eb57a6af408f15f4dd7895bbd1b96a2938ec24a7dcf23acb94ece0b6d7b0640358bc56bdb448194b9305311aff038a834a079f', 'osef', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `video`
--

CREATE TABLE IF NOT EXISTS `video` (
`id` int(11) NOT NULL,
  `date_creation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_update` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `category` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `url` varchar(512) NOT NULL,
  `id_artiste` int(11) NOT NULL,
  `id_quartier` int(11) NOT NULL,
  `text` text NOT NULL,
  `weekly` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `video`
--

INSERT INTO `video` (`id`, `date_creation`, `date_update`, `category`, `name`, `url`, `id_artiste`, `id_quartier`, `text`, `weekly`) VALUES
(1, '2015-07-19 13:08:07', '2015-07-19 14:33:06', 0, 'video 2015', 'https://www.youtube.com/watch?v=2J96pMBGYSQ', 1, 2, '<p>coucou</p>\r\n\r\n<p>Viiid&eacute;&eacute;&eacute;&eacute;&eacute;ooooooooo</p>\r\n', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `artiste`
--
ALTER TABLE `artiste`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `partner`
--
ALTER TABLE `partner`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `quartier`
--
ALTER TABLE `quartier`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `style`
--
ALTER TABLE `style`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `text`
--
ALTER TABLE `text`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `video`
--
ALTER TABLE `video`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `artiste`
--
ALTER TABLE `artiste`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `partner`
--
ALTER TABLE `partner`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `quartier`
--
ALTER TABLE `quartier`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `style`
--
ALTER TABLE `style`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `text`
--
ALTER TABLE `text`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `video`
--
ALTER TABLE `video`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
