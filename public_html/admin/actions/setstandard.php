<?php

include $_SERVER["DOCUMENT_ROOT"]  . "/php/init.php";

$visningsbild = Visningsbild::loadByFilename($_GET["id"]);
$visningsbild->setNamn(Visningsbild::STANDARD);

$urlHandler->redirect("Visningsbild", URL_ADMIN_LIST);

?>