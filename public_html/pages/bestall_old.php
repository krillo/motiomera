<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");
$levelId = $_GET["id"];


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

$smarty = new MMSmarty();
if($levelNamn){
	$smarty->assign("pagetitle", "Förläng ditt medlemsskap");
}else{
	$smarty->assign("pagetitle", "Skaffa ett medlemsskap");
}

$smarty->assign("level",$level);
$campaignCodes = Order::getCampaignCodes("medlem");
$cc_array = array();

foreach($campaignCodes as $id=>$cc) {
	if(isset($cc["popupid"])) {
		$helper = Help::loadById($cc["popupid"]);
	}
	$cc["popupwidth"] = $helper->getSizeX();
	$cc["popupheight"] = $helper->getSizeY();
	$cc_array[$id] = $cc;
}

$smarty->assign("campaignCodes", $cc_array);
$smarty->display('bestall_old.tpl');
?>