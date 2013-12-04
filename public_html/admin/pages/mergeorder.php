<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");
Security::demand(ADMIN);

$status = "";
if(!empty($_GET["status"])){
	$status = $_GET["status"];
}

$smarty = new AdminSmarty;
$smarty->assign("status", $status);
$smarty->display('mergeorder.tpl');
?>