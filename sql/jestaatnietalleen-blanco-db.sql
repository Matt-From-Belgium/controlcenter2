-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 25, 2014 at 07:41 PM
-- Server version: 5.5.38-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `jestaatnietalleen`
--

-- --------------------------------------------------------

--
-- Table structure for table `abonnees`
--

CREATE TABLE IF NOT EXISTS `abonnees` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `voornaam` varchar(30) NOT NULL,
  `familienaam` varchar(30) NOT NULL,
  `mailadres` varchar(50) NOT NULL,
  `confirmed` enum('Y','N') NOT NULL DEFAULT 'N',
  `secretkey` char(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `abonnementen`
--

CREATE TABLE IF NOT EXISTS `abonnementen` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `naam` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `abonnementen`
--

INSERT INTO `abonnementen` (`id`, `naam`) VALUES
(3, 'Veurne-Diksmuide'),
(4, 'Ieper'),
(5, 'Brugge');

-- --------------------------------------------------------

--
-- Table structure for table `abonnementenlink`
--

CREATE TABLE IF NOT EXISTS `abonnementenlink` (
  `abonnement` int(2) NOT NULL,
  `abonnee` int(5) NOT NULL,
  PRIMARY KEY (`abonnement`,`abonnee`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ajaxwhitelist`
--

CREATE TABLE IF NOT EXISTS `ajaxwhitelist` (
  `id` int(6) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `file` varchar(500) NOT NULL,
  `function` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `ajaxwhitelist`
--

INSERT INTO `ajaxwhitelist` (`id`, `file`, `function`) VALUES
(000001, '/core/logic/usermanagement/fbLoginAjax.php', 'checkFBAccount'),
(000002, '/core/templatesystem/templatelogic.php', 'setCookiesOk');

-- --------------------------------------------------------

--
-- Table structure for table `albums`
--

CREATE TABLE IF NOT EXISTS `albums` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE IF NOT EXISTS `languages` (
  `id` int(2) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `name` varchar(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `search_by_name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `name`) VALUES
(01, 'nederlands'),
(02, 'english');

-- --------------------------------------------------------

--
-- Table structure for table `login_attempts`
--

CREATE TABLE IF NOT EXISTS `login_attempts` (
  `username` varchar(20) NOT NULL DEFAULT '',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`username`,`time`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE IF NOT EXISTS `modules` (
  `id` int(2) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `modules`
--

INSERT INTO `modules` (`id`, `name`) VALUES
(01, 'Usermanagement'),
(03, 'nieuwsbrief'),
(04, 'fotoalbum');

-- --------------------------------------------------------

--
-- Table structure for table `nieuwsbriefabonnementen`
--

CREATE TABLE IF NOT EXISTS `nieuwsbriefabonnementen` (
  `nieuwsbrief` int(5) NOT NULL,
  `abonnement` int(2) NOT NULL,
  PRIMARY KEY (`nieuwsbrief`,`abonnement`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `nieuwsbrieven`
--

CREATE TABLE IF NOT EXISTS `nieuwsbrieven` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `maand` int(2) NOT NULL,
  `jaar` int(4) NOT NULL,
  `titel` varchar(50) NOT NULL,
  `verstuurd` enum('Y','N') DEFAULT 'N',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `parameters`
--

CREATE TABLE IF NOT EXISTS `parameters` (
  `id` int(3) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `value` varchar(500) NOT NULL,
  `overridable` bit(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `search_by_name` (`name`(20))
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26 ;

--
-- Dumping data for table `parameters`
--

INSERT INTO `parameters` (`id`, `name`, `value`, `overridable`) VALUES
(001, 'CORE_LANGUAGE', '1', b'1'),
(002, 'CORE_USER_SELF_ACTIVATION', '1', b'0'),
(003, 'CORE_USER_ADMIN_ACTIVATION', '1', b'0'),
(004, 'CORE_USER_EXT_USERGROUP', '001', b'0'),
(005, 'CORE_USER_EXT_REGISTRATION', '1', b'0'),
(006, 'CORE_NOACCESS_URL', '', b'0'),
(007, 'CORE_SERVER_MAILADRESS', 'noreply@dragoneyehosting.be', b'0'),
(008, 'CORE_DEBUG_MODE', '1', b'0'),
(009, 'CORE_DEBUG_MAIL', 'matthias.bauw@gmail.com', b'0'),
(010, 'CORE_FB_LOGIN_ENABLED', '0', b'0'),
(011, 'CORE_FB_APPID', '', b'0'),
(012, 'CORE_FB_SAPPID', '', b'0'),
(013, 'CORE_FB_SCOPE', 'email', b'0'),
(014, 'CORE_SITE_NAME', 'testserver', b'0'),
(015, 'CORE_FB_APP_NAMESPACE', '', b'0'),
(016, 'SITE_META_TITLE', 'CONTROLCENTER SERVER', b'0'),
(017, 'SITE_META_DESCRIPTION', 'Wij zijn een groep van enthousiaste zangers en zangeressen uit Diksmuide. Het koor pint zich niet vast op een genre maar probeert u telkens weer te verrassen. In december bracht CHANTage een kerstconcert in Woumen, voor 2014 staat er een Beatles-concert gepland.', b'0'),
(018, 'SITE_META_IMAGE', '', b'0'),
(019, 'SITE_META_URL', '', b'0'),
(020, 'CORE_RECAPTCHA_PUBLIC', '6LdR-eYSAAAAAEboUatksFIHeb6m4CvmTT1-7_5p ', b'0'),
(021, 'CORE_RECAPTCHA_PRIVATE', '6LdR-eYSAAAAAM2V3SNVOLmiFEQJOz6TrgWBNfu3 ', b'0'),
(023, 'NIEUWSBRIEF_PROMOTEXT', 'Schrijf je in voor de nieuwsbrief en kom alles te over je rechten en plichten als KMO-werknemer. Bovendien ontvang je alle gegevens over de acties die we organiseren in onze regio.', b'1'),
(024, 'CORE_MAINTENANCE_MODE', '1', b'0'),
(025, 'CORE_SSL_ENABLED', '0', b'1');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int(5) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `module` int(2) unsigned zerofill DEFAULT NULL,
  `name` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `module`, `name`) VALUES
(00001, 01, 'manage usergroups'),
(00002, 01, 'add users'),
(00003, 01, 'edit users'),
(00004, NULL, 'interrupt service'),
(00005, NULL, 'login during interruption'),
(00007, 03, 'beheerscherm openen'),
(00008, 03, 'abonneedetails bekijken'),
(00009, 03, 'nieuwsbrieven versturen'),
(00011, 04, 'manage albums'),
(00012, NULL, 'algemeen beheerscherm');

-- --------------------------------------------------------

--
-- Table structure for table `photos`
--

CREATE TABLE IF NOT EXISTS `photos` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `extension` varchar(4) NOT NULL,
  `album` int(3) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

-- --------------------------------------------------------

--
-- Table structure for table `templatealiases`
--

CREATE TABLE IF NOT EXISTS `templatealiases` (
  `id` int(2) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `pc_directory` varchar(30) NOT NULL,
  `phone_directory` varchar(30) DEFAULT NULL,
  `tablet_directory` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `search_by_name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `templatealiases`
--

INSERT INTO `templatealiases` (`id`, `name`, `pc_directory`, `phone_directory`, `tablet_directory`) VALUES
(01, 'frontend', 'jestaatnietalleen', NULL, NULL),
(02, 'backend', 'jestaatnietalleen', NULL, NULL),
(03, 'mail', 'mail', NULL, NULL),
(04, 'maintenance', 'binnenkort', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `usergroupmembers`
--

CREATE TABLE IF NOT EXISTS `usergroupmembers` (
  `user` int(6) unsigned zerofill NOT NULL,
  `usergroup` int(3) unsigned zerofill NOT NULL,
  PRIMARY KEY (`user`,`usergroup`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `usergroupmembers`
--

INSERT INTO `usergroupmembers` (`user`, `usergroup`) VALUES
(000001, 001),
(000001, 002);

-- --------------------------------------------------------

--
-- Table structure for table `usergrouppermissions`
--

CREATE TABLE IF NOT EXISTS `usergrouppermissions` (
  `usergroup` int(3) unsigned zerofill NOT NULL,
  `moduletask` int(5) unsigned zerofill NOT NULL,
  PRIMARY KEY (`usergroup`,`moduletask`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `usergrouppermissions`
--

INSERT INTO `usergrouppermissions` (`usergroup`, `moduletask`) VALUES
(001, 00001),
(001, 00002),
(001, 00003),
(001, 00004),
(001, 00005),
(001, 00007),
(001, 00012),
(002, 00007),
(002, 00008),
(002, 00009),
(002, 00011);

-- --------------------------------------------------------

--
-- Table structure for table `usergroups`
--

CREATE TABLE IF NOT EXISTS `usergroups` (
  `id` int(3) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `search_by_groupname` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `usergroups`
--

INSERT INTO `usergroups` (`id`, `name`) VALUES
(001, 'administrators'),
(002, 'redactie');

-- --------------------------------------------------------

--
-- Table structure for table `userpermissions`
--

CREATE TABLE IF NOT EXISTS `userpermissions` (
  `user` int(6) unsigned zerofill NOT NULL,
  `moduletask` int(5) unsigned zerofill NOT NULL,
  PRIMARY KEY (`user`,`moduletask`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=42 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `facebookid`, `password`, `salt`, `passwordchangerequired`, `userconfirmation`, `adminconfirmation`, `realname`, `realfirstname`, `mailadress`, `website`, `country`) VALUES
(000001, 'Matt', '', 'b03561292506d42a9b45217b6e27e6dec9afd2a787b6c49b503f296728d085affc395b9c517b9ec2aaf8bf3535cf79e372a89705c4714d7f28d0fa8a147211fd', 'dc4f4e55dee83cad9345fc8e3f2131b18a00bece7cf09593141deead9235ed9a169a5da7b324719eb9240a029e7c172490e9714bf20f5f204d8bbd2b1ef3e9ce', '0', '1', '1', 'Bauw', 'Matthias', 'matthias.bauw@gmail.com', NULL, NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
