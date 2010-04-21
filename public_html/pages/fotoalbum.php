<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

Security::demand(USER);

$smarty = new MMSmarty();
$smarty->assign("pagetitle", "Mina fotoalbum");

$albumsmedlem = $USER;

if (isset($_GET["mid"]) && $_GET["mid"] > 0) {
	// hämta fotoalbumen för användaren angiven i url'en
	$medlem = Medlem::loadById($_GET["mid"]);
	$fotoalbum = Fotoalbum::listAsArray($medlem);
	$smarty->assign("isAgare", false);
	$smarty->assign("medlem", $medlem);
	$smarty->assign("egensida", "0");
	$albumsmedlem = $medlem;
	
} else {
	// hämta användarens egna fotoalbum
	$fotoalbum = Fotoalbum::listAsArray($USER);
	$smarty->assign("isAgare", true);
	$smarty->assign("egensida", "1");
}

// Hämta bilder till alla album
for($x=0;$x<count($fotoalbum);$x++) {
	$album = Fotoalbum::loadById($fotoalbum[$x]["id"]);
	if ($album->harMedlemTilltrade($USER)) {
		// användaren har tillträde till detta fotoalbum, så ta med i listan
		
		$fotoalbum[$x]["bilder"] = FotoalbumBild::listAsArray($albumsmedlem, $fotoalbum[$x]["id"]);
	} else {
		unset($fotoalbum[$x]);
	}
}

$smarty->assign("fotoalbum", $fotoalbum);
$smarty->assign("x", 0);
$smarty->assign("show", false);

$smarty->display('fotoalbum.tpl');
?>