use `motiomera`;

ALTER TABLE `mm_kommun` ADD `abroad` SET( 'true', 'false' ) NOT NULL DEFAULT 'false' AFTER `info` ;
