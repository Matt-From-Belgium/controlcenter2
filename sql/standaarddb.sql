-- phpMyAdmin SQL Dump
-- version 4.1.8
-- http://www.phpmyadmin.net
--
-- Machine: localhost
-- Gegenereerd op: 28 apr 2015 om 13:36
-- Serverversie: 5.5.40-36.1-log
-- PHP-versie: 5.4.23

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
-- Tabelstructuur voor tabel `ajaxwhitelist`
--

CREATE TABLE IF NOT EXISTS `ajaxwhitelist` (
  `id` int(6) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `file` varchar(500) NOT NULL,
  `function` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `file` (`file`),
  KEY `function` (`function`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Gegevens worden geëxporteerd voor tabel `ajaxwhitelist`
--

INSERT INTO `ajaxwhitelist` (`id`, `file`, `function`) VALUES
(000001, '/core/logic/usermanagement/fbLoginAjax.php', 'checkFBAccount'),
(000002, '/core/templatesystem/templatelogic.php', 'setCookiesOk');

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
-- Gegevens worden geëxporteerd voor tabel `languages`
--

INSERT INTO `languages` (`id`, `name`) VALUES
(01, 'nederlands'),
(02, 'english');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `login_attempts`
--

CREATE TABLE IF NOT EXISTS `login_attempts` (
  `username` varchar(20) NOT NULL DEFAULT '',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`username`,`time`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `modules`
--

CREATE TABLE IF NOT EXISTS `modules` (
  `id` int(2) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Gegevens worden geëxporteerd voor tabel `modules`
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
  UNIQUE KEY `search_by_name` (`name`(20)),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=25 ;

--
-- Gegevens worden geëxporteerd voor tabel `parameters`
--

INSERT INTO `parameters` (`id`, `name`, `value`, `overridable`) VALUES
(001, 'CORE_LANGUAGE', '1', b'1'),
(002, 'CORE_USER_SELF_ACTIVATION', '1', b'0'),
(003, 'CORE_USER_ADMIN_ACTIVATION', '0', b'0'),
(004, 'CORE_USER_EXT_USERGROUP', '001', b'0'),
(005, 'CORE_USER_EXT_REGISTRATION', '0', b'0'),
(006, 'CORE_NOACCESS_URL', '', b'0'),
(007, 'CORE_SERVER_MAILADRESS', 'noreply@dragoneyehosting.be', b'0'),
(008, 'CORE_DEBUG_MODE', '0', b'0'),
(009, 'CORE_DEBUG_MAIL', 'matthiasba@linux.be', b'0'),
(010, 'CORE_FB_LOGIN_ENABLED', '0', b'0'),
(011, 'CORE_FB_APPID', '', b'0'),
(012, 'CORE_FB_SAPPID', '', b'0'),
(013, 'CORE_FB_SCOPE', 'email', b'0'),
(014, 'CORE_SITE_NAME', 'CONTROLCENTER SERVER', b'0'),
(015, 'CORE_FB_APP_NAMESPACE', '', b'0'),
(016, 'SITE_META_TITLE', 'CONTROLCENTER SERVER', b'0'),
(017, 'SITE_META_DESCRIPTION', 'Controlcenter testserver', b'0'),
(022, 'CORE_SSL_ENABLED', '0', b'0'),
(018, 'SITE_META_IMAGE', '', b'0'),
(019, 'SITE_META_URL', '', b'0'),
(020, 'CORE_RECAPTCHA_PUBLIC', '6LdR-eYSAAAAAEboUatksFIHeb6m4CvmTT1-7_5p ', b'0'),
(021, 'CORE_RECAPTCHA_PRIVATE', '6LdR-eYSAAAAAM2V3SNVOLmiFEQJOz6TrgWBNfu3 ', b'0'),
(023, 'CORE_MAINTENANCE_MODE', '1', b'0'),
(024, 'CORE_FB_LOAD_API', '0', b'0');

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
-- Gegevens worden geëxporteerd voor tabel `permissions`
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
  `pc_directory` varchar(30) NOT NULL,
  `phone_directory` varchar(30) DEFAULT NULL,
  `tablet_directory` varchar(30) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `search_by_name` (`name`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Gegevens worden geëxporteerd voor tabel `templatealiases`
--

INSERT INTO `templatealiases` (`id`, `name`, `pc_directory`, `phone_directory`, `tablet_directory`) VALUES
(01, 'frontend', 'alpha3', NULL, NULL),
(02, 'backend', 'alpha3', NULL, NULL),
(03, 'mail', 'mail', NULL, NULL),
(04, 'maintenance', 'maintenance', NULL, NULL);

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
-- Gegevens worden geëxporteerd voor tabel `usergroupmembers`
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
-- Gegevens worden geëxporteerd voor tabel `usergrouppermissions`
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
-- Gegevens worden geëxporteerd voor tabel `usergroups`
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
  UNIQUE KEY `search_by_username` (`username`),
  KEY `username` (`username`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=76 ;

--
-- Gegevens worden geëxporteerd voor tabel `users`
--

INSERT INTO `users` (`id`, `username`, `facebookid`, `password`, `salt`, `passwordchangerequired`, `userconfirmation`, `adminconfirmation`, `realname`, `realfirstname`, `mailadress`, `website`, `country`) VALUES
(000001, 'Matt', '', 'c469a4d67bcacc1f5e504f7ea5dbf9d01bd8f7474f150af51e55789fa46db6a663ec2c6863ba70bf8fd467fea9d902e5792f7dcc9f36d10d2580115f2a117e0d', 'a9ef9d9a4799b3d1e706e0a5d5a3637241bb7821252265f9f52889e95411d37b7f3f258b751512b35a78605a42d5743dfbb42f7871e25e0b04031d3cd2ab79ba', '', '1', '1', 'Bauw', 'Matthias', 'matthias.bauw@gmail.com', NULL, NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
