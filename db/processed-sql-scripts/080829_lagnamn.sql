USE `motiomera`;

DROP TABLE IF EXISTS `mm_lagnamn`;
CREATE TABLE `mm_lagnamn` (
`id` INT NOT NULL AUTO_INCREMENT ,
`namn` VARCHAR( 100 ) CHARACTER SET utf8 COLLATE utf8_swedish_ci NOT NULL ,
`img` VARCHAR( 200 ) CHARACTER SET utf8 COLLATE utf8_swedish_ci NOT NULL ,
`serialize` blob NOT NULL,
PRIMARY KEY ( `id` )
)  ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;
