<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

Security::demand(USER);

$smarty = new MMSmarty();
$smarty->assign("pagetitle", "Redigera fotoalbum");

// hämta information om fotoalbumet vi ska ändra
$fotoalbum = Fotoalbum::loadById($_GET["fid"]);
if (!$fotoalbum->isAgare()) {
	// Besökaren äger INTE detta album
	$urlHandler->redirect("Fotoalbum", "URL_LIST");
	exit;
}

$smarty->assign("fotoalbum", $fotoalbum);
$smarty->assign("fid", $_GET["fid"]);

$grupper = Grupp::listByMedlem($USER);
$foretag = Foretag::loadByMedlem($USER);

if($foretag) {
	if ($fotoalbum->harForetagTilltrade($foretag->getId(), $fotoalbum->getId())) {
		$smarty->assign("foretag_checked", "checked");
	}
}

$smarty->assign("grupper", $grupper);
$smarty->assign("foretag", $foretag);

$smarty->display('fotoalbumandra.tpl');
?>