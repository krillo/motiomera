#!/usr/bin/php
<?php
  define('ROOT', "/var/www/motiomera/public_html");
  chdir(ROOT);
  require_once(ROOT ."/php/init.php");

  echo date('Y-m-d h:i:s') ." [INFO] Start friday email reminder cron script. \n";
  Misc::logMotiomera("Start friday email reminder cron script ", 'INFO');
  Foretag::sendRemindAboutSteg();
  Misc::logMotiomera("End friday email reminder cron script \n", 'INFO');
?>