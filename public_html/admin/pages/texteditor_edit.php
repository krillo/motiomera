<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

Security::demand(ADMIN);

$smarty = new AdminSmarty;

$teman = array(
		"Full Featured" => "Komplett",
		"Simple" => "Enkel"
	);
	
$smarty->assign("opt_teman",$teman);

if(!isset($_GET["id"])){
	$textEditorId = null;
	$sel_typ = null;
}else{
	$texteditor = TextEditor::loadById($_GET["id"]);
	$textEditorId = $_GET["id"];
	$sel_typ = $texteditor->getTema();
	$smarty->assign("texteditor", $texteditor);
}
$smarty->assign("sel_typ", $sel_typ);
$smarty->assign("textEditorId", $textEditorId);

$smarty->display("edittexteditor.tpl");

?>