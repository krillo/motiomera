<?php

include $_SERVER["DOCUMENT_ROOT"] . "/php/init.php";

$lag = Lag::loadById($_POST["lid"]);
$foretag = $lag->getForetag();
foreach($_POST['mid'] as $mid){
	$medlem = Medlem::loadById($mid);
	$lag->addMedlem($medlem);
}

header("Location: " . $urlHandler->getUrl("Lag", URL_EDIT, $lag->getId()));

?>