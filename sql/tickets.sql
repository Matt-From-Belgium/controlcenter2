CREATE TABLE tickets(
id int(4) ZEROFILL NOT NULL PRIMARY KEY AUTO_INCREMENT,
datum DATE DEFAULT NULL,
naam VARCHAR(50) NOT NULL,
voornaam VARCHAR(50) NOT NULL,
mail VARCHAR(50) NOT NULL,
straat VARCHAR(50) NOT NULL,
huisnummer VARCHAR(10) NOT NULL,
gemeente INT(5) UNSIGNED ZEROFILL NOT NULL,
aantal int(2) NOT NULL,
voorstelling int(1) NOT NULL,
status ENUM('Definitief','Wacht op betaling','Wacht op betaling (herinnering verstuurd)','Automatisch geannuleerd','Manueel geannuleerd') NOT NULL DEFAULT 'Wacht op betaling',
referral int(2) NOT NULL) AUTO_INCREMENT=0300;