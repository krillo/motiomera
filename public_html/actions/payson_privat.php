<?php

require_once($_SERVER["DOCUMENT_ROOT"] . "/php/init.php");

if (!isset($_POST) or empty($_POST)) {
  throw new UserException('Felaktigt anrop', 'Sättet att anropa denna sida var felaktig försök igen här: <a href="/#buy/private">Bli Medlem</a>');
}

$order = new stdClass;
!empty($_REQUEST['type']) ? $order->type = $_REQUEST['type'] : $order->type = '';
!empty($_REQUEST['private-type']) ? $order->private_type = $_REQUEST['private-type'] : $order->private_type = '';
!empty($_REQUEST['m_total']) ? $order->total = $_REQUEST['m_total'] : $order->total = 0;
!empty($_REQUEST['m_priv3']) ? $order->PRIV3 = $_REQUEST['m_priv3'] : $order->PRIV3 = 0;
!empty($_REQUEST['m_priv12']) ? $order->PRIV12 = $_REQUEST['m_priv12'] : $order->PRIV12 = 0;
!empty($_REQUEST['m_steg01']) ? $order->STEG01 = $_REQUEST['m_steg01'] : $order->STEG01 = 0;
!empty($_REQUEST['m_frakt02']) ? $order->FRAKT02 = $_REQUEST['m_frakt02'] : $order->FRAKT02 = 0;
!empty($_REQUEST['discount']) ? $order->discount = $_REQUEST['discount'] : $order->discount = '';

!empty($_REQUEST['anamn']) ? $order->anamn = $_REQUEST['anamn'] : $order->anamn = '';
!empty($_REQUEST['sex']) ? $order->sex = $_REQUEST['sex'] : $order->sex = '';
!empty($_REQUEST['kid']) ? $order->kid = $_REQUEST['kid'] : $order->kid = '18';  //default to Helsingborg :)
!empty($_REQUEST['email1']) ? $order->email = $_REQUEST['email1'] : $order->email = '';
!empty($_REQUEST['email2']) ? $order->email2 = $_REQUEST['email2'] : $order->email2 = '';
!empty($_REQUEST['pass']) ? $order->pass = $_REQUEST['pass'] : $order->pass = '';
!empty($_REQUEST['pass2']) ? $order->pass2 = $_REQUEST['pass2'] : $order->pass2 = '';

!empty($_REQUEST['firstname']) ? $order->fname = $_REQUEST['firstname'] : $order->fname = '';
!empty($_REQUEST['lastname']) ? $order->lname = $_REQUEST['lastname'] : $order->lname = '';
!empty($_REQUEST['co']) ? $order->co = $_REQUEST['co'] : $order->co = '';
!empty($_REQUEST['phone']) ? $order->phone = $_REQUEST['phone'] : $order->phone = '';
!empty($_REQUEST['street1']) ? $order->street1 = $_REQUEST['street1'] : $order->street1 = '';
!empty($_REQUEST['street2']) ? $order->street2 = $_REQUEST['street2'] : $order->street2 = '';
!empty($_REQUEST['street3']) ? $order->street3 = $_REQUEST['street3'] : $order->street3 = '';
!empty($_REQUEST['zip']) ? $order->zip = $_REQUEST['zip'] : $order->zip = '';
!empty($_REQUEST['city']) ? $order->city = $_REQUEST['city'] : $order->city = '';
!empty($_REQUEST['country']) ? $order->country = $_REQUEST['country'] : $order->country = '';
!empty($_REQUEST['paytype']) ? $order->paytype = $_REQUEST['paytype'] : $order->paytype = '';
!empty($_REQUEST['campcode']) ? $order->campcode = $_REQUEST['campcode'] : $order->campcode = '';
!empty($_REQUEST['kanal']) ? $order->channel = $_REQUEST['kanal'] : $order->channel = '';

$order->PRIV3 = (int) $order->PRIV3;
$order->PRIV12 = (int) $order->PRIV12;
$order->FRAKT02 = (int) $order->FRAKT02;
$order->total = (int) $order->total;

$order->street = $order->street1;
!empty($order->street2) ? $order->street = $order->street . ' ' . $order->street2 : null;
!empty($order->street3) ? $order->street = $order->street . ' ' . $order->street3 : null;
$callbackVars = '?email1=' . $order->email . '&email2=' . $order->email2 . '&firstname=' . $order->fname . '&lastname=' . $order->lname . '&co=' . $order->co .'&phone=' . $order->phone;
$callbackVars .= '&street1=' . $order->street1 .'&street2=' . $order->street2 .'&zip=' . $order->zip .'&city=' . $order->city .'&country=' . $order->country;
//echo $callbackVars;
switch ($order->private_type) {
  case 'medlem':   //ny medlem
    //if ($order->email != $order->email2) {
    if ($order->email == $order->email2) {
      global $UrlHandler;
      throw new UserException('Epost matchar inte', 'De angivna epost-adresserna är inte samma, försök igen här: <a href="/#buy/private'.$callbackVars.'">Bli Medlem</a>');
    }
    if (Medlem::upptagenEpost($order->email)) {
      throw new UserException('Upptagen epost', 'Den epost adress du angav är tyvärr upptagen. <a href="/pages/glomtlosen.php?email=' . $order->email . '" >Glömt ditt lösenord?</a>');
    }
    if ($order->anamn == '') {
      throw new UserException('Användarnamn ej ifyllt', 'Alla fällt måste vara ifyllda, försök igen: <a href="/#buy/private">Bli Medlem</a>');
    }
    if (($order->PRIV3 == 0 && $order->PRIV12 == 0)) { //return to checkout
      throw new UserException('Ingen tidsperiod', 'Du måste välja en tidsperiod på abonnemanget, försök igen: <a href="/#buy/private">Bli Medlem</a>');
    }
    //do a price check to avoid javascript hacking
    $noFraud = Order::priceCheckPrivate($order->PRIV3, $order->PRIV12, $order->STEG01, $order->FRAKT02, $order->total, $order->discount);
    if ($noFraud) {  //javascript prices match to local calculation
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
      throw new UserException('Priset stämmer inte', 'Försök igen: <a href="/#buy/private">Bli Medlem</a>');
    }
    break;
  case 'medlem_extend':  //förläng abonnemang
    if ($order->email == '' OR $order->fname == '' OR $order->lname == '') {
      throw new UserException('Du måste fylla i alla fält', 'Du måste fylla i alla fält.<a href="/pages/bestall.php?email=' . $order->email . '&firstname=' . $order->fname . '&lastname=' . $order->lname . '" >Prova igen</a>');
    }
    if (($order->PRIV3 == 0 && $order->PRIV12 == 0)) { //return to checkout
      throw new UserException("Ingen tidsperiod", 'Du måste välja en tidsperiod på abonnemanget, <a href="/pages/bestall.php?email=' . $order->email . '&firstname=' . $order->fname . '&lastname=' . $order->lname . '" >Prova igen</a>');
    }
    $noFraud = Order::priceCheckPrivate($order->PRIV3, $order->PRIV12, $order->STEG01, $order->FRAKT02, $order->total, $order->discount);
    if ($noFraud) {  //javascript prices match to local calculation
      $medlem = Medlem::getInloggad();
      $medlem->setAddress($order->street);
      $medlem->setCo($order->co);
      $medlem->setZip($order->zip);
      $medlem->setCity($order->city);
      $medlem->setPhone($order->phone);
      $medlem->setCountry($order->country);
      $medlem->commit();
      $ordertyp = "medlem_extend";
    } else {
      throw new UserException("Priset stämmer inte", ' <a href="/pages/bestall.php?email=' . $order->email . '&firstname=' . $order->fname . '&lastname=' . $order->lname . '" >Prova igen</a>');
    }
    break;
  default:
    break;
}



$token = null;
$msg = '';
if ($order->PRIV3 == 1) {
  $msg = Order::$campaignCodes['PRIV3']['text'] . ' ';
}
if ($order->PRIV12 == 1) {
  $msg = Order::$campaignCodes['PRIV12']['text'] . ' ';
}
if ($order->STEG01 == 1) {
  $msg .= Order::$campaignCodes['STEG01']['text'] . ' och frakt';
}
$paysonMsg = $msg;
if ($order->email == 'krillo@gmail.com' OR (strpos($order->email, '@erendi.se') > 0)) {
  $sumToPay = 10;   //for testing only pay 1 kr and allways kristian@erendi.se, don't forget to return the money in payson
  $order->email = 'kristian@erendi.se';
  $order->fname = 'kristian';
  $order->ename = 'erendi';
  $paysonMsg = $order->total . ' ' . $paysonMsg;
} else {
  $sumToPay = $order->total;
}
$data = Order::setupPaysonConnection($order->email, $order->fname, $order->lname, $sumToPay, $paysonMsg);
$payResponse = $data['payResponse'];
Misc::logMotiomera(print_r($order, true), 'INFO', 'payson');
$api = $data['api'];
if ($payResponse->getResponseEnvelope()->wasSuccessful()) {  // Payson Step 3: verify that it suceeded
  Misc::logMotiomera(print_r($payResponse, true), 'INFO', 'payson');
  $token = $payResponse->getToken();
  echo $token;
//header("Location: " . $api->getForwardPayUrl($payResponse)); //do the redirection to payson
} else {
  // log error to logfile and send a email
  Misc::logMotiomera(print_r($payResponse, true), 'ERROR', 'payson');
  Misc::sendEmail('kristian@motiomera.se', $SETTINGS["email"], 'Payson error', print_r($payResponse, true));
  throw new UserException('Problem med Payson.se', 'Det är något problem med betaltjänsten Payson.se. Prova igen senare. <a href="/#buy/private">Bli Medlem</a>');
}

