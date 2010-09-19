#!/usr/bin/php
<?php
  define('ROOT', dirname(__FILE__)."/../public_html");
  chdir(ROOT);
  require_once(ROOT ."/php/init.php");

  echo date('Y-m-d h:i:s') ." [INFO] krillo start. \n";
  Misc::logMotiomera("Start krillo cron script ", 'INFO');
  //Sammanstallning::sammanstallPokaler();
  Misc::logMotiomera("End krillo cron script \n", 'INFO');
  echo date('Y-m-d h:i:s') ." [INFO] krillo stop. \n";
?>
