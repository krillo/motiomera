<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

$grupp = Grupp::loadById($_GET["gid"]);
$medlem = Medlem::loadById($_GET["mid"]);

$grupp->unignore($medlem);

$urlHandler->redirect("Grupp", URL_EDIT, $grupp->getId());

?>