ALTER TABLE `mm_medlem` ADD `fb_id` VARCHAR(20)  NULL  DEFAULT NULL  AFTER `id`;
ALTER TABLE `mm_medlem` CHANGE `sessionId` `sessionId` VARCHAR(200)  NOT NULL  DEFAULT '';


