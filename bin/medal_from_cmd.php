#!/bin/php
<?php
/**
 * Run this script to fix medals week by week
 * Pass two parameters -  year and week
 * php medal_from_cmd.php 2010 37
 */
define('ROOT', dirname(__FILE__) . "/../public_html");
chdir(ROOT);
require_once(ROOT . "/php/init.php");

$year =  $_SERVER["argv"][1];
$week =  $_SERVER["argv"][2];
echo date('Y-m-d h:i:s') . " [INFO] Start medal from command line year: $year, week $week \n";
Misc::logMotiomera("Start medal from command line year: $year, week $week ", 'INFO');
Sammanstallning::sammanstallMedaljer($year, $week);
Misc::logMotiomera("End medal from command line \n", 'INFO');
?>