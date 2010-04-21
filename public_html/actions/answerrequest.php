<?php

include $_SERVER["DOCUMENT_ROOT"] . "/php/init.php";

Security::demand(USER);

$grupp = Grupp::loadById($_GET["gid"]);
$medlem = Medlem::loadById($_GET["mid"]);
if($_GET["do"] == "accept")
	$grupp->acceptRequest($medlem);
else
	$grupp->denyRequest($medlem);
	
$urlHandler->redirect("Grupp", URL_EDIT, $grupp->getId());

?>