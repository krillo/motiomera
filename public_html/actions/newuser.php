<?php
/**
 * This script creates a new user
 * 
 */
include $_SERVER["DOCUMENT_ROOT"] . "/php/init.php";

if (!isset($_POST) or empty($_POST)) {
	throw new UserException('Felaktigt anrop', 'Sättet att anropa denna sida var felaktig försök igen här: <a href="'.$urlHandler->getUrl('Medlem', URL_CREATE).'">Bli Medlem</a>');
}
if ((!empty($_POST["epostcheck"])) && ($_POST["epost"] != $_POST["epostcheck"])) {
	global $UrlHandler;	
	throw new UserException("Epost matchar inte", "Dom angivna epost adresserna är inte samma, försök igen här: <a href=\"" . $urlrlHandler->getUrl("Medlem", URL_CREATE) . "\">Bli Medlem</a>");	throw new UserException("Epost matchar inte", "Dom angivna epost adresserna är inte samma, försök igen här: <a href=\"" . $urlrlHandler->getUrl("Medlem", URL_CREATE) . "\">Bli Medlem</a>");
}
if (empty($_POST['anamn'])) {
	throw new UserException("Användarnamn ej ifyllt", "Alla fällt måste vara ifyllda försök igen: <a href=\"" . $urlrlHandler->getUrl("Medlem", URL_CREATE) . "\">Bli Medlem</a>");
}
if (!empty($_POST["kid"])) {
	$kommun = Kommun::loadById($_POST["kid"]);
}

//kampanjkod added by krillo 11-01-18, concatenate kampanjkod with maffcode for storage in the db
$maffcode = $_POST["maffcode"];
if(isset($_POST["kontotyp"]) && $_POST["kontotyp"] == 'kampanjkod'){
  $maffcode = $_POST["kampanjkod"] . $maffcode;
}

if (!Medlem::upptagenEpost($_POST["epost"])) {
	//normal order flow
	try{
		$m = new Medlem($_POST["epost"], $_POST["anamn"], $kommun, $_POST["kon"], $_POST["fnamn"], $_POST["enamn"], $_POST["kontotyp"], $maffcode);
	} catch (Exception $e){
		$msg = $e->getMessage();
		throw new UserException($msg, null, $urlHandler->getUrl('Medlem', URL_CREATE), 'Tillbaka');				
	}
} else {
	throw new UserException('Upptagen epost', 'Den epost adress du angav är tyvärr upptagen');
}


//kampanjkod added by krillo 11-01-18
if($_POST["kontotyp"] == "kampanjkod"){
  $key = mb_convert_case(urldecode($_POST["kampanjkod"]), MB_CASE_LOWER, "UTF-8");
  $AS400Kampanjkod = Order::$kampanjkoder[$key];
	if($AS400Kampanjkod == "free"){
    $m->addPaidUntil(92);  //set account valid for three months
    $m->setLevelId(1);     //set level to pro
    $m->confirm($_POST["losenord"]);
    $m->sendActivationEmail();
    $m->commit();
    throw new UserException("Välkommen till MotioMera!", "Grattis, du är nu medlem i MotioMera! Men innan du kan köra igång måste du aktivera ditt konto. <br />Det är enkelt, så här gör du:</p><p>Vi har nu skickat ett mail till adressen " . $m->getEpost() . ". När du klickar på länken som finns i mailet så aktiveras ditt Motiomera-konto. Proceduren är en säkerhetsåtgärd som vi använder för att ingen ska registrera ett konto i ditt namn. Om du inte ser meddelandet kan det av misstag ha blivit klassificerat som skräppost. Se efter om du hittar e-postmeddelandet i din skräppost-mapp.</p><p>Hoppas du får en rolig tid hos MotioMera!<br />Med vänlig hälsning</p><p><b>MotioMera</b>");
  } elseif(key_exists($AS400Kampanjkod, Order::$campaignCodes)){  //special campaign is verified proceed with order
    $m->confirm($_POST["losenord"]);
    $o = new Order("medlem", $m, $AS400Kampanjkod);
    $o->setMedlem($m);
    $o->gorUppslag();  //this function sends header and breaks execution
    die();
  }
}




if (isset($_POST["inv"]) && isset($m) && isset($_POST["losenord"])) { //invited thru mail, no confirm
	$m->confirm($_POST["losenord"]);
	$m->commit();
	$m = Medlem::loadByEpost($_POST["epost"]); //to get medlem.id for Grupp::Settleinvite
	$m->sendWelcomeMail();
	Grupp::settleInvite($_POST["inv"], $m);	
	throw new UserException("Välkommen till Motiomera!", "Ditt konto har nu skapats och du kan logga in uppe till höger.");
} elseif ($_POST["kontotyp"] == "trial" || $_POST["kontotyp"] == "" || $_POST["kontotyp"] == "foretagsnyckel" ) {

	/*if($_POST["kontotyp"] == "trial"){
	if(Medlem::usedTrialKonto($_POST['epost']) == false){
	$m->setUsedTrialKonto($_POST['epost']);
	}else{
	throw new UserException("Trial konto tid &auml;r f&ouml;rbrukat", "Du har redan anv&auml;nt din trial period, f&ouml;r att betala v&auml;ljer du ett betal alternativ h&auml;r: <a href=\"".$urlHandler->getUrl("Medlem", URL_CREATE)."\" title=\"Bli medlem\">Bli Medlem</a>");
	}
	}*/
	
	$m->confirm($_POST["losenord"]);
	if ($_POST["kontotyp"] == "foretagsnyckel") {
		$m->setForetagsnyckel_temp($_POST["foretagsnyckel"]);
		$m->commit();
	}	
	$m->sendActivationEmail();	
	throw new UserException("Välkommen till MotioMera!", "Grattis, du är nu medlem i MotioMera! Men innan du kan köra igång måste du aktivera ditt konto. <br />Det är enkelt, så här gör du:</p><p>Vi har nu skickat ett mail till adressen " . $m->getEpost() . ". När du klickar på länken som finns i mailet så aktiveras ditt Motiomera-konto. Proceduren är en säkerhetsåtgärd som vi använder för att ingen ska registrera ett konto i ditt namn. Om du inte ser meddelandet kan det av misstag ha blivit klassificerat som skräppost. Se efter om du hittar e-postmeddelandet i din skräppost-mapp.</p><p>Hoppas du får en rolig tid hos MotioMera!<br />Med vänlig hälsning</p><p><b>MåBra</b><br />- specialtidningen för kropp & själ");

	
//normal order flow	
} else {
	$m->confirm($_POST["losenord"]);
	$o = new Order("medlem", $m, $_POST["kontotyp"]);
	$o->setMedlem($m);
	$o->gorUppslag();
}
?>
