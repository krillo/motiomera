<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

Security::demand(EDITOR);

$smarty = new AdminSmarty;

if(!empty($_GET["id"])){
	$fraga = QuizFraga::loadById($_GET["id"]);
	$fragaId = $fraga->getId();
	$smarty->assign("fraga", $fraga);
	$smarty->assign("fragarad", $fraga->getFraga());
	
	$alternativ = $fraga->listAlternativ();
	$smarty->assign("alternativ", $alternativ);
	
	$rattSvar = $fraga->getRattSvar();
	if($rattSvar)
		$smarty->assign("rattSvar", $rattSvar);
	
	$kommun = $fraga->getKommun();
}else{
	$fragaId = "";
	$kommun = Kommun::loadById($_GET["kid"]);
}

$smarty->assign("fragaId", $fragaId);

$smarty->assign("kommun", $kommun);
$smarty->display('editquiz.tpl');

?>