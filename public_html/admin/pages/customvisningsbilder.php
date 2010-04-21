<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

Security::demand(ADMIN);

$smarty = new AdminSmarty;

$unapproved = CustomVisningsbild::listUnapproved();
$smarty->assign("unapproved", $unapproved);

$approved = CustomVisningsbild::listApproved();
$smarty->assign("approved", $approved);


$smarty->display('customvisningsbilder.tpl');

?>