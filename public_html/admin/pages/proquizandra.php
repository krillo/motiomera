<?php
	
	require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

	Security::demand(ADMIN);	

	$smarty = new AdminSmarty;
	$smarty->assign("pagetitle", "Redigera Quiz");
	
	// hämta information om quizet vi ska ändra
	$quiz = MinaQuiz::loadById($_GET["id"]);

	$smarty->assign("quiz", $quiz);
	$smarty->assign("fragor", $quiz->getQuestions());
	$smarty->assign("id", $_GET["id"]);

	$grupper = Grupp::listByMedlem($USER);
	$foretag = Foretag::loadByMedlem($USER);

	if($foretag) {
		if ($quiz->harForetagTilltrade($foretag->getId(), $quiz->getId())) {
			$smarty->assign("foretag_checked", "checked");
		}
	}

	$smarty->assign("grupper", $grupper);
	$smarty->assign("foretag", $foretag);
	
	$smarty->display('proquizandra.tpl');
?>