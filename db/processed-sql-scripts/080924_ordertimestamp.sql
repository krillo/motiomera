use 'motiomera';

ALTER TABLE `mm_order` ADD `updatedDate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `expired`;