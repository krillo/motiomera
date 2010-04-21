<?php

include $_SERVER["DOCUMENT_ROOT"]  . "/php/init.php";



switch($_GET["do"]){

	case "customvisningsbild":
		$avatar = CustomVisningsbild::loadByFilename($_GET["id"]);
		$avatar->approveVisningsbild();
		$urlHandler->redirect("CustomVisningsbild", URL_ADMIN_LIST);
		break;
		
	case "kommundialekt":
		$dialekt = Kommundialekt::loadById($_GET["id"]);
		$mod = ($_GET["mod"] == "approve") ? true : false;
		$dialekt->setGodkand($mod);
		$dialekt->commit();
		break;

}
	
if(empty($_GET["redirect"]))
	header("Location: " . $_SERVER["HTTP_REFERER"]);
else
	header("Location: " . $_GET["redirect"]);
	
?>