ALTER TABLE `mm_foretag` ADD `created_date` DATETIME  NOT NULL  AFTER `updated_date`;
ALTER TABLE `mm_foretag` ADD `orderId` INT(11)  NOT NULL  AFTER `created_date`;
ALTER TABLE `mm_foretag` ADD `payerFName` VARCHAR(255)  NULL  DEFAULT NULL AFTER `payerName`;
ALTER TABLE `mm_foretag` ADD `payerLName` VARCHAR(255)  NULL  DEFAULT NULL AFTER `payerFName`;

ALTER TABLE `mm_order` ADD `sumMoms` INT(11)  NULL  DEFAULT NULL  AFTER `sum`;
ALTER TABLE `mm_order` CHANGE `sumMoms` `sumMoms` INT(11)  UNSIGNED  NULL  DEFAULT NULL;
ALTER TABLE `mm_order` ADD `orderRefCode` VARCHAR(50) DEFAULT NULL AFTER `sumMoms`;
ALTER TABLE `mm_order` ADD `filnamnFaktura` VARCHAR(200) DEFAULT NULL AFTER `filnamn`;

ALTER TABLE `mm_medlem` ADD `address` VARCHAR(255)  NULL  DEFAULT NULL  AFTER `foretagsnyckel_temp`;
ALTER TABLE `mm_medlem` ADD `co` VARCHAR(255)  NULL  DEFAULT NULL  AFTER `address`;
ALTER TABLE `mm_medlem` ADD `zip` VARCHAR(255)  NULL  DEFAULT NULL  AFTER `co`;
ALTER TABLE `mm_medlem` ADD `city` VARCHAR(255)  NULL  DEFAULT NULL  AFTER `zip`;
ALTER TABLE `mm_medlem` ADD `phone` VARCHAR(255)  NULL  DEFAULT NULL  AFTER `city`;
ALTER TABLE `mm_medlem` ADD `country` VARCHAR(255)  NULL  DEFAULT NULL  AFTER `phone`;



/*

update mm_foretag set payerfname = (SELECT SUBSTRING_INDEX((select payerName from mm_foretag where id = 2935), ' ', 1)) where id = 2935;


SELECT SUBSTRING_INDEX((select payerName from mm_foretag where id = 2935), ' ', 1);
SELECT SUBSTRING_INDEX((select payerName from mm_foretag where id = 2935), ' ', -1);


*/