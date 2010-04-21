<?php

include $_SERVER["DOCUMENT_ROOT"] . "/php/init.php";

Security::demand(USER);

$foretag = Foretag::loadById($_POST["fid"]);
$foretag->gaMedI($_POST["nyckel"]);

header("Location: " . $urlHandler->getUrl("Foretag", URL_VIEW, $foretag->getId()));

?>