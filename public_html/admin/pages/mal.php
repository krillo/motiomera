<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

Security::demand(EDITOR);

$smarty = new AdminSmarty;


$opt_kommun = Misc::arrayKeyMerge(array(""=>"Välj..."), Kommun::listNamn());
$smarty->assign("opt_kommun", $opt_kommun);


if(!empty($_GET["id"])){
	$mal = Mal::loadById($_GET["id"]);
	$kommun = $mal->getKommun();
	$malId = $mal->getId();
	$sel_kommun = $kommun->getId();
	
	$smarty->assign("mal", $mal);
	$smarty->assign("malId", $malId);
	$smarty->assign("kommun", $kommun);
	$smarty->assign("sel_kommun", $sel_kommun);
}else{
	$smarty->assign("kommunId", null);
}

$smarty->display('mal.tpl');

?>