use `motiomera`;

 CREATE TABLE `mm_minaquiz` (
`id` INT NOT NULL AUTO_INCREMENT ,
`titel` VARCHAR( 155 ) CHARACTER SET utf8 COLLATE utf8_swedish_ci NOT NULL ,
`datetime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , 
`medlem_id` INT NOT NULL ,
PRIMARY KEY ( `id` )
);

 CREATE TABLE `mm_minaquiz_fragor` (
`id` INT NOT NULL AUTO_INCREMENT ,
`minaquiz_id` INT NOT NULL,
`fraga` TEXT CHARACTER SET utf8 COLLATE utf8_swedish_ci NOT NULL ,
`svar_1` TEXT CHARACTER SET utf8 COLLATE utf8_swedish_ci NOT NULL ,
`svar_2` TEXT CHARACTER SET utf8 COLLATE utf8_swedish_ci NOT NULL ,
`svar_3` TEXT CHARACTER SET utf8 COLLATE utf8_swedish_ci NOT NULL ,
`ratt_svar` ENUM('1','2','3') ,
`ordning` INT NOT NULL ,
`datetime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
PRIMARY KEY ( `id` )
);

 CREATE TABLE `mm_minaquiz_besvarade` (
`id` INT NOT NULL AUTO_INCREMENT ,
`medlem_id` INT NOT NULL ,
`minaquiz_fragor_id` INT NOT NULL ,
`svar` ENUM('1','2','3') ,
`datetime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
PRIMARY KEY ( `id` )
);
