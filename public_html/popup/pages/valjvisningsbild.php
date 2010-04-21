<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

$smarty = new PopSmarty();

$visningsbilder = Visningsbild::listAll();
$smarty->assign("visningsbilder", $visningsbilder);

$smarty->display('valjvisningsbild.tpl');

?>