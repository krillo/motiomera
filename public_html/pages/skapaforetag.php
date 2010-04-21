<?php
include $_SERVER["DOCUMENT_ROOT"]  . "/php/init.php";

$smarty = new MMSmarty();
$smarty->assign("pagetitle", "Beställning för företag");

$nummer = array();
for($i = 1; $i <= 100; $i++){
	$nummer[$i] = $i;
}

$opt_kommuner = Misc::arrayKeyMerge(array(""=>"Välj..."), Kommun::listField("namn"));
$campaignCodes = Order::getCampaignCodes("foretag");
$cc_array = array();

foreach($campaignCodes as $id=>$cc) {
	if(isset($cc["popupid"])) {
		$helper = Help::loadById($cc["popupid"]);
	}	
	$cc["popupwidth"] = $helper->getSizeX();
	$cc["popupheight"] = $helper->getSizeY();	
	$cc_array[$id] = $cc;
}

//find and list 10 mondays at least 14 days ahead  
$datumalternativ=array();
$datumstralt=array();
$addDays = 14 + (8 - date("w"));  //the fist monday at least 14 days ahead
$firstMonday = date("Y-m-d", strtotime(date("Y-m-d") . "+$addDays days"));
$monday = $firstMonday;  
for($i=0; $i<10; ++$i) {
  $datumalternativ[] = $monday;
  $datumstralt[] = "Måndagen den ".date('j',strtotime($monday))." ".ucfirst(Misc::month(date('n',strtotime($monday))) );   //nice format
  $monday = date("Y-m-d", strtotime($monday . "+7 days"));  
}

$smarty->assign("campaignCodes", $cc_array);
$smarty->assign("datumalternativ", $datumalternativ);
$smarty->assign("datumstralt", $datumstralt);
$smarty->assign("nummer", $nummer);
$smarty->assign("opt_kommuner", $opt_kommuner);
$smarty->assign("startdatum", Foretag::STARTDATUM_INTERVAL_START);

$time = strtotime(Foretag::STARTDATUM_INTERVAL_START);
setlocale(LC_ALL,"sv_SE");
$startdatumStr = date("j", $time) . " " . strftime("%B", $time) . ", " . date("Y");
$smarty->assign("startdatumStr", $startdatumStr);

$compAffCode = "";
if(!empty($_GET['caff'])){
	$compAffCode = $_GET['caff'];
}
$smarty->assign('compAffCode', $compAffCode);
$smarty->display('skapaforetag.tpl');




?>