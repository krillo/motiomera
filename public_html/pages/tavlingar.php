<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

$smarty = new MMSmarty;
$smarty->assign("pagetitle", "Tävlingar");

$namn = "Tävlingar";

$texteditor = TextEditor::loadByNamn($namn);

$smarty->assign("texteditor", $texteditor);

$smarty->display('texteditorsida.tpl');

?>
