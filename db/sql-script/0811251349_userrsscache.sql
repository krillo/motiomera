USE `motiomera`;

 CREATE TABLE `mm_userrsscache` (
`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`medlem_id` INT UNSIGNED NOT NULL ,
`title` VARCHAR( 155 ) CHARACTER SET utf8 COLLATE utf8_swedish_ci NOT NULL ,
`description` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_swedish_ci NOT NULL ,
`pubDate` DATETIME NOT NULL ,
`link` VARCHAR( 155 ) NOT NULL ,
`commentsLink` VARCHAR( 155 ) NOT NULL
) ENGINE = MYISAM CHARACTER SET utf8 COLLATE utf8_swedish_ci 
