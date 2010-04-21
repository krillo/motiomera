<?
	include $_SERVER["DOCUMENT_ROOT"]  . "/php/init.php";

	$smarty = new MMSmarty();
	$smarty->assign("pagetitle", "Quiz");
	$nr_of_rights = $_SESSION['quiz_r'];
	$nr_of_wrongs = $_SESSION['quiz_w'];

	
	$smarty->assign("kommunurl", $_GET["kommun"]);
	
	$kommunnamn = Kommun::convertFromUrlNamn($_GET["kommun"]);
	$smarty->assign("kommunnamn", $kommunnamn);
	
	$kommun = Kommun::loadByNamn($kommunnamn);
		
	$smarty->assign("kommun", $kommun);
	
	$kommunbilder = $kommun->listKommunbilder(true);
	$kommunbild = current($kommunbilder);
	$smarty->assign("kommunbild", $kommunbild);

	
	$smarty->assign("nr_of_rights", $nr_of_rights);
	$smarty->assign("nr_of_wrongs", $nr_of_wrongs);
	$smarty->display('quiz_result.tpl');
	
?>