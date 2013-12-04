use `motiomera`;

CREATE TABLE `mm_blockeradmedlem` (
  `medlem_id` int(11) NOT NULL,
  `blockerad_medlem_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY  (`medlem_id`,`blockerad_medlem_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;