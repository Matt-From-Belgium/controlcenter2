-- phpMyAdmin SQL Dump
-- version 3.4.11.1
-- http://www.phpmyadmin.net
--
-- Machine: localhost
-- Genereertijd: 23 dec 2012 om 02:21
-- Serverversie: 5.1.65
-- PHP-Versie: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databank: `projectk_data`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `auditiekandidaten`
--

CREATE TABLE IF NOT EXISTS `auditiekandidaten` (
  `id` int(4) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `secretkey` varchar(32) NOT NULL,
  `voornaam` varchar(30) NOT NULL,
  `naam` varchar(30) NOT NULL,
  `mailadres` varchar(30) NOT NULL,
  `stemgroep` int(1) NOT NULL,
  `definitief` enum('Y','N') DEFAULT 'N',
  `adres` varchar(100) DEFAULT NULL,
  `gsm` varchar(10) DEFAULT NULL,
  `geboortedatum` date DEFAULT NULL,
  `hoogstenoot` varchar(50) DEFAULT NULL,
  `laagstenoot` varchar(50) DEFAULT NULL,
  `partiturenlezen` enum('Y','N') DEFAULT NULL,
  `ervaring` text,
  `zangles` enum('Y','N') DEFAULT NULL,
  `instrument` text,
  `ervaringinstrument` text,
  `motivatie` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=35 ;

--
-- Gegevens worden uitgevoerd voor tabel `auditiekandidaten`
--

INSERT INTO `auditiekandidaten` (`id`, `secretkey`, `voornaam`, `naam`, `mailadres`, `stemgroep`, `definitief`, `adres`, `gsm`, `geboortedatum`, `hoogstenoot`, `laagstenoot`, `partiturenlezen`, `ervaring`, `zangles`, `instrument`, `ervaringinstrument`, `motivatie`) VALUES
(0001, '49cf8b3c188bbcb0b17d555c1f3d2a6f', 'Sarah', 'Finaut', 'zara.vino@gmail.com', 1, 'Y', 'Lange Veldstraat 10\r\n8600 Diksmuide', '0486693482', '1981-07-12', 'hoge sol', 'euh.... hangt af van hoe erg the hangover is', 'Y', 'Yup :)', 'Y', 'Piano', 'nee, samenspel in de muziekschool is al moeilijk genoeg', 'Een koor zonder koekje zou triest zijn ;)'),
(0002, '52bc8433c75350dcddab2f0e4aa6860b', 'Matthias', 'Bauw', 'matthias@projectkoor.be', 4, 'N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(0003, '5ad819f2b0fddd578fce9161655299ea', 'Ward', 'Depla', 'ward.depla@hotmail.com', 5, 'N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(0004, '5a0c6eb47a3d2938bdf155984d8c8305', 'Jan', 'Segaert', 'jansegaert@telenet.be', 4, 'N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(0005, '8dfab4c15769d8d28b22d5d92b127ca0', 'Jan', 'Demuynck', 'jan.demuynck@sint-michiel.be', 4, 'N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(0006, 'a65d512ce7c001b62b0fe6cd64b9b1e3', 'Lies', 'Soete', 'liessoete@hotmail.com', 3, 'N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(0007, '09450213e9ad74cfae0948674385b16f', 'Hannes', 'De Kesel', 'hdekesel@gmail.com', 6, 'Y', '\\''t kloosterhof 78', '0486218561', '1981-05-29', '? sorry', '? sorry', 'Y', 'yup', 'N', 'gitaar', 'nope', 'ongelofelijk veel zin om te zingen en plezier te maken ! '),
(0008, '3998fd9b21504c35a5904e4dd7543aa7', 'Hannes', 'De Kesel', 'hdekesel@gmail.com', 6, 'N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(0009, '8c4325193f5fd22626bb1426a9f72a23', 'Hannes', 'De Kesel', 'hdekesel@gmail.com', 6, 'N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(0010, '1be2c93a07c481309e6d39741a695ecd', 'Hannes', 'De Kesel', 'hdekesel@gmail.com', 6, 'N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(0011, 'bb89c0b528dd99b4fb4e6e6e14be46e4', 'Sofie', 'Danneels', 'danneelssofie@gmail.com', 1, 'N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(0012, '27e00f7b5a8587662974b900b67e5fba', 'Nele', 'Fiers', 'nele.fiers@gmail.com', 3, 'N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(0013, 'c8433f6763567316649742be48c59ecb', 'leen', 'remaut', 'leenremaut@yahoo.com', 3, 'N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(0014, '65d83cc13ae54e6b0ad95f9873ddc350', 'leen', 'remaut', 'leenremaut@yahoo.com', 3, 'N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(0015, '7531ed574d85feb814cab9cb0d5ab7d0', 'Griet', 'Borremans', 'griet.borremans@fulladsl.be', 2, 'Y', 'Bruggestraat 35\r\n8920 Poelkapelle', '0494152134', '1974-08-19', 'hoge mi (hoger kan nog, maar niet comfortabel)', 'lage la', 'Y', 'ja', 'Y', 'cello', 'ja, orkest en live begeleiding van kleinkunst genre ', 'zingen is leven en voelen en stilstaan en delen, en mogen samenwerken en samenzijn met mensen die deze passie delen, is een ervaring waarvan ik al heb mogen proeven en dat smaakt naar meer... ;-)\r\n  '),
(0016, '95d9b7ef23d03bd65ee3ba0cbf76f365', 'Aron', 'Storme', 'griet.borremans@fulladsl.be', 4, 'N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(0017, '12debdac4a1ad221ffbf5a5935e0b687', 'Ulrieke', 'Storme', 'griet.borremans@fulladsl.be', 2, 'N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(0018, 'b61c39b0788e7f1f4a9904b9ca7c6d04', 'heide', 'boussier', 'heideboussier@scarlet.be', 1, 'Y', 'Hoogleedsesteenweg 106\r\n8800 roeselare', '0496631304', '1976-12-14', 'hoge sol', 'lage mi', 'Y', 'ja', 'Y', 'piano, gitaar', 'nee', 'Eindelijk een koor die verschillende genres zal zingen en niet alleen kerkmuziek. Zelfstudie zodat goed afgewerkt kan worden. '),
(0019, '418e9e0ae8ad0679bf784d5ce9d98e43', 'Veerle', 'Dekeyser', 'Veerledekeyser@hotmail.com', 1, 'N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(0020, '9f0c7f4efcdff23fbdb7ac2401bf3dac', 'Geert', 'De Deygere', 'gddmontvrin@gmail.com', 5, 'N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(0021, '0d8552001abea183c851b506c2b47988', 'GREET', 'CORNETTE', 'greet.cornette@telenet.be', 1, 'N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(0022, '92358ed80797381e7cb371581812b629', 'ELS', 'DAENEKINDT', 'els.daenekindt@skynet.be', 1, 'Y', 'Iepersteenweg 25\r\n8600 Woumen', '0485452015', '1964-02-20', 'hoge mi', 'lage fa', 'Y', 'ja', 'N', 'nee', 'nee', 'Ben zelf dirigente van een zangkoor, maar wil eens aan de andere kant van een koor staan. Ik hou van zingen. Ik heb al meerdere uitvoeringen van Geert gezien. Ik dacht toen altijd, daar wil ik ook wel eens aan meedoen. Bij deze mijn inschrijving.'),
(0023, '1a2ea3b716d2e04c462d54c69995139a', 'els', 'vanoverschelde', 'geert.dewyse1@telenet.be', 3, 'N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(0024, '8e1b0b71ee2f0f712ebfc12676588231', 'Alexia', 'Deboutte', 'deboutte.alexia@hotmail.com', 3, 'N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(0025, '6b378a275751070c61b69588df68ec45', 'Alexia', 'Deboutte', 'deboutte.alexia@hotmail.com', 3, 'N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(0026, '6b378a275751070c61b69588df68ec45', 'Alexia', 'Deboutte', 'deboutte.alexia@hotmail.com', 3, 'N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(0027, 'db7165be8ca9b17bd76000a95808ed26', 'Alexia', 'Deboutte', 'deboutte.alexia@hotmail.com', 3, 'N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(0028, 'db7165be8ca9b17bd76000a95808ed26', 'Alexia', 'Deboutte', 'deboutte.alexia@hotmail.com', 3, 'N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(0029, '9b1e36e3303df62d83322af514894f12', 'Alexia', 'Deboutte', 'deboutte.alexia@hotmail.com', 3, 'N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(0030, '9b1e36e3303df62d83322af514894f12', 'Alexia', 'Deboutte', 'deboutte.alexia@hotmail.com', 3, 'N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(0031, '3687dd60202ad3b8da231d0ab6e833c1', 'Alexia', 'Deboutte', 'deboutte.alexia@hotmail.com', 3, 'N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(0032, '3687dd60202ad3b8da231d0ab6e833c1', 'Alexia', 'Deboutte', 'deboutte.alexia@hotmail.com', 3, 'N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(0033, '93279d397e42bf0c050c2b4926b1cd4c', 'Alexia', 'Deboutte', 'deboutte.alexia@hotmail.com', 3, 'N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(0034, '93279d397e42bf0c050c2b4926b1cd4c', 'Alexia', 'Deboutte', 'deboutte.alexia@hotmail.com', 3, 'N', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

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
  `value` varchar(50) NOT NULL,
  `overridable` bit(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `search_by_name` (`name`(20))
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=9 ;

--
-- Gegevens worden uitgevoerd voor tabel `parameters`
--

INSERT INTO `parameters` (`id`, `name`, `value`, `overridable`) VALUES
(001, 'CORE_LANGUAGE', '1', '\0'),
(002, 'CORE_USER_SELF_ACTIVATION', '1', '\0'),
(003, 'CORE_USER_ADMIN_ACTIVATION', '1', '\0'),
(004, 'CORE_USER_EXT_USERGROUP', '001', '\0'),
(005, 'CORE_USER_EXT_REGISTRATION', '1', '\0'),
(006, 'CORE_NOACCESS_URL', '', '\0'),
(007, 'CORE_SERVER_MAILADRESS', 'noreply@projectkoor.be', '\0'),
(008, 'AUDITIES_ADMIN_MAIL', 'inschrijving@projectkoor.be', '\0');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `permissions`
--

CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int(5) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `module` int(2) unsigned zerofill DEFAULT NULL,
  `name` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

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
(01, 'frontend', 'projectkoor'),
(02, 'backend', 'alpha3'),
(03, 'mail', 'projectmail');

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
