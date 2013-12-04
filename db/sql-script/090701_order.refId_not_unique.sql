ALTER TABLE `trunkomera`.`mm_order` DROP INDEX `refId`,
 ADD INDEX `refId` USING BTREE(`refId`);
