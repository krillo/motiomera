<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

$smarty = new MMSmarty;
$smarty->assign("pagetitle", "Vanliga frågor");

$namn = "Stegräknare";

$texteditor = TextEditor::loadByNamn($namn);

$smarty->assign("texteditor", $texteditor);

$smarty->assign("formurl",$SETTINGS["UPPSLAG_URL"]);

$smarty->display('stegraknare.tpl');

?>