<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

Security::demand(ADMIN);

$smarty = new AdminSmarty;
$listLagNamn = LagNamn::listAll();
$smarty->assign("listLagNamn", $listLagNamn);

$smarty->display('listlagnamn.tpl');

?>