#!/usr/bin/php
<?php
  define('ROOT', dirname(__FILE__)."/../public_html");
  chdir(ROOT);
  require_once(ROOT ."/php/init.php");

  echo date('Y-m-d h:i:s') ." [INFO] Start:  truncate feed on mypage cron script. \n";
  Misc::logMotiomera("Start:  truncate feed on mypage cron script ", 'INFO');
  Feed::truncateFeedItems();
  Misc::logMotiomera("End truncate feed on mypage cron script \n", 'INFO');
?>