<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

Security::demand(ADMIN);

$smarty = new AdminSmarty;

$dialekter = Kommundialekt::listEjGodkanda();

$smarty->assign("dialekter", $dialekter);

$alladialekter = Kommundialekt::listAll();
$smarty->assign("alladialekter", $alladialekter);

$smarty->display('listkommunfiler.tpl');

?>