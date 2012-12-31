<?php
 /**
  * 13-01-01 Kristian Erendi, Reptilo.se
  */
require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");
$req = new stdClass;
!empty($_REQUEST['row_id']) ? $req->row_id = addslashes($_REQUEST['row_id']) : $req->row_id = ''; 
!empty($_REQUEST['mm_id']) ? $req->mm_id = addslashes($_REQUEST['mm_id']) : $req->mm_id = ''; 
!empty($_REQUEST['date']) ? $req->date = addslashes($_REQUEST['date']) : $req->date = ''; 
$status = Steg::deleteStepRow($req->row_id);
include($_SERVER["DOCUMENT_ROOT"]."/ajax/includes/display_step_rows.php");