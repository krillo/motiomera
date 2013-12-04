<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");


if ( isset($_POST['fid']) && isset($_POST['nbr'])){
  $foretag = Foretag::loadById($_POST["fid"]);
  $fid = $_POST["fid"];
  if($foretag->sendReclamation($_POST['nbr'])){
  	//successfully sent 
  	//write a record to mm_reclamation 
    $rec = new Reclamation($_POST['fid'], $_POST['nbr']);
    header("Location: " . $SETTINGS["url"] . "/pages/editforetag.php?fid=$fid&tab=5");
  }else{
    header("Location: " . $SETTINGS["url"] . "/pages/editforetag.php?fid=$fid&tab=5");
  }  
}
?>