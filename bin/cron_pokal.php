#!/usr/bin/php
<?php
  define('ROOT', "/var/www/motiomera/current/public_html");
  chdir(ROOT);
  require_once(ROOT ."/php/init.php");

  echo date('Y-m-d h:i:s') ." [INFO] Start pokal cron script. \n";
  Misc::logMotiomera("Start pokal cron script ", 'INFO');
  Sammanstallning::sammanstallPokaler();
  Misc::logMotiomera("End pokal cron script \n", 'INFO');
?>