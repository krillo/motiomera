ALTER TABLE `motiomera`.`mm_order` ADD COLUMN `isValid` TINYINT(1) NOT NULL DEFAULT 1 AFTER `compAffCode`;
ALTER TABLE `motiomera`.`mm_foretag` ADD COLUMN `isValid` TINYINT NOT NULL DEFAULT 1 COMMENT 'change this when working to default 0 since a new foretag is not equal to valid' AFTER `compAffCode`;



