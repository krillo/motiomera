<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

Security::demand(USER);

$smarty = new MMSmarty;
$smarty->assign("pagetitle", "Klubb");

if(isset($USER)) {
	$adressbok = Adressbok::loadByMedlem($USER);
	$smarty->assign("adressbok", $adressbok);
	$kontakter = $adressbok->listKontakter();
}

if(!empty($_GET["id"])){
	$grupp = Grupp::loadById($_GET["id"]);
	Security::demand(USER, $grupp->getSkapare());
	
	$sel_publik = ($grupp->getPublik());
	// echo $grupp->getPublik();
	$smarty->assign("sel_publik", $sel_publik);

	$medlemmar = $grupp->listMedlemmar();
	$smarty->assign("grupp", $grupp);
	$smarty->assign("medlemmar", $medlemmar);
	$ansokningar = $grupp->listRequests();
	if(count($ansokningar) > 0)
		$smarty->assign("ansokningar", $ansokningar);
	$ignored = $grupp->listIgnored();
	if(count($ignored) > 0)
		$smarty->assign("ignored", $ignored);
		
	$allaMedlemmar = $grupp->listMedlemmar(true);
	$opt_kontakter=array();
	foreach($kontakter as $kontakt){
		if(!in_array($kontakt->getId(), array_keys($allaMedlemmar)))
			$opt_kontakter[$kontakt->getId()] = $kontakt->getANamn();
	}
	$smarty->assign("opt_kontakter", $opt_kontakter);
	
	$invMedlemmar=$grupp->listInvited();
	$smarty->assign("invMedlemmar",$invMedlemmar);

	foreach($kontakter as $kontakt){
		if(!in_array($kontakt->getId(), array_keys($allaMedlemmar)))
			$opt_kontakter[$kontakt->getId()] = $kontakt->getANamn();
	}

	$requestMedlemmar=$grupp->listRequests();
	$smarty->assign("requestMedlemmar",$requestMedlemmar);
	$smarty->assign("time", $grupp->getStart());

}
else {
	$alla_kontakter=array();
	foreach($kontakter as $kontakt) {
		$alla_kontakter[$kontakt->getId()] = $kontakt->getANamn();
	}
	$smarty->assign("alla_kontakter",$alla_kontakter);
	$smarty->assign('today', date('Y-m-d'));
}


$opt_publik = array("1"=>"Visa f&ouml;r alla", "0"=>"Visa bara f&ouml;r klubbmedlemmar");
$smarty->assign("opt_publik", $opt_publik);
$smarty->display('editklubb.tpl');


?>
