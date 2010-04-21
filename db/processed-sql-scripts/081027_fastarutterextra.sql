use `motiomera`;

ALTER TABLE `mm_medlem` ADD `userOnStaticRoute` SET( 'true', 'false' ) NOT NULL DEFAULT 'false' AFTER `customerId`;

CREATE TABLE `motiomera`.`mm_fastautmaningar_avklarade` (
`id` INT NOT NULL ,
`medlem_id` INT NOT NULL ,
`fastrutt_id` INT NOT NULL ,
`fardig_datum` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ,
`steg` INT NOT NULL
)