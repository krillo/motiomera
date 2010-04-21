<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

Security::demand(USER);

if (!SajtDelar::medlemHasAccess($USER,'minaQuizAndra')) {
	throw new SecurityException("Ej behörig", "Du har inte behörighet att visa denna sida");
}

$smarty = new MMSmarty();
$smarty->assign("pagetitle", "Redigera Quiz");

// hämta information om quizet vi ska ändra
$quiz = MinaQuiz::loadById($_GET["id"]);
if (!$quiz->isAgare()) {
	// Besökaren äger INTE detta quiz
	$urlHandler->redirect("MinaQuiz", "URL_LIST");
	exit;
}
$fragor = $quiz->getQuestions();
foreach ($fragor as $id => $fraga) {
	foreach ($fraga as $key => $value) {
		$fragor[$id][$key] = str_replace('"', '&quot;', $value);
	}
}
$smarty->assign("quiz", $quiz);
$smarty->assign("fragor", $fragor);
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

$smarty->display('minaquizandra.tpl');
?>