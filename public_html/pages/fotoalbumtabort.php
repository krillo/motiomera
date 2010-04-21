<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

Security::demand(USER);

$smarty = new MMSmarty();
$smarty->assign("pagetitle", "Ta bort fotoalbum");

if (empty($_GET["fid"])) {
	$urlHandler->redirect("Fotoalbum", "URL_LIST");
}

$fotoalbum = Fotoalbum::loadById($_GET["fid"]);
if (!$fotoalbum->isAgare()) {
	// Besökaren äger INTE detta album
	$urlHandler->redirect("Fotoalbum", "URL_LIST");
	exit;
}

$smarty->assign("fotoalbum", $fotoalbum);

$smarty->display('fotoalbumtabort.tpl');
?>