<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

Security::demand(USER);

$smarty = new MMSmarty();
$smarty->assign("pagetitle", "Skapa fotoalbum");

$grupper = Grupp::listByMedlem($USER);
$foretag = Foretag::loadByMedlem($USER);

$smarty->assign("grupper", $grupper);
$smarty->assign("foretag", $foretag);

$smarty->display('fotoalbumskapa.tpl');
?>