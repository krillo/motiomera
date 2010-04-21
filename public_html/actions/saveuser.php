<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

$USER->setFNamn($_POST["fnamn"]);
$USER->setENamn($_POST["enamn"]);
$USER->setEpost($_POST["epost"]);
if($_POST["losen"] != "")
	$USER->setLosenord($_POST["losen"]);
$USER->commit();

header("Location: /pages/installningar.php");

?>