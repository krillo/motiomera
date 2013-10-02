<?php

include $_SERVER["DOCUMENT_ROOT"] . "/php/init.php";
$smarty = new MMSmarty();
$smarty->assign("pagetitle", "Rutt");

// Ta bort eventuella temp-sträckor som inte sparats:
if (isset($USER)) {
  $USER->cleanTempStrackor();
}
$medlem = (!empty($_GET["id"])) ? Medlem::loadById($_GET["id"]) : $USER;
$smarty->assign("medlem", $medlem);




$stegtotal = $medlem->getStegTotal();
$kmTotal = Steg::stegToKm($stegtotal);


// Kommunjakt
$rutt = new Rutt($medlem);
$rutten = $rutt->getRutt();
$currentKommun = $medlem->getCurrentKommun();
$kommunnamn = Kommun::listNamn(true);
$rutter = $rutt->getRutt();
foreach ($rutter as $index => $temprutt) {
  $totalKm = $temprutt["TotalKm"];
}
if (empty($totalKm)) {
  $totalKm = 0;
}

$totalKmKvar = $totalKm - $kmTotal;
$smarty->assign("totalKmKvar", $totalKmKvar);
$dagar7000 = ceil($totalKmKvar / 7);
$dagar11000 = ceil($totalKmKvar / 11);
$smarty->assign("dagar7000", $dagar7000);
$smarty->assign("dagar11000", $dagar11000);

if (count($rutten) > 0) {
  $lastKommun = $rutten[count($rutten) - 1]["Kommun"];
} else {
  $lastKommun = "";
}


include_once ROOT . '/php/libs/php-ofc-library/open-flash-chart-object.php';
ob_start();
open_flash_chart_object(580, 200, '/data/rapport_graf.php?id=' . $medlem->getId(), false, '/');
$graf = ob_get_contents();
ob_end_clean();

$smarty->assign("graf", $graf);

global $USER, $urlHandler, $medlem, $opt_angransande, $rutt, $rutten, $currentKommun;

//$smarty->noShowHeaderFooter(); // dölj header och footer då detta är en flik

if (!isset($USER) || (!empty($_GET["id"]) && $_GET["id"] != $USER->getId())) {
  $smarty->assign("notown", "true");
}

$smarty->assign("opt_angransande", $opt_angransande);

$smarty->assign("rutt", $rutt);
if ($rutten[0]):
  $smarty->assign("rutten", $rutten);
endif;
$smarty->assign("currentKommun", $currentKommun);

if (isset($USER) && ($USER->getId() == $_GET["id"] or trim($_GET["id"])) == ""):
  $smarty->assign("egensida", "1");
endif;


$fastaUtmaningar = Rutt::getAllFastaUtmaningar();
$smarty->assign("fastaUtmaningar", $fastaUtmaningar);
if (!empty($USER)) {
  $userOnStaticRoute = $USER->getUserOnStaticRoute();
  $userIsAbroadId = $USER->getUserAbroadId();
  if ($userOnStaticRoute == true) {
    $smarty->assign("userOnStaticRoute", $userOnStaticRoute);
  }

}

$smarty->display('rapport_rutt.tpl');