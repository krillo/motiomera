<?php

include $_SERVER["DOCUMENT_ROOT"] . "/php/init.php";

$lag = Lag::loadById($_GET["lid"]);
$medlem = Medlem::loadById($_GET["mid"]);
$lag->removeMedlem($medlem);

header("Location: " . $urlHandler->getUrl("Lag", URL_EDIT, $lag->getId()));

?>