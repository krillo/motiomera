<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

Security::demand(USER, null, false);

$smarty = new PopSmarty();

if(!empty($_GET["sid"])){
	$steg = Steg::loadById($_GET["sid"]);
	$smarty->assign("steg", $steg);
}
	
if(!isset($_POST["aid"]) && isset($steg)){
	$aktivitet_sel = $steg->getaktivitetId();
	$aktivitet = $steg->getaktivitet();
}else if(isset($_POST["aid"])){
	$aktivitet_sel = $_POST["aid"];
	$aktivitet = aktivitet::loadById($_POST["aid"]);
}else{
	$aktivitet_sel = "";
}

if(isset($aktivitet))
	$smarty->assign("aktivitet", $aktivitet);

$smarty->assign("aktivitet_sel", $aktivitet_sel);

$aktiviteter = Aktivitet::listAll();
$opt_aktivitet = array();
foreach($aktiviteter as $key => $aktivitet){
	if ($aktivitet->getBorttagen() != 'ja' || (!isset($opt_aktivitet[$aktivitet->getNamn() . " (min)"]) or !isset($opt_aktivitet[$aktivitet->getNamn()]))) {
		if($aktivitet->getEnhet() == "minuter"){
			$opt_aktivitet[$aktivitet->getNamn() . " (min)"] = array('id'=>$aktivitet->getId(),'namn'=> $aktivitet->getNamn() . " (min)");
		}else{
			$opt_aktivitet[$aktivitet->getNamn()] = array('id'=>$aktivitet->getId(),'namn'=> $aktivitet->getNamn());
		}
		if($aktivitet->getNamn() == "Steg"){
			$sel_aktivitet = $aktivitet->getId();
		}
	}
}
$opt_aktiv = array();
foreach ($opt_aktivitet as $arr) {
	$opt_aktiv[$arr['id']] = $arr['namn'];
}

$smarty->assign("opt_aktivitet", $opt_aktiv);
$smarty->assign("sel_aktivitet", $sel_aktivitet);

$smarty->display('steg.tpl');

?>
