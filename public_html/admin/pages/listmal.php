<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

Security::demand(ADMIN);

$smarty = new AdminSmarty;
$listMal = Mal::listAll();
$smarty->assign("listMal", $listMal);

$smarty->display('listmal.tpl');

?>