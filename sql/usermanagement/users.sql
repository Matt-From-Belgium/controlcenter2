CREATE TABLE users(
id int(6) UNSIGNED ZEROFILL NOT NULL AUTO_INCREMENT PRIMARY KEY,
username VARCHAR(15) NOT NULL,
password VARCHAR(32) NOT NULL,
passwordchangerequired ENUM('1','0') DEFAULT '0' NOT NULL,
userconfirmation ENUM('1','0') DEFAULT '0' NOT NULL,
adminconfirmation ENUM('1','0') DEFAULT '0' NOT NULL,
realname VARCHAR(30) NOT NULL,
realfirstname VARCHAR(30) NOT NULL,
mailadress VARCHAR(50) NOT NULL,
website VARCHAR(50),
country VARCHAR(20));
CREATE UNIQUE INDEX search_by_username ON users(username(15));