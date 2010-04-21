<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

Security::demand(USER);

$smarty = new MMSmarty;


$mygroups = Grupp::listBySkapare($USER);
$allgroups = Grupp::listPublic();
$invites = $USER->listInvites();
$joinedgroups = $USER->listJoinedGroups();


$smarty->assign("allgroups", $allgroups);
$smarty->assign("mygroups", $mygroups);

if(count($joinedgroups) > 0)
	$smarty->assign("joinedgroups", $joinedgroups);
if(count($invites) > 0)
	$smarty->assign("invites", $invites);

$smarty->display('grupper.tpl');

?>