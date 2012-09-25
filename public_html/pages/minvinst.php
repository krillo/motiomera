<?php
include $_SERVER["DOCUMENT_ROOT"]  . "/php/init.php";
$smarty = new MMSmarty();
$smarty->assign("pagetitle", "Du har vunnit!");


$smarty->display('minvinst.tpl');
?>