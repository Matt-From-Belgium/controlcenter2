-- phpMyAdmin SQL Dump
-- version 3.5.8.1deb1
-- http://www.phpmyadmin.net
--
-- Machine: localhost
-- Genereertijd: 09 aug 2013 om 21:27
-- Serverversie: 5.5.32-0ubuntu0.13.04.1
-- PHP-versie: 5.4.9-4ubuntu2.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databank: `controlcenter`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `countries`
--

CREATE TABLE IF NOT EXISTS `countries` (
  `code` char(2) NOT NULL DEFAULT '',
  PRIMARY KEY (`code`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Gegevens worden uitgevoerd voor tabel `countries`
--

INSERT INTO `countries` (`code`) VALUES
('AD'),
('AE'),
('AF'),
('AG'),
('AI'),
('AL'),
('AM'),
('AN'),
('AO'),
('AQ'),
('AR'),
('AS'),
('AT'),
('AU'),
('AW'),
('AX'),
('AZ'),
('BA'),
('BB'),
('BD'),
('BE'),
('BF'),
('BG'),
('BH'),
('BI'),
('BJ'),
('BL'),
('BM'),
('BN'),
('BO'),
('BR'),
('BS'),
('BT'),
('BV'),
('BW'),
('BY'),
('BZ'),
('CA'),
('CC'),
('CF'),
('CG'),
('CH'),
('CI'),
('CK'),
('CL'),
('CM'),
('CN'),
('CO'),
('CR'),
('CU'),
('CV'),
('CX'),
('CY'),
('CZ'),
('DE'),
('DJ'),
('DK'),
('DM'),
('DO'),
('DZ'),
('EC'),
('EE'),
('EG'),
('EH'),
('ER'),
('ES'),
('ET'),
('FI'),
('FJ'),
('FK'),
('FM'),
('FO'),
('FR'),
('GA'),
('GB'),
('GD'),
('GE'),
('GF'),
('GG'),
('GH'),
('GI'),
('GL'),
('GM'),
('GN'),
('GP'),
('GQ'),
('GR'),
('GS'),
('GT'),
('GU'),
('GW'),
('GY'),
('HK'),
('HM'),
('HN'),
('HR'),
('HT'),
('HU'),
('ID'),
('IE'),
('IL'),
('IM'),
('IN'),
('IO'),
('IQ'),
('IR'),
('IS'),
('IT'),
('JE'),
('JM'),
('JO'),
('JP'),
('KE'),
('KG'),
('KH'),
('KI'),
('KM'),
('KN'),
('KP'),
('KR'),
('KW'),
('KY'),
('KZ'),
('LA'),
('LB'),
('LC'),
('LI'),
('LK'),
('LR'),
('LS'),
('LT'),
('LU'),
('LV'),
('LY'),
('MA'),
('MC'),
('MD'),
('ME'),
('MF'),
('MG'),
('MH'),
('MK'),
('ML'),
('MM'),
('MN'),
('MO'),
('MP'),
('MQ'),
('MR'),
('MS'),
('MT'),
('MU'),
('MV'),
('MW'),
('MX'),
('MY'),
('MZ'),
('NA'),
('NC'),
('NE'),
('NF'),
('NG'),
('NI'),
('NL'),
('NO'),
('NP'),
('NR'),
('NU'),
('NZ'),
('OM'),
('PA'),
('PE'),
('PF'),
('PG'),
('PH'),
('PK'),
('PL'),
('PM'),
('PN'),
('PR'),
('PS'),
('PT'),
('PW'),
('PY'),
('QA'),
('RE'),
('RO'),
('RS'),
('RU'),
('RW'),
('SA'),
('SB'),
('SC'),
('SD'),
('SE'),
('SG'),
('SH'),
('SI'),
('SJ'),
('SK'),
('SL'),
('SM'),
('SN'),
('SO'),
('SR'),
('ST'),
('SV'),
('SY'),
('SZ'),
('TC'),
('TD'),
('TF'),
('TG'),
('TH'),
('TJ'),
('TK'),
('TL'),
('TM'),
('TN'),
('TO'),
('TR'),
('TT'),
('TV'),
('TW'),
('TZ'),
('UA'),
('UG'),
('UM'),
('US'),
('UY'),
('UZ'),
('VA'),
('VC'),
('VE'),
('VG'),
('VI'),
('VN'),
('VU'),
('WF'),
('WS'),
('YE'),
('YT'),
('ZA'),
('ZM'),
('ZW');

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Gegevens worden uitgevoerd voor tabel `parameters`
--

INSERT INTO `parameters` (`id`, `name`, `value`, `overridable`) VALUES
(001, 'CORE_LANGUAGE', '1', b'1'),
(002, 'CORE_USER_SELF_ACTIVATION', '1', b'1'),
(003, 'CORE_USER_ADMIN_ACTIVATION', '1', b'1'),
(004, 'CORE_USER_EXT_USERGROUP', '001', b'1'),
(005, 'CORE_USER_EXT_REGISTRATION', '0', b'1'),
(006, 'CORE_NOACCESS_URL', '', b'1'),
(007, 'CORE_SERVER_MAILADRESS', 'noreply@dragoneyehosting.be', b'1'),
(008, 'CORE_DEBUG_MODE', '0', b'0'),
(009, 'CORE_DEBUG_MAIL', 'matthiasba@Linux.be', b'1');

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
  `username` varchar(15) NOT NULL,
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Gegevens worden uitgevoerd voor tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `passwordchangerequired`, `userconfirmation`, `adminconfirmation`, `realname`, `realfirstname`, `mailadress`, `website`, `country`) VALUES
(000001, 'Admin', '71ea9c396816dc0db6571445abe2842f', '0', '1', '1', 'Bauw', 'Matthias', 'matthias.bauw@gmail.com', '', 'BE');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
