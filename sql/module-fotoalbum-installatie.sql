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

INSERT INTO  `gandalf`.`ajaxwhitelist` (`id` ,`file` ,`function`) VALUES (NULL ,  '/modules/fotoalbum/logic/albumlogic.php',  'addAlbum');
INSERT INTO  `gandalf`.`ajaxwhitelist` (`id` ,`file` ,`function`) VALUES (NULL ,  '/modules/fotoalbum/logic/albumlogic.php',  'getAlbums');
INSERT INTO  `gandalf`.`ajaxwhitelist` (`id` ,`file` ,`function`) VALUES (NULL ,  '/modules/fotoalbum/logic/albumlogic.php',  'addPhoto');
INSERT INTO  `gandalf`.`ajaxwhitelist` (`id` ,`file` ,`function`) VALUES (NULL ,  '/modules/fotoalbum/logic/ajaxLogic.php',  'GetAlbumPhotosAjax');
INSERT INTO  `gandalf`.`ajaxwhitelist` (`id` ,`file` ,`function`) VALUES (NULL ,  '/modules/fotoalbum/logic/albumlogic.php',  'changeDescription');
INSERT INTO  `gandalf`.`ajaxwhitelist` (`id` ,`file` ,`function`) VALUES (NULL ,  '/modules/fotoalbum/logic/albumlogic.php',  'deletePhoto');
