ALTER TABLE `mm_medlem` CHANGE `veckotavling` `veckotavling_status` INT(11)  NOT NULL  DEFAULT '0';
ALTER TABLE `mm_medlem` ADD `veckotavling_week` VARCHAR(20)  NULL  DEFAULT NULL  AFTER `veckotavling_status`;
ALTER TABLE `mm_medlem` CHANGE `veckotavling_datum` `veckotavling_datum` DATETIME  NULL;
ALTER TABLE `mm_medlem` ADD `veckotavling_price` VARCHAR(255)  NULL  DEFAULT NULL  AFTER `veckotavling_datum`;
ALTER TABLE `mm_medlem` ADD `veckotavling_price_text` VARCHAR(255)  NULL  DEFAULT NULL  AFTER `veckotavling_price`;
ALTER TABLE `mm_medlem` ADD `veckotavling_steg` INT(10)  NULL  DEFAULT NULL  AFTER `veckotavling_week`;
