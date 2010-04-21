<?php

require $_SERVER["DOCUMENT_ROOT"] . "/php/init.php";

switch($_POST["table"]){

	case "steg":
		$steg = Steg::loadById($_POST["id"]);
		$steg->delete();
		echo "OK";
		exit;
		break;
	


}


?>