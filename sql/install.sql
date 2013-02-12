CREATE TABLE abonnementen(
id int(2) NOT NULL AUTO_INCREMENT PRIMARY KEY,
naam varchar(50) NOT NULL);

CREATE TABLE abonnees(
id int(5) NOT NULL AUTO_INCREMENT PRIMARY KEY,
voornaam varchar(30) NOT NULL,
familienaam varchar(30) NOT NULL,
mailadres varchar(50) NOT NULL,
confirmed ENUM('Y','N') DEFAULT 'N' NOT NULL,
secretkey CHAR(32) NOT NULL);

CREATE TABLE abonnementenlink(
abonnement int(2) NOT NULL,
abonnee int(5) NOT NULL,
PRIMARY KEY(abonnement,abonnee)
);

CREATE TABLE nieuwsbrieven(
id int(5) NOT NULL AUTO_INCREMENT PRIMARY KEY,
timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
maand int(2) NOT NULL,
jaar int(4) NOT NULL,
titel varchar(50) NOT NULL,
verstuurd enum('Y','N') DEFAULT 'N');

CREATE TABLE nieuwsbriefabonnementen(
nieuwsbrief int(5) NOT NULL,
abonnement int(2) NOT NULL,
PRIMARY KEY(nieuwsbrief,abonnement));
