<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

$smarty = new MMSmarty;
$smarty->assign("pagetitle", "Välkommen");

$namn = "Välkommen till MotioMera";

$texteditor = TextEditor::loadByNamn($namn);

$smarty->assign("texteditor", $texteditor);

$smarty->display('texteditorsida.tpl');

?>