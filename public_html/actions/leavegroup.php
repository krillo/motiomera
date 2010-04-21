<?php

include $_SERVER["DOCUMENT_ROOT"] . "/php/init.php";

$grupp = Grupp::loadById($_GET["gid"]);
$grupp->leaveGrupp();

$urlHandler->redirect("Grupp", URL_VIEW, $grupp->getId());

?>