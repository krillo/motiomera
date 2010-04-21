<?php

include $_SERVER["DOCUMENT_ROOT"] . "/php/init.php";

if (isset($_POST["gid"])) {
	$grupp = Grupp::loadById($_POST["gid"]);

	if(isset($_POST["mid"])) {

		foreach($_POST["mid"] as $currMid) {
			$medlem = Medlem::loadById($currMid);
			$grupp->invite($medlem);
		}
	}

	if(isset($_POST["referer"]) && $_POST["referer"] == "editgrupp")
		$urlHandler->redirect("Grupp", URL_EDIT, $grupp->getId());
	else
		$urlHandler->redirect("Medlem", URL_VIEW, $medlem->getId());

}

?>