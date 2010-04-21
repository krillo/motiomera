<?php

include $_SERVER["DOCUMENT_ROOT"] . "/php/init.php";

$grupp = Grupp::loadById($_POST["gid"]);
if (isset($_POST['ownMsg'])) {
	$ownMsg = "<p>". $_POST['ownMsg'] ."</p>";
} else {
	$ownMsg = null;
}
$USER->joinGrupp($grupp, $ownMsg);

$urlHandler->redirect("Grupp", URL_VIEW, $grupp->getId());

?>
