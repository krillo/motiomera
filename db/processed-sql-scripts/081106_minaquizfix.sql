use `motiomera`;

ALTER TABLE `mm_minaquiz` ADD `tilltrade` ENUM('alla','vissa') NOT NULL DEFAULT 'alla';
ALTER TABLE `mm_minaquiz` ADD `tilltrade_alla_grupper` ENUM('ja','nej') NOT NULL DEFAULT 'nej';
ALTER TABLE `mm_minaquiz` ADD `tilltrade_foretag` ENUM('ja','nej') NOT NULL DEFAULT 'nej';
ALTER TABLE `mm_minaquiz` ADD `tillagd` DATETIME NOT NULL;
ALTER TABLE `mm_minaquiz` DROP `datetime`;
