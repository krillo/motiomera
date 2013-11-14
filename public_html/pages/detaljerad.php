<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");
$smarty = new MMSmarty();
$pageTitle = "Detaljerad rapport";
$smarty->assign("pagetitle", $pageTitle);
$smarty->display('detaljerad.tpl');
