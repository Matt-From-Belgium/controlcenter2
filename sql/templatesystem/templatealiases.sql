CREATE TABLE templatealiases(
id int(2) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT PRIMARY KEY,
name varchar(20) NOT NULL,
directory varchar(30) NOT NULL);
CREATE UNIQUE INDEX search_by_name ON templatealiases(name(20));