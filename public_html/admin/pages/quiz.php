<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

Security::demand(EDITOR);

$smarty = new AdminSmarty;

$kommun = Kommun::loadById($_GET["kid"]);
$quiz = Quiz::loadByKommun($kommun);

$fragor = $quiz->listFragor();

$smarty->assign("kommun", $kommun);
$smarty->assign("fragor", $fragor);


$smarty->display('quiz.tpl');

?>