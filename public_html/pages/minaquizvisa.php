<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

Security::demand(USER);

$smarty = new MMSmarty();

if (empty($_GET["id"])) {
	$urlHandler->redirect("MinaQuiz", "URL_LIST");
}

$quiz = MinaQuiz::loadById($_GET["id"]);
$medlem = Medlem::loadById($quiz->getMedlemId());
$smarty->assign("pagetitle", ucfirst($quiz->getNamn()). " &mdash; ". $medlem->getANamn()  ." &mdash; Visa quiz");

if ($quiz->isAgare()) {
	// Besökaren äger detta album
	$smarty->assign("isAgare", true);

	// vilka har tillgång till detta quiz?
	$tilltrade = $quiz->getTilltrade();
	if ($tilltrade == "alla") {
		$smarty->assign("tilltrade", "Alla har tillträde till detta album");
	} else {
		if ($quiz->getTilltradeAllaGrupper() == "ja") {
			$tilltrade = "Alla grupper";
			$grupper = null;
		} else {
			$grupper = $quiz->getTilltradesGrupper();
		
			if ($grupper != null) {
				if (count($grupper) == 1) {
					$grupp = Grupp::loadById($grupper[0]);
					$tilltrade = "Gruppen " . $grupp->getNamn();
				} else {
					$tilltrade = "Följande grupper: <strong>";
					for($x=0;$x<count($grupper);$x++) {
						$grupp = Grupp::loadById($grupper[$x]);
						$tilltrade .= $grupp->getNamn() . ", ";
					}
					$tilltrade = substr($tilltrade, 0, strlen($tilltrade)-2);
					$tilltrade .= "</strong>";
				}
			}
		}

		if ($quiz->harForetagTilltrade() == true) {
			$foretag = Foretag::loadByMedlem($quiz->getMedlem());
			if ($grupper != null || $quiz->getTilltradeAllaGrupper() == "ja") {
				// både grupper och företag har tillgång
				$tilltrade .= " samt företaget <strong>" . $foretag->getNamn() . "</strong>";
			} else {
				// endast företag har tillgång
				$tilltrade = "Endast företaget <strong>" . $foretag->getNamn() . "</strong>";
			}
		}
		if (isset($foretag) || $grupper != null || $quiz->getTilltradeAllaGrupper() == "ja") {
			$tilltrade .= " har tillträde till detta quiz";
		} else {
			$tilltrade = "Ingen har tillträde till detta quiz";
		}
	
		$smarty->assign("tilltrade", $tilltrade);
	}
} else {
	// har besökaren tilltrade till detta quiz?
	if (!$quiz->harMedlemTilltrade($USER)) {
		// nej, skicka till sitt eget quiz
		$urlHandler->redirect("MinaQuiz", "URL_LIST");
	} else {
		$smarty->assign("isAgare", false);
	}
}



$fragor = $quiz->getQuestions();

$smarty->assign("quiz", $quiz);
$smarty->assign("titel", $quiz->getNamn());
$smarty->assign("fragor", $fragor);
$smarty->assign("referer", $_SERVER['HTTP_REFERER']);
$smarty->assign("x", 0);
$smarty->assign("show", false);
$smarty->assign("id", $_GET["id"]);

$smarty->display('minaquizvisa.tpl');


?>