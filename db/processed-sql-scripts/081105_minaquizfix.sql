use `motiomera`;

ALTER TABLE `mm_minaquiz` DROP `titel`;
ALTER TABLE `mm_minaquiz` ADD `namn` VARCHAR(200) NOT NULL AFTER `id`;
