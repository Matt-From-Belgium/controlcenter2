DELETE FROM tickets WHERE tickets.id>0;
DELETE FROM voorstellingen WHERE voorstellingen.id>0;

INSERT INTO voorstellingen (datumtijd,volzet,datum) VALUES ('5 april 2013 om 20u','N','2013-04-05');
INSERT INTO voorstellingen (datumtijd,volzet,datum) VALUES ('6 april 2013 om 20u','N','2013-04-06');
INSERT INTO voorstellingen (datumtijd,volzet,datum) VALUES ('12 april 2013 om 20u','N','2013-04-12');
INSERT INTO voorstellingen (datumtijd,volzet,datum) VALUES ('13 april 2013 om 20u','N','2013-04-13');


