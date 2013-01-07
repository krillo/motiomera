<?php
 /**
  * 13-01-01 Kristian Erendi, Reptilo.se
  */
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-type: application/json');
require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");
$req = new stdClass;
!empty($_REQUEST['row_id']) ? $req->row_id = addslashes($_REQUEST['row_id']) : $req->row_id = ''; 
!empty($_REQUEST['mm_id']) ? $req->mm_id = addslashes($_REQUEST['mm_id']) : $req->mm_id = ''; 
!empty($_REQUEST['date']) ? $req->date = addslashes($_REQUEST['date']) : $req->date = ''; 
$status = Steg::deleteStepRow($req->row_id);
include($_SERVER["DOCUMENT_ROOT"]."/ajax/data/getUserSteps.php");
