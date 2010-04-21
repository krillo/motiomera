<?php
	
	require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

	Security::demand(ADMIN);	

	$smarty = new AdminSmarty;
	$smarty->assign("pagetitle", "Påminnelser");

	$queries = Paminnelse_sql::listQueries();
	$smarty->assign('queries', $queries);

	$yttre_mallar = Paminnelse_meddelanden::listMeddelanden();

	$smarty->assign('yttre_mallar', $yttre_mallar);

	if(isset($ADMIN) && $ADMIN->isTyp(SUPERADMIN)) {
		$smarty->assign('superAdmin', true);
	} else {
		$smarty->assign('superAdmin', false);
	}

	$smarty->display('paminnelser.tpl');

?>