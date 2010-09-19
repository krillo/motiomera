#!/usr/bin/php
<?php
  define('ROOT', dirname(__FILE__)."/../public_html");
  chdir(ROOT);
  require_once(ROOT ."/php/init.php");

  echo date('Y-m-d h:i:s') ." [INFO] Start kundnummer cron script. \n";
  Misc::logMotiomera("Start kundnummer cron script ", 'INFO');
  Order::hamtaNyaKundnummer();
  Order::liftTillaggOrderStatus();
  Misc::logMotiomera("End kundnummer cron script \n", 'INFO');
?>