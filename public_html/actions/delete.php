<?php

include $_SERVER["DOCUMENT_ROOT"]  . "/php/init.php";

switch($_GET["table"]){

	case "steg":
		$steg = Steg::loadById($_GET["id"]);
		try{
			$steg->delete();
		}catch(StegException $e){
			if($e->getCode() == -6)
				throw new UserException("Stegrapport låst", "Stegrapporten är låst och kan därför inte tas bort. Stegrapporter blir låsta när du kommer fram till en ny kommun.");
		
		}
		break;

	case "allasteg":			//** Send mail for verification
		
		global $USER;
		if(isset($USER)) {
			$medlem = $USER;
		}
		if(!empty($medlem)) {
			$code = Security::getMedlemEncryptedString($medlem);

			$subject = "Nollställning av steg och rutt på Motiomera.se";

			$email = new MMSmarty();
			$link = 'http:/'.$_SERVER['SERVER_NAME'].'/actions/delete.php?table=verifieraallasteg&verification='.$code;
			$email->assign("link", $link);
			$body = $email->fetch('removeallstegemail.tpl');
			
			Misc::sendEmail($USER->getEpost(), $SETTINGS["email"], $subject, $body);
			throw new UserException("E-post skickat för nollställning","Ett e-post meddelande har skickats till din registerade e-post för att du ska kunna nollställa steg och rutt.");
		}
	
		break;
	case "verifieraallasteg":		//** Attempt to verify to remove sträcka/steg

		global $USER;
		//$argCode = $_GET['verification'];
		
		if(!empty($USER)) {
			//if ($argCode == Security::getMedlemEncryptedString($USER)) {
				
				$USER->removeAllStrackor();
				$USER->removeAllSteg();
				$USER->setUserOnStaticRoute('false');
				$USER->commit();

				throw new UserException('Stegen nollställda', 'Vi har nu nollställt dina steg och du kan börja om från början. Vill du starta i någon annan kommun än din hemkommun kan du välja att göra detta innan du startar din nya rutt.');
				//$urlHandler->redirect("Medlem", URL_VIEW_OWN);
			//}
			//else
			//	throw new UserException("Felaktig kod!", "Din kod matchade ej den krävs för att nollställa steg och rutt.");
		}
		else {
			throw new UserException("Du måste vara inloggad för att ta bort alla steg", "Logga in och klicka på länken igen för att nollställa dina steg.");
		}

		break;
	case "grupp":
		$grupp = Grupp::loadById($_GET["id"]);
		Security::demand(USER);
		$grupp->delete();
		break;
	
	case "lag":
		$lag = Lag::loadById($_GET["id"]);
		$foretag = $lag->getForetag();
		Security::demand(FORETAG, $foretag);
		$lag->delete();
		$urlHandler->redirect("Foretag", URL_EDIT, array($foretag->getId(), 0));
		break;

	case "stracka":
		$stracka = Stracka::loadById($_GET["id"]);
		$stracka->delete();
		if(isset($_GET["ajax"])) {
			echo "ok";
			exit;
		}
		$urlHandler->redirect("Rutt", URL_VIEW);
		break;
		
	case "foretag":
		$foretag = Foretag::loadById($_GET["id"]);
		Security::demand(ADMIN);
		$foretag->delete();
		Foretag::deleteForetagsnyckelWithNoForetag();
		Lag::deleteLagWithNoForetag();
		$urlHandler->redirect("Foretag", URL_ADMIN_LIST);
		break;
	
	case "alla_lag":
		$foretag = Foretag::loadById($_GET["id"]);
		Security::demand(FORETAG, $foretag);
		$lag = $foretag->listLag();
		foreach($lag as $thislag){
			$thislag->delete();
		}
		$urlHandler->redirect("Foretag", URL_EDIT, array($foretag->getId(), 0));
		break;
	
	case "customvisningsbild":
		$visningsbild = CustomVisningsbild::loadByFilename($_GET["id"]);
		$visningsbild->delete();
		if(isset($_GET["redirect"]) && $_GET["redirect"] == "admin"){
			$urlHandler->redirect("CustomVisningsbild", URL_ADMIN_LIST);
		}else{
			$urlHandler->redirect("Visningsbild", URL_LIST);
		}
		break;
		
	case "malmanager":
		Security::demand(USER);
		$malManager = new MalManager($USER);
		$malManager->removeMal();
		$urlHandler->redirect("MalManager", URL_VIEW);
		break;
		
	case "medlem":
			$USER->delete();
		break;
		
	case "adressbok":
		Security::demand(USER);
		$adressbok = Adressbok::loadByMedlem($USER);
		$medlem = Medlem::loadById($_GET["mid"]);
		$adressbok->removeKontakt($medlem);
		$urlHandler->redirect("Adressbok", URL_VIEW);
		break;
		
	case "fotoalbum":
		Security::demand(USER);
		$fotoalbum = Fotoalbum::loadById($_GET["fid"]);
		$_GET["redirect"] = $urlHandler->getUrl("Fotoalbum", URL_LIST);
		$fotoalbum->delete();
		break;
		
	case "fotoalbumbild":
		Security::demand(USER);
		$bild = FotoalbumBild::loadById($_GET["id"]);
		$_GET["redirect"] = $urlHandler->getUrl("Fotoalbum", URL_VIEW, $bild->getFotoalbumId());
		$bild->delete();
		break;
		
	case "minaquiz":
		Security::demand(USER);
		$quiz = MinaQuiz::loadById($_GET["id"]);
		$_GET['redirect'] = $urlHandler->getUrl("MinaQuiz", URL_LIST);
		$quiz->delete();
		break;
	
	case "minaquizfraga":
		Security::demand(USER);
		$quiz = MinaQuiz::loadById($_GET["qid"]);
		$quiz->deleteQuestion($_GET['fid']);
		$_GET['redirect'] = $urlHandler->getUrl("MinaQuiz", URL_EDIT, $_GET['qid']);
		break;
}

if(empty($_GET["redirect"]))
	header("Location: /");
else
	header("Location: " . urldecode($_GET["redirect"]));

?>
