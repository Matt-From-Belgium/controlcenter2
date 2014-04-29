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

--
-- Tabelstructuur voor tabel `abonnementen`
--

CREATE TABLE IF NOT EXISTS `abonnementen` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `naam` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Tabelstructuur voor tabel `abonnementenlink`
--

CREATE TABLE IF NOT EXISTS `abonnementenlink` (
  `abonnement` int(2) NOT NULL,
  `abonnee` int(5) NOT NULL,
  PRIMARY KEY (`abonnement`,`abonnee`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Tabelstructuur voor tabel `nieuwsbriefabonnementen`
--

CREATE TABLE IF NOT EXISTS `nieuwsbriefabonnementen` (
  `nieuwsbrief` int(5) NOT NULL,
  `abonnement` int(2) NOT NULL,
  PRIMARY KEY (`nieuwsbrief`,`abonnement`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

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

INSERT INTO `parameters` (`name`, `value`, `overridable`) VALUES ('NIEUWSBRIEF_PROMOTEXT', 'Schrijf je in voor de nieuwsbrief en kom alles te over je rechten en plichten als KMO-werknemer. Bovendien ontvang je alle gegevens over de acties die we organiseren in onze regio.', '0');

