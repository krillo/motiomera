<?php

include $_SERVER["DOCUMENT_ROOT"]  . "/php/init.php";

switch($_GET["do"]){
	case "kommunvapen":
		$kommun = Kommun::loadById($_GET["kid"]);
		$kommunvapen = new Kommunvapen($_FILES["image"], $kommun);
		$urlHandler->redirect("Kommun", URL_EDIT, $kommun->getId());
		break;

	case "kommunkarta":
		$kommun = Kommun::loadById($_GET["kid"]);
		$kommunkarta = new Kommunkarta($_FILES["image"], $kommun);
		$urlHandler->redirect("Kommun", URL_EDIT, $kommun->getId());
		break;
		
	case "visningsbild":
		$visningsbild = new Visningsbild($_FILES["image"]);
		$urlHandler->redirect("Visningsbild", URL_ADMIN_LIST);
		break;
		
	case "avatar":
		$standard = (isset($_POST["standard"])) ? true : false;
		new Avatar($_FILES["image"], null, $standard);
		$urlHandler->redirect("Avatar", URL_ADMIN_LIST);
		break;
}

header("Location: /admin/");

?>