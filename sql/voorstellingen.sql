SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE TABLE voorstellingen (
  id int(1) NOT NULL auto_increment,
  datumtijd varchar(30) NOT NULL,
  volzet enum('J','N') NOT NULL default 'N',
  datum date NOT NULL,
  PRIMARY KEY  (id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO voorstellingen (id, datumtijd, volzet, datum) VALUES(1, 'Vrijdag 15 april 2011 om 20u', 'N', '2011-04-15');
INSERT INTO voorstellingen (id, datumtijd, volzet, datum) VALUES(2, 'Zaterdag 16 april 2011 om 20u', 'N', '2011-04-16');
INSERT INTO voorstellingen (id, datumtijd, volzet, datum) VALUES(3, 'Zondag 17 april 2011 om 15u', 'N', '2011-04-17');
INSERT INTO voorstellingen (id, datumtijd, volzet, datum) VALUES(4, 'Dinsdag 19 april 2011 om 20u', 'N', '2011-04-19');
INSERT INTO voorstellingen (id, datumtijd, volzet, datum) VALUES(5, 'Woensdag 20 april 2011 om 14u', 'N', '2011-04-20');
INSERT INTO voorstellingen (id, datumtijd, volzet, datum) VALUES(6, 'Woensdag 20 april 2011 om 20u', 'N', '2011-04-20');
INSERT INTO voorstellingen (id, datumtijd, volzet, datum) VALUES(7, 'Vrijdag 22 april 2011 om 20u', 'N', '2011-04-22');
INSERT INTO voorstellingen (id, datumtijd, volzet, datum) VALUES(8, 'Zaterdag 23 april 2011 om 20u', 'N', '2011-04-23');
INSERT INTO voorstellingen (id, datumtijd, volzet, datum) VALUES(9, 'Zondag 24 april 2011 om 14u', 'N', '2011-04-24');
INSERT INTO voorstellingen (id, datumtijd, volzet, datum) VALUES(10, 'Zondag 24 april 2011 om 20u\r\n', 'N', '2011-04-24');
INSERT INTO voorstellingen (id, datumtijd, volzet, datum) VALUES(11, 'Woensdag 27 april 2011 om 20u', 'N', '2011-04-27');
INSERT INTO voorstellingen (id, datumtijd, volzet, datum) VALUES(12, 'Vrijdag 29 april 2011 om 20u', 'N', '2011-04-29');
INSERT INTO voorstellingen (id, datumtijd, volzet, datum) VALUES(13, 'Zaterdag 30 april 2011 om 14u', 'N', '2011-04-30');
INSERT INTO voorstellingen (id, datumtijd, volzet, datum) VALUES(14, 'Zaterdag 30 april 2011 om 20u', 'N', '2011-04-30');
INSERT INTO voorstellingen (id, datumtijd, volzet, datum) VALUES(15, 'Zondag 01 mei 2011 om 15u', 'N', '2011-05-01');
