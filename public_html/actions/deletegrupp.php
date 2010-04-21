<?php

include $_SERVER["DOCUMENT_ROOT"] . "/php/init.php";

$grupp = Grupp::loadById($_GET["id"]);
$grupp->delete();

header("Location: /pages/grupper.php");

?>