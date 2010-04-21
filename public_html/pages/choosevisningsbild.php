<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

Security::demand(USER);

$smarty = new MMSmarty();
$smarty->assign("pagetitle", "Välj visningsbild");

$visningsbilder = Visningsbild::listAll();
$smarty->assign("visningsbilder", $visningsbilder);

$visningsbild = $USER->getVisningsbild();
$smarty->assign("visningsbild", $visningsbild);

$customVisningsbild = $USER->getCustomVisningsbild();
$smarty->assign("customVisningsbild", $customVisningsbild);

$unapprovedVisningsbild = $USER->getCustomVisningsbild(false);
$smarty->assign("unapprovedVisningsbild", $unapprovedVisningsbild);

$visningsbilder = Visningsbild::listAll();

$smarty->display('choosevisningsbild.tpl');

?>