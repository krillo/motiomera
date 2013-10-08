<?php

include $_SERVER["DOCUMENT_ROOT"] . "/php/init.php";

if (!isset($FORETAG))
  Security::demand(USER);


Misc::setTestData(107);

$smarty = new MMSmarty();

if (isset($_GET["lid"])) {
  $lag = Lag::loadById($_GET["lid"]);
  $lag2 = Lag::loadById($_GET["lid"]);
  $foretag = Foretag::loadByLag($_GET["lid"]);

  $smarty->assign("pagetitle", "Lagsidan");

  $smarty->assign("lag", $lag);
  $smarty->assign("lag2", $lag);
  if (isset($USER) && $lag->isMember($USER))
    $ismember = true;
  else
    $ismember = false;

  $smarty->assign("ismember", $ismember);

  $medlemmar = $lag->listMedlemmar();
  $smarty->assign("medlemmar", $medlemmar);

  if (count($medlemmar) > 0) {
    $smarty->assign("medlemmar", $medlemmar);

    $start = $lag->getStart();


    $topplista = new Topplista();
    $topplista->addParameter(Topplista::PARAM_LAG, $lag);
    $topplista->addParameter(Topplista::PARAM_START, $start);


    $topplistan = $topplista->getTopplista(10);

    if (count($topplistan) < 2)
      $multiplier = 0;
    else
      $multiplier = 500 / (count($topplistan) - 1);

    $topplistan = array_reverse($topplistan);

    $i = 0;
    $positioner = array();
    foreach ($topplistan as $position) {
      $positioner[$position["medlem"]->getId()] = round($i * $multiplier);
      $i++;
    }

    $smarty->assign("positioner", $positioner);
  }

  $foretag2 = $lag->getForetag();
  $smarty->assign("foretag2", $foretag2);

  $smarty->assign("medlem", $USER);

  // Topplistor
  $forraVeckan = date("Y-m-d H:i:s", strtotime(date("Y-m-d")) - (60 * 60 * 24 * 7));
  $topplista = new Topplista();
  $topplista->addParameter(Topplista::PARAM_START, $forraVeckan);
  $topplista->addParameter(Topplista::PARAM_START, $lag->getStart());
  $topplista->addParameter(Topplista::PARAM_STOP, $foretag->getSlutdatum());
  $topplista->addParameter(Topplista::PARAM_LAG, $lag);
  $smarty->assign("topplista", $topplista);

  // Fotoalbum
  $bildblock = FotoalbumBild::loadLagsBildblock($lag, $antal = 16);
  $smarty->assign("bildblock", $bildblock);



  $anslagstavla = $lag->getAnslagstavla();
  //$anslagstavlaRader = $anslagstavla->getAllaRader();
  //$anslagstavlaAntalRader = $anslagstavla->getAntalRader();
  //$smarty->assign("anslagstavlarader", $anslagstavlaRader);
  //$smarty->assign("anslagstavlaantalrader", $anslagstavlaAntalRader);


  $aTavla = $anslagstavla->getAnslagstavalaLista();
  $nbrPosts = count($aTavla);
  $smarty->assign('atavla', $aTavla);
  $smarty->assign('nbrPosts', $nbrPosts);


  $smarty->assign("bildURL", $lag->getBildFullUrl());
}

// Grafer:

include_once ROOT . '/php/libs/php-ofc-library/open-flash-chart-object.php';

ob_start();
open_flash_chart_object( 300, 200, '/data/veckograf.php?lid=' . $lag->getId(),false,'/' );
$graf = ob_get_contents();
ob_end_clean();

$smarty->assign("graf",$graf);

$smarty->display('lag.tpl');
