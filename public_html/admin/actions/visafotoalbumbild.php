<?php

require $_SERVER["DOCUMENT_ROOT"] . "/php/init.php";
Security::demand(ADMIN);
$bild = FotoalbumBild::loadById($_GET["id"]);
$filename = FOTOALBUM_PATH . "/" . $_GET["id"] . "_" . $_GET["storlek"] . ".jpg";

header("Content-type: image/jpeg");
header("Content-length: " . filesize($filename));

readfile($filename);
?>