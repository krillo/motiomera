<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

/** detta körs på fredag för att skicka mail till Företagsanvändare som slutar sitt tävlande nästa vecka */
	Foretag::sendRemindAboutSteg();


?>
