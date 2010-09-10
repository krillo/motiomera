#!/usr/bin/php
<?php
  define('ROOT', "/var/www/motiomera/public_html");
  chdir(ROOT);
  require_once(ROOT ."/php/init.php");

  echo date('Y-m-d h:i:s') ." [INFO] Start put PDF-files on FTP cron script. \n";
  Misc::logMotiomera("Start put PDF-files on FTP cron script ", 'INFO');
  Foretag::uploadOrderFilesFTP();
  Misc::logMotiomera("End put PDF-files on FTP cron script \n", 'INFO');
?>
