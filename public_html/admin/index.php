<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");


Security::demand(EDITOR);

if(isset($ADMIN) && $ADMIN->getTyp() == "kommun"){

	$kommun = Kommun::loadByNamn($ADMIN->getANamn());
	$urlHandler->redirect("Kommun", URL_EDIT, $kommun->getId());
	
}

$smarty = new AdminSmarty();
$smarty->display('index.tpl');

?>