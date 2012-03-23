<?php

require_once($_SERVER["DOCUMENT_ROOT"] . "/php/init.php");

error_reporting(E_ALL);
ini_set('display_errors', '1');

$order = new stdClass;
!empty($_REQUEST['type']) ? $order->type = $_REQUEST['type'] : $order->type = 'foretag';
!empty($_REQUEST['m_exmoms']) ? $order->exmoms = $_REQUEST['m_exmoms'] : $order->exmoms = 0;
!empty($_REQUEST['m_freight']) ? $order->freight = $_REQUEST['m_freight'] : $order->freight = 0;
!empty($_REQUEST['m_total']) ? $order->total = $_REQUEST['m_total'] : $order->total = 0;
!empty($_REQUEST['m_incmoms']) ? $order->incmoms = $_REQUEST['m_incmoms'] : $order->incmoms = 0;
!empty($_REQUEST['startdatumRadio']) ? $order->startdatumRadio = $_REQUEST['startdatumRadio'] : $order->startdatumRadio = 0;
!empty($_REQUEST['discount']) ? $order->discount = $_REQUEST['discount'] : $order->discount = '';
!empty($_REQUEST['RE03']) ? $order->RE03 = $_REQUEST['RE03'] : $order->RE03 = 0;
!empty($_REQUEST['RE04']) ? $order->RE04 = $_REQUEST['RE04'] : $order->RE04 = 0;
!empty($_REQUEST['company']) ? $order->company = $_REQUEST['company'] : $order->company = '';
!empty($_REQUEST['co']) ? $order->co = $_REQUEST['co'] : $order->co = '';
!empty($_REQUEST['firstname']) ? $order->fname = $_REQUEST['firstname'] : $order->fname = '';
!empty($_REQUEST['lastname']) ? $order->lname = $_REQUEST['lastname'] : $order->lname = '';
!empty($_REQUEST['refcode']) ? $order->refcode = $_REQUEST['refcode'] : $order->refcode = '';
!empty($_REQUEST['email']) ? $order->email = $_REQUEST['email'] : $order->email = '';
!empty($_REQUEST['phone']) ? $order->phone = $_REQUEST['phone'] : $order->phone = '';
!empty($_REQUEST['street1']) ? $order->street1 = $_REQUEST['street1'] : $order->street1 = '';
!empty($_REQUEST['street2']) ? $order->street2 = $_REQUEST['street2'] : $order->street2 = '';
!empty($_REQUEST['street3']) ? $order->street3 = $_REQUEST['street3'] : $order->street3 = '';
!empty($_REQUEST['zip']) ? $order->zip = $_REQUEST['zip'] : $order->zip = '';
!empty($_REQUEST['city']) ? $order->city = $_REQUEST['city'] : $order->city = '';
!empty($_REQUEST['country']) ? $order->country = $_REQUEST['country'] : $order->country = '';
!empty($_REQUEST['del-company']) ? $order->delCompany = $_REQUEST['del-company'] : $order->delCompany = '';
!empty($_REQUEST['del-co']) ? $order->delco = $_REQUEST['del-co'] : $order->delco = '';
!empty($_REQUEST['del-name']) ? $order->delName = $_REQUEST['del-name'] : $order->delName = '';
!empty($_REQUEST['del-email']) ? $order->delEmail = $_REQUEST['del-email'] : $order->delEmail = '';
!empty($_REQUEST['del-phone']) ? $order->delPhone = $_REQUEST['del-phone'] : $order->delPhone = '';
!empty($_REQUEST['del-street1']) ? $order->delStreet1 = $_REQUEST['del-street1'] : $order->delStreet1 = '';
!empty($_REQUEST['del-street2']) ? $order->delStreet2 = $_REQUEST['del-street2'] : $order->delStreet2 = '';
!empty($_REQUEST['del-street3']) ? $order->delStreet3 = $_REQUEST['del-street3'] : $order->delStreet3 = '';
!empty($_REQUEST['del-zip']) ? $order->delZip = $_REQUEST['del-zip'] : $order->delZip = '';
!empty($_REQUEST['del-city']) ? $order->delCity = $_REQUEST['del-city'] : $order->delCity = '';
!empty($_REQUEST['del-country']) ? $order->delcountry = $_REQUEST['del-country'] : $order->delcountry = '';
!empty($_REQUEST['channel']) ? $order->channel = $_REQUEST['channel'] : $order->channel = '';
!empty($_REQUEST['paytype']) ? $order->paytype = $_REQUEST['paytype'] : $order->paytype = '';
!empty($_REQUEST['campcode']) ? $order->campcode = $_REQUEST['campcode'] : $order->campcode = '';


