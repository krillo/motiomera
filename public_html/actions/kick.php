<?php

require $_SERVER["DOCUMENT_ROOT"] . "/php/init.php";

switch($_GET["table"]){


	case "grupp":
		$grupp = Grupp::loadById($_GET["gid"]);
		$medlem = Medlem::loadById($_GET["mid"]);
		$grupp->leaveGrupp($medlem);
		$urlHandler->back();

}