use `motiomera`;

CREATE TABLE `mm_reminders` (
	id INT(40) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	title VARCHAR(255) NOT NULL,
	query TEXT NOT NULL	
)