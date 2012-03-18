<?php
include $_SERVER["DOCUMENT_ROOT"]  . "/php/init.php";
$smarty = new MMSmarty();
$smarty->assign("pagetitle", "Bli medlem");

$smarty->display('blimedlem.tpl');
?>