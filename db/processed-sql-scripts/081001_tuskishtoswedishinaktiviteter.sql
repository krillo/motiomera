use `motiomera`;

ALTER TABLE `mm_aktivitet` CHANGE `enhet` `enhet` VARCHAR( 32 ) CHARACTER SET utf8 COLLATE utf8_swedish_ci NOT NULL ,
CHANGE `beskrivning` `beskrivning` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_swedish_ci NOT NULL;