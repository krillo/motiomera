<?php

include $_SERVER["DOCUMENT_ROOT"]  . "/php/init.php";

Security::demand(USER);

$smarty = new MMSmarty();

if(isset($_GET["id"])){
	$grupp = Grupp::loadById($_GET["id"]);
	
if($grupp->getPublik()==0 && !(isset($USER) && $grupp->isMember($USER))){

	if(!Security::authorized(ADMIN)){
		throw new UserException("Kan ej visa Klubb", "Denna Klubb visas endast för klubbens medlemmar");
	}
}

	$smarty->assign("pagetitle", ucfirst($grupp->getNamn())." &mdash; Klubb");
	$smarty->assign("grupp", $grupp);
	$skapare = $grupp->getSkapare();
	$smarty->assign("skapare", $skapare);
		
	$medlemmar = $grupp->listMedlemmar();

	if(count($medlemmar) > 0){
		$smarty->assign("medlemmar", $medlemmar);

		$start = $grupp->getStart();
						

		$topplista = new Topplista();
		$topplista->addParameter(Topplista::PARAM_GRUPP, $grupp);
		$topplista->addParameter(Topplista::PARAM_START, $start);
		
		$topplistan = $topplista->getTopplista(10);
		
		if(count($topplistan) < 2)
			$multiplier = 0;
		else
			$multiplier = 500/(count($topplistan)-1);

		$topplistan = array_reverse($topplistan);

		$i = 0;		
		$positioner = array();
		foreach($topplistan as $position){
			$positioner[$position["medlem"]->getId()] = round($i*$multiplier);
			$i++;
		}		
			
		$smarty->assign("positioner", $positioner);		
		
	}

	if($USER->getId() == $grupp->getSkapareId()){ // medlemmen är gruppens ägare
	
		$ignored = $grupp->listIgnored();
		if(count($ignored) > 0)
			$smarty->assign("ignored", $ignored);
	
		$smarty->assign("owner", true);	
		if($grupp->getSkapareId() != $USER->getId())
			throw new UserException("Nekad", "Du har inte tillåtelse att redigera den här gruppen");
	
		$smarty->assign("gruppnamn", $grupp->getNamn());
		
		$requests = $grupp->listRequests();
		if(count($requests) > 0)
			$smarty->assign("requests", $requests);
		
	}
	else { // medlemmen är INTE gruppens ägare
		//$smarty->assign("owner", false);
		$requestable = $grupp->isRequestable($USER);
		$ismember = $grupp->isMember($USER);
		$smarty->assign("requestable", $requestable);	
		$smarty->assign("ismember", $ismember);
	}
	
	
	$smarty->assign("medlem",$USER);
	
	// Topplistor
	
	$forraVeckan = date("Y-m-d H:i:s", strtotime(date("Y-m-d"))-(60*60*24*7));
	
	$topplista = new Topplista();
	$topplista->addParameter(Topplista::PARAM_START, $forraVeckan);
	$topplista->addParameter(Topplista::PARAM_GRUPP, $grupp);
	$smarty->assign("topplista", $topplista);

	// Fotoalbum
	$bildblock = FotoalbumBild::loadGruppsBildblock($grupp, $antal = 16);
	$smarty->assign("bildblock", $bildblock);
	
	
	/*
	// Skapa anslagstavla om detta saknas (temp, behövs bara när grupper finns som skapades innan anslagstavlorna fanns)
	if($grupp->getAnslagstavlaId() == 0) {
	
		$grupp->setAnslagstavla(new Anslagstavla($grupp->getId(),0));
		
		$grupp->commit();
	}*/
	
	$anslagstavla = $grupp->getAnslagstavla();
	//$anslagstavla->addRad("Besök på gruppsidan av " . $USER->getANamn() . ", tidsstämpel " . time());
	$anslagstavlaRader = $anslagstavla->getAllaRader();
	$anslagstavlaAntalRader = $anslagstavla->getAntalRader();
	$smarty->assign("anslagstavlarader", $anslagstavlaRader);
	$smarty->assign("anslagstavlaantalrader", $anslagstavlaAntalRader);

	$aTavla = $anslagstavla->getAnslagstavalaLista();	
	$nbrPosts = count($aTavla);
	$smarty->assign('atavla', $aTavla);	
	$smarty->assign('nbrPosts', $nbrPosts);		
	
	
}

$smarty->assign("person_klubb_array",array("",$grupp->getId()));

// Grafer:

include_once ROOT . '/php/libs/php-ofc-library/open-flash-chart-object.php';

ob_start();
open_flash_chart_object( 300, 200, '/data/veckograf.php?gid=' . $grupp->getId(),false,'/' );
$graf = ob_get_contents();
ob_end_clean();

$smarty->assign("graf",$graf);


$smarty->display('klubb.tpl');


?>
