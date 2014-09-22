-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Machine: localhost
-- Genereertijd: 22 sep 2014 om 17:35
-- Serverversie: 5.5.38-0ubuntu0.14.04.1
-- PHP-versie: 5.5.9-1ubuntu4.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
-- Tabelstructuur voor tabel `ajaxwhitelist`
--

CREATE TABLE IF NOT EXISTS `ajaxwhitelist` (
  `id` int(6) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `file` varchar(500) NOT NULL,
  `function` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Gegevens worden uitgevoerd voor tabel `ajaxwhitelist`
--

INSERT INTO `ajaxwhitelist` (`id`, `file`, `function`) VALUES
(000001, '/core/logic/usermanagement/fbLoginAjax.php', 'checkFBAccount'),
(000002, '/core/templatesystem/templatelogic.php', 'setCookiesOk'),
(000005, '/modules/fotoalbum/logic/albumlogic.php', 'addAlbum'),
(000006, '/modules/fotoalbum/logic/albumlogic.php', 'getAlbums'),
(000007, '/modules/fotoalbum/logic/albumlogic.php', 'addPhoto'),
(000008, '/modules/fotoalbum/logic/ajaxLogic.php', 'GetAlbumPhotosAjax'),
(000009, '/modules/fotoalbum/logic/albumlogic.php', 'changeDescription'),
(000010, '/modules/fotoalbum/logic/albumlogic.php', 'deletePhoto'),
(000011, '/modules/fotoalbum/logic/ajaxLogic.php', 'albumBeschrijvingWijzigenAjax');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `albums`
--

CREATE TABLE IF NOT EXISTS `albums` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
-- Tabelstructuur voor tabel `login_attempts`
--

CREATE TABLE IF NOT EXISTS `login_attempts` (
  `username` varchar(20) NOT NULL DEFAULT '',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`username`,`time`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden uitgevoerd voor tabel `login_attempts`
--

INSERT INTO `login_attempts` (`username`, `time`) VALUES
('', '2014-09-09 19:40:57'),
('admin', '2014-09-01 11:44:21'),
('admin', '2014-09-01 11:44:30'),
('admin', '2014-09-01 11:44:34'),
('jestaatnietalleen', '2014-09-05 14:00:54'),
('jestaatnietalleen', '2014-09-05 14:01:16'),
('matt', '2014-09-15 09:50:42');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `modules`
--

CREATE TABLE IF NOT EXISTS `modules` (
  `id` int(2) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `name` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Gegevens worden uitgevoerd voor tabel `modules`
--

INSERT INTO `modules` (`id`, `name`) VALUES
(01, 'Usermanagement'),
(03, 'nieuwsbrief'),
(04, 'fotoalbum');

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=26 ;

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
(007, 'CORE_SERVER_MAILADRESS', 'info@jestaatnietalleen.be', b'0'),
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
-- Tabelstructuur voor tabel `permissions`
--

CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int(5) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `module` int(2) unsigned zerofill DEFAULT NULL,
  `name` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- Gegevens worden uitgevoerd voor tabel `permissions`
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
-- Tabelstructuur voor tabel `photos`
--

CREATE TABLE IF NOT EXISTS `photos` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `extension` varchar(4) NOT NULL,
  `album` int(3) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

--
-- Gegevens worden uitgevoerd voor tabel `photos`
--

INSERT INTO `photos` (`id`, `extension`, `album`, `description`) VALUES
(10, 'jpg', 2, ''),
(11, 'jpg', 2, ''),
(12, 'jpg', 2, ''),
(13, 'jpg', 2, ''),
(15, 'jpg', 2, ''),
(16, 'jpg', 2, ''),
(17, 'jpg', 2, ''),
(18, 'jpg', 2, ''),
(19, 'jpg', 3, ''),
(20, 'jpg', 3, '');

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
  UNIQUE KEY `search_by_name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Gegevens worden uitgevoerd voor tabel `templatealiases`
--

INSERT INTO `templatealiases` (`id`, `name`, `pc_directory`, `phone_directory`, `tablet_directory`) VALUES
(01, 'frontend', 'jestaatnietalleen', 'jestaatnietalleen-mobile', NULL),
(02, 'backend', 'jestaatnietalleen', NULL, NULL),
(03, 'mail', 'jestaatnietalleen-mail2', NULL, NULL),
(04, 'maintenance', 'binnenkort', NULL, NULL);

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
(000042, 002),
(000042, 003),
(000043, 002),
(000043, 003),
(000044, 002),
(000044, 003);

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
(001, 00007),
(001, 00012),
(002, 00007),
(002, 00008),
(002, 00009),
(002, 00011),
(002, 00012),
(003, 00005);

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
(003, 'testers');

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
  UNIQUE KEY `search_by_username` (`username`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=45 ;

--
-- Gegevens worden uitgevoerd voor tabel `users`
--

INSERT INTO `users` (`id`, `username`, `facebookid`, `password`, `salt`, `passwordchangerequired`, `userconfirmation`, `adminconfirmation`, `realname`, `realfirstname`, `mailadress`, `website`, `country`) VALUES
(000001, 'Matt', '', 'b03561292506d42a9b45217b6e27e6dec9afd2a787b6c49b503f296728d085affc395b9c517b9ec2aaf8bf3535cf79e372a89705c4714d7f28d0fa8a147211fd', 'dc4f4e55dee83cad9345fc8e3f2131b18a00bece7cf09593141deead9235ed9a169a5da7b324719eb9240a029e7c172490e9714bf20f5f204d8bbd2b1ef3e9ce', '0', '1', '1', 'Bauw', 'Matthias', 'matthias.bauw@gmail.com', NULL, NULL),
(000042, 'peter', '', '1ebeb25315efa1b87866f032e7883918cc94c863db6e08235ed8897570a65424571bd915f5f518af6cd2ab6e0a58534b36383cfd806d6f03f76e7c8b14af43af', '5e1c7b21cc54f765cbfea297c7038aaaf2e7ad00e7db27342dc507a920ac1f50faf7afe8e76b981cc0f8c45056cd2af36ab4509d4e6f742462406712d6263085', '1', '1', '1', 'Debaenst', 'Peter', 'peter.debaenst@acv-csc.be', NULL, NULL),
(000043, 'petur', '', '7ceb7baedb1b122b7928adb5534aabf9884de70efd626d07fac6a6b6aa2561640b1c095a815839868d0c03b867dc6984a55931cad663a7e3da6a516d8168c292', 'aa42f5a582985d6b00d9bcc698973da87ab42d89f32024c74be6b1ce9b837d685a7189a72219ad5462adf98de9db507f754589c96ea9c026adaa0cfcf8eb4949', '1', '1', '1', 'Edvardsson', 'Pétur', 'petur.edvardsson@acv-csc.be', NULL, NULL),
(000044, 'geert', '', 'f004fb70861bbd5e943641aa690c70d4274f32eff1a4f8365a91bbc734c13687142c7792bee54fc768f36e7b9579d9adc7de1449bf9b679b8d6d3e6b59c4024d', 'a1f7be6538b6e118aa3bccd767cda99991185f5d329cc3d3579686f89328c14f6f4406a069d067925f5159db7aad25fe9dc599d1485e9fcdd2b1247f8e05a98a', '1', '1', '1', 'Delbaere', 'Geert', 'geert.delbaere@acv-csc.be', NULL, NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
