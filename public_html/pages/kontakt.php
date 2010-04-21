<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

$smarty = new MMSmarty;

$namn = "Kontakta Oss";

$texteditor = TextEditor::loadByNamn($namn);

$smarty->assign("texteditor", $texteditor);

$smarty->display('texteditorsida.tpl');

?>