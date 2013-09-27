<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/php/init.php");
error_reporting(E_ALL);
ini_set('display_errors', '1');

$fb = new stdClass;
!empty($_REQUEST['email']) ? $fb->email = Security::secure_postdata($_REQUEST['email']) : $fb->email = '';
!empty($_REQUEST['fbid']) ? $fb->fbid = Security::secure_postdata($_REQUEST['fbid']) : $fb->fbid = '';
try {
  $status = Medlem::loggaInFb($fb->fbid, $fb->email);
  if ($status > 0) {
    $fb->loggedin = 1;
    $fb->mm_id = $status;       
  } else {
    $fb->loggedin = 0;
  }
  //write it as json
  header('Cache-Control: no-cache, must-revalidate');
  header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
  header('Content-type: application/json');
  echo json_encode($fb);
  
} catch (MedlemException $e) {
  if ($e->getCode() == -5) {
    throw new UserException("Felaktig inloggning", $felInloggString);
  } else if ($e->getCode() == -15) {

    throw new UserException("Kontot ej aktiverat", "Du måste aktivera ditt konto för att kunna logga in.
		<br><br>
		Du kan ange din aktiveringskod manuellt för att aktivera ditt konto :<br>
		<form method='get' action='/actions/activate.php'><input type='text' name='q' value='" . (isset($_GET['q']) ? $_GET['q'] : "") . "' size=50><br><input type='submit' value='Aktivera'></form>		
		");
  } else if ($e->getCode() == -19) {
    $urlHandler->redirect("Medlem", URL_BUY, $e->getMedlemId());
  }
}



/*
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

/*
*************************






//if somone trys to extend their account with foretagsnyckel
if ($fb->extend == 'true') {
  if ($USER->getForetagsnyckel(true) == "" && $fb->nyckel != '') {
    $USER->setForetagsnyckel($fb->nyckel);
    $USER->commit();
  }
} else {

  if (!isset($_POST) or empty($_POST)) {
    throw new UserException('Felaktigt anrop', 'Sättet att anropa denna sida var felaktig försök igen här: <a href="/pages/foretagsnyckel.php?mmForetagsnyckel=' . $fb->nyckel . '">Bli Medlem</a>');
  }
  if ($fb->email != $fb->email2) {
    throw new UserException('Epost matchar inte', 'De angivna epost adresserna är inte samma, försök igen här: <a href="/pages/foretagsnyckel.php?mmForetagsnyckel=' . $fb->nyckel . '">Bli Medlem</a>');
  }
  if (Medlem::upptagenEpost($fb->email)) {
    throw new UserException('Upptagen epost', 'Den epost adress du angav är tyvärr upptagen. <a href="/pages/glomtlosen.php?email=' . $fb->email . '" >Glömt ditt lösenord?</a>');
  }
  if ($fb->anamn == '') {
    throw new UserException('Användarnamn ej ifyllt', 'Alla fällt måste vara ifyllda, försök igen: <a href="/pages/foretagsnyckel.php?mmForetagsnyckel=' . $fb->nyckel . '">Bli Medlem</a>');
  }

  $kommun = Kommun::loadById($fb->kid);
  $kontotyp = ''; //legacy or not used right now
  $maffcode = ''; //legacy or not used right now
  $medlem = new Medlem($fb->email, $fb->anamn, $kommun, $fb->sex, $fb->fname, $fb->lname, $kontotyp, $maffcode);
  $medlem->setEpostBekraftad(1); //medlem valid
  $medlem->setLevelId(1);
  $medlem->confirm($fb->pass);
  $medlem->setForetagsnyckel_temp($fb->nyckel);
  $medlem->setForetagsnyckel($fb->nyckel);
  $medlem->commit();
  $medlem->loggaIn($fb->email, $fb->pass, true);
}


header("Location: " . '/pages/minsida.php?mmForetagsnyckel=' . $fb->nyckel);
 * 
 */