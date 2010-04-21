<?
	include $_SERVER["DOCUMENT_ROOT"]  . "/php/init.php";

	if(isset($USER)){
		$save = true;
	}
	else{
		$save = false;
	}
	
	$nr_of_questions = $_GET['nr_of_questions'];
	$kommun_namn = $_GET['kommun_namn'];
	$nr_of_rights = 0;
	$nr_of_wrongs = 0;

	for($i=0;$i<$nr_of_questions;$i++){
		$fr = 'frid' . $i;
		$frid = $_GET[$fr];
		
		$ch = 'chid' . $i;
		$chid = $_GET[$ch];
		
		if ($_GET['isPro'.$i] == '1') {
			$rattId = MinaQuiz::getRightAnswerById($frid);
		} else {
			$quizFraga = QuizFraga::loadById($frid);
			$rattId = $quizFraga->getRattSvarId();
		}
		
		if ($rattId == $chid) {
			$nr_of_rights++;
		} else {
			$nr_of_wrongs++;
		}
		
	}

	//******************************************************//
	//Code for defining if the quiz was successfull or not
	//******************************************************//

	if($save){
		//Here the code for deciding if the quiz succeded
		$success = false;
		if($nr_of_rights == 4) {
			$success = true;
		}
		if($success){
			$medlem_id = $USER->getId();
			Quiz::saveQuiz($medlem_id,  Kommun::convertFromUrlNamn($kommun_namn));
		}
	}

	//******************************************************//

	$_SESSION["quiz_r"] = $nr_of_rights;
	$_SESSION["quiz_w"] = $nr_of_wrongs;
	

	header("Location: /pages/quiz_result.php?kommun=$kommun_namn");
	exit();
	
?>