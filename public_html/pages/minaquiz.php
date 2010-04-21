<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

Security::demand(USER);

$smarty = new MMSmarty();
$smarty->assign("pagetitle", "Mina quiz");

$quizsmedlem = $USER;

if (isset($_GET["id"]) && $_GET["id"] > 0) {
	if (!SajtDelar::medlemHasAccess($USER,'minaQuizVisa')) {
		throw new UserException("Ej behörig", "Du har inte behörighet att visa denna sida");
	}
	// hämta MinaQuizen för användaren angiven i url'en
	$medlem = Medlem::loadById($_GET["id"]);
	$MinaQuiz = MinaQuiz::listAsArray($medlem);
	foreach ($MinaQuiz as $id => $quiz) {
		$MinaQuiz[$id]['fragor'] = MinaQuiz::getQuestions($id);
	}
	$smarty->assign("isAgare", false);
	$smarty->assign("medlem", $medlem);
	$smarty->assign("egensida", "0");
	$quizsmedlem = $medlem;
	
} else {
	if (!SajtDelar::medlemHasAccess($USER,'minaQuiz')) {
		throw new UserException("Ej behörig", "Du har inte behörighet att visa denna sida");
	}

	// hämta användarens egna MinaQuiz
	$MinaQuiz = MinaQuiz::listAsArray($USER);
	foreach ($MinaQuiz as $key => $quiz) {
		$MinaQuiz[$key]['fragor'] = MinaQuiz::getQuestions($quiz['id']);
	}
	$smarty->assign("isAgare", true);
	$smarty->assign("egensida", "1");
}

$smarty->assign("MinaQuiz", $MinaQuiz);
$smarty->assign("x", 0);
$smarty->assign("show", false);
$smarty->assign('visa_antal_fragor', 3);

$smarty->display('minaquiz.tpl');

?>