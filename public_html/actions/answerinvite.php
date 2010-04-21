<?php

include $_SERVER["DOCUMENT_ROOT"] . "/php/init.php";


Security::demand(USER);

//kolla valid gruppid innan loadbyid
if (Grupp::isValidGroupId($_GET["gid"])) {

	$grupp = Grupp::loadById($_GET["gid"]);
	if($_GET["do"] == "accept")
		$USER->acceptInvite($grupp);
	else
		$USER->denyInvite($grupp);

	$urlHandler->redirect("Grupp", URL_VIEW, $grupp->getId());
}
else {
	//redirect till något annat
	throw new GruppException("Felaktig inbjudan", -13);
}

?>