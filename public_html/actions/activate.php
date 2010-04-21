<?php

if (isset($_GET["q"])) {

	include $_SERVER["DOCUMENT_ROOT"] . "/php/init.php";

	$decoded = explode("|", urldecode(base64_decode($_GET["q"])));

	if((int)$decoded[0]==0 || !Medlem::verifyValidMemberId((int)$decoded[0]))

		throw new UserException("Fel aktiveringskod!", "Den kod du försöker aktivera är ej giltig.
		<br><br>
		Du kan ange din aktiveringskod manuellt om du vill pröva igen :<br>
		<form method='get' action='?'><input type='text' name='q' value='".(isset($_GET['q'])?$_GET['q']:"")."' size=50><br><input type='submit' value='Aktivera'></form>
		");

	$m = Medlem::loadById($decoded[0]);

	if($m->getEpostBekraftad() == 1){
		throw new UserException("Konto redan aktiverat!", "Detta konto är redan aktivt, logga in här till höger på sidan.");
	}else{
		$m->setEpostBekraftad(1);
		
		// Check for temp key, if we find one we 
		$nyckel = $m->getForetagsnyckel_temp();
		if($nyckel) {
			$m->setForetagsnyckel_temp(""); // clear the temp key
			$m->setForetagsnyckel($nyckel);
		}
		$m->commit();
	}


	global $SETTINGS;
	if (isset($SETTINGS["default_adminmail"]) && isset($m)) {

		$m->sendWelcomeMail();
	}
	else
		throw new MedlemException('Setting saknas', -22);


throw new UserException("Konto aktiverat!", "Ditt konto har nu aktiverats och du kan logga in uppe till höger.");
}
?>
