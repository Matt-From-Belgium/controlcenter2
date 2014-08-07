INSERT INTO `controlcenter`.`parameters` (
`id` ,
`name` ,
`value` ,
`overridable`
)
VALUES (
NULL , 'CORE_SSL_ENABLED', '0', b '0'
);

ALTER TABLE `users` CHANGE `password` `password` VARCHAR( 128 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ;

