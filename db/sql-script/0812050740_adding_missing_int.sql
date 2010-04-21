ALTER TABLE `mm_lag_save` ADD `foretag_id` INT UNSIGNED NOT NULL ;

ALTER TABLE `mm_lag_save` ADD INDEX ( `foretag_id` ) ;
