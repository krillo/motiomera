<?php
include $_SERVER["DOCUMENT_ROOT"]  . "/php/init.php";
$smarty = new MMSmarty();
$smarty->assign("pagetitle", "Registrering med verifikationskod");

$smarty->display('foretag_kampanj.tpl');
?>
