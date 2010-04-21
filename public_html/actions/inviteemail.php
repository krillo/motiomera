<?php

include $_SERVER["DOCUMENT_ROOT"] . "/php/init.php";

$grupp = Grupp::loadById($_POST["id"]);
if(explode(",", $_POST["epost"]) == True){
	foreach (explode(",", $_POST["epost"]) as $key => $value){
		try{
			$grupp->bjudInEpost($value);
		}catch(Exception $e){
			$backUrl = $urlHandler->getUrl("Grupp", URL_EDIT, $grupp->getId());
			if(get_class($e) == "MiscException"){
				if($e->getCode() == -1){
					throw new UserException("Ogiltig e-postadress", "E-postadressen är ogiltig. Var god försök igen.", $backUrl, "Försök igen");
				}else{
					throw $e;
				}
			}else if(get_class($e) == "GruppException"){
				if($e->getCode() == -11){
					$medlem = Medlem::loadByEpost($_POST["epost"]);
					throw new UserException("Befintlig medlem", 'Det finns redan en medlem (<a href="' . $urlHandler->getUrl("Medlem", URL_VIEW, $medlem->getId()) . '">' . $medlem->getANamn() . '</a>) med den här e-postadressen på Motiomera.se!', $backUrl, "Tillbaka");
					//throw new UserException("Gick ej skicka inbjudan", "Det gick ej skicka en inbjudan till angiven e-post.");
				}else{
					throw $e;
				}
			}else{
				throw $e;
			}
		}
	}
}else{
	try{
		$grupp->bjudInEpost($_POST["epost"]);
	}catch(Exception $e){

		$backUrl = $urlHandler->getUrl("Grupp", URL_EDIT, $grupp->getId());
	
		if(get_class($e) == "MiscException"){
	
			if($e->getCode() == -1){
				throw new UserException("Ogiltig e-postadress", "E-postadressen är ogiltig. Var god försök igen.", $backUrl, "Försök igen");
			}else{
				throw $e;
			}
		}else if(get_class($e) == "GruppException"){
			if($e->getCode() == -11){
		
				$medlem = Medlem::loadByEpost($_POST["epost"]);
				throw new UserException("Befintlig medlem", 'Det finns redan en medlem (<a href="' . $urlHandler->getUrl("Medlem", URL_VIEW, $medlem->getId()) . '">' . $medlem->getANamn() . '</a>) med den här e-postadressen på Motiomera.se!', $backUrl, "Tillbaka");
			}else{
				throw $e;
			}
		}else{
			throw $e;
		}
	
	}
}

throw new UserException("Inbjudan skickad!", "En inbjudan till din klubb har nu skickats till " . $_POST["epost"] . ".", $urlHandler->getUrl("Grupp", URL_EDIT, $grupp->getId()), "Tillbaka");

?>