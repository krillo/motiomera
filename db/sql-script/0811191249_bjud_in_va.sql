use `motiomera`;

CREATE TABLE `mm_inbjudningar` (
  `id` int(11) NOT NULL auto_increment,
  `medlem_id` int(9) unsigned NOT NULL,
  `epost` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `medlem_id` (`medlem_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;
