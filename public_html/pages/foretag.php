<?php
include $_SERVER["DOCUMENT_ROOT"] . "/php/init.php";

$smarty = new MMSmarty();
if (!empty($_GET["fid"])) {
  $foretag = Foretag::loadById($_GET["fid"]);
} elseif (isset($USER)) {
  $foretag = $USER->getForetag();
}
$smarty->assign("pagetitle", "Företagssida");

//print_r($USER);
//print_r($ADMIN);
//print_r($FORETAG);
//print_r($foretag);
if (!isset($ADMIN)) {  //if not admin - next test
  if (!isset($FORETAG) || $FORETAG->getId() != $foretag->getId()) {  //if not foretagsadmin or wrong company - next test
    if(!isset($USER) || !$foretag->isAnstalld($USER)){  //if not user or wrong company - exception
      throw new UserException("Du tillhör inte det här företaget", "Du måste tillhöra det här företaget för att kunna se den här sidan.");
    }
  }
}

$kommun = $foretag->getKommun();
$smarty->assign("this_foretag", $foretag);
$smarty->assign("kommun", $kommun);
$medlemmar = $foretag->listMedlemmar();
$lagList = $foretag->listLag();
$smarty->assign("medlemmar", $medlemmar);
$smarty->assign("lagList", $lagList);


// steggrafik
$smarty->assign("medlem", $USER);
if (count($lagList) > 0 && $foretag->getStartDatum() <= date("Y-m-d")) {
  $smarty->assign("medlemmar", $medlemmar);
  $topplistan = $foretag->getTopplistaLag(false, true);
  $flagslice = null;
  $nr = null;
  if (count($topplistan) < 2) {
    $multiplier = 0;
  } else {
    if (count($topplistan) > 10) {
      $flagslice = true;
      $nr = 9;
    } else {
      $nr = count($topplistan) - 1;
    }
    if (empty($nr)) {
      $nr = 1;
    }
    $multiplier = 650 / ($nr);
  }
  $i = 0;
  $positioner = array();
  foreach ($topplistan as $lag) {
    $positioner[$i] = $lag;
    $i++;
  }

  $positioner = array_reverse($positioner);
  if (count($positioner) > 10) {
    $positioner = array_slice($positioner, count($positioner) - 10, 10);
  }

  $smarty->assign("positioner", $positioner);
  $smarty->assign("nr", $nr);
  $smarty->assign("multiply", $multiplier);
  $smarty->assign("topplistan", $topplistan);
}

//false if no custom added
$foretagCustomBild = CustomForetagsbild::getImgUrlIfValidFile($foretag->getId());
$smarty->assign("foretagCustomBild", $foretagCustomBild);

$topplistaDeltagare = new Topplista();
$topplistaDeltagare->addParameter(Topplista::PARAM_FORETAG, $foretag);
$topplistaDeltagare->addParameter(Topplista::PARAM_START, $foretag->getStartdatum());
$topplistaDeltagare->addParameter(Topplista::PARAM_STOP, $foretag->getSlutdatum());
$smarty->assign("topplistaDeltagare", $topplistaDeltagare);


/* $topplistaForetag = Foretag::getTopplistaForetag();
  $smarty->assign("topplistaForetag", $topplistaForetag);
 */
// Fotoalbum
$bildblock = FotoalbumBild::loadForetagsBildblock($foretag, $antal = 20);
$smarty->assign("bildblock", $bildblock);

// Grafer:
include_once ROOT . '/php/libs/php-ofc-library/open-flash-chart-object.php';

ob_start();
open_flash_chart_object(300, 200, '/data/veckograf.php?fid=' . $foretag->getId(), false, '/');
$graf = ob_get_contents();
ob_end_clean();

$smarty->assign("graf", $graf);
$smarty->display('foretag.tpl');
?>