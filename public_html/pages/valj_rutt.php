<?php

include $_SERVER["DOCUMENT_ROOT"]  . "/php/init.php";

Security::demand(USER);

$smarty = new MMSmarty();
$smarty->assign("pagetitle", "Välj rutt");


$smarty->assign("doldMeny", true);



$medlem = $USER;
if(!empty($_GET["id"]))
	$smarty->assign("notown", "true");

$smarty->assign("medlem", $medlem);


if(isset($_GET["ajax"])) {
	$smarty->noShowHeaderFooter();
	$smarty->assign("ajax",true);
}
else {
	$smarty->assign("ajax",false);
}




$stegtotal = $USER->getStegTotal();	
$kmTotal = Steg::stegToKm($stegtotal);

if ($stegtotal == 0){
	$smarty->assign('firstrun', true);
	$opt_kommuner = Misc::arrayKeyMerge(array(""=>"Välj..."), Kommun::listNamn(false,false));
	$op_namn = array();
	$op_id = array();
	foreach ($opt_kommuner as $key => $value) {
		$op_namn[] = $value;
		$op_id[] = $key;
	}
	$smarty->assign('op_namn', $op_namn);
	$smarty->assign('op_id', $op_id);
	$rutt = new Rutt($medlem);
} else {
}

$rutt = new Rutt($medlem);

$rutten = $rutt->getRutt();
$currentKommun = $medlem->getCurrentKommun();
$kommunnamn = Kommun::listNamn();

$rutter = $rutt->getRutt();

//$temprutt["temp"]
$totalKm=0;
foreach($rutter as $index=>$temprutt) {

	$totalKm = $temprutt["TotalKm"];
}

$totalKmKvar = $totalKm - $kmTotal;
$totalStegKvar = $totalKmKvar * 1000;
if ($totalStegKvar<0) $totalStegKvar = 0;

$smarty->assign("totalKmKvar",$totalKmKvar);
$smarty->assign("totalStegKvar",$totalStegKvar);

$dagar7000 = (ceil($totalKmKvar/7)>0?(ceil($totalKmKvar/7)):0);
$dagar11000 = (ceil($totalKmKvar/11)>0?(ceil($totalKmKvar/11)):0);

$smarty->assign("dagar7000",$dagar7000);
$smarty->assign("dagar11000",$dagar11000);



$lastKommun = $rutten[count($rutten)-1]["Kommun"];
$avstand = $lastKommun->listAvstand();
$opt_angransande = array(""=>"Välj...");
foreach($avstand as $tempavstand){
	$opt_angransande[$tempavstand["id"]] = $kommunnamn[$tempavstand["id"]] . " (" . $tempavstand["km"] . "km)";
}

$lastNonTempIndex = $rutt->getIndexOfLastNonTemp();

//
$smarty->assign("lastNonTempIndex", $lastNonTempIndex);
$smarty->assign("rutt", $rutt);
	$smarty->assign("rutten", $rutten);
	$smarty->assign("currentKommun", $currentKommun);
include_once ROOT . '/php/libs/php-ofc-library/open-flash-chart-object.php';

$smarty->display('valj_rutt.tpl');

?>
