-- phpMyAdmin SQL Dump
-- version 4.0.8
-- http://www.phpmyadmin.net
--
-- Machine: localhost
-- Genereertijd: 04 mrt 2014 om 13:28
-- Serverversie: 5.1.67-rel14.3-log
-- PHP-versie: 5.3.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databank: `ccenter2_data`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `languages`
--

CREATE TABLE IF NOT EXISTS `languages` (
  `id` int(2) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `name` varchar(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `search_by_name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Gegevens worden uitgevoerd voor tabel `languages`
--

INSERT INTO `languages` (`id`, `name`) VALUES
(01, 'nederlands'),
(02, 'english');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `modules`
--

CREATE TABLE IF NOT EXISTS `modules` (
  `id` int(2) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Gegevens worden uitgevoerd voor tabel `modules`
--

INSERT INTO `modules` (`id`, `name`) VALUES
(01, 'Usermanagement');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `parameters`
--

CREATE TABLE IF NOT EXISTS `parameters` (
  `id` int(3) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `value` varchar(500) NOT NULL,
  `overridable` bit(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `search_by_name` (`name`(20))
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=22 ;

--
-- Gegevens worden uitgevoerd voor tabel `parameters`
--

INSERT INTO `parameters` (`id`, `name`, `value`, `overridable`) VALUES
(001, 'CORE_LANGUAGE', '1', b'1'),
(002, 'CORE_USER_SELF_ACTIVATION', '1', b'0'),
(003, 'CORE_USER_ADMIN_ACTIVATION', '1', b'0'),
(004, 'CORE_USER_EXT_USERGROUP', '001', b'0'),
(005, 'CORE_USER_EXT_REGISTRATION', '0', b'0'),
(006, 'CORE_NOACCESS_URL', '', b'0'),
(007, 'CORE_SERVER_MAILADRESS', 'noreply@dragoneyehosting.be', b'0'),
(008, 'CORE_DEBUG_MODE', '0', b'0'),
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
(021, 'CORE_RECAPTCHA_PRIVATE', '6LdR-eYSAAAAAM2V3SNVOLmiFEQJOz6TrgWBNfu3 ', b'0');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `permissions`
--

CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int(5) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `module` int(2) unsigned zerofill DEFAULT NULL,
  `name` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Gegevens worden uitgevoerd voor tabel `permissions`
--

INSERT INTO `permissions` (`id`, `module`, `name`) VALUES
(00001, 01, 'manage usergroups'),
(00002, 01, 'add users'),
(00003, 01, 'edit users'),
(00004, NULL, 'interrupt service'),
(00005, NULL, 'login during interruption');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `templatealiases`
--

CREATE TABLE IF NOT EXISTS `templatealiases` (
  `id` int(2) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `directory` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `search_by_name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Gegevens worden uitgevoerd voor tabel `templatealiases`
--

INSERT INTO `templatealiases` (`id`, `name`, `directory`) VALUES
(01, 'frontend', 'alpha3'),
(02, 'backend', 'alpha3'),
(03, 'mail', 'mail');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `usergroupmembers`
--

CREATE TABLE IF NOT EXISTS `usergroupmembers` (
  `user` int(6) unsigned zerofill NOT NULL,
  `usergroup` int(3) unsigned zerofill NOT NULL,
  PRIMARY KEY (`user`,`usergroup`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Gegevens worden uitgevoerd voor tabel `usergroupmembers`
--

INSERT INTO `usergroupmembers` (`user`, `usergroup`) VALUES
(000001, 001);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `usergrouppermissions`
--

CREATE TABLE IF NOT EXISTS `usergrouppermissions` (
  `usergroup` int(3) unsigned zerofill NOT NULL,
  `moduletask` int(5) unsigned zerofill NOT NULL,
  PRIMARY KEY (`usergroup`,`moduletask`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Gegevens worden uitgevoerd voor tabel `usergrouppermissions`
--

INSERT INTO `usergrouppermissions` (`usergroup`, `moduletask`) VALUES
(001, 00001),
(001, 00002),
(001, 00003),
(001, 00004),
(001, 00005);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `usergroups`
--

CREATE TABLE IF NOT EXISTS `usergroups` (
  `id` int(3) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `search_by_groupname` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Gegevens worden uitgevoerd voor tabel `usergroups`
--

INSERT INTO `usergroups` (`id`, `name`) VALUES
(001, 'administrators');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `userpermissions`
--

CREATE TABLE IF NOT EXISTS `userpermissions` (
  `user` int(6) unsigned zerofill NOT NULL,
  `moduletask` int(5) unsigned zerofill NOT NULL,
  PRIMARY KEY (`user`,`moduletask`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(6) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `facebookid` varchar(20) DEFAULT NULL,
  `password` varchar(32) NOT NULL,
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=41 ;

--
-- Gegevens worden uitgevoerd voor tabel `users`
--

INSERT INTO `users` (`id`, `username`, `facebookid`, `password`, `passwordchangerequired`, `userconfirmation`, `adminconfirmation`, `realname`, `realfirstname`, `mailadress`, `website`, `country`) VALUES
(000001, 'Admin', '', '71ea9c396816dc0db6571445abe2842f', '0', '1', '1', 'Bauw', 'Matthias', 'matthias@ittakestwo.be', '', 'BE');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
