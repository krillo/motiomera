use `motiomera`;

ALTER TABLE `mm_paminnelse_sql` ADD `dagar_mellan_utskick` INT(11) NOT NULL AFTER `namn`;
ALTER TABLE `mm_paminnelse_aktiva` ADD `senaste_utskick` DATE NOT NULL ;