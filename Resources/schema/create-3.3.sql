CREATE TABLE `cleverage_settings` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `identifier` varchar(255) NOT NULL DEFAULT '',
  `value` TEXT NOT NULL,
  `updatedAt` DATETIME NOT NULL,
  `updatedBy` TEXT NOT NULL,
  `scope` varchar(255) NOT NULL DEFAULT 'default',
  PRIMARY KEY (`id`),
  UNIQUE KEY `identifier_scope` (`identifier`,`scope`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;