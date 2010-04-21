use `motiomera`;

CREATE TABLE `mm_medlemprofildatatext` (
  `medlem_id` int(10) unsigned NOT NULL,
  `profilData_id` int(10) unsigned NOT NULL,
  `profilDataText` varchar(60) NOT NULL,
  PRIMARY KEY  (`medlem_id`,`profilData_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;
