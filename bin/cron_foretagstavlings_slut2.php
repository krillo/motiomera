#!/usr/bin/php
<?php
  define('ROOT', "/var/www/motiomera/public_html");
  chdir(ROOT);
  require_once(ROOT ."/php/init.php");

  echo date('Y-m-d h:i:s') ." [INFO] Start:  End and save competition data on monday cron script. \n";
  Misc::logMotiomera("Start:  End and save competition data on monday cron script ", 'INFO');
  Foretag::saveAndEndForetagsTavling();
  Misc::logMotiomera("End end and save competition data on monday cron script \n", 'INFO');
?>

