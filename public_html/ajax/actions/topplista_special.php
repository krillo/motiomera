<?
global $USER;

require $_SERVER["DOCUMENT_ROOT"] . "/php/init.php";

$smarty = new MMSmarty();


$topplista = new Topplista();

if(!empty($_POST["kommun_id"])){
	$kommun = Kommun::loadById($_POST["kommun_id"]);
	$topplista->addParameter(Topplista::PARAM_KOMMUN, $kommun);
}
if(!empty($_POST["kon"])){
	$topplista->addParameter(Topplista::PARAM_KON, $_POST["kon"]);
}
if(!empty($_POST["fodelsearFran"]) || !empty($_POST["fodelsearTill"])){
	$topplista->addParameter(Topplista::PARAM_FODELSEAR, array($_POST["fodelsearFran"], $_POST["fodelsearTill"]));
}
if(!empty($_POST["lan"])){
	$topplista->addParameter(Topplista::PARAM_LAN, utf8_encode($_POST["lan"]));
}

//visa inte medlemmen som utför sök
$topplista->addParameter(Topplista::PARAM_DONTSHOWMEMBER, $USER->getId());

if (!empty($attribut)) {
	foreach($attribut as $thisAttribut){

		if(!empty($_POST["profilData".$thisAttribut->getId()])){
			$topplista->addParameter(Topplista::PARAM_PROFILINFO, array($thisAttribut->getId(), $_POST["profilData".$thisAttribut->getId()]));
		}
	}
}
$nolink = array();
global $USER;

$adressbok = Adressbok::loadByMedlem($USER);
$kontakter = $adressbok->listKontakter();
foreach($kontakter as $kontakt){
	$nolink[] = $kontakt->getId();
}

$smarty->assign("nolink",$nolink);

$smarty->assign("topplista", $topplista);

$smarty->noShowHeaderFooter();
$smarty->display('topplista_special_result.tpl');


?>