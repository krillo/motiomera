use `motiomera`;

ALTER TABLE `mm_aktivitet` ADD `borttagen` ENUM('ja','nej') NOT NULL DEFAULT 'nej';

UPDATE `mm_aktivitet` SET borttagen = 'ja' WHERE id IN (3, 4);