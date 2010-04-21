use `motiomera`;

ALTER TABLE `mm_tagg` CHANGE `skapad` `skapad` TIMESTAMP ON UPDATE CURRENT_TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ;
ALTER TABLE `mm_fastautmaningar_avklarade` ADD PRIMARY KEY ( `id` ) ;
ALTER TABLE `mm_fastautmaningar_avklarade` CHANGE `id` `id` INT( 11 ) UNSIGNED NOT NULL AUTO_INCREMENT ;
