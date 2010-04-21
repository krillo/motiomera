use `motiomera`;

 CREATE TABLE `mm_minaquizGruppAcl` (
`minaquiz_id` INT(10) UNSIGNED NOT NULL,
`grupp_id` INT(10) UNSIGNED NOT NULL,
PRIMARY KEY ( `minaquiz_id`, `grupp_id` )
);
