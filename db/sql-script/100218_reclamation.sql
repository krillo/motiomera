CREATE TABLE `motiomera`.`mm_reclamation` (
  `id` INTEGER UNSIGNED NOT NULL DEFAULT NULL AUTO_INCREMENT,
  `foretag_id` INTEGER UNSIGNED NOT NULL,
  `count` INTEGER UNSIGNED NOT NULL,
  `date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
)
ENGINE = InnoDB;