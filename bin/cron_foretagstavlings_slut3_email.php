#!/usr/bin/php
<?php
  define('ROOT', "/var/www/motiomera/public_html");
  chdir(ROOT);
  require_once(ROOT ."/php/init.php");

  echo date('Y-m-d h:i:s') ." [INFO] Start:  send result email on tuesday cron script. \n";
  Misc::logMotiomera("Start:  send result email on tuesday cron script ", 'INFO');
  Foretag::foretagsTavlingEndSendEmail();
  Misc::logMotiomera("End send result email on tuesday cron script \n", 'INFO');
?>