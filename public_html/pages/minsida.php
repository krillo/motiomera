<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");
Security::demand(USER);
//print_r($USER->getId());
//print_r($USER);

//added by krillo 2011-01-19  keep expired user locket out
if($USER->getPaidUntil() < date("Y-m-d")){
  $urlHandler->redirect("Medlem", URL_BUY, $USER->getId());
}

$smarty = new MMSmarty;
$smarty->assign("pagetitle", "Min sida");

// Ta bort eventuella temp-sträckor som inte sparats:
$USER->cleanTempStrackor();


// Topplistor
$forraVeckan = date("Y-m-d H:i:s",strtotime("-7 days"));

$topplista = new Topplista();
$topplista->addParameter(Topplista::PARAM_START, $forraVeckan);
$smarty->assign("topplista", $topplista);
unset($topplista);
$usrLag = $USER->getLag();
if(isset($usrLag)) {
	$smarty->assign("lagnamn", $USER->getLag()->getNamn());
	$smarty->assign("lagid", $USER->getLag()->getId());
}
$usrForetag=$USER->getForetag();
if(isset($usrForetag) && $usrForetag->aktivTavling()) {
	$smarty->assign("foretagnamn", $USER->getForetag()->getNamn());

	if($USER->getForetag()->getStartDatum() > date("Y-m-d")){
		$smarty->assign("tavlingstart", Misc::getDagarMellanTvaDatum(date("Y-m-d"), $USER->getForetag()->getStartDatum()));
	}
}

$stegList = $USER->listSteg();

$smarty->assign("stegList", $stegList);
unset($steglist);
$totalbyday = $USER->listTotalStegByDay(7);
$stegtotal = $USER->getStegTotal();
$caltotal = Misc::getCalFromSteg($stegtotal);
$kmTotal = Steg::stegToKm($stegtotal);

$smarty->assign("totalbyday", $totalbyday);
$smarty->assign("stegtotal", $stegtotal);
$smarty->assign("caltotal", $caltotal);
$smarty->assign("kmtotal", $kmTotal);


unset($totalbyday);
unset($stegtotal);
unset($caltotal);

date("Y-m-d H:i:s",strtotime("-7 days"));



$stegSenasteVeckan = Steg::getStegTotal($USER,date("Y-m-d H:i:s",strtotime("-7 days")),date("Y-m-d H:i:s"));
$calstegSenasteVeckan = Misc::getCalFromSteg($stegSenasteVeckan);


$smarty->assign("calstegSenasteVeckan",$calstegSenasteVeckan);
$smarty->assign("calstegsnitt", $calstegSenasteVeckan/7);
$smarty->assign("stegSenasteVeckan",$stegSenasteVeckan);
$smarty->assign("stegsnitt", $stegSenasteVeckan/7);

unset($calstegSenasteVeckan);
unset($stegSenasteVeckan);


$aktivitet_namn = Aktivitet::listField("namn");
$smarty->assign("aktivitet_namn", $aktivitet_namn);
$smarty->assign("todaytotal", $USER->getTotalStegByDay());

unset($aktivitet_namn);


// Kommunjakten

$rutt = new Rutt($USER);
$smarty->assign("rutt", $rutt);
$rutter = $rutt->getRutt();

$kommunvapenList = array();

$exclude = array();
$antalKommuner = 0;

$totalKm=0;

foreach($rutter as $index=>$temprutt){
	
	$antalKommuner++;
	
	if($temprutt["Kommun"]->getKommunvapen() && $index < $rutt->getCurrentIndex()){	
		$kommunId = $temprutt["Kommun"]->getKommunvapen()->getKommun()->getId();
		if(!in_array($kommunId, $exclude)){

			$kommunvapenList[] = $temprutt["Kommun"]->getKommunvapen();
			$exclude[] = $kommunId;
		}
	}
	
	$totalKm = $temprutt["TotalKm"];
	$slutmal = $temprutt["Kommun"];
}

$totalKmKvar = $totalKm - $kmTotal;



$smarty->assign("totalKmKvar",$totalKmKvar);

unset($totalKmKvar);
if(isset($slutmal)) {
	$smarty->assign("slutmal",$slutmal);
}

$smarty->assign("antalKommuner",$rutt->getCurrentIndex()+1);

$smarty->assign("antalKommunerKvar",$antalKommuner - ($rutt->getCurrentIndex()+1));

$currentKommun = $rutt->getCurrentKommun();
$tempbilder = Kommunbild::listByKommun($currentKommun);

if($tempbilder){
	$currentKommunBild = current($tempbilder);
	#echo $currentKommunBild->getImgPath().'<hr>';
	if ( is_file($currentKommunBild->getImgPath()) ):
		$smarty->assign("currentKommunBild", $currentKommunBild->getBild());
		$smarty->assign("currentThumb", $currentKommunBild->getThumb());
		$smarty->assign("currentMiddle", $currentKommunBild->getMiddle());
	endif;	
}


$startKommun = $rutt->getStartKommun();

$smarty->assign("kommunvapenList", $kommunvapenList);
$smarty->assign("currentKommun", $currentKommun);
$smarty->assign("startKommun", $startKommun);
$smarty->assign("currentIndex", $rutt->getCurrentIndex());
$smarty->assign("rutter", $rutter);

