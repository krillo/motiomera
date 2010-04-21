use `motiomera`;

ALTER TABLE `mm_anslagstavla` ADD `lag_id` INT UNSIGNED NOT NULL ;
ALTER TABLE `mm_anslagstavla` DROP INDEX `grupp_id` ,
ADD INDEX `grupp_id` ( `grupp_id` , `foretag_id` , `lag_id` ) 