<?php
/**
* Class and Function List:
* Function list:
* Classes list:
*/
require_once ($_SERVER["DOCUMENT_ROOT"] . "/php/init.php");

if (empty($_GET["l"])) {
	$urlHandler->redirect("Fotoalbum", "URL_LIST");
}
Security::demand(USER);
$smarty = new MMSmarty();
$smarty->assign("pagetitle", "Namnge bilder");

// Hämta alla nya bilder
$sql = $db->query("	SELECT
						*
					FROM
						mm_fotoalbumbild
					WHERE
						id >= '" . intval($_GET["l"]) . "'
							AND
						medlem_id = '" . $USER->getId() . "'
							AND
						namn IS NULL
					ORDER BY
						id ASC
");
$bilder = array();

$opt_kommuner = Misc::arrayKeyMerge(array(""=>"Välj..."), Kommun::listNamn());
$smarty->assign("opt_kommuner", $opt_kommuner);

while ($row = mysql_fetch_array($sql)) {
	$bilder[] = $row;
}
$smarty->assign("bilder", $bilder);

if (empty($_GET["fid"]) || $_GET["fid"] == 0) {
	$smarty->assign("visaAlbumlista", true);

	// Hämta alla fotoalbum
	$sql = $db->query("	SELECT
							*
						FROM
							mm_fotoalbum
						WHERE
							medlem_id = '" . $USER->getId() . "'
						ORDER BY
							namn ASC
	");
	$fotoalbum = array();
	while ($row = mysql_fetch_array($sql)) {
		$fotoalbum[] = $row;
	}
	
	if (count($fotoalbum) > 1) {
		$smarty->assign("flerAnEttAlbum", true);
	}
	$smarty->assign("fotoalbum", $fotoalbum);
} else {

	// kolla så att besökaren äger fotoalbumet angivet i url'en
	$fotoalbum = Fotoalbum::loadById($_GET["fid"]);
	
	if (!$fotoalbum->isAgare()) {

		// Besökaren äger INTE detta album
		$urlHandler->redirect("Fotoalbum", "URL_LIST");
		exit;
	}
}
$smarty->display('fotoalbumnamngebilder.tpl');
?>
