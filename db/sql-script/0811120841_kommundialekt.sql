use `motiomera`;

CREATE TABLE `mm_kommundialekt` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `kommun_id` int(10) unsigned NOT NULL,
  `kon` enum('man','kvinna') collate utf8_swedish_ci NOT NULL,
  `alder` enum('ung','gammal') collate utf8_swedish_ci NOT NULL,
  `url` varchar(255) collate utf8_swedish_ci NOT NULL,
  `medlem_id` int(10) unsigned default NULL,
  `godkand` int(10) unsigned NOT NULL,
  KEY `kommun_id` (`kommun_id`)
) ENGINE=MyISAM ;