use `motiomera` ;

ALTER TABLE `mm_tavling_save` DROP `tavlingsrundor` ;

ALTER TABLE `mm_tavling_save` ADD `id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST ;
