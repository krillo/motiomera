#!/usr/bin/php
<?php
  define('ROOT', "/var/www/motiomera/public_html");
  chdir(ROOT);
  require_once(ROOT ."/php/init.php");

  echo date('Y-m-d h:i:s') ." [INFO] Start:  Cache Rss fedd from mabra.com cron script. \n";
  Misc::logMotiomera("Start:  Cache Rss fedd from mabra.com cron script ", 'INFO');
  RSSHandler::refreshCache();
  Misc::logMotiomera("End Cache Rss fedd from mabra.com cron script \n", 'INFO');







/*


        // create curl resource
        $ch = curl_init();

        // set url
        curl_setopt($ch, CURLOPT_URL, "http://mabra.com/feed/");

        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // $output contains the output string
        $output = curl_exec($ch);


        // close curl resource to free up system resources
        curl_close($ch);

  echo $output;

*/

?>