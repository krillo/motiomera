<?php
	
	require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

	Security::demand(ADMIN);	

	$smarty = new AdminSmarty;
	$smarty->assign("pagetitle", "proquiz");

	// hämta alla MinaQuiz som har användarID 0
	$MinaQuiz = MinaQuiz::listAsArray(0);
	foreach ($MinaQuiz as $key => $quiz) {
		$MinaQuiz[$key]['fragor'] = MinaQuiz::getQuestions($quiz['id']);
	}
	$smarty->assign("isAgare", true);
	$smarty->assign("egensida", "1");

	$smarty->assign("proquiz", $MinaQuiz);
	$smarty->assign("x", 0);
	$smarty->assign("show", false);
	$smarty->assign('visa_antal_fragor', 3);

	$smarty->display('proquiz.tpl');

?>