ALTER TABLE `mm_foretag` ADD `created_date` DATETIME  NOT NULL  AFTER `updated_date`;
ALTER TABLE `mm_foretag` ADD `orderId` INT(11)  NOT NULL  AFTER `created_date`;
ALTER TABLE `mm_order` ADD `sumMoms` INT(11)  NULL  DEFAULT NULL  AFTER `sum`;
ALTER TABLE `mm_order` CHANGE `sumMoms` `sumMoms` INT(11)  UNSIGNED  NULL  DEFAULT NULL;
ALTER TABLE `mm_order` ADD `orderRefCode` VARCHAR(50) DEFAULT NULL AFTER `sumMoms`;
ALTER TABLE `mm_order` ADD `filnamnFaktura` VARCHAR(200) DEFAULT NULL AFTER `filnamn`;