<?php

/**
 * Description: set the fadmin
 * The data is returned as a jason object in the format below:
 *
 * Date: 2013-09-02
 * Author: Kristian Erendi
 * URI: http://reptilo.se
 */
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-type: application/json');
require_once($_SERVER["DOCUMENT_ROOT"] . "/php/init.php");
$req = new stdClass;
!empty($_REQUEST['mm_id']) ? $req->mm_id = addslashes($_REQUEST['mm_id']) : $req->mm_id = '';
!empty($_REQUEST['fid']) ? $req->fid = addslashes($_REQUEST['fid']) : $req->fid = '';
$response['success'] = 0;
try {
  $foretag = Foretag::loadById($req->fid);
  $reset = $foretag->resetAllFadmin();
  if ($reset) {
    $medlem = Medlem::loadById($req->mm_id);
    $medlem->setFadmin($req->fid);
    $medlem->commit();
    $response['success'] = $req->mm_id;
  }
} catch (Exception $exc) {
  $response['error'] = $exc->getTraceAsString();
}
echo json_encode($response);
die();