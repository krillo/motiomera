<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

$smarty = new MMSmarty;
$smarty->assign("pagetitle", "Om MotioMera");

$namn = "Om Motiomera";

$texteditor = TextEditor::loadByNamn($namn);

$smarty->assign("texteditor", $texteditor);

$smarty->display('texteditorsida.tpl');

?>