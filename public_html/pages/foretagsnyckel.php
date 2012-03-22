<?php
include $_SERVER["DOCUMENT_ROOT"]  . "/php/init.php";
$smarty = new MMSmarty();
$smarty->assign("pagetitle", "Bli medlem med företagsnyckel");


$smarty->assign('compAffCode', $compAffCode);
$smarty->display('foretagsnyckel.tpl');
?>