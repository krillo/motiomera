<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

$smarty = new PopSmarty();

if ($USER):
	$medlem = Medlem::loadById($USER->getId());
	$smarty->assign("user_id", $USER->getId());
endif;

$smarty->assign("HTTP_REFERER", $_SERVER["HTTP_REFERER"]);
$smarty->assign("HTTP_USER_AGENT", $_SERVER["HTTP_USER_AGENT"]);

$smarty->display('kontakta.tpl');
?>