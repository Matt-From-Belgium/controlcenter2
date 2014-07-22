CREATE TABLE IF NOT EXISTS `albums` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;


CREATE TABLE photos(
id int(5) NOT NULL AUTO_INCREMENT PRIMARY KEY,
extension varchar(4) NOT NULL,
album int(3) NOT NULL,
description VARCHAR(255));
