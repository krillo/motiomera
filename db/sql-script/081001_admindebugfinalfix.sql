 use `motiomera`;

 ALTER TABLE `mm_admin` DROP `debug`;
 ALTER TABLE `mm_admin` ADD `debug` SET( 'true', 'false' ) NOT NULL DEFAULT 'false' AFTER `sessionId`; 