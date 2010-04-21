use `motiomera`;
ALTER TABLE `mm_medlem` ADD `block_mail` ENUM( 'true', 'false' ) NOT NULL DEFAULT 'false' AFTER `atkomst` ;