CREATE TABLE albums(
id int(3) NOT NULL AUTO_INCREMENT PRIMARY KEY,
name varchar(50) NOT NULL);

CREATE TABLE photos(
id int(5) NOT NULL AUTO_INCREMENT PRIMARY KEY,
extension varchar(4) NOT NULL,
album int(3) NOT NULL,
description VARCHAR(255));
