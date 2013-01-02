<?php
 /**
  * 13-01-03 Kristian Erendi, Reptilo.se
  */
require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");
$req = new stdClass;
!empty($_REQUEST['mm_id']) ? $req->mm_id = addslashes($_REQUEST['mm_id']) : $req->mm_id = ''; 
!empty($_REQUEST['comment']) ? $req->comment = addslashes($_REQUEST['comment']) : $req->comment = NULL; 
!empty($_REQUEST['smiley']) ? $req->smiley = addslashes($_REQUEST['smiley']) : $req->smiley = NULL; 
!empty($_REQUEST['date']) ? $req->date = addslashes($_REQUEST['date']) : $req->date = ''; 
!empty($_REQUEST['diary_id']) ? $req->diary_id = addslashes($_REQUEST['diary_id']) : $req->diary_id = NULL;
if($req->diary_id == NULL){
  $dagbok = new Dagbok($req->mm_id, $req->comment, $req->smiley, $req->date);
} else {
  $dagbok = Dagbok::loadById($req->diary_id);
  $dagbok->setKommentar($req->comment);
  $dagbok->setBetyg($req->smiley);
  $dagbok->commit();
}
include($_SERVER["DOCUMENT_ROOT"]."/ajax/includes/display_step_rows.php");