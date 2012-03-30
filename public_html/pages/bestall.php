<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");
$levelId = $_GET["id"];
$smarty = new MMSmarty();
$smarty->assign("pagetitle", "Förläng ditt medlemsskap");

//$m = Medlem::loadById($_GET["id"]);
//var_dump(get_defined_vars());
//exit;

try {
	$level = Level::loadById($levelId);
	$levelNamn = $level->getNamn();
} catch(Exception $e) {
	// the level didn't exist, use default instead
	$level = Level::getDefault();
	$levelNamn = false;
}

$smarty->assign("level",$level);
$campaignCodes = Order::getCampaignCodes("medlem");

$smarty->display('bestall.tpl');
?>