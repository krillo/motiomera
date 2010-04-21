<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

Security::demand(USER);

$smarty = new MMSmarty();
$smarty->assign("pagetitle", "Redigera bild");

if (empty($_GET["id"])) {
	$urlHandler->redirect("Fotoalbum", "URL_LIST");
}

$bild = FotoalbumBild::loadById($_GET["id"]);
$fotoalbum = Fotoalbum::loadById($bild->getFotoalbumId());

if (!$fotoalbum->isAgare()) {
	// Besökaren äger INTE detta album
	$urlHandler->redirect("Fotoalbum", "URL_LIST");
}

$opt_kommuner = Misc::arrayKeyMerge(array(""=>"Välj..."), Kommun::listNamn());
$smarty->assign("opt_kommuner", $opt_kommuner);

$alla_fotoalbum = Fotoalbum::listAsArray($USER);

$smarty->assign("bild", $bild);
$smarty->assign("alla_fotoalbum", $alla_fotoalbum);

$smarty->display('fotoalbumandrabild.tpl');
?>
