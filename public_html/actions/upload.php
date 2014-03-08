<?php

require $_SERVER["DOCUMENT_ROOT"] . "/php/init.php";

switch($_GET["do"]){

	case "customvisningsbild":
		try{
			new CustomVisningsbild($_FILES["image"]);
		}catch(CustomVisningsbildException $e){
		
			if($e->getCode() == -1){
			
				throw new UserException("Filen är för stor", "Bilden får max vara 1 MB stor. Var god försök igen!");
			
			}
			if($e->getCode() == -4) {
				throw new UserException("Filen är i fel format", "Bilden måste vara i något av formaten .gif, .jpg eller .png. Försök igen!");
			}
		
		}
		$urlHandler->redirect("CustomVisningsbild", URL_EDIT);
		break;

	case "customlagbild":
		try{
			new CustomLagbild($_FILES["image"], null, $_GET['lagid']);
		}catch(CustomLagbildException $e){
			if($e->getCode() == -1){		
				throw new UserException("Filen är för stor", "Bilden får max vara 1 MB stor. Var god försök igen!<br><br>a href=".$urlHandler->getUrl("Lag", URL_EDIT, $_GET['lagid']).">Tillbaka</a>");
			}
			elseif($e->getCode() == -5){
				$formats = CustomLagBild::getAllowedFormats();
				$formatstring = '';
				$first=true;
				foreach($formats as $format) {
					$formatstring.= (!$first?', ':'').$format;
					$first=false;
				}
				throw new UserException("Bilden har fel storlek", "Bilden måste vara av format ".CustomLagBild::WIDTH."x".CustomLagBild::HEIGHT." pixlar och av typer : ".$formatstring."<br><br><a href=".$urlHandler->getUrl("Lag", URL_EDIT, $_GET['lagid']).">Tillbaka</a>");
			}
			elseif($e->getCode() == -7){			
				throw new UserException("Bilden har fel filtyp", "Bilden måste vara av typ : ".$formatstring."
				<br><br><a href=".$urlHandler->getUrl("Lag", URL_EDIT, $_GET['lagid']).">Tillbaka</a>");
			
			}
		
		}
		$urlHandler->redirect("Lag", URL_EDIT, $_GET['lagid']);
		break;

	case "customforetagsbild":
		try{
			new CustomForetagsbild($_FILES["image"], null, $_POST['fid']);
		}catch(CustomForetagsbildException $e){
			if($e->getCode() == -1){			
				throw new UserException("Bilden är för stor", "Bilden får max vara 1 MB stor. Var god försök igen!<br><br><a href=".$urlHandler->getUrl("Foretag", URL_EDIT, array($_POST["fid"],1)).">Tillbaka</a>");			
			}
			elseif($e->getCode() == -5){
				throw new UserException("Bilden har fel storlek", "Bilden får max vara av format ".CustomForetagsBild::WIDTH."x".CustomForetagsBild::HEIGHT." pixlar och av typ : ". implode(', ' , CustomLagbild::getAllowedFormats()) .".<br><br><a href=".$urlHandler->getUrl("Foretag", URL_EDIT, array($_POST["fid"],1)).">Tillbaka</a>");			
			}
			elseif($e->getCode() == -7){
				throw new UserException("Bilden har fel filtyp", "Bilden måste vara av typ : ". implode(', ' , CustomLagbild::getAllowedFormats()) ."
				<br><br><a href=".$urlHandler->getUrl("Foretag", URL_EDIT, array($_POST["fid"],1)).">Tillbaka</a>");			
			}
		
		}

		$urlHandler->redirect("Foretag", URL_EDIT, array($_POST["fid"],1));
		break;

	break;

	case "fotoalbumbild":
		if (empty($_GET["fid"])) {
			$_GET["fid"] = 0;
		}
		$low_id = 0;
		for($x=0;$x<count($_FILES);$x++) {
			if (!empty($_FILES["image$x"]["tmp_name"])) {
				$arr = array();
				$arr["tmp_name"] = $_FILES["image$x"]["tmp_name"];
				$arr["name"] = $_FILES["image$x"]["name"];
	
				// Ladda upp/skapa varje bild
				$bild = new FotoalbumBild($arr, null, $_GET["fid"]);
				$id = $bild->getId();
				if ($id < $low_id || $low_id == 0) $low_id = $id;
			}
		}
		//exit;
		// förflytta användaren till sidan där han/hon ska namnge bilderna
		header("location:/pages/fotoalbumnamngebilder.php?l=" . $low_id . "&fid=" . $_GET["fid"]);
		exit;
		break;
}

if(empty($_GET["redirect"]))
	header("Location: " . $_SERVER["HTTP_REFERER"]);
else
	header("Location: " . $_GET["redirect"]);

?>