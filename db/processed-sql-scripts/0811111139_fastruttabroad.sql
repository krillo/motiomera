use `motiomera`;

ALTER TABLE `mm_fastautmaningar` ADD `abroad` SET( 'true', 'false' ) NOT NULL DEFAULT 'false' AFTER `namn` ;
