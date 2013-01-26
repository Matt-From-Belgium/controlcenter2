CREATE TABLE auditiekandidaten(
id int(4) UNSIGNED ZEROFILL PRIMARY KEY AUTO_INCREMENT,
secretkey varchar(32) NOT NULL,
voornaam VARCHAR(30) NOT NULL,
naam VARCHAR(30) NOT NULL,
mailadres VARCHAR(30) NOT NULL,
stemgroep int(1) NOT NULL
);