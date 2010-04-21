<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

Security::demand(EDITOR);

$smarty = new AdminSmarty;


if(isset($_GET["id"])){

	$dialekt = Kommundialekt::loadById($_GET["id"]);
	$smarty->assign("dialekt", $dialekt);
	$kommun = $dialekt->getKommun();
	$sel_alder = $dialekt->getAlder();
	$sel_kon = $dialekt->getKon();	
	$smarty->assign("sel_kon", $sel_kon);
	$smarty->assign("sel_alder", $sel_alder);
	$smarty->assign("dialekt", $dialekt);
}else{
	$kommun = Kommun::loadById($_GET["kid"]);
}

$opt_alder = array(""=>"Välj...", "ung"=>"Ung", "gammal"=>"Gammal");
$smarty->assign("opt_alder", $opt_alder);

$opt_kon = array(""=>"Välj...", "man"=>"Man", "kvinna"=>"Kvinna");
$smarty->assign("opt_kon", $opt_kon);

$smarty->assign("kommun", $kommun);


$smarty->display('editkommundialekt.tpl');

?>