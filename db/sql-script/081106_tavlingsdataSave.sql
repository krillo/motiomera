use `motiomera`;

CREATE TABLE `mm_tavling_save` (
`medlem_id` INT UNSIGNED NOT NULL ,
`foretag_id` INT UNSIGNED NOT NULL ,
`lag_id` INT UNSIGNED NOT NULL ,
`foretagsnyckel` VARCHAR( 150 ) NOT NULL ,
`tavlings_id` INT UNSIGNED NOT NULL ,
`steg` INT NOT NULL ,
`tavlingsrundor` INT NOT NULL ,
INDEX ( `medlem_id` , `foretag_id` , `lag_id` , `tavlings_id` )
);

CREATE TABLE `mm_tavling` (
`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`startdatum` DATE NOT NULL
);

CREATE TABLE `mm_lag_save` (
`id` INT UNSIGNED NOT NULL ,
`foretag_id` INT UNSIGNED NOT NULL ,
`bildUrl` VARCHAR( 255 ) NOT NULL ,
`namn` VARCHAR( 255 ) NOT NULL ,
PRIMARY KEY ( `id` , `foretag_id` )
);

