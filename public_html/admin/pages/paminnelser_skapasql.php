<?php
	
	require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

	Security::demand(ADMIN);	

	$smarty = new AdminSmarty;
	$smarty->assign("pagetitle", "Skapa påminnelse");

	$yttre_mallar = Paminnelse_meddelanden::listAll();
	
	$smarty->assign('yttre_mallar', $yttre_mallar);

	$smarty->display('paminnelser_skapasql.tpl');

?>