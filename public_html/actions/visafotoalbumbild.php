<?php

require $_SERVER["DOCUMENT_ROOT"] . "/php/init.php";

// kolla rättigheter/tillträde
$bild = FotoalbumBild::loadById($_GET["id"]);
if ($bild->getFotoalbumId() > 0) {
	$fotoalbum = Fotoalbum::loadById($bild->getFotoalbumId());

	// har besökaren tilltrade till detta fotoalbum?
	if (!$fotoalbum->harMedlemTilltrade($USER)) {
		// nej
		exit;
	}
} elseif ($bild->getMedlemId() != $USER->getId()) {
	// äger inte bilden
	exit;
}

$filename = FOTOALBUM_PATH . "/" . $_GET["id"] . "_" . $_GET["storlek"] . ".jpg";

header("Content-type: image/jpeg");
header("Content-length: " . filesize($filename));

readfile($filename);
?>