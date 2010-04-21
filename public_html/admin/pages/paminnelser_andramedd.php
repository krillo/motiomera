<?php
	
	require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

	Security::demand(ADMIN);	

	$smarty = new AdminSmarty;
	$smarty->assign("pagetitle", "Ändra yttre mall");

	if (!isset($_GET['mid']) || !is_numeric($_GET['mid'])) {
		throw new Paminnelse_meddelandenException("Felaktigt SQL-ID angivet", 1);
	}
	
	$meddelande = Paminnelse_meddelanden::loadById($_GET['mid']);
	$smarty->assign('meddelande', $meddelande);

	$smarty->display('paminnelser_andramedd.tpl');

?>