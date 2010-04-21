<?php

include $_SERVER["DOCUMENT_ROOT"]  . "/php/init.php";

Security::demand(USER);

$smarty = new MMSmarty();

if(isset($_GET["id"])){

	$grupp = Grupp::loadById($_GET["id"]);
	$smarty->assign("grupp", $grupp);
	$skapare = $grupp->getSkapare();
	$smarty->assign("skapare", $skapare);
		
	$medlemmar = $grupp->listMedlemmar();
	if(count($medlemmar) > 0)
		$smarty->assign("medlemmar", $medlemmar);

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
	
	// Topplista
	
	$topplista = new Topplista();
	$topplista->setGrupp($grupp);
	$topplista->makeMedlemTopplista();
	$dataArr = $topplista->getData();
	$smarty->assign("topplista", $dataArr);
	
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
	$anslgstavlaAntalRader = $anslagstavla->getAntalRader();
		
	$smarty->assign("anslagstavlarader", $anslagstavlaRader);
	$smarty->assign("anslagstavlaantalrader", $anslgstavlaAntalRader);

}

$smarty->display('grupp.tpl');


?>