<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

Security::demand(USER);

$smarty = new MMSmarty();

$opt_lan = array(""=>"Välj...");
foreach(Kommun::listLan() as $lan){
	$lann = substr($lan, 0, -5);
	if(substr($lann, -1, 1) == "s"){
		$lann = substr($lann, 0, -1);
	}
	$opt_lan[$lann] = $lann;
}
$sel_lan = (!empty($_POST["lan"])) ? $_POST["lan"] : "";
$smarty->assign("opt_lan", $opt_lan);
$smarty->assign("sel_lan", $sel_lan);


$lanKommuner = array();

$kommuner = Kommun::listAll();

foreach(Kommun::listLan() as $lan){
	$sel = (isset($_POST["kommun_id"])) ? $_POST["kommun_id"] : "";
	
	$opt_kommuner = array(""=>"Välj...");
	foreach($kommuner as $kommun){
		if($kommun->getLan() == $lan){
			$opt_kommuner[$kommun->getId()] = $kommun->getNamn();
		}
	}
	
	$lanKommuner[] = array("opt"=>$opt_kommuner);
}

$smarty->assign("lanKommuner", $lanKommuner);

$opt_kon = array(""=>"Välj...", "man"=>"Man", "kvinna"=>"Kvinna");
$sel_kon = (isset($_POST["kon"])) ? $_POST["kon"] : "";
$smarty->assign("opt_kon", $opt_kon);
$smarty->assign("sel_kon", $sel_kon);

$opt_fodelsear = array(""=>"Välj...");
for($i = 2008; $i > 1899; $i--)
	$opt_fodelsear[$i] = $i;
$sel_fodelsear = (isset($_POST["fodelsear"])) ? $_POST["fodelsear"] : "";

$smarty->assign("opt_fodelsear", $opt_fodelsear);
$smarty->assign("sel_fodelsear", $sel_fodelsear);

// Profildata

$attribut = ProfilData::listAll();

foreach($attribut as $thisAttribut){

	$alternativ = $thisAttribut->getProfilDataVals();
	
	$opt_alternativ = array(""=>"Välj...");
	foreach($alternativ as $thisAlternativ){
		$opt_alternativ[$thisAlternativ->getId()] = $thisAlternativ->getVarde();
	}
	
	$sel_alternativ = (!empty($_POST["profilData".$thisAttribut->getId()])) ? $_POST["profilData".$thisAttribut->getId()] : "";
	
	$profilData[$thisAttribut->getId()] = array("namn"=>$thisAttribut->getNamn(), "opt"=>$opt_alternativ, "sel"=>$sel_alternativ, "formId"=>"profilData".$thisAttribut->getId());


}


$smarty->assign("profilData", $profilData);




$topplista = new Topplista();

if(!empty($_POST["kommun_id"])){
	$kommun = Kommun::loadById($_POST["kommun_id"]);
	$topplista->addParameter(Topplista::PARAM_KOMMUN, $kommun);
}
if(!empty($_POST["kon"])){
	$topplista->addParameter(Topplista::PARAM_KON, $_POST["kon"]);
}
if(!empty($_POST["fodelsear"])){
	$topplista->addParameter(Topplista::PARAM_FODELSEAR, $_POST["fodelsear"]);
}

if(!empty($_POST["lan"])){
	$topplista->addParameter(Topplista::PARAM_LAN, $_POST["lan"]);
}

foreach($attribut as $thisAttribut){

	if(!empty($_POST["profilData".$thisAttribut->getId()])){
		$topplista->addParameter(Topplista::PARAM_PROFILINFO, array($thisAttribut->getId(), $_POST["profilData".$thisAttribut->getId()]));
	}
}

	
$smarty->assign("topplista", $topplista);


$smarty->display('topplista.tpl');

?>