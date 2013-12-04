use `motiomera`;

ALTER TABLE `mm_admin` ADD `debug` ENUM( '0', '1' ) NOT NULL AFTER `sessionId` ;