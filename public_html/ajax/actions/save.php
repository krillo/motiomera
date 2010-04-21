<?php



require $_SERVER["DOCUMENT_ROOT"] . "/php/init.php";

switch($_POST["table"]){

	case "avatar":
		$avatar = Avatar::loadByFilename($_POST["filnamn"]);
		$USER->setAvatar($avatar);
		$USER->commit();
		echo "OK";
		break;

	case "lagavatar":

		//fix
		$lag = Lag::loadById($_POST["lagid"]);
		$lag->setBildUrl($_POST["filnamn"]);
		$lag->commit();
		//$lagavatar = LagAvatar::loadByFilename($_POST["lagid"]);
		/*
		$USER->setAvatar($avatar);
		$USER->commit();*/
		echo "OK";
		break;

	case "visningsbild":
		$visningsbild = Visningsbild::loadByFilename($_POST["filnamn"]);
		$USER->setVisningsbild($visningsbild);
		$USER->commit();
		echo "OK";
		break;
		
	case "invite":
		$grupp = Grupp::loadById($_POST["gid"]);
		$medlem = Medlem::loadById($_POST["mid"]);
		$grupp->invite($medlem);
		echo "OK";
		break;
	
	case "medlemStatus":
		$USER->setStatus(utf8_encode($_POST["status"]));
		$USER->commit();
		echo "OK" . $USER->getStatus();
		break;
		
}

?>