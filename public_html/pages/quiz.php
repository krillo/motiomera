<?php
include $_SERVER["DOCUMENT_ROOT"] . "/php/init.php";
$access = new Access();
$access->logged_in = true;
if(!$access->accessTo()){
  throw new UserException('Vänligen logga in för att se sidan', '');
}


$smarty = new MMSmarty();
$kommun = Kommun::loadByNamn(Kommun::convertFromUrlNamn($_GET['kommun']));
$smarty->assign("pagetitle", "Kommunquiz för " . $kommun->getNamn());

$smarty->assign("kommun", $kommun);

$kommunbilder = $kommun->listKommunbilder(true);
$kommunbild = current($kommunbilder);

$smarty->assign("kommunbild", $kommunbild);

$smarty->assign("kommunurl", $_GET["kommun"]);

$quiz = Quiz::loadByKommun($kommun);
$fragor = $quiz->listFragor();
$questions_and_answers = array();
$antalFragor = 0;
$antalVanligafragor = 0;
$antalProfragor = 0;
foreach ($fragor as $id => $fraga) {
  $alt_arr = null;
  $text_fraga = addslashes($fraga->getFraga());
  $rattsvar = $fraga->getRattSvarId();
  $quizAlternativ = $fraga->listAlternativ();

  foreach ($quizAlternativ as $id2 => $qalt) {
    $alt_arr[] = array($id2, addslashes($qalt->getText()));
  }
  $antalFragor++;
  $antalVanligafragor++;
  $questions_and_answers[] = array($id, $text_fraga, $rattsvar, $alt_arr, 0);
}
if ($sajtDelarObj->medlemHasAccess($USER, 'proQuiz')) {
  $proQuestions = MinaQuiz::getProQuestions();
  foreach ($proQuestions as $proQuestion) {
    $id = $proQuestion['id'];
    $text_fraga = 'PRO-fråga om ' . $proQuestion['quiznamn'] . '|' . $proQuestion['fraga'];
    $rattsvar = $proQuestion['ratt_svar'];
    $alt_arr = array(
        array(1, $proQuestion['svar_1']),
        array(2, $proQuestion['svar_2']),
        array(3, $proQuestion['svar_3']),
    );
    $antalFragor++;
    $antalProfragor++;
    $questions_and_answers[] = array($id, $text_fraga, $rattsvar, $alt_arr, 1);
  }
}
shuffle($questions_and_answers);
$smarty->assign("antalFragor", $antalFragor);
$smarty->assign("antalVanligafragor", $antalVanligafragor);
$smarty->assign("antalProfragor", $antalProfragor);

$smarty->assign("questions_and_answers", $questions_and_answers);
$smarty->assign("kommun_namn", $_GET['kommun']);
$smarty->display('quiz.tpl');
?>