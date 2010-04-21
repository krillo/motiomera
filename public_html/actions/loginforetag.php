<?php

include $_SERVER["DOCUMENT_ROOT"]  . "/php/init.php";

$felInloggString='Användarnamnet eller lösenordet är felaktigt.<br />Prova att logga in igen om du tror att du har skrivit fel.<br /><br/>'.
'<a href="../pages/foretaglogin.php">Tillbaka</a>';

if(isset($FORETAG)) { // Redan inloggad
	header("Location: /pages/foretag.php");
}

$remember = (isset($_POST["autologin"])) ? true : false;

try {
	$status = Foretag::loggaIn($_POST["username"],$_POST["password"], $remember);
	
} catch(ForetagException $e){
	
	if($e->getCode() == -5){
		throw new UserException("Felaktig inloggning", $felInloggString);
	}else if($e->getCode() == -15){

		throw new UserException("Kontot ej aktiverat", "Du måste aktivera ditt konto för att kunna logga in.");
	
	}else if($e->getCode() == -19){
		
		$urlHandler->redirect("Medlem", URL_BUY, $e->getMedlemId());
	}
}


if(!isset($status) || !$status) { // Felaktigt inlogg
	throw new UserException("Felaktig inloggning", $felInloggString);
}else{
	$foretag = Foretag::getInloggad();
	$urlHandler->redirect("Foretag", URL_EDIT);
}

?>