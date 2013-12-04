use `motiomera`;

 ALTER TABLE `mm_medlem` CHANGE `browser` `browser` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_swedish_ci NOT NULL;
 ALTER TABLE `mm_order` CHANGE `browser` `browser` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_swedish_ci NOT NULL;