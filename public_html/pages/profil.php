<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");
//Security::demand(USER);
if (isset($_GET['mid']) && Misc::isValidId($_GET['mid'])) {
	$medlem = Medlem::loadById($_GET["mid"]);	
} else {
	throw new UserException('Profilen finns inte', 'Den medlem du söker har antingen tagit bort sig eller ej funnits');
}

if(!$medlem->synlig() && !(isset($USER) && $USER->getId() == $medlem->getId())){
	switch($medlem->getAtkomst()){
		default:
		case "medlem":
			$msg = "Du måste vara inloggad på MotioMera för att ta del av den här profilen.<br/>Logga in ovan eller skaffa ett inlogg idag:<p/><a href='/pages/blimedlem.php' style='font-weight:bold;'><img src='/img/icons/BliMedlemIcon.gif' alt='Bli Medlem'/></a>";
			break;
		case "adress":
			$msg = "Den här personen har valt att begränsa tillgången till profilen till sina vänner.";
			break;
		case "ingen":
			$msg = "Den här personen har valt att inte visa sin profil för någon.";
			break;
		case "foretag":
			$msg = "Denna profil är endast synlig för medlemmar av samma företag.";
			break;
	}
	if(!Security::authorized(ADMIN)){
		throw new UserException("Kan ej visa profil", $msg);
	}
}

$smarty = new MMSmarty;
$grupper = Grupp::listByMedlem($medlem);

if ($medlem->getLatestCachedRss()) {
	$rss = $medlem->getLatestCachedRss();
	$rss['description'] = strip_tags($rss['description']);
	$smarty->assign('rssFeed', $rss); //array 
}

// Kommunquiz
$successfull_quizzes = $medlem->getSuccessfullQuizzes();
$smarty->assign("successfull_quizzes", sizeof($successfull_quizzes));

// Medlemsquiz
$quizblock = MinaQuiz::loadMedlemsQuizblock($medlem, $antal = 5);
$smarty->assign("quizblock", $quizblock);
$smarty->assign("hasQuiz", MinaQuiz::hasQuiz($medlem));

$usrLag=$medlem->getLag();
if(isset($usrLag)) {
	$smarty->assign("lagnamn", $medlem->getLag()->getNamn());
	$smarty->assign("lagid", $medlem->getLag()->getId());
}
$usrForetag=$medlem->getForetag();
if(isset($usrForetag) && $usrForetag->aktivTavling()) {
	$smarty->assign("foretagnamn", $medlem->getForetag()->getNamn());
	if($medlem->getForetag()->getStartDatum() > date("Y-m-d")){
		$smarty->assign("tavlingstart", Misc::getDagarMellanTvaDatum(date("Y-m-d"), $medlem->getForetag()->getStartDatum()));
	}
}

if(count($grupper) > 0)
	$smarty->assign("grupper", $grupper);
	
// Topplistor

$forraVeckan = date("Y-m-d H:i:s", strtotime('-7 days'));

$topplista = new Topplista();
$topplista->addParameter(Topplista::PARAM_START, $forraVeckan);
$smarty->assign("topplista", $topplista);


$topplista_array = array($medlem->getId(),"");
$smarty->assign("topplista_array", $topplista_array);


$smarty->assign("medlem", $medlem);

$visningsbild = $medlem->getVisningsbild();
$smarty->assign("visningsbild", $visningsbild);

$rutt = new Rutt($medlem);

$currentKommun = $rutt->getCurrentKommun();


$smarty->assign("currentKommun", $currentKommun);

$latestKommun = $medlem->getLatestKommun();
$smarty->assign("latestKommun", $latestKommun);

