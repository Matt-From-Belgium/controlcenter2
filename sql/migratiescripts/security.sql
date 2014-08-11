INSERT INTO `controlcenter`.`parameters` (`name` ,`value` ,`overridable`) VALUES ('CORE_SSL_ENABLED', '0', '0');

ALTER TABLE `users` CHANGE `password` `password` VARCHAR( 128 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;
ALTER TABLE  `users` ADD  `salt` VARCHAR( 128 ) NOT NULL AFTER  `password` ;

