<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

Security::demand(USER);

$smarty = new MMSmarty();
$smarty->assign("pagetitle", "Rapport");

$stegList = $USER->listSteg();
$smarty->assign("stegList", $stegList);


$smarty->display('rapportsteg.tpl');


?>
