<?php

include $_SERVER["DOCUMENT_ROOT"]  . "/php/init.php";

//Security::demand(USER);

$smarty = new MMSmarty();
$smarty->assign("pagetitle", "Rapport");

// Ta bort eventuella temp-sträckor som inte sparats:
if(isset($USER)) {
	$USER->cleanTempStrackor();
}

$tabs = new TabBox("rapport", 590, null);

$medlem = (!empty($_GET["id"])) ? Medlem::loadById($_GET["id"]) : $USER;


$smarty->assign("medlem", $medlem);

	$tabs->addTab("Rutt", "rutt");
	$tabs->addTab("Steg", "detaljerat");

if(isset($_GET["tab"]))
	$tabs->setSelected($_GET["tab"]);

$smarty->assign("tabs", $tabs);



$stegtotal = $medlem->getStegTotal();
$kmTotal = Steg::stegToKm($stegtotal);


// Kommunjakt
$rutt = new Rutt($medlem);
$rutten = $rutt->getRutt();
$currentKommun = $medlem->getCurrentKommun();
$kommunnamn = Kommun::listNamn(true);

$rutter = $rutt->getRutt();

foreach($rutter as $index=>$temprutt) {

	$totalKm = $temprutt["TotalKm"];
}
if(empty($totalKm)){
	$totalKm=0;
}

$totalKmKvar = $totalKm - $kmTotal;

$smarty->assign("totalKmKvar",$totalKmKvar);

$dagar7000 = ceil($totalKmKvar / 7);
$dagar11000 = ceil($totalKmKvar / 11);

$smarty->assign("dagar7000",$dagar7000);
$smarty->assign("dagar11000",$dagar11000);

if(count($rutten) > 0){
	$lastKommun = $rutten[count($rutten)-1]["Kommun"];
}else{
	$lastKommun ="";
}

/*if($lastKommun) {
	$avstand = $lastKommun->listAvstand();
	$opt_angransande = array(""=>"Välj...");

	//print_r($avstand);
	foreach($avstand as $tempavstand){
		// echo $tempavstand["id"];
		$opt_angransande[$tempavstand["id"]] = $kommunnamn[$tempavstand["id"]] . " (" . $tempavstand["km"] . "km)";
	}
}*/

include_once ROOT . '/php/libs/php-ofc-library/open-flash-chart-object.php';

ob_start();
open_flash_chart_object( 580, 200, '/data/rapport_graf.php?id=' . $medlem->getId(),false,'/' );
$graf = ob_get_contents();
ob_end_clean();

$smarty->assign("graf",$graf);


$smarty->display('rapport.tpl');

?>
