use `motiomera`;

ALTER TABLE `mm_order` ADD `orderStatus` VARCHAR( 10 ) NOT NULL AFTER `id` ;
ALTER TABLE `mm_order` ADD `filnamn` VARCHAR( 200 ) NOT NULL AFTER `skapadDatum` ;