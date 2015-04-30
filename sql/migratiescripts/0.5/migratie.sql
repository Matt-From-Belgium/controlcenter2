ALTER TABLE `parameters` 
ADD COLUMN `environmental` TINYINT(1) NOT NULL AFTER `overridable`;
INSERT INTO `controlcenter`.`parameters` (`name`, `value`, `overridable`) VALUES ('CORE_VERSION', '0.5', 0);
