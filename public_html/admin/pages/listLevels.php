<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

Security::demand(SUPERADMIN);

$smarty = new AdminSmarty();

$levels = Level::listAll();
$smarty->assign("levels", $levels);

$defaultLevel = Level::getDefault();
$smarty->assign("defaultLevel", $defaultLevel);

$sajtDelar = SajtDelar::getSajtDelar();
$smarty->assign("sajtDelar", $sajtDelar);


$smarty->display("listLevels.tpl");