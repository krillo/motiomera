use `motiomera`;

 CREATE TABLE `motiomera`.`mm_gratisperiod` (
`id` INT NOT NULL ,
`mail` VARCHAR( 155 ) CHARACTER SET utf8 COLLATE utf8_swedish_ci NOT NULL ,
`datetime` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , 
`medlem_id` INT NOT NULL ,
PRIMARY KEY ( `id` ) ,
INDEX ( `datetime` )
);
