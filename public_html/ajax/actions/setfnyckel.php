<?php

/**
* Description: set foretagsnyckel, checks first that it is available
* if available then change else message not available 
* 
* Date: 2013-09-13
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
  !empty($_REQUEST['fnyckel']) ? $req->fnyckel = addslashes($_REQUEST['fnyckel']) : $req->fnyckel = '';
}
$medlem = Medlem::loadById($req->mm_id);
$response = $medlem->setFnyckelAjax($req->fnyckel);
echo json_encode($response);