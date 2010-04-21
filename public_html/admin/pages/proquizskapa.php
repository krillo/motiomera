<?php
	
	require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

	Security::demand(ADMIN);	

	$smarty = new AdminSmarty;
	$smarty->assign("pagetitle", "proquizskapa");
	
	$smarty->display('proquizskapa.tpl');
	
?>