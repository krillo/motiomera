<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

Security::demand(ADMIN);

$smarty = new AdminSmarty;

$visningsbilder = Visningsbild::listAll();

$smarty->assign("visningsbilder", $visningsbilder);

$smarty->display('visningsbilder.tpl');

?>