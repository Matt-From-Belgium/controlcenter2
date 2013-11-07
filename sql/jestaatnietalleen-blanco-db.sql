-- phpMyAdmin SQL Dump
-- version 3.5.8.1deb1
-- http://www.phpmyadmin.net
--
-- Machine: localhost
-- Genereertijd: 07 nov 2013 om 22:04
-- Serverversie: 5.5.34-0ubuntu0.13.04.1
-- PHP-versie: 5.4.9-4ubuntu2.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databank: `jestaatnietalleen`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `abonnees`
--

CREATE TABLE IF NOT EXISTS `abonnees` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `voornaam` varchar(30) NOT NULL,
  `familienaam` varchar(30) NOT NULL,
  `mailadres` varchar(50) NOT NULL,
  `confirmed` enum('Y','N') NOT NULL DEFAULT 'N',
  `secretkey` char(32) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `abonnementen`
--

CREATE TABLE IF NOT EXISTS `abonnementen` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `naam` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Gegevens worden uitgevoerd voor tabel `abonnementen`
--

INSERT INTO `abonnementen` (`id`, `naam`) VALUES
(1, 'Regio Veurne'),
(2, 'Regio Diksmuide');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `abonnementenlink`
--

CREATE TABLE IF NOT EXISTS `abonnementenlink` (
  `abonnement` int(2) NOT NULL,
  `abonnee` int(5) NOT NULL,
  PRIMARY KEY (`abonnement`,`abonnee`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `albums`
--

CREATE TABLE IF NOT EXISTS `albums` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Gegevens worden uitgevoerd voor tabel `modules`
--

INSERT INTO `modules` (`id`, `name`) VALUES
(01, 'Usermanagement'),
(02, 'nieuwsbrief'),
(03, 'fotoalbum');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `nieuwsbriefabonnementen`
--

CREATE TABLE IF NOT EXISTS `nieuwsbriefabonnementen` (
  `nieuwsbrief` int(5) NOT NULL,
  `abonnement` int(2) NOT NULL,
  PRIMARY KEY (`nieuwsbrief`,`abonnement`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `nieuwsbrieven`
--

CREATE TABLE IF NOT EXISTS `nieuwsbrieven` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `maand` int(2) NOT NULL,
  `jaar` int(4) NOT NULL,
  `titel` varchar(50) NOT NULL,
  `verstuurd` enum('Y','N') DEFAULT 'N',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Gegevens worden uitgevoerd voor tabel `parameters`
--

INSERT INTO `parameters` (`id`, `name`, `value`, `overridable`) VALUES
(001, 'CORE_LANGUAGE', '1', b'0'),
(002, 'CORE_USER_SELF_ACTIVATION', '1', b'0'),
(003, 'CORE_USER_ADMIN_ACTIVATION', '1', b'0'),
(004, 'CORE_USER_EXT_USERGROUP', '001', b'0'),
(005, 'CORE_USER_EXT_REGISTRATION', '0', b'0'),
(006, 'CORE_NOACCESS_URL', '', b'0'),
(007, 'CORE_SERVER_MAILADRESS', 'noreply@jestaatnietalleen.be', b'0'),
(008, 'NIEUWSBRIEF_PROMOTEXT', 'Schrijf je in voor de nieuwsbrief en kom alles te over je rechten en plichten als KMO-werknemer. Bovendien ontvang je alle gegevens over de acties die we organiseren in onze regio.', b'0'),
(009, 'CORE_DEBUG_MAIL', 'matthiasba@Linux.be', b'0'),
(010, 'CORE_DEBUG_MODE', '0', b'0');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `permissions`
--

CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int(5) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `module` int(2) unsigned zerofill DEFAULT NULL,
  `name` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Gegevens worden uitgevoerd voor tabel `permissions`
--

INSERT INTO `permissions` (`id`, `module`, `name`) VALUES
(00001, 01, 'manage usergroups'),
(00002, 01, 'add users'),
(00003, 01, 'edit users'),
(00004, NULL, 'interrupt service'),
(00005, NULL, 'login during interruption'),
(00006, 02, 'nieuwsbrieven versturen'),
(00007, 02, 'beheerscherm openen'),
(00008, 02, 'abonneedetails bekijken'),
(00009, 03, 'manage albums');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `photos`
--

CREATE TABLE IF NOT EXISTS `photos` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `extension` varchar(4) NOT NULL,
  `album` int(3) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
(01, 'frontend', 'jestaatnietalleen'),
(02, 'backend', 'jestaatnietalleen'),
(03, 'mail', 'jestaatnietalleen-mail');

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
(000001, 001),
(000001, 002),
(000001, 003);

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
(001, 00005),
(001, 00006),
(001, 00007),
(001, 00008),
(001, 00009),
(002, 00006),
(002, 00007),
(003, 00007);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `usergroups`
--

CREATE TABLE IF NOT EXISTS `usergroups` (
  `id` int(3) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `search_by_groupname` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Gegevens worden uitgevoerd voor tabel `usergroups`
--

INSERT INTO `usergroups` (`id`, `name`) VALUES
(001, 'administrators'),
(002, 'redactie'),
(003, 'consulteren');

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
(000001, 'Matt', '1d499a4fc2c8ce2b234233b08f103d3c', '0', '1', '1', 'Bauw', 'Matthias', 'matthias.bauw@gmail.com', '', 'BE');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
