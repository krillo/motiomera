<?php



require $_SERVER["DOCUMENT_ROOT"] . "/php/init.php";

$help = Help::loadById($_GET["id"]);

$help->set_avfardad($USER->getId());


?>