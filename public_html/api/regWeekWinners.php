<?php
/*
 * 2012-09-10 Krillo
 * Call this by ajax
 * prints:
 * 1. a member list
 * 2. html clip code
 * 3. email list
 * 
 */
require_once($_SERVER["DOCUMENT_ROOT"] . "/php/init.php");
error_reporting(E_ALL);
ini_set('display_errors', '1');
!empty($_REQUEST['winnerIds']) ? $winnerIds = $_REQUEST['winnerIds'] : $winnerIds = '';
$idArray = explode(',', $winnerIds);
$status = Medlem::updateVeckoVinnare($idArray);
echo $status; 