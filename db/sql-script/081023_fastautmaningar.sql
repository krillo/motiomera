use 'motiomera';

CREATE TABLE `motiomera`.`mm_fastautmaningar` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`kommunTill_id` INT NOT NULL ,
INDEX ( `kommunTill_id` ));