<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

Security::demand(ADMIN);

$smarty = new AdminSmarty;

$fotoalbumbilder = FotoalbumBild::listUncheckedAsArray();
$smarty->assign("fotoalbumbilder", $fotoalbumbilder);
$smarty->display('kontrolleraBilder.tpl');

?>