if(isset($rutter[$rutt->getCurrentIndex()+1])){
	$kmTillNasta = $rutt->getKmTillNasta();
	$nastaKommun = $rutter[$rutt->getCurrentIndex()+1]["Kommun"];
	
	$tempbilder = Kommunbild::listByKommun($nastaKommun);

	if($tempbilder){
		$nastaKommunBild = current($tempbilder);
		// echo $nastaKommunBild->getMiddle();
		// echo $nastaKommunBild->getImgPath().'<hr>';
		if ( is_file($nastaKommunBild->getImgPath()) )
		{
			$smarty->assign("nastaMiddle", $nastaKommunBild->getMiddle());
			$smarty->assign("nastaKommunBild", $nastaKommunBild->getBild());
			$smarty->assign("nastaThumb", $nastaKommunBild->getThumb());
		}
	}

	$smarty->assign("kmTillNasta", $kmTillNasta);
	$smarty->assign("nastaKommun", $nastaKommun);

	$kommunvapen_mal = $nastaKommun->getKommunvapen();
	$smarty->assign("kommunvapenMal", $kommunvapen_mal);
	
	$kommunbilderlista_mal = $nastaKommun->listKommunbilder();
	$kommunbild_mal = next($kommunbilderlista_mal);

	$smarty->assign("kommunbild_mal", $kommunbild_mal);
	
	// 122 punkter totalt

	$avatarEndPos = ceil(122 * (($rutter[$rutt->getCurrentIndex()+1]["ThisKm"] - $kmTillNasta) / $rutter[$rutt->getCurrentIndex()+1]["ThisKm"]));
	$avatarEndPosProcent = ceil(100 * (($rutter[$rutt->getCurrentIndex()+1]["ThisKm"] - $kmTillNasta) / $rutter[$rutt->getCurrentIndex()+1]["ThisKm"]));

}
else {

	$avatarEndPos = 0;
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



// Troféer 
$guldmedaljer = Sammanstallning::listMedaljer($USER, Sammanstallning::M_GULD);
$silvermedaljer = Sammanstallning::listMedaljer($USER, Sammanstallning::M_SILVER);

$guldpokaler = Sammanstallning::listPokaler($USER, Sammanstallning::P_GULD);
$silverpokaler = Sammanstallning::listPokaler($USER, Sammanstallning::P_SILVER);

$stegSilvermedalj = Sammanstallning::MEDALJ_SILVER_NIVA;
$stegGuldmedalj = Sammanstallning::MEDALJ_GULD_NIVA;
$stegGuldpokal = Sammanstallning::POKAL_GULD_NIVA;
$stegSilverpokal = Sammanstallning::POKAL_SILVER_NIVA;

$staticRoutePokal = Rutt::getStaticRoutesDoneForUser($USER->getId());


$smarty->assign('staticRoutePokal', $staticRoutePokal);
$smarty->assign("stegSilvermedalj", $stegSilvermedalj);
$smarty->assign("stegGuldmedalj", $stegGuldmedalj);
$smarty->assign("stegGuldpokal", $stegGuldpokal);
$smarty->assign("stegSilverpokal", $stegSilverpokal);

$smarty->assign("silvermedaljer", $silvermedaljer);
$smarty->assign("guldmedaljer", $guldmedaljer);
$smarty->assign("silverpokaler", $silverpokaler);
$smarty->assign("guldpokaler", $guldpokaler);



// Mål
/*$malManager = new MalManager($USER);
$smarty->assign("malManager", $malManager);

$currentMal = $malManager->getCurrentMal();
$smarty->assign("currentMal", $currentMal);
*/


// Fotoalbum
$bildblock = FotoalbumBild::loadMedlemsBildblock($USER, $antal = 16);
$smarty->assign("bildblock", $bildblock);

// Mina Quiz
$quizblock = MinaQuiz::loadMedlemsQuizblock($USER, $antal = 5, true); // True på slutet betyder att sidan är "Min sida" och inte "Profil".
$smarty->assign("quizblock", $quizblock);
$smarty->assign("hasQuiz", false);

// Feed
$feed = Feed::loadByMedlem($USER);
$smarty->assign("feed", $feed->listRows(true));
$smarty->assign("medlem", $USER);

// Grafer:
include_once ROOT.'/php/libs/php-ofc-library/open-flash-chart-object.php';

ob_start();
open_flash_chart_object( 300, 200, '/data/veckograf.php?id=' . $USER->getId(),false,'/' );
$graf = ob_get_contents();
ob_end_clean();

$smarty->assign("graf", $graf);

$smarty->assign("GOOGLEMAPS_APIKEY", GOOGLEMAPS_APIKEY);
$smarty->assign("avatarUrl", $USER->getAvatar()->getUrl());

// Usesr blogg data
$smarty->assign("blogTitle", "Min Blogg");

// Copy USER to medlem
$smarty->assign("medlem", $USER);

// Check if user has registered enough steps to pass the medal limit
$medaljLimitSilver = 49000;
$medaljLimitGuld = 77000;
$startDatum = date('Y-m-d', strtotime('last monday'));
$slutDatum = date('Y-m-d', strtotime($startDatum . ' +7 days'));
$antalSteg = $USER->getStegTotal($startDatum, $slutDatum);
$medaljLimitReached = false;
if ($antalSteg >= $medaljLimitSilver) {
	$medaljLimitReached = true;
	$smarty->assign('medaljNamn', 'silvermedalj');
	$smarty->assign('medaljBild', '/img/icons/medalj_silver.gif');
	$smarty->assign('medaljLimit', $medaljLimitSilver);
}
if ($antalSteg >= $medaljLimitGuld) {
	$medaljLimitReached = true;
	$smarty->assign('medaljNamn', 'guldmedalj');
	$smarty->assign('medaljBild', '/img/icons/medalj_guld.gif');
	$smarty->assign('medaljLimit', $medaljLimitGuld);
}

$smarty->assign('medaljLimitReached', $medaljLimitReached);
$smarty->assign('selfProfile', true);


//tavlingar
$tavlingArray = Tavling::getMemberCompetitions($USER->getid());
$smarty->assign('tavlingArray', $tavlingArray);
$smarty->display('minsida.tpl');
?>