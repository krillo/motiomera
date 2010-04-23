ALTER TABLE `motiomera`.`mm_tavling_save` ADD COLUMN `creat_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `steg`;
ALTER TABLE `motiomera`.`mm_tavling_save` ADD COLUMN `start_datum` TIMESTAMP NOT NULL   AFTER `creat_date`;
ALTER TABLE `motiomera`.`mm_tavling_save` ADD COLUMN `stop_datum` TIMESTAMP NOT NULL   AFTER `start_datum`;
ALTER TABLE `motiomera`.`mm_lag_save` ADD COLUMN `creat_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `foretag_id`;