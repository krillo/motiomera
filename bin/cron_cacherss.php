#!/usr/bin/php
<?php
  define('ROOT', dirname(__FILE__)."/../public_html");
  chdir(ROOT);
  require_once(ROOT ."/php/init.php");

  echo date('Y-m-d h:i:s') ." [INFO] Start:  Cache Rss fedd from mabra.com cron script. \n";
  Misc::logMotiomera("Start:  Cache Rss fedd from mabra.com cron script ", 'INFO');
  $rss = new RSS();
  $rss->getFeed();
  Misc::logMotiomera("End Cache Rss fedd from mabra.com cron script \n", 'INFO');
?>