<?php

require_once($_SERVER["DOCUMENT_ROOT"] . "/php/init.php");
Security::demand(ADMIN);
error_reporting(E_ALL);
ini_set('display_errors', '1');

$req = new stdClass;
!empty($_REQUEST['file']) ? $req->file = $_REQUEST['file'] : $req->file = '';
if ($req->file != '') {
  switch (true) {
    case strpos($req->file, '_FAK_') > 0:
      $localFile = FORETAGSFAKTURA_LOCAL_PATH . "/" . $req->file;
      echo '<pre>';
      echo file_get_contents($localFile);
      echo '</pre>';
      break;
    case strpos($req->file, '_MEM_') > 0:
      $localFile = MEDLEMSFIL_LOCAL_PATH . "/" . $req->file;
      echo '<pre>';
      echo file_get_contents($localFile);
      echo '</pre>';
      break;
    default:
      $webFilePath = "/files/order_files/" . $req->file;
      $localFile = FORETAGSFIL_LOCAL_PATH . "/" . $req->file;
      	header("location: " . $webFilePath);
      die();
      header('Content-type: application/pdf');
      header('Content-Disposition: inline; filename="' . $webFilePath . '"');
      header('Content-Length: ' . filesize($localFile));
      @readfile($localFile);
      break;
  }
}