if(isset($USER) && isset($medlem) && $medlem->getId() != $USER->getId()){
	$minaGrupper = $USER->listCreatedGroups();
	if(count($minaGrupper) > 0){
		$opt_minaGrupper = array();
		foreach($minaGrupper as $grupp){
			if($grupp->isRequestable($medlem))
				$opt_minaGrupper[$grupp->getId()] = $grupp->getNamn();
		}
		if(count($opt_minaGrupper) > 0)
			$smarty->assign("opt_minaGrupper", $opt_minaGrupper);
	}
	
	//$myAdressBook = new Adressbok($USER);
	//$smarty->assign("isMyContact", $myAdressBook->isKontakt($medlem));
	$smarty->assign("isMyContact", $medlem->inAdressbok($USER));
	
}else{
	$smarty->assign("isMyContact", false);
}

$isInloggad = $medlem->isInloggad();
$smarty->assign("isInloggad", $isInloggad);

if(count(Grupp::listInbjudningsbaraGrupper($medlem)) > 0){
	$smarty->assign("invitable", "true");
}

// Fotoalbum
$bildblock = FotoalbumBild::loadMedlemsBildblock($medlem, $antal = 20);
$smarty->assign("bildblock", $bildblock);


// Kommunjakten

$stegtotal = $medlem->getStegTotal();
$caltotal = Misc::getCalFromSteg($stegtotal);
$kmTotal = Steg::stegToKm($stegtotal);
$smarty->assign("stegtotal", $stegtotal);
$smarty->assign("stegsnitt", $stegtotal/7);
$smarty->assign("caltotal", $caltotal);



$stegSenasteVeckan = Steg::getStegTotal($medlem,date("Y-m-d H:i:s",strtotime("-7 days")),date("Y-m-d H:i:s"));
$calstegSenasteVeckan = Misc::getCalFromSteg($stegSenasteVeckan);

$smarty->assign("calstegSenasteVeckan",$calstegSenasteVeckan);
$smarty->assign("calstegsnitt", $calstegSenasteVeckan/7);
$smarty->assign("stegSenasteVeckan",$stegSenasteVeckan);
$smarty->assign("stegsnitt", $stegSenasteVeckan/7);


$rutt = new Rutt($medlem);
$smarty->assign("rutt", $rutt);
$rutter = $rutt->getRutt();

$kommunvapenList = array();

$exclude = array();
foreach($rutter as $index=>$temprutt){

	if($temprutt["Kommun"]->getKommunvapen() && $index < $rutt->getCurrentIndex()){	
		$kommunId = $temprutt["Kommun"]->getKommunvapen()->getKommun()->getId();
		if(!in_array($kommunId, $exclude)){
			$kommunvapenList[] = $temprutt["Kommun"]->getKommunvapen();
			$exclude[] = $kommunId;
		}
	}
}

$currentKommun = $rutt->getCurrentKommun();
$tempbilder = Kommunbild::listByKommun($currentKommun);

if($tempbilder){
	$currentKommunBild = current($tempbilder);
	if ( is_file($currentKommunBild->getImgPath()) ) {
		$smarty->assign("currentKommunBild", $currentKommunBild->getBild());
		$smarty->assign("currentThumb", $currentKommunBild->getThumb());
		$smarty->assign("currentMiddle", $currentKommunBild->getMiddle());
	}
}

$startKommun = $rutt->getStartKommun();

$smarty->assign("kommunvapenList", $kommunvapenList);
$smarty->assign("currentKommun", $currentKommun);
$smarty->assign("startKommun", $startKommun);
$smarty->assign("currentIndex", $rutt->getCurrentIndex());
$smarty->assign("rutter", $rutter);

