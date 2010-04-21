<?php

include $_SERVER["DOCUMENT_ROOT"]  . "/php/init.php";

switch($_GET["table"]){
		
	case "medlem":
		$medlem = Medlem::loadById($_GET["id"]);
		if(isset($_POST["tabort"])){		
			$medlem->delete();
			$urlHandler->redirect("Medlem", URL_ADMIN_LIST);
		}
		break;
		
	
	case "admin":
		$admin = Admin::loadById($_GET["id"]);
		$admin->delete();
		$urlHandler->redirect("Admin", URL_ADMIN_LIST);
		break;
		
	case "kommun":
		$kommun = Kommun::loadById($_GET["id"]);
		$kommun->delete();
		$urlHandler->redirect("Kommun", URL_ADMIN_LIST);
		break;
		
	case "kommunavstand":
		$kommun = Kommun::loadById($_GET["id"]);
		$target = Kommun::loadById($_GET["target"]);
		$kommun->removeAvstand($target);
		break;
	
	case "visningsbild":
		$visningsbild = Visningsbild::loadByFilename($_GET["id"]);
		$visningsbild->delete();
		$urlHandler->redirect("Visningsbild", URL_ADMIN_LIST);
		break;
		
	case "kommunvapen":
		$kommun = Kommun::loadById($_GET["id"]);
		$kommun->getKommunvapen()->delete();
		$urlHandler->redirect("Kommun", URL_EDIT, $kommun->getId());
		break;
		
	case "kommunkarta":
		$kommun = Kommun::loadById($_GET["id"]);
		$kommun->getKommunkarta()->delete();
		$urlHandler->redirect("Kommun", URL_EDIT, $kommun->getId());
		break;
		
	case "kommunbild":
		$kommunbild = Kommunbild::loadById($_GET["id"]);
		$kommun = $kommunbild->getKommun();
		$kommunbild->delete();
		$urlHandler->redirect("Kommun", URL_EDIT, $kommun->getId());
		break;
		
	case "avatar":
		$avatar = Avatar::loadByFilename($_GET["id"]);
		$avatar->delete();
		$urlHandler->redirect("Avatar", URL_ADMIN_LIST);
		break;
	
	case "quizfraga":
		$quizFraga = QuizFraga::loadById($_GET["id"]);
		$quizFraga->delete();
		$urlHandler->redirect("QuizFraga", URL_ADMIN_LIST, $quizFraga->getKommun()->getId());
		break;
	
	case "quizalternativ":
		$quizAlternativ = QuizAlternativ::loadById($_GET["id"]);
		$quizAlternativ->delete();
		$urlHandler->redirect("QuizFraga", URL_ADMIN_EDIT, $quizAlternativ->getQuizFraga()->getId());
		break;

	case "minaquiz":
		Security::demand(USER);
		$quiz = MinaQuiz::loadById($_GET["id"]);
		$_GET['redirect'] = $urlHandler->getUrl("ProQuiz", URL_ADMIN_LIST);
		$quiz->delete();
		break;

	case "minaquizfraga":
		Security::demand(USER);
		$quiz = MinaQuiz::loadById($_GET["qid"]);
		$quiz->deleteQuestion($_GET['fid']);
		$_GET['redirect'] = $urlHandler->getUrl("ProQuiz", URL_ADMIN_EDIT, $_GET['qid']);
		break;		

	case "texteditor":
		$texteditor = TextEditor::loadById($_GET["id"]);
		$texteditor->delete();
		$urlHandler->redirect("TextEditor", URL_ADMIN_LIST);
		break;
	
	case "help":
		$help = Help::loadById($_GET["id"]);
		$help->delete();
		$urlHandler->redirect("Help", URL_ADMIN_LIST);
		break;
		
	case "lagnamn":
		$lagnamn = LagNamn::loadById($_GET["id"]);
		$lagnamn->delete();
		$urlHandler->redirect("LagNamn", URL_ADMIN_LIST);
		break;	
	
	case "profildata":
		$profildata = ProfilData::loadById($_GET["id"]);
		$profildata->delete();
		$urlHandler->redirect("ProfilData", URL_ADMIN_LIST);
		break;
	case "profildataval":
		$profildataval = ProfilDataVal::loadById($_GET["id"]);
		$profilDataId = $profildataval->getProfilDataId();
		$profildataval->delete();
		$urlHandler->redirect("ProfilData", URL_ADMIN_EDIT,$profilDataId);
		break;
		
	case "level":
		$level = Level::loadById($_GET["id"]);
		$level->delete();
		$urlHandler->redirect("Level", URL_ADMIN_LIST);
		break;
		
	case'fastautmaningar':
		if(!empty($_GET['rid'])){
			Rutt::deleteFastRutt($_GET['rid']);
			$urlHandler->redirect("FastaUtmaningar", URL_ADMIN_LIST);
		}
		break;
	
	case "kommundialekt":
		
		$dialekt = Kommundialekt::loadById($_GET["id"]);
		$kommun = $dialekt->getKommun();
		$dialekt->delete();
		$urlHandler->redirect("Kommun", URL_EDIT, $kommun->getId());
		
		break;
		
	case "paminnelse_sql":
		
		Security::Demand(SUPERADMIN);
		$paminnelse = Paminnelse_sql::loadById($_GET['id']);
		$db->nonquery('DELETE FROM ' . Paminnelse_sql::REMINDERS_TABLE . ' '
		            . 'WHERE sql_id = ' . $paminnelse->getId()
		);
		$paminnelse->delete();
		$urlHandler->redirect('Paminnelser', URL_ADMIN_LIST);
		
		break;
		
	case "paminnelse_meddelanden":
		
		$meddelande = Paminnelse_meddelanden::loadById($_GET['id']);
		$db->nonquery('UPDATE ' . Paminnelse_meddelanden::QUERIES_TABLE . ' '
		           . 'SET meddelande_id = NULL '
		           . 'WHERE meddelande_id = ' . $meddelande->getId()
		);
		$meddelande->delete();
		$urlHandler->redirect('Paminnelser', URL_ADMIN_LIST);
		
		break;
}

if(empty($_GET["redirect"]))
	header("Location: /admin/");
else
	header("Location: " . urldecode($_GET["redirect"]));

?>
