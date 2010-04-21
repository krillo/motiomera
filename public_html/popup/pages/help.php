<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

$smarty = new PopSmarty();

$id = $_GET["id"];

$texteditor = Help::loadById($id);

$smarty->assign("texteditor", $texteditor);


$smarty->display('help.tpl');
?>