$avatarEndPos = 0;
if(isset($rutter[$rutt->getCurrentIndex()+1])){
	$kmTillNasta = $rutter[$rutt->getCurrentIndex()+1]["TotalKm"] - $kmTotal;
	$nastaKommun = $rutter[$rutt->getCurrentIndex()+1]["Kommun"];
	
	if($rutter[$rutt->getCurrentIndex()+1]["TotalKm"] > 0) {
		$avatarEndPos = ceil(122 * (($rutter[$rutt->getCurrentIndex()+1]["ThisKm"] - $kmTillNasta) / $rutter[$rutt->getCurrentIndex()+1]["ThisKm"]));
		$avatarEndPosProcent = ceil(100 * (($rutter[$rutt->getCurrentIndex()+1]["ThisKm"] - $kmTillNasta) / $rutter[$rutt->getCurrentIndex()+1]["ThisKm"]));
	}
	else {
		$avatarEndPos = 0;
	}


	$tempbilder = Kommunbild::listByKommun($nastaKommun);

	if($tempbilder){
		$nastaKommunBild = current($tempbilder);
		if ( is_file($nastaKommunBild->getImgPath()) ) {
			$smarty->assign("nastaKommunBild", $nastaKommunBild->getBild());
			$smarty->assign("nastaThumb", $nastaKommunBild->getThumb());
			$smarty->assign('nastaMiddle', $nastaKommunBild->getMiddle());
		}
	}
	
	$smarty->assign("kmTillNasta", $kmTillNasta);
	$smarty->assign("nastaKommun", $nastaKommun);

	$kommunvapen_mal = $nastaKommun->getKommunvapen();
	$smarty->assign("kommunvapenMal", $kommunvapen_mal);
	
	$kommunbilderlista_mal = $nastaKommun->listKommunbilder();
	$kommunbild_mal = next($kommunbilderlista_mal);

	$smarty->assign("kommunbild_mal", $kommunbild_mal);
}
	$smarty->assign("avatarEndPos",$avatarEndPos);

if (isset($avatarEndPosProcent)){
	$smarty->assign("avatarEndPosProcent",$avatarEndPosProcent);
}


$kommunvapen_start = $currentKommun->getKommunvapen();

$smarty->assign("kommunvapenStart", $kommunvapen_start);

$kommunbilderlista_start = $currentKommun->listKommunbilder();
$kommunbild_start = next($kommunbilderlista_start);

$smarty->assign("kommunbild_start", $kommunbild_start);

$smarty->assign("isProfil",1);

// Troféer 
$smarty->assign("avatarUrl", $medlem->getAvatar()->getUrl());

$guldmedaljer = Sammanstallning::listMedaljer($medlem, Sammanstallning::M_GULD);
$silvermedaljer = Sammanstallning::listMedaljer($medlem, Sammanstallning::M_SILVER);

$guldpokaler = Sammanstallning::listPokaler($medlem, Sammanstallning::P_GULD);
$silverpokaler = Sammanstallning::listPokaler($medlem, Sammanstallning::P_SILVER);

$smarty->assign("silvermedaljer", $silvermedaljer);
$smarty->assign("guldmedaljer", $guldmedaljer);
$smarty->assign("silverpokaler", $silverpokaler);
$smarty->assign("guldpokaler", $guldpokaler);

$smarty->assign("OM", $medlem->getBeskrivning());
// Medlems blockering
if(!empty($USER)) {
	$smarty->assign("blockerad_av_medlem", (int)MedlemsBlockering::verifyBlocked($medlem->getId(), $USER->getId()));
	$smarty->assign("blockerat_medlem", (int)MedlemsBlockering::verifyBlocked($USER->getId(), $medlem->getId()));
}
// Grafer:

include_once ROOT . '/php/libs/php-ofc-library/open-flash-chart-object.php';

ob_start();
open_flash_chart_object( 300, 200, '/data/veckograf.php?id=' . $medlem->getId(),false,'/' );
$graf = ob_get_contents();
ob_end_clean();

$smarty->assign("graf",$graf);

if (isset($USER) && $USER->getId() == $medlem->getId()) {
	$selfProfile = true;
} else {
	$selfProfile = false;
}
$smarty->assign('selfProfile', $selfProfile);


$smarty->assign("pagetitle", ucfirst($medlem->getANamn())." &mdash; Profil");

$smarty->display('profil.tpl');
?>
