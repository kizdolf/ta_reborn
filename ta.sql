-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 01, 2014 at 04:22 PM
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `artiste`
--

INSERT INTO `artiste` (`id`, `date_creation`, `date_update`, `name`, `path_pics`, `text`, `url`, `itw`) VALUES
(9, '2014-08-27 09:59:59', '2014-09-01 14:01:20', 'Aloe Black', '../portfolio/artistes/Aloe Black', '<p>Official video for Aloe Blacc&#39;s &quot;I Need A Dollar&quot; from the album Good Things (Stones Throw) Director: Kahlil Joseph Photography: Matthew J.Lloyd Video Produced by WHat Matters Most and Funk Factory Films This video also contains a short section with a new track called &quot;So Hard&quot; from the album Good Things</p>\r\n', 'https://fr.wikipedia.org/wiki/Aloe_Blacc', 'https://soundcloud.com/toulouse-acoustics/toulouse-acoustics-present-i-me-mine'),
(10, '2014-08-27 12:34:35', '2014-08-29 16:05:23', 'Smia', '../portfolio/artistes/Smia', '<p>smia to bacio, a lot.</p>\r\n', 'lol.com', ''),
(11, '2014-08-27 14:54:15', '2014-08-28 10:04:48', 'test_itw', '../portfolio/artistes/test_itw', 'coucou sounclound!', 'youtube', 'https://soundcloud.com/toulouse-acoustics/toulouse-acoustics-present-i-me-mine'),
(12, '2014-08-28 14:06:10', '2014-08-29 14:44:16', 'LOCAL ONE', '../portfolio/artistes/LOCAL ONE', '<p>Bla</p>\r\n', 'osef', 'osef aussi'),
(13, '2014-08-28 16:22:19', '2014-08-29 16:01:12', 'aze', '../portfolio/artistes/aze', '', 'aze', 'aze'),
(14, '2014-08-29 16:24:05', '0000-00-00 00:00:00', 'looooveee', '../portfolio/artistes/looooveee', '<p>tic tac</p>\r\n', 'blabla.vom', 'https://soundcloud.com/toulouse-acoustics/toulouse-acoustics-present-i-me-mine');

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `quartier`
--

