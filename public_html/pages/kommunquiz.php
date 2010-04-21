<?
	include $_SERVER["DOCUMENT_ROOT"]  . "/php/init.php";

	$smarty = new MMSmarty();
$smarty->assign("pagetitle", "Kommunquiz");

	
	$smarty->assign("kommuner", Kommun::listAll(true));

	$smarty->display('kommunquiz.tpl');
	
?>