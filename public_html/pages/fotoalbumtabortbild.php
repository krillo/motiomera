<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

Security::demand(USER);

$smarty = new MMSmarty();
$smarty->assign("pagetitle", "Ta bort bild");

if (empty($_GET["id"])) {
	$urlHandler->redirect("Fotoalbum", "URL_LIST");
}

$bild = FotoalbumBild::loadById($_GET["id"]);
$fotoalbum = Fotoalbum::loadById($bild->getFotoalbumId());
if (!$fotoalbum->isAgare()) {
	// Besökaren äger INTE detta album
	$urlHandler->redirect("Fotoalbum", "URL_LIST");
	exit;
}

$smarty->assign("bild", $bild);
$smarty->assign("fotoalbum", $fotoalbum);

$smarty->display('fotoalbumtabortbild.tpl');
?>