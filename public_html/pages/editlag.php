<?php

require $_SERVER["DOCUMENT_ROOT"] . "/php/init.php";



$smarty = new MMSmarty();
$smarty->assign("pagetitle", "Lag");


if(isset($_GET["lid"])){

	$lag = Lag::loadById($_GET["lid"]);

	Security::demand(FORETAG, $lag->getForetag());
	
	$smarty->assign("lagarr", array($lag->getForetag()->getId(), 0));
	
	$smarty->assign("editlag", $lag);
	
	$foretag = $lag->getForetag();
	$smarty->assign("foretag", $foretag);
	
	$invitable = $lag->listInvitable();
	$smarty->assign("invitable", $invitable);
	
	$i = 0;
	// $opt_invitable = array(""=>"Välj...");
	foreach($invitable as $medlem){
		$opt_invitable[$medlem->getId()] = $medlem->getFNamn() ." ". $medlem->getENamn();
		$i++;
	}
	
	if($i != null){
		$smarty->assign("opt_invitable", $opt_invitable);
	}
	
	$medlemmar = $lag->listMedlemmar();
	$smarty->assign("medlemmar", $medlemmar);
	$smarty->assign("lagid", $lag->getId());
}else{

if(!empty($_GET['fid'])){
	$smarty->assign("fid", $_GET['fid']);
	$smarty->assign("lagarr", array($_GET['fid'], 0));
}
	$smarty->assign("lagid", "");
	Security::demand(FORETAG);
}

$smarty->display('editlag.tpl');

?>