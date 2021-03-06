INSERT INTO parameters (name ,value ,overridable) VALUES ('CORE_SSL_ENABLED', '0', '0');

ALTER TABLE `users` CHANGE `password` `password` VARCHAR( 128 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;
ALTER TABLE  `users` ADD  `salt` VARCHAR( 128 ) NOT NULL AFTER  `password` ;

CREATE TABLE IF NOT EXISTS `ajaxwhitelist` (
  `id` int(6) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `file` varchar(500) NOT NULL,
  `function` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

CREATE TABLE login_attempts(
	username varchar(20),
	time timestamp default current_timestamp,
	primary key(username,time)
);

INSERT INTO `ajaxwhitelist` (`id`, `file`, `function`) VALUES
(000001, '/core/logic/usermanagement/fbLoginAjax.php', 'checkFBAccount'),
(000002, '/core/templatesystem/templatelogic.php', 'setCookiesOk');
