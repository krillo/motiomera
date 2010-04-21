<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

Security::demand(ADMIN);

$smarty = new AdminSmarty;
$textEditors = TextEditor::listAll();
$smarty->assign("listTextEditors", $textEditors);

$smarty->display('listTextEditors.tpl');

?>