INSERT INTO `quartier` (`id`, `date_creation`, `date_update`, `name`, `path_pics`, `text`, `nb_videos`, `url`) VALUES
(4, '2014-08-27 09:58:17', '0000-00-00 00:00:00', 'Capitole', '../portfolio/quartiers/Capitole', '\r\nLorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod\r\ntempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,\r\nquis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo\r\nconsequat. Duis aute irure dolor in reprehenderit in voluptate velit esse\r\ncillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non\r\nproident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 3, 'https://docs.angularjs.org/api/ng/function/angular.forEach'),
(5, '2014-08-27 12:43:14', '2014-08-28 09:45:06', 'Jean Jaures', '../portfolio/quartiers/Jean Jaures', 'jean Jeaures cey bo ON SE CALME LAAAA', 1, 'https://docs.angularjs.org/api/ng/function/angular.forEach'),
(7, '2014-08-28 15:28:40', '0000-00-00 00:00:00', 'for yours eyes', '../portfolio/quartiers/for yours eyes', '<p>easy hein?</p>\r\n', 2, 'https://docs.angularjs.org/api/ng/function/angular.forEach'),
(8, '2014-08-29 14:31:42', '0000-00-00 00:00:00', 'quartier sans vidÃ©os', '../portfolio/quartiers/quartier sans vidÃ©os', '<p>y&#39;a rien &agrave; voir circulez.</p>\r\n', 0, ''),
(9, '2014-08-29 15:47:17', '0000-00-00 00:00:00', 'des photos!', '../portfolio/quartiers/des photos!', '<p>tof tof tof</p>\r\n', 0, 'https://docs.angularjs.org/api/ng/function/angular.forEach'),
(10, '2014-08-29 16:25:29', '0000-00-00 00:00:00', 'say what?', '../portfolio/quartiers/say what?', '<p>test</p>\r\n', 0, 'huhu'),
(11, '2014-08-29 17:31:18', '0000-00-00 00:00:00', 'Bisous', '../portfolio/quartiers/Bisous', '<p>lol</p>\r\n', 0, 'non');

-- --------------------------------------------------------

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
(3, 'about', '2014-08-29 12:58:03', 'about', 'roo'),
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
(1, '2014-08-28 07:49:04', '2014-08-29 10:18:03', '2014-09-01 08:41:33', 'smio', 'b9be3c1558ec0a007212388ff055bf4a958769279832dedee15c04f8c8e514f33bda053348f69887b9304191aa1d914533c23ffa581c7720582c27d0d063df73', 'kiz', 0, 33),
(2, '2014-08-29 10:06:26', '2014-08-29 12:35:59', '0000-00-00 00:00:00', 're', '344907e89b981caf221d05f597eb57a6af408f15f4dd7895bbd1b96a2938ec24a7dcf23acb94ece0b6d7b0640358bc56bdb448194b9305311aff038a834a079f', 'bof', 1, 0),
(3, '2014-08-29 10:11:29', '2014-08-29 15:52:36', '2014-08-29 17:34:19', 'test2', '344907e89b981caf221d05f597eb57a6af408f15f4dd7895bbd1b96a2938ec24a7dcf23acb94ece0b6d7b0640358bc56bdb448194b9305311aff038a834a079f', 'nonon', 3, 9),
(4, '2014-08-29 10:13:32', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'rofl', '344907e89b981caf221d05f597eb57a6af408f15f4dd7895bbd1b96a2938ec24a7dcf23acb94ece0b6d7b0640358bc56bdb448194b9305311aff038a834a079f', 'coucou', 0, 0);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=27 ;

--
-- Dumping data for table `video`
--

INSERT INTO `video` (`id`, `date_creation`, `date_update`, `category`, `name`, `url`, `id_artiste`, `id_quartier`, `text`, `weekly`) VALUES
(21, '2014-08-27 09:59:59', '2014-09-01 08:41:41', 1, 'I Need A Dollar', 'https://www.youtube.com/watch?v=iR6oYX1D-0w', 9, 4, '<p>Aloe Blacc est un chanteur soul, rappeur et musicien am&eacute;ricain. Son premier album solo, intitul&eacute; Shine Through a &eacute;t&eacute; &eacute;dit&eacute; par Stones Throw Records en 2006, et son second album Good Things en 2010. Il a commenc&eacute; sa carri&egrave;re en 1995 dans le groupe Emanon, mais les m&eacute;dias s&#39;int&eacute;ressent &agrave; lui &agrave; partir de la chanson I Need a Dollar.</p>\r\n', 1),
(22, '2014-08-27 12:34:35', '2014-08-28 13:23:32', 0, 'nomVideoTESTUPDATE', 'https://www.youtube.com/watch?v=iR6oYX1D-0w', 10, 4, '<p>ya plus rien cette semaine!!</p>\r\n', 0),
(23, '2014-08-27 14:54:15', '2014-08-29 07:58:17', 0, 'weekly is this one now :) ', 'https://www.youtube.com/watch?v=ew_cIyRP0UI&list=PL-THY7w0kcYTeVb_BF3arlFlu5dKn_HNe&shuffle=364', 11, 5, '<p>aze</p>\r\n', 0),
(24, '2014-08-28 14:06:10', '2014-08-28 14:34:35', 1, 'LOCAL VID', 'https://www.youtube.com/watch?v=iR6oYX1D-0w', 12, 4, '<p>bla</p>\r\n', 0),
(25, '2014-08-28 16:22:19', '2014-08-29 15:55:16', 0, 'aze', 'http://vimeo.com/103959209', 13, 7, '<p>avec du texte c&#39;est &ugrave;ieux...</p>\r\n', 0),
(26, '2014-08-29 16:24:05', '0000-00-00 00:00:00', 1, 'puttaaaaiiinnn', 'https://www.youtube.com/watch?v=iR6oYX1D-0w', 14, 7, '<p>loul</p>\r\n', 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
