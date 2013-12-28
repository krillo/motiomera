<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

//allow only Ystad and Helsingborg if not logged in
$logged_in = false;
$req_kommun =  $_SERVER['REQUEST_URI'];
if(strcmp($req_kommun,'/kommun/Ystad/') != 0 && strcmp($req_kommun,'/kommun/Helsingborg/') != 0){
  Security::demand(USER);
  $logged_in = true;
}


$from = array("aa", "ae", "oe", "AA", "AE", "OE");
$to = array("å","ä","ö","Å","Ä","Ö");
	
function detectUTF8($string)
{
        return preg_match('%(?:
        [\xC2-\xDF][\x80-\xBF]        # non-overlong 2-byte
        |\xE0[\xA0-\xBF][\x80-\xBF]               # excluding overlongs
        |[\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}      # straight 3-byte
        |\xED[\x80-\x9F][\x80-\xBF]               # excluding surrogates
        |\xF0[\x90-\xBF][\x80-\xBF]{2}    # planes 1-3
        |[\xF1-\xF3][\x80-\xBF]{3}                  # planes 4-15
        |\xF4[\x80-\x8F][\x80-\xBF]{2}    # plane 16
        )+%xs', $string);
}

function convertUrlNamn($namn){
	$from = array("aa", "ae", "oe", "AA", "AE", "OE", "Aa", "Ae", "Oe", "_");
	$to = array("å","ä","ö","Å","Ä","Ö","Å","Ä","Ö"," ");
	
	if(detectUTF8($namn)) {
		return urldecode($namn);
	}
	else {
		return utf8_encode(urldecode($namn));
	}
}	

if(!($kommun = Kommun::loadByNamn(convertUrlNamn(($_GET["knamn"]))))) {
	exit;
}
	
$smarty = new MMSmarty;

$kommunNamn = $kommun->getNamn();
$kommunId = $kommun->getId();
$smarty->assign("logged_in", $logged_in);
$smarty->assign("kommun", $kommun);
$smarty->assign("kommunNamn", $kommunNamn);
$smarty->assign("kommunId", $kommunId);
$smarty->assign("pagetitle", "Kommun - ".$kommunNamn);
	
$notin = array($kommun->getId());

$kommunnamn = Kommun::listNamn();

$medlemmarIKommun = Medlem::loadByJustNuKommun($kommun);
if($medlemmarIKommun) {
	$smarty->assign("medlemmarIKommun", $medlemmarIKommun);
}

$smarty->assign("kommunnamn", $kommunnamn);	

$avstand = $kommun->listAvstand();	
$smarty->assign("avstand", $avstand);

$grannkommuner = array();

$taggs = Tagg::listByTagId($kommun->getId(),Kommun::TABLE);
$taggbilder = array();
if ($taggs) {
	foreach ($taggs as $tagg) {
		$bild = FotoalbumBild::loadById($tagg->getObjektId());
		if ($bild->getApproved()) {
			$taggbilder[] = $bild;
		}
	}
}
// print_r($taggbilder);
$smarty->assign('taggbilder', $taggbilder);

$kommuner_ids = array();
foreach($avstand as $tmp){
	$kommuner_ids[] = $tmp["id"];
}

$grannkommuner = Kommun::listByIds($kommuner_ids);


$smarty->assign("grannkommuner", $grannkommuner);

$avstandArgs = array();
foreach($avstand as $temp){
	$notin[] = $temp["id"];
	$avstandArgs[$temp["id"]] = array($kommun->getId(), $temp["id"]);
}
$smarty->assign("avstandArgs", $avstandArgs);


$lan_slug = Misc::url_slug($kommun->getLan());
$smarty->assign("lan_slug", $lan_slug);

$kommunvapen = $kommun->getKommunvapen();
$smarty->assign("kommunvapen", $kommunvapen);

$kommunkarta = $kommun->getKommunkarta();
$smarty->assign("kommunkarta", $kommunkarta);

$kommunbilder = $kommun->listKommunbilder();
$smarty->assign("kommunbilder", $kommunbilder);


//dialekter

$dialekter = Kommundialekt::listByKommun($kommun);
$smarty->assign("dialekter", $dialekter);


$smarty->display('kommun.tpl');



?>
