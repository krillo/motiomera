<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

$smarty = new MMSmarty;
$smarty->assign("pagetitle", "Visa bild");

switch($_GET["typ"]){
	case "kommunbild":
		$kommunbild = Kommunbild::loadById($_GET["id"]);
		$bild = $kommunbild->getBild();
		$namn = $kommunbild->getNamn();
		$smarty->assign("kommun", $kommunbild->getKommun());
		$smarty->assign("beskrivning", $kommunbild->getBeskrivning());
		break;

}

$smarty->assign("namn", $namn);
$smarty->assign("bild", $bild);

$smarty->display('bild.tpl');

?>