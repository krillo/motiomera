-- add Facebook
ALTER TABLE `mm_medlem` ADD `fbid` INT  NULL  DEFAULT NULL  AFTER `id`;

-- new table dagbok
CREATE TABLE `mm_dagbok` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mm_id` int(11) NOT NULL,
  `kommentar` varchar(140) COLLATE utf8_swedish_ci DEFAULT NULL,
  `betyg` int(1) DEFAULT NULL,
  `datum` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

-- new transparent images
update mm_lag set bildUrl = replace(bildUrl, 'jpg', 'png') where bildUrl like 'Lag_%';
update mm_lagnamn set img = replace(img, 'jpg', 'png') where img like '%Lag_%';

-- two kommuner was missing lan
update mm_kommun set lan = 'Skåne Län' where id = 122;
update mm_kommun set lan = 'Norrbottens Län' where id = 271;
