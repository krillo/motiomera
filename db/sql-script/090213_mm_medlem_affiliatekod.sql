USE `motiomera`;
ALTER TABLE `motiomera`.`mm_medlem` ADD COLUMN `mAffCode` VARCHAR(20) AFTER `rssUrl`;
ALTER TABLE `motiomera`.`mm_foretag` ADD COLUMN `kanal` VARCHAR(30) AFTER `startdatum`, ADD COLUMN `compAffCode` VARCHAR(20) AFTER `kanal`;
ALTER TABLE `motiomera`.`mm_order` ADD COLUMN `kanal` VARCHAR(30) AFTER `ip`, ADD COLUMN `compAffCode` VARCHAR(20) AFTER `kanal`;
