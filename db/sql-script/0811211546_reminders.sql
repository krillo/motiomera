use `motiomera`;

CREATE TABLE `mm_paminnelse_sql` (
	id INT(40) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	namn VARCHAR(255) NOT NULL,
	query TEXT NOT NULL,
	meddelande_id INT(40) NOT NULL
);

CREATE TABLE `mm_paminnelse_meddelanden` (
	id INT(40) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	namn VARCHAR(255) NOT NULL,
	mall TEXT NOT NULL
);

CREATE TABLE `mm_paminnelse_aktiva` (
	id INT(40) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	medlem_id INT(40) NOT NULL,
	sql_id INT(40) NOT NULL
);

ALTER TABLE `mm_paminnelse_aktiva` ADD INDEX ( `medlem_id` , `sql_id` );
ALTER TABLE `mm_paminnelse_sql` ADD `inre_mall` TEXT NOT NULL AFTER `query` ;