USE `motiomera`;

ALTER TABLE `mm_stracka` ADD `fastRutt_id` INT NOT NULL AFTER `kommunTill_id` ;

ALTER TABLE `mm_stracka` ADD INDEX ( `fastRutt_id` );
 ALTER TABLE `mm_stracka` ADD INDEX ( `medlem_id` );