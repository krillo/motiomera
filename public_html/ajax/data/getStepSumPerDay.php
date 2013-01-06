<?php
/**
 * Description: Returns total sum steps per day per user. Data is returned from from_date to to_date 
 * The data is returned as a jason object in the format below:
 * 
 * Date: 2013-01-04
 * Author: Kristian Erendi 
 * URI: http://reptilo.se 
 * 
 * 
$steps = array(
    array(1, 7120),
    array(2, 5120),
    array(3, 8120),
);
$average = array(
    array(1.3, 7920),
    array(2.3, 6120),
    array(3.3, 9120),
);
$response['steps'] = $steps;
$response['average'] = $average;
echo json_encode($response);
 */
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-type: application/json');
require_once($_SERVER["DOCUMENT_ROOT"] . "/php/init.php");
$req = new stdClass;
!empty($_REQUEST['mm_id']) ? $req->mm_id = addslashes($_REQUEST['mm_id']) : $req->mm_id = '';
!empty($_REQUEST['from_date']) ? $req->from_date = addslashes($_REQUEST['from_date']) : $req->from_date = '';
!empty($_REQUEST['to_date']) ? $req->to_date = addslashes($_REQUEST['to_date']) : $req->to_date = '';
$steps = Steg::getStegTotalPerDays($req->mm_id, $req->from_date, $req->to_date);
$average = Steg::getStegTotalAveragePerDays($req->mm_id, $req->from_date, $req->to_date);
$ticks = Steg::getTicks($req->from_date, $req->to_date);
$stats = Steg::getStepStats($req->mm_id, $req->from_date, $req->to_date);
$response['steps'] = $steps;
$response['average'] = $average;
$response['ticks'] = $ticks;
$response['stats'] = $stats;
echo json_encode($response);