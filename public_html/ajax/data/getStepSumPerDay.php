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





/*

[[1.3, "lör 29/12"],[2.3, "sön 30/12"],[3.3, "mån 31/12"], [4.3, "tis 1/1"], [5.3, "ons 2/1"], [6.3, "tor 3/1"], [7.3, "fre 4/1"]];

Array
(
    [0] => Array
        (
            [steg] => 7120
            [datum] => 2013-01-01
        )

    [1] => Array
        (
            [steg] => 12612
            [datum] => 2013-01-02
        )

    [2] => Array
        (
            [steg] => 7029
            [datum] => 2013-01-03
        )

)
{"steps":"[[1,7120], [2,12612], [3,7029], ]"}


    $response = array(
        'percent' => $this->percent,
        'yes' => $this->yes,
        'no' => $this->no,
        'total' => $this->total
    );



  foreach ($stepsArray as $key => $value) {
    $i++;
    $steps .= '[' . $i . ',' . $value['steg'] . '], ';
  }



 */