USE `motiomera`;

ALTER TABLE `mm_foretag` ADD `startdatum` DATE NOT NULL AFTER `giltig` ;
ALTER TABLE `mm_foretagsnycklar` ADD `lag_id` INT NULL AFTER `datum` ;
ALTER TABLE `mm_order` ADD `companyName` VARCHAR( 255 ) NULL AFTER `date` ;
