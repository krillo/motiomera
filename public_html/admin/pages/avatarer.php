<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

Security::demand(ADMIN);

$smarty = new AdminSmarty;

$avatarer = Avatar::listAll();
$smarty->assign("avatarer", $avatarer);

$smarty->display('avatarer.tpl');

?>