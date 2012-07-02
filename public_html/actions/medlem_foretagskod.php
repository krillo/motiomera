<?php
//for reference see: /pages/foretag_kampanj_code.php
require_once($_SERVER["DOCUMENT_ROOT"] . "/php/init.php");

error_reporting(E_ALL);
ini_set('display_errors', '1');

$order = new stdClass;
!empty($_REQUEST['mmForetagsnyckel']) ? $order->nyckel = $_REQUEST['mmForetagsnyckel'] : $order->nyckel = '';
!empty($_REQUEST['type']) ? $order->type = $_REQUEST['type'] : $order->type = 'foretagsnyckel';
!empty($_REQUEST['anamn']) ? $order->anamn = $_REQUEST['anamn'] : $order->anamn = '';
!empty($_REQUEST['sex']) ? $order->sex = $_REQUEST['sex'] : $order->sex = '';
!empty($_REQUEST['kid']) ? $order->kid = $_REQUEST['kid'] : $order->kid = '18';  //default to helsingborg :)
!empty($_REQUEST['mailone']) ? $order->email = $_REQUEST['mailone'] : $order->email = '';
!empty($_REQUEST['email2']) ? $order->email2 = $_REQUEST['email2'] : $order->email2 = '';
!empty($_REQUEST['pass']) ? $order->pass = $_REQUEST['pass'] : $order->pass = '';
!empty($_REQUEST['pass2']) ? $order->pass2 = $_REQUEST['pass2'] : $order->pass2 = '';
!empty($_REQUEST['firstname']) ? $order->fname = $_REQUEST['firstname'] : $order->fname = '';
!empty($_REQUEST['lastname']) ? $order->lname = $_REQUEST['lastname'] : $order->lname = '';
!empty($_REQUEST['extend']) ? $order->extend = $_REQUEST['extend'] : $order->extend = '';
!empty($_REQUEST['compcampcode']) ? $order->compcampcode = $_REQUEST['compcampcode'] : $order->compcampcode = '';











/*

  case 'company_campaign':  //special case - company competition, the users register themselves in a company competition - no payment here! 
    if ($order->email == '' OR $order->fname == '' OR $order->lname == '') {
      throw new UserException('Du måste fylla i alla fält', 'Du måste fylla i alla fält.<a href="/pages/foretag_kampanj.php?email=' . $order->email . '&firstname=' . $order->fname . '&lastname=' . $order->lname . '" >Prova igen</a>');
    }
    $companyId = Foretag::getCompanyIdByCampaignMemberCode();
    if ( is_int($companyId) && $companyId > 0) {
      //everthing looks fine sofar, create the user
      try {
        $kommun = Kommun::loadById($order->kid);

        $kontotyp = ''; //legacy or not used right now
        $maffcode = ''; //legacy or not used right now
        $medlem = new Medlem($order->email, $order->anamn, $kommun, $order->sex, $order->fname, $order->lname, $kontotyp, $maffcode);
        $medlem->confirm($order->pass);

        $medlem->setAddress($order->street);
        $medlem->setCo($order->co);
        $medlem->setZip($order->zip);
        $medlem->setCity($order->city);
        $medlem->setPhone($order->phone);
        $medlem->setCountry($order->country);
        $medlem->commit();
        $ordertyp = "medlem";
      } catch (Exception $e) {
        $msg = $e->getMessage();
        throw new UserException($msg, null, $urlHandler->getUrl('Medlem', URL_CREATE), 'Tillbaka');
      }
    } else {
      throw new UserException("priset stämmer inte", "Försök igen: <a href=\"" . $urlHandler->getUrl("Medlem", URL_CREATE) . "\">Bli Medlem</a>");
    }
    break;
    
  */  




//if somone trys to extend their account with foretagsnyckel
if ($order->extend == 'true') {
  if ($USER->getForetagsnyckel(true) == "" && $order->nyckel != '') {
    $USER->setForetagsnyckel($order->nyckel);
    $USER->commit();
  }
} else {

  if (!isset($_POST) or empty($_POST)) {
    throw new UserException('Felaktigt anrop', 'Sättet att anropa denna sida var felaktig försök igen här: <a href="/pages/foretagsnyckel.php?mmForetagsnyckel=' . $order->nyckel . '">Bli Medlem</a>');
  }
  if ($order->email != $order->email2) {
    throw new UserException('Epost matchar inte', 'De angivna epost adresserna är inte samma, försök igen här: <a href="/pages/foretagsnyckel.php?mmForetagsnyckel=' . $order->nyckel . '">Bli Medlem</a>');
  }
  if (Medlem::upptagenEpost($order->email)) {
    throw new UserException('Upptagen epost', 'Den epost adress du angav är tyvärr upptagen. <a href="/pages/glomtlosen.php?email=' . $order->email . '" >Glömt ditt lösenord?</a>');
  }
  if ($order->anamn == '') {
    throw new UserException('Användarnamn ej ifyllt', 'Alla fällt måste vara ifyllda, försök igen: <a href="/pages/foretagsnyckel.php?mmForetagsnyckel=' . $order->nyckel . '">Bli Medlem</a>');
  }

  $kommun = Kommun::loadById($order->kid);
  $kontotyp = ''; //legacy or not used right now
  $maffcode = ''; //legacy or not used right now
  $medlem = new Medlem($order->email, $order->anamn, $kommun, $order->sex, $order->fname, $order->lname, $kontotyp, $maffcode);
  $medlem->setEpostBekraftad(1); //medlem valid
  $medlem->setLevelId(1);
  $medlem->confirm($order->pass);
  $medlem->setForetagsnyckel_temp($order->nyckel);
  $medlem->setForetagsnyckel($order->nyckel);
  $medlem->commit();
  $medlem->loggaIn($order->email, $order->pass, true);
}



header("Location: " . '/pages/minsida.php?mmForetagsnyckel=' . $order->nyckel);
?>