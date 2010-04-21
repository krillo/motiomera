<?php

/*
	Denna fil hanterar inloggning pŒ Motiomera, och skickar sedan medlemmen till min sida
*/

include $_SERVER["DOCUMENT_ROOT"]  . "/php/init.php";

if($medlem = Medlem::getInloggad()) {

	$medlem->loggaUt();
}

@session_destroy();

header("location:/");

//header("location:/forum/ucp.php?mode=logout&sid=" . $_COOKIE["phpbb3_kq7es_sid"]);


?>