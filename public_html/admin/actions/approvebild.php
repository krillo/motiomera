<?php

include $_SERVER["DOCUMENT_ROOT"]  . "/php/init.php";

$nrofpictures = $_POST["nrofpictures"];
$approve_type = $_POST["approve_type"];

for($i=1;$i<=$nrofpictures;$i++){
	$bildid = $_POST["bild_$i"];
	$fotoalbumbild = FotoalbumBild::loadById($bildid);
	$fotoalbumbild->setApproved($approve_type);
}

header("Location: " . $_SERVER["HTTP_REFERER"]);

?>