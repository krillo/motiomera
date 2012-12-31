<?php
 /**
  * 12-12-31 Kristian Erendi, Reptilo.se
  * Yes, working on new years eve!
  */
header("Content-Type: text/html; charset=utf-8");
require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");
$req = new stdClass;
!empty($_REQUEST['mm_id']) ? $req->mm_id = addslashes($_REQUEST['mm_id']) : $req->mm_id = ''; 
!empty($_REQUEST['count']) ? $req->count = addslashes($_REQUEST['count']) : $req->count = ''; 
!empty($_REQUEST['date']) ? $req->date = addslashes($_REQUEST['date']) : $req->date = ''; 
!empty($_REQUEST['activity_id']) ? $req->activity_id = addslashes($_REQUEST['activity_id']) : $req->activity_id = ''; 

$user = Medlem::loadById($req->mm_id);
$activity = Aktivitet::loadById($req->activity_id);
$steg = new Steg($user, $activity, date($req->date . " H:i:s"), $req->count, false);
print_r($steg);