<?php

/**
 * Description: Returns step date for register step dialog, shown in an table
 * The data is returned as a jason object in the format below:
 * 
 * Date: 2013-01-06
 * Author: Kristian Erendi 
 * URI: http://reptilo.se 
 */
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-type: application/json');
require_once($_SERVER["DOCUMENT_ROOT"] . "/php/init.php");
if (empty($req)) {
  $req = new stdClass;
  !empty($_REQUEST['mm_id']) ? $req->mm_id = addslashes($_REQUEST['mm_id']) : $req->mm_id = '';
  !empty($_REQUEST['date']) ? $req->date = addslashes($_REQUEST['date']) : $req->date = '';
}
$medlem = Medlem::loadById($req->mm_id);
$list = Steg::listByDatum($req->date, $medlem);
foreach ($list as $stegId => $stegObject) {
  $i++;
  $class = $i % 2 == 0 ? 'mm-even' : 'mm-odd';
  $aktivitet = '';
  $antal = '';
  if ($stegObject->getAktivitetId() != 5) {
    $aktivitet = $stegObject->getAktivitet()->getNamn();
    $antal = $stegObject->getAntal();
  }
  $row = array($class, $req->date, $stegObject->getAktivitetId(), $aktivitet, $antal, $stegObject->getSteg(), $stegId);
  $table[] = $row;
  /*
    $rowObj = new stdClass;
    $rowObj->id = $stegId;
    $rowObj->class = $class;
    $rowObj->date = $req->date;
    $rowObj->activity = $aktivitet;
    $rowObj->count = $antal;
    $rowObj->steps = $stegObject->getSteg();
    $tab[$stegId] = $rowObj;
   */
}
$dagbok = Dagbok::getEntryBymmIdDate($req->mm_id, $req->date);
$response['table'] = $table;
//$response['tab'] = $tab;
$response['diary'] = $dagbok;
echo json_encode($response);