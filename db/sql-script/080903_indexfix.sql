 USE `motiomera`;

 ALTER TABLE `mm_feeditem` ADD INDEX ( `medlem_id` );
 ALTER TABLE `mm_kontakt` ADD INDEX ( `kontakt_id` );
 ALTER TABLE `mm_medlemIGrupp` ADD INDEX ( `medlem_id` );
