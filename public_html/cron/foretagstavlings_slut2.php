<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

/** detta körs på måndag när företagstävlingen officiellt är slut */
	Foretag::saveAndEndForetagsTavling();


?>
