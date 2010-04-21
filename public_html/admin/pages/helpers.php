<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

Security::demand(ADMIN);

$smarty = new AdminSmarty;
$helpers = Help::listAll();
$smarty->assign("listHelpers", $helpers);

$smarty->display('listHelpers.tpl');

?>