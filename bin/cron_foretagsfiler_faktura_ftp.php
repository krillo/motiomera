#!/usr/bin/php
<?php
  define('ROOT', dirname(__FILE__)."/../public_html");
  chdir(ROOT);
  require_once(ROOT ."/php/init.php");

  echo date('Y-m-d h:i:s') ." [INFO] Start put Faktura-files on FTP cron script. \n";
  Misc::logMotiomera("Start put Faktura-files on FTP cron script ", 'INFO');
  Foretag::uploadOrderFakturaFilesFTP();
  Misc::logMotiomera("End put Faktura-files on FTP cron script \n", 'INFO');
?>
