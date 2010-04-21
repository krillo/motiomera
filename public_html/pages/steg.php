<?php

include $_SERVER["DOCUMENT_ROOT"]  . "/php/init.php";

$smarty = new MMSmarty();
$smarty->assign("pagetitle", "Steg");

if(!empty($_GET["sid"])){
	$steg = Steg::loadById($_GET["sid"]);
	$smarty->assign("steg", $steg);
}
	
if(!isset($_POST["aid"]) && isset($steg)){
	$aktivitet_sel = $steg->getAktivitetId();
	$aktivitet = $steg->getAktivitet();
}else if(isset($_POST["aid"])){
	$aktivitet_sel = $_POST["aid"];
	$aktivitet = Aktivitet::loadById($_POST["aid"]);
}else{
	$aktivitet_sel = "";
}

if(isset($aktivitet))
	$smarty->assign("aktivitet", $aktivitet);

$smarty->assign("aktivitet_sel", $aktivitet_sel);

$aktivitet_names = Misc::arrayKeyMerge(array(""=>"Välj..."), Aktivitet::listField("namn"));


$smarty->assign("aktivitet_names", $aktivitet_names);
$smarty->display('steg.tpl');

?>