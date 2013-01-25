CREATE TABLE languages(
id int(2) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT PRIMARY KEY,
name varchar(10) NOT NULL);
CREATE UNIQUE INDEX search_by_name ON languages(name(10));