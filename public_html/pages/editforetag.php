<?php

include $_SERVER["DOCUMENT_ROOT"]  . "/php/init.php";


if(!empty($_GET["fid"]) && !(isset($FORETAG) && $FORETAG->getId() == $_GET["fid"])){
	Security::demand(ADMIN);
	$foretag = Foretag::loadById($_GET["fid"]);
}else{
	Security::demand(FORETAG);
	$foretag = $FORETAG;
}
$smarty = new MMSmarty();
$tid = Tavling::getTavlingsId($foretag->getId());
//echo $tid[tavlings_id];
$smarty->assign("tid", $tid[tavlings_id]);
$smarty->assign("pagetitle", "Redigera företag");

$tabs = new TabBox("foretag", 590, null);
$tabs->addTab("Lag", "lag");
$tabs->addTab("Inställningar", "installningar");
$tabs->addTab("Deltagare", "anstallda");
$tabs->addTab("Tilläggsbeställning", "tillaggsbest");
$tabs->addTab("Nycklar", "nycklar");
$tabs->addTab("Reklamation", "reklamation");

if((!empty($_GET["tab"])) && ($_GET["tab"] < 6)){
	$tabs->setSelected($_GET["tab"]);
}else{
	$tabs->setSelected("Lag");
}

$smarty->assign("tabs", $tabs);
$smarty->assign("foretaget", $foretag);
$sel_kommun = $foretag->getKommunId();
$smarty->assign("sel_kommun", $sel_kommun);

$smarty->assign("id", $foretag->getId());
$nycklar = $foretag->listNycklar(true);
$smarty->assign("nycklar", $nycklar);

//show this page a week after the contest is finished
$registerUntilDate = date('Y-m-d', strtotime(date("Y-m-d", strtotime($foretag->getSlutDatum())) . " +1 days"));
$sendResultDate = date('Y-m-d', strtotime(date("Y-m-d", strtotime($foretag->getSlutDatum())) . " +2 days"));
$showPageUtilDate = date('Y-m-d', strtotime(date("Y-m-d", strtotime($foretag->getSlutDatum())) . " +7 days"));

$datesArray = array(
array("v " . date("W", strtotime($foretag->getStartdatum())) . ' &nbsp;&nbsp; '. $foretag->getStartdatum(), Misc::veckodag(date('N', strtotime($foretag->getStartdatum()))), 'Startdatum för er företagstävling'),
array("v " . date("W", strtotime($foretag->getSlutDatum())) . ' &nbsp;&nbsp; '. $foretag->getSlutDatum(), Misc::veckodag(date('N', strtotime($showPageUtilDate))), 'Slutdatum för er företagstävling'),
array("v " . date("W", strtotime($registerUntilDate)) . ' &nbsp;&nbsp; '. $registerUntilDate, Misc::veckodag(date('N', strtotime($registerUntilDate))), 'Sista dagen för registrering av steg'),
array("v " . date("W", strtotime($sendResultDate)) . ' &nbsp;&nbsp; '. $sendResultDate, Misc::veckodag(date('N', strtotime($sendResultDate))), 'Tävlingsresultatet skickas per mail till alla deltagare'),
array("v " . date("W", strtotime($showPageUtilDate)) . ' &nbsp;&nbsp; '. $showPageUtilDate, Misc::veckodag(date('N', strtotime($showPageUtilDate))), 'Administrationssidan är tillgänglig tom detta datum'),
);
$smarty->assign("datesArray", $datesArray);

if($showPageUtilDate > date('Y-m-d') OR isset($ADMIN)) {
  $smarty->display('editforetag.tpl');  
} else {
  $smarty->display('nytavling.tpl');  
}


?>
