#!/usr/bin/php
<?php
  define('ROOT', dirname(__FILE__)."/../public_html");
  chdir(ROOT);
  require_once(ROOT ."/php/init.php");

  echo date('Y-m-d h:i:s') ." [INFO] Start create PDF-files cron script. \n";
  Misc::logMotiomera("Start create PDF-files cron script ", 'INFO');
  Foretag::skapaFiler();
  Misc::logMotiomera("End create PDF-files cron script \n", 'INFO');
?>