$paymenttype = 'payson';
$ip = $_SERVER['REMOTE_ADDR'];
$orderPRIV3 = null;
$orderPRIV12 = null;
$orderFRAKT02 = null;
$orderId = '';
if ($order->PRIV3 > 0) {
  echo "inne priv3";

  $orderPRIV3 = Order::__constructOrderWithSameRefId($ordertyp, $medlem, 'PRIV3', 1, $order->channel, $order->campcode, 0, false, $token);
  $orderPRIV3->setCompanyName($order->fname . ' ' . $order->lname);
  $orderPRIV3->setPrice(Order::$campaignCodes['PRIV3']['pris']);
  $orderPRIV3->setQuantity($order->PRIV3);
  $orderPRIV3->setAntal($order->PRIV3);
  $orderPRIV3->setItem(Order::$campaignCodes['PRIV3']['text']);
  $orderPRIV3->setSum($order->total);
  $orderPRIV3->setSumMoms($order->total);
  $orderPRIV3->setPayment($paymenttype);
  $orderPRIV3->setDate();
  $orderPRIV3->setIpNr($ip);
  $orderPRIV3->setMedlem($medlem);
  $orderPRIV3->commit();
  $orderId = $orderPRIV3->getId();
}
if ($order->PRIV12 > 0) {
  $orderPRIV12 = Order::__constructOrderWithSameRefId($ordertyp, $medlem, 'PRIV12', 1, $order->channel, $order->campcode, 0, false, $token);
  $orderPRIV12->setCompanyName($order->fname . ' ' . $order->lname);
  $orderPRIV12->setPrice(Order::$campaignCodes['PRIV12']['pris']);
  $orderPRIV12->setQuantity(1);
  $orderPRIV12->setAntal(1);
  $orderPRIV12->setItem(Order::$campaignCodes['PRIV12']['text']);
  $orderPRIV12->setSum($order->total);
  $orderPRIV12->setSumMoms($order->total);
  $orderPRIV12->setPayment($paymenttype);
  $orderPRIV12->setDate();
  $orderPRIV12->setIpNr($ip);
  $orderPRIV12->setMedlem($medlem);
  $orderPRIV12->commit();
  if ($orderId == '') {
    $orderId = $orderPRIV12->getId();
  }
}
if ($order->STEG01 > 0) {
  $orderSTEG01 = Order::__constructOrderWithSameRefId($ordertyp, $medlem, 'STEG01', 1, $order->channel, $order->campcode, 0, false, $token);
  $orderSTEG01->setCompanyName($order->fname . ' ' . $order->lname);
  $orderSTEG01->setPrice(Order::$campaignCodes['STEG01']['pris']);
  $orderSTEG01->setQuantity(1);
  $orderSTEG01->setAntal(1);
  $orderSTEG01->setItem(Order::$campaignCodes['STEG01']['text']);
  $orderSTEG01->setSum($order->total);
  $orderSTEG01->setSumMoms($order->total);
  $orderSTEG01->setPayment($paymenttype);
  $orderSTEG01->setDate();
  $orderSTEG01->setIpNr($ip);
  $orderSTEG01->setMedlem($medlem);
  $orderSTEG01->commit();
  if ($orderId == '') {
    $orderId = $orderSTEG01->getId();
  }
}
if ($order->FRAKT02 != 0) {
  $orderFRAKT02 = Order::__constructOrderWithSameRefId($ordertyp, $medlem, 'FRAKT02', 1, $order->channel, $order->campcode, 0, false, $token);
  $orderFRAKT02->setCompanyName($order->fname . ' ' . $order->lname);
  $orderFRAKT02->setPrice(Order::$campaignCodes['FRAKT02']['pris']);
  $orderFRAKT02->setQuantity(1);  //used to get nbr of step counters
  $orderFRAKT02->setAntal(1);
  $orderFRAKT02->setItem(Order::$campaignCodes['FRAKT02']['text']);
  $orderFRAKT02->setSum($order->total);
  $orderFRAKT02->setSumMoms($order->total);
  $orderFRAKT02->setPayment($paymenttype);
  $orderFRAKT02->setDate();
  $orderFRAKT02->setIpNr($ip);
  $orderFRAKT02->setMedlem($medlem);
  $orderFRAKT02->commit();
}

//Payson Step 3: verify that it suceeded
if ($payResponse->getResponseEnvelope()->wasSuccessful()) {
  header("Location: " . $api->getForwardPayUrl($payResponse));   //payson Step 4: forward user to payson
}
?>