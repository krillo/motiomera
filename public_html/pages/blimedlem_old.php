<?php

include $_SERVER["DOCUMENT_ROOT"]  . "/php/init.php";

$smarty = new MMSmarty();
$smarty->assign("pagetitle", "Bli medlem");

if(isset($_GET["inv"])){
	try{
		$inv = Grupp::decodeInvite($_GET["inv"]);
	}catch(GruppException $e){
		if($e->getCode() == -13)
			throw new UserException("Ogiltig inbjudan", "Den här inbjudan är ogiltig, vad god försök igen lite senare.");
	}
	$smarty->assign("inv", $inv);

}


$helper = Help::loadById(13);

$firstwidth = $helper->getSizeX();
$firstheight = $helper->getSizeY();

$smarty->assign("firstwidth",$firstwidth);
$smarty->assign("firstheight",$firstheight);

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


$opt_kommuner = Misc::arrayKeyMerge(array(""=>"Välj..."), Kommun::listNamn());
$smarty->assign("opt_kommuner", $opt_kommuner);

$mAffCode = "";
if(!empty($_GET['maff'])){
	$mAffCode = $_GET['maff'];
}
$smarty->assign('maffcode', $mAffCode);

$smarty->display('blimedlem_old.tpl');


?>