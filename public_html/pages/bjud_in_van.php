<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

Security::demand(USER);	
$smarty = new MMSmarty;

$smarty->display('bjud_in_van.tpl');
?>