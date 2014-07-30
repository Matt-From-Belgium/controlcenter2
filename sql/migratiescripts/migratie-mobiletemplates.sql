ALTER TABLE  `templatealiases` CHANGE  `directory`  `pc_directory` VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;
ALTER TABLE  `templatealiases` ADD  `phone_directory` VARCHAR( 30 ) NULL DEFAULT NULL AFTER  `pc_directory` ,
ADD  `tablet_directory` VARCHAR( 30 ) NULL DEFAULT NULL AFTER  `phone_directory` ;

