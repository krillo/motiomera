<?

	include $_SERVER["DOCUMENT_ROOT"] . "/php/init.php";
	Security::demand(USER);

	if (isset($_POST['id']) && $USER) {
		if (isset($_POST['answer'])) {
			if (MinaQuiz::isAnswered($_POST['id'])) {
				echo 'false';
			} else {
				if (MinaQuiz::answerQuestion($_POST['id'], $_POST['answer'] == 'true' ? true : false)) {
					echo 'true';
				}
			}
		} else {
			if (MinaQuiz::quizExists($_POST['id'])) {
				$quiz = MinaQuiz::loadById($_POST['id']);
				$quiz->getAnswers($USER, false);
				$fragor = array();
				$fragor_all = $quiz->getQuestions();
				foreach ($fragor_all as $id => $fraga) {
					if (!isset($fraga['svar'])) {
						$fragor[$id] = $fraga;
					}
				}
				$questions = array();
				if ($questions = $fragor) {
					$count = 0;
					
					foreach ($questions as $key => $value)
					{
						$svar1Bool = 'false';
						$svar2Bool = 'false';
						$svar3Bool = 'false';

						switch ($value['ratt_svar']) 
						{
							case '1':
								$svar1Bool = 'true';
								break;
							case '2':
								$svar2Bool = 'true';
								break;
							case '3':
								$svar3Bool = 'true';
								break;
							default:
								exit;
								break;
						}

						$json['questions'][$count]['question'] = $value['fraga'];
						$json['questions'][$count]['quiz_id'] = $value['minaquiz_id'];
						$json['questions'][$count]['question_id'] = $value['id'];
						$json['questions'][$count]['answers'][] = array('answer' => $value['svar_1'],'isCorrect' => $svar1Bool);
						$json['questions'][$count]['answers'][] = array('answer' => $value['svar_2'],'isCorrect' => $svar2Bool);
						$json['questions'][$count]['answers'][] = array('answer' => $value['svar_3'],'isCorrect' => $svar3Bool);
						$count++;
					}

					echo json_encode($json);
				}
			}
		}
	}
?>