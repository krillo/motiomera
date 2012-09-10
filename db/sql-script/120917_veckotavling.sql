ALTER TABLE `mm_medlem` ADD `veckotavling` INT  NOT NULL  DEFAULT '0'  AFTER `country`;
ALTER TABLE `mm_medlem` ADD `veckotavling_datum` DATE  NULL  DEFAULT NULL  AFTER `veckotavling`;
