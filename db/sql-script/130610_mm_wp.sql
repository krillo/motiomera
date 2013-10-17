ALTER TABLE `mm_medlem` ADD `fbid` INT  NULL  DEFAULT NULL  AFTER `id`;
CREATE TABLE `mm_dagbok` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mm_id` int(11) NOT NULL,
  `kommentar` varchar(140) CHARACTER SET latin1 DEFAULT NULL,
  `betyg` int(1) DEFAULT NULL,
  `datum` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

update mm_lag set bildUrl = replace(bildUrl, 'jpg', 'png') where bildUrl like 'Lag_%';
update mm_lagnamn set img = replace(img, 'jpg', 'png') where img like '%Lag_%';