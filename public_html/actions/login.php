<?php

/*
	Denna fil hanterar inloggning på Motiomera, och skickar sedan medlemmen till min sida
*/

include $_SERVER["DOCUMENT_ROOT"]  . "/php/init.php";

$felInloggString='E-postadressen eller lösenordet är felaktigt.<br />Prova att logga in igen om du tror att du har skrivit fel.<br /></br>Om du har glömt ditt lösenord kan du klicka <a href="/pages/glomtlosen.php" title="Best&auml;ll ett nytt l&ouml;senord">här</a> för att få ett nytt lösenord utskickat via e-post.';

if(isset($USER)) { // Redan inloggad
	header("Location: /");
}

$remember = (isset($_POST["autologin"])) ? true : false;

try{
	$status = Medlem::loggaIn(trim($_POST["username"]),trim($_POST["password"]), $remember);
}catch(MedlemException $e){
	if($e->getCode() == -5){
		throw new UserException("Felaktig inloggning", $felInloggString);
	}else if($e->getCode() == -15){

		throw new UserException("Kontot ej aktiverat", "Du måste aktivera ditt konto för att kunna logga in.
		<br><br>
		Du kan ange din aktiveringskod manuellt för att aktivera ditt konto :<br>
		<form method='get' action='/actions/activate.php'><input type='text' name='q' value='".(isset($_GET['q'])?$_GET['q']:"")."' size=50><br><input type='submit' value='Aktivera'></form>		
		");
	
	}else if($e->getCode() == -19){
		
		$urlHandler->redirect("Medlem", URL_BUY, $e->getMedlemId());
		
	}

}

if(!isset($status) || !$status) { // Felaktigt inlogg
	throw new UserException("Felaktig inloggning", $felInloggString);
}else{

	//företagsanvändare skickas till sitt lags sida
	$USER = Medlem::getInloggad();
	$lag = $USER->getLag();
	//sparar ip och browser här ifall det kommer behövas
	$USER->saveBrowserAndIp();
	header("location: /pages/minsida.php");
}