<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

Security::demand(USER);

$smarty = new MMSmarty();
$smarty->assign("pagetitle", "Ladda upp bild");

// Inget album valt? Då väljer vi det första!
if(empty($_GET["fid"])) {

	list($key,$album) = each(Fotoalbum::loadByMedlem($USER));
	
	$_GET["fid"] = $album->getId();

}

// Hämta information om fotoalbumet vi ska ladda upp till
if (isset($_GET["fid"]) && $_GET["fid"] > 0) {
	$fotoalbum = Fotoalbum::loadById($_GET["fid"]);

	if (!$fotoalbum->isAgare()) {
		// Besökaren äger INTE detta album
		$urlHandler->redirect("Fotoalbum", "URL_LIST");
		exit;
	}

	$smarty->assign("namn", $fotoalbum->getNamn());
	$smarty->assign("fid", $_GET["fid"]);
}

// upload progress meter
require $_SERVER["DOCUMENT_ROOT"] . "/php/libs/uploadprogressmeter/UploadProgressMeter.class.php";
$fileWidget = new UploadProgressMeter();
/* 	$fileWidget->enableDebug(); */
$smarty->assign("fileWidget", $fileWidget);

$smarty->display('fotoalbumladdaupp.tpl');
?>