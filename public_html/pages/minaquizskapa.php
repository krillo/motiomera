<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

Security::demand(USER);

if (!SajtDelar::medlemHasAccess($USER,'minaQuizSkapa')) {
	throw new SecurityException("Ej behörig", "Du har inte behörighet att visa denna sida");
}

$smarty = new MMSmarty();
$smarty->assign("pagetitle", "Skapa quiz");

$grupper = Grupp::listByMedlem($USER);
$foretag = Foretag::loadByMedlem($USER);

$smarty->assign("grupper", $grupper);
$smarty->assign("foretag", $foretag);

$smarty->display('minaquizskapa.tpl');

?>