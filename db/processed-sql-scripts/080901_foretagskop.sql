USE `motiomera`;

ALTER TABLE  `mm_foretag` ADD  `epost` VARCHAR( 255 ) NULL AFTER  `losenord` ;
ALTER TABLE  `mm_lag` ADD  `bildUrl` VARCHAR( 128 ) NOT NULL AFTER  `namn` ;
ALTER TABLE  `mm_foretag` DROP INDEX  `namn`;