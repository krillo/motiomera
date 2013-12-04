use 'motiomera';

CREATE TABLE `mm_level_sajtdelar` (
  `sajtdel` varchar(100) NOT NULL,
  `levelId` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`sajtdel`,`levelId`)
)