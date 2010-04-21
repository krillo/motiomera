<?php
	
	require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

	Security::demand(ADMIN);	

	$smarty = new AdminSmarty;
	$smarty->assign("pagetitle", "Ändra påminnelse");

	if (!isset($_GET['qid']) || !is_numeric($_GET['qid'])) {
		throw new Paminnelse_sqlException("Felaktigt SQL-ID angivet", 1);
	}
	
	$query = Paminnelse_sql::loadById($_GET['qid']);
	$yttre_mallar = Paminnelse_meddelanden::listAll();
	
	$smarty->assign('query', $query);
	$smarty->assign('yttre_mallar', $yttre_mallar);

	if(isset($ADMIN) && $ADMIN->isTyp(SUPERADMIN)) {
		$smarty->assign('superAdmin', true);
	} else {
		$smarty->assign('superAdmin', false);
	}


	$smarty->display('paminnelser_andrasql.tpl');

?>