<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

Security::demand(ADMIN);

$smarty = new AdminSmarty;
$profilDatas = ProfilData::listAll();
$smarty->assign("listProfilData", $profilDatas);

$smarty->display('listProfilData.tpl');

?>