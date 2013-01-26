CREATE TABLE stemgroepen(
id int(2) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT PRIMARY KEY,
stemgroep varchar(30) NOT NULL);

INSERT INTO stemgroepen (id,stemgroep) VALUES (1,'Sopraan');
INSERT INTO stemgroepen (id,stemgroep) VALUES (2,'Mezzo');
INSERT INTO stemgroepen (id,stemgroep) VALUES (3,'Alt');
INSERT INTO stemgroepen (id,stemgroep) VALUES (4,'Tenor');
INSERT INTO stemgroepen (id,stemgroep) VALUES (5,'Bariton');
INSERT INTO stemgroepen (id,stemgroep) VALUES (6,'Bas');