<?php

require $_SERVER["DOCUMENT_ROOT"] . "/php/init.php";

Security::demand(FORETAG);

$foretag = Foretag::loadById($_GET["fid"]);
if($FORETAG->getId() != $foretag->getId())
	Security::demand(ADMIN);
	
$medlem = Medlem::loadById($_GET["mid"]);
$foretag->kickMedlem($medlem);


header("Location: " . $_SERVER["HTTP_REFERER"]);
	

?>