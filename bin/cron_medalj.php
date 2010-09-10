#!/usr/bin/php
<?php
  define('ROOT', "/var/www/motiomera/public_html");
  chdir(ROOT);
  require_once(ROOT ."/php/init.php");

  echo date('Y-m-d h:i:s') ." [INFO] Start medal cron script. \n";
  Misc::logMotiomera("Start medal cron script ", 'INFO');
  Sammanstallning::sammanstallMedaljer();
  Misc::logMotiomera("End medal cron script \n", 'INFO');
?>