//copy buyer data to delivery data
if ($order->delStreet1 == '' && $order->delCity == '') { //consider deliverydata empty
  $order->delCompany = $order->company;
  $order->delName = $order->fname . ' ' . $order->lname;
  $order->delStreet1 = $order->street1;
  $order->delStreet2 = $order->street2;
  $order->delStreet3 = $order->street3;
  $order->delco = $order->co;
  $order->delZip = $order->zip;
  $order->delCity = $order->city;
  $order->delEmail = $order->email;
  $order->delPhone = $order->phone;
  $order->delcountry = $order->country;
}

$order->RE03 = (int) $order->RE03;
$order->RE04 = (int) $order->RE04;
$order->exmoms = (int) $order->exmoms;
$order->total = (int) $order->total;
$order->incmoms = round($order->incmoms, 2);

$order->startdatum = '';
if (!empty($_REQUEST["startdatumRadio"])) {
  if ($_REQUEST["startdatumRadio"] != 'egetdatum') {
    $order->startdatum = $_REQUEST["startdatumRadio"];
  } else {
    $order->startdatum = $_REQUEST["startdatum"];
  }
}

if (($order->RE03 == 0 && $order->RE04 == 0)) { //return to checkout
  $url = $SETTINGS["url"] . '/pages/kassaforetag.php?nbr=0';
  header('Location: ' . $url);
  exit;
} else {

  //do a price check to avoid javascript hacking
  $noFraud = Order::priceCheck($order->RE03, $order->RE04, $order->exmoms, $order->freight, $order->total, $order->incmoms, $order->discount);
  if ($noFraud) {  //javascript prices match to local calculation
    //everthing looks fine sofar, create the company 
    $kommun = Kommun::loadById(150);  //Use Ale - legacy
    $foretagLosen = Foretag::skapaLosen();  //a new is created in api/order if a purchase is made
    $isValid = 0;
    $foretag = new Foretag($order->company, $kommun, $foretagLosen, $order->startdatum, $order->channel, $order->campcode, $isValid);  //last param is "Order::isValid" and is set to 0 - i.e. not a valid order yet
    $foretag->setTempLosenord($foretagLosen);  //a new is created in api/order if a purchase is made. Store this!
    $foretag->setPayerName($order->fname . ' ' . $order->lname);
    $foretag->setPayerFName($order->fname);
    $foretag->setPayerLName($order->lname);
    $order->street = $order->street1;
    !empty($order->street2) ? $order->street = $order->street . ' ' . $order->street2 : null;
    !empty($order->street3) ? $order->street = $order->street . ' ' . $order->street3 : null;
    $foretag->setPayerAddress($order->street);
    $foretag->setPayerCo($order->co);
    $foretag->setPayerZipCode($order->zip);
    $foretag->setPayerCity($order->city);
    $foretag->setPayerEmail($order->email);
    $foretag->setPayerPhone($order->phone);
    $foretag->setPayerMobile($order->phone);
    $foretag->setPayerCountry($order->country);
    $foretag->setReciverCompanyName($order->delCompany);
    $foretag->setReciverName($order->delName);
    $order->delStreet = $order->delStreet1;
    !empty($order->delStreet2) ? $order->delStreet = $order->delStreet . ' ' . $order->delStreet2 : null;
    !empty($order->delStreet3) ? $order->delStreet = $order->delStreet . ' ' . $order->delStreet3 : null;
    $foretag->setReciverAddress($order->delStreet);
    $foretag->setReciverCo($order->delco);
    $foretag->setReciverZipCode($order->delZip);
    $foretag->setReciverCity($order->delCity);
    $foretag->setReciverEmail($order->delEmail);
    $foretag->setReciverPhone($order->delPhone);
    $foretag->setReciverMobile($order->delPhone);
    $foretag->setReciverCountry($order->delcountry);
    $foretag->setCompanyName($order->company);
    $foretag->setCreatedDate();
    $foretag->commit();

    $token = null;
    if ($order->paytype == 'Direktbetalning') { //do a payson connection
      $nbrpers = $order->RE03 + $order->RE04;
      $paysonMsg = "Motiomera, $nbrpers deltagare, $order->RE03 stegräknare";
       if ($order->email == 'krillo@gmail.com' OR (strpos($order->email, '@erendi.se') > 0)) {
        $sumToPay = 1;   //for testing only pay 1 kr and allways kristian@erendi.se, don't forget to return the money in payson
        $order->email = 'kristian@erendi.se';
      } else {
        $sumToPay = $order->total;
        $paysonMsg = $order->incmoms .' '. $paysonMsg;
      }           
      $data = Order::setupPaysonConnection($order->email, $order->fname, $order->lname,$sumToPay , $paysonMsg);
      $payResponse = $data['payResponse'];
      $api = $data['api'];
      if ($payResponse->getResponseEnvelope()->wasSuccessful()) {  // Step 3: verify that it suceeded
        //print_r($payResponse);
        $token = $payResponse->getToken();
        echo $token;
        //header("Location: " . $api->getForwardPayUrl($payResponse)); //do the redirection to payson
      } else {
        throw new UserException("Problem med Payson.se", "Det är något problem med betaltjänsten Payson.com. Prova igen senare eller välj faktura.");
      }
    }

    $paymenttype = '';
    if (empty($token)) {
      $refId = Order::genRefId();
      $paymenttype = 'faktura';
    } else {
      $refId = $token;
      $paymenttype = 'payson';
    }
    $ip = $_SERVER['REMOTE_ADDR'];
    $ordertyp = "foretag";
    $foretagLosen = Foretag::skapaLosen();  //a new is created in api/order if a purchase is made
    $orderRE03 = null;
    $orderRE04 = null;
    $orderFR = null;
    $orderId = '';
    if ($order->RE03 > 0) {
      $orderRE03 = Order::__constructOrderWithSameRefId($ordertyp, $foretag, 'RE03', $order->RE03, $order->channel, $order->campcode, 0, false, $refId);
      $orderRE03->setForetag($foretag);
      $orderRE03->setCompanyName($order->company);
      $priceRE03 = ((int) Order::$campaignCodes['RE03']['pris'] * $order->RE03);
      $orderRE03->setPrice($priceRE03);
      $orderRE03->setQuantity($order->RE03);
      $orderRE03->setAntal($order->RE03);
      $orderRE03->setItem(Order::$campaignCodes['RE03']['text']);
      $orderRE03->setSum($order->total);
      $orderRE03->setSumMoms($order->incmoms);
      $orderRE03->setPayment($paymenttype);
      $orderRE03->setDate();
      $orderRE03->setIpNr($ip);
      $orderRE03->setOrderRefCode($order->refcode);
      $orderRE03->commit();
      $orderId = $orderRE03->getId();
    }
    if ($order->RE04 > 0) {
      $orderRE04 = Order::__constructOrderWithSameRefId($ordertyp, $foretag, 'RE04', $order->RE04, $order->channel, $order->campcode, 0, false, $refId);
      $orderRE04->setForetag($foretag);
      $orderRE04->setCompanyName($order->company);
      $priceRE04 = ((int) Order::$campaignCodes['RE04']['pris'] * $order->RE04);
      $orderRE04->setPrice($priceRE04);
      $orderRE04->setQuantity($order->RE04);
      $orderRE04->setAntal($order->RE04);
      $orderRE04->setItem(Order::$campaignCodes['RE04']['text']);
      $orderRE04->setSum($order->total);
      $orderRE04->setSumMoms($order->incmoms);
      $orderRE04->setPayment($paymenttype);
      $orderRE04->setDate();
      $orderRE04->setIpNr($ip);
      $orderRE04->setOrderRefCode($order->refcode);
      $orderRE04->commit();
      if ($orderId == '') {
        $orderId = $orderRE04->getId();
      }
    }
    if ($order->freight != 'FRAKT00') {
      $orderFR = Order::__constructOrderWithSameRefId($ordertyp, $foretag, $order->freight, 1, $order->channel, $order->campcode, 0, false, $refId);
      $orderFR->setForetag($foretag);
      $orderFR->setCompanyName($order->company);
      $priceFR = (int) Order::$campaignCodes[$order->freight]['pris'];
      $orderFR->setPrice($priceFR);
      $orderFR->setQuantity(0);  //used to get nbr of step counters
      $orderFR->setAntal(1);
      $orderFR->setItem(Order::$campaignCodes[$order->freight]['text']);
      $orderFR->setSum($order->total);
      $orderFR->setSumMoms($order->incmoms);
      $orderFR->setPayment($paymenttype);
      $orderFR->setDate();
      $orderFR->setIpNr($ip);
      $orderFR->setOrderRefCode($order->refcode);
      $orderFR->commit();
    }
    $foretag->setOrderId($orderId);  //update the company with orderid in the db
    $foretag->commit();

    //do the redirect to payson or faktura
    if (!empty($token)) {
      //Payson Step 3: verify that it suceeded
      if ($payResponse->getResponseEnvelope()->wasSuccessful()) {
        //payson Step 4: forward user to payson
        //echo 'redir to payson';
        header("Location: " . $api->getForwardPayUrl($payResponse));
      }
    } else { //faktura
      $kvittoPage = $SETTINGS["paysonReturnUrl"] . '?TOKEN=' . $refId;
      //echo $kvittoPage;
      header("Location: " . $kvittoPage); //do the redirection to kvittosidan
    }
  } else {
    echo 'priset stämmer inte...';
  }
}
?>