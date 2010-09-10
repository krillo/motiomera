<?php
if (!defined("INIT")) {
	include $_SERVER["DOCUMENT_ROOT"]  . "/php/init.php";
}




/*

        if($ch = curl_init()){
          echo "curl init success";
          curl_setopt($ch, CURLOPT_URL, "http://mabra.com/feed");
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          $output = curl_exec($ch);
          curl_close($ch);
          echo $output;
        }else{
          echo "curl falure";
        }

*/




echo "start of cron via web";
RSSHandler::refreshCache();










?>
