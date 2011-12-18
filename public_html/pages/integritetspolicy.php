<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");
$smarty = new MMSmarty();

$smarty->display('integritetspolicy.tpl');
?>