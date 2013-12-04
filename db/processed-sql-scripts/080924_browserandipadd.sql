use `motiomera`;

ALTER TABLE `mm_medlem` ADD `browser` VARCHAR( 50 ) NOT NULL AFTER `pokalStart`;
ALTER TABLE `mm_medlem` ADD `ip` VARCHAR( 150 ) NOT NULL AFTER `browser`;
ALTER TABLE `mm_order` ADD `browser` VARCHAR( 50 ) NOT NULL AFTER `updatedDate`;
ALTER TABLE `mm_order` ADD `ip` VARCHAR( 150 ) NOT NULL AFTER `browser`;