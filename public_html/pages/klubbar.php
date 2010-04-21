<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

Security::demand(USER);

$smarty = new MMSmarty;
$smarty->assign("pagetitle", "Klubbar");

//rensa temporära sträckor
Stracka::cleanTempStrackor($USER);

$mygroups = Grupp::listBySkapare($USER);
$allgroups = Grupp::listPublic();
$invites = $USER->listInvites();
$joinedgroups = $USER->listJoinedGroups();

$grupper = array();
foreach($allgroups as $grupp){
	$grupper[$grupp->getSkapare()->getKommun()->getId()][] = $grupp;
}

$medlemsgrupper = Grupp::listByMedlem($USER);
$medlemsgrupper_id = array();
foreach($medlemsgrupper as $grupp){
	$medlemsgrupper_id[] = $grupp->getId();
}
if(count($medlemsgrupper) > 0)
	$smarty->assign("medlemsgrupper", $medlemsgrupper);
$smarty->assign("medlemsgrupper_id", $medlemsgrupper_id);

$kommuner = Kommun::listAll();
$kommunerOchGrupper = array();
foreach($kommuner as $kommun){

	if(isset($grupper[$kommun->getId()])){
	
		$kommunerOchGrupper[$kommun->getId()] = $grupper[$kommun->getId()];
		
	}

}


$smarty->assign("kommunerOchGrupper", $kommunerOchGrupper);
$smarty->assign("kommuner", $kommuner);


$smarty->assign("allgroups", $allgroups);
$smarty->assign("mygroups", $mygroups);

if(count($joinedgroups) > 0)
	$smarty->assign("joinedgroups", $joinedgroups);
if(count($invites) > 0)
	$smarty->assign("invites", $invites);

$smarty->display('klubbar.tpl');

?>