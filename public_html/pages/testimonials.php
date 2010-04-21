<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

$smarty = new MMSmarty;
$smarty->assign("pagetitle", "Några nöjda användare");

$namn = "Några nöjda användare";

$texteditor = TextEditor::loadByNamn($namn);

$smarty->assign("texteditor", $texteditor);

$smarty->display('texteditorsida.tpl');

?>