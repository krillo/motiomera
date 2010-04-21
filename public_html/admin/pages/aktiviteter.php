<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

Security::demand(ADMIN);

$aktiviteter = Aktivitet::listAll();
if(!empty($_GET["id"]))
	$aktivitet = Aktivitet::loadById($_GET["id"]);

$smarty = new AdminSmarty;
$smarty->assign("aktiviteter", $aktiviteter);
if(isset($aktivitet))
	$smarty->assign("aktivitet", $aktivitet);
$smarty->display('aktiviteter.tpl');

?>