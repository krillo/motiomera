<?php
require $_SERVER["DOCUMENT_ROOT"] . "/php/init.php";

if(empty($_REQUEST["action"])){
  header("location: /pages/api_result.php?ret=400", true, '400');
  exit;
} 
switch($_REQUEST["action"]){
  case "login":
    try{
      if(empty($_REQUEST["mem"]) or empty($_REQUEST["in"])){
        header("Location: /pages/api_result.php?ret=400", true, '400');
        exit;
      }
      $status = Medlem::loggaIn(trim($_REQUEST["mem"]),trim($_REQUEST["in"]));
      if(!isset($status) || !$status) {
        throw new UserException("Felaktig inloggning", $felInloggString);
      }
      $USER = Medlem::getInloggad();
      $USER->saveBrowserAndIp();
      header("Location: /pages/api_result.php?ret=200", true, '200');
      exit;
    }catch(MedlemException $e){
      header("Location: /pages/api_result.php?ret=400", true, '400');
      exit;
    }
    break;
    
  case "loginsave":
    try{
      if(empty($_REQUEST["mem"]) or empty($_REQUEST["in"]) or empty($_REQUEST["steg0_aid"]) or empty($_REQUEST["steg0_datum"]) or empty($_REQUEST["steg0_antal"])){
        header("Location: /pages/api_result.php?ret=400", true, '400');
        exit;
      }
      $status = Medlem::loggaIn(trim($_REQUEST["mem"]),trim($_REQUEST["in"]));
      if(!isset($status) || !$status) {
        throw new UserException("Felaktig inloggning", $felInloggString);
      }
      $USER = Medlem::getInloggad();
      $USER->saveBrowserAndIp();

      $a = Aktivitet::loadById($_REQUEST["steg0_aid"]);
      new Steg($USER, $a, date($_REQUEST["steg0_datum"] . " H:i:s"), $_REQUEST["steg0_antal"]);
      header("Location: /pages/api_result.php?ret=200", true, '301');
      exit;
    }catch(Exception $e){
      header("Location: /pages/api_result.php?ret=400", true, '400');
      exit;
      //throw new UserException("Felaktig inloggning", $felInloggString);
    }
    break;
  default:
    
}
?>
