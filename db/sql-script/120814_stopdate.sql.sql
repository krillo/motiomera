ALTER TABLE `mm_foretag` ADD `slutdatum` DATE  NOT NULL  AFTER `startdatum`;
ALTER TABLE `mm_foretag` ADD `veckor` INT(10)  NOT NULL  AFTER `stopdatum`;
update `mm_foretag` set veckor = 5;
update mm_foretag set slutdatum = DATE_ADD(`startdatum`, INTERVAL `veckor` * 7 - 1 DAY);
ALTER TABLE `mm_tavling_save` ADD `antal_dagar` INT  NOT NULL  DEFAULT '0'  AFTER `stop_datum`;
update `mm_tavling_save` set `antal_dagar` = 35;