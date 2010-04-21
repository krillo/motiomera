<?php

include $_SERVER["DOCUMENT_ROOT"]  . "/php/init.php";

$steg = Steg::loadById($_GET["sid"]);
$steg->delete();
header("Location: /");

?>