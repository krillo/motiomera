<?php

require_once($_SERVER["DOCUMENT_ROOT"] . "/php/init.php");

error_reporting(E_ALL);
ini_set('display_errors', '1');


// NOTICE the parameters with prefix "del-" is the actual company
// and without "del-" is all payer (faktura) data


$req = new stdClass;
!empty($_REQUEST['type']) ? $req->type = $_REQUEST['type'] : $req->type = 'foretag';
!empty($_REQUEST['m_exmoms']) ? $req->exmoms = $_REQUEST['m_exmoms'] : $req->exmoms = 0;
!empty($_REQUEST['m_freight']) ? $req->freight = $_REQUEST['m_freight'] : $req->freight = 0;
!empty($_REQUEST['m_total']) ? $req->total = $_REQUEST['m_total'] : $req->total = 0;
!empty($_REQUEST['m_incmoms']) ? $req->incmoms = $_REQUEST['m_incmoms'] : $req->incmoms = 0;
!empty($_REQUEST['startdatumRadio']) ? $req->startdatumRadio = $_REQUEST['startdatumRadio'] : $req->startdatumRadio = 0;
!empty($_REQUEST['weeks']) ? $req->weeks = $_REQUEST['weeks'] : $req->weeks = Foretag::DEFAULT_NO_WEEKS;
!empty($_REQUEST['discount']) ? $req->discount = $_REQUEST['discount'] : $req->discount = '';
!empty($_REQUEST['RE03']) ? $req->RE03 = $_REQUEST['RE03'] : $req->RE03 = 0;
!empty($_REQUEST['RE04']) ? $req->RE04 = $_REQUEST['RE04'] : $req->RE04 = 0;
!empty($_REQUEST['company']) ? $req->company = $_REQUEST['company'] : $req->company = '';
!empty($_REQUEST['co']) ? $req->co = $_REQUEST['co'] : $req->co = '';
!empty($_REQUEST['firstname']) ? $req->fname = $_REQUEST['firstname'] : $req->fname = '';
!empty($_REQUEST['lastname']) ? $req->lname = $_REQUEST['lastname'] : $req->lname = '';
!empty($_REQUEST['refcode']) ? $req->refcode = $_REQUEST['refcode'] : $req->refcode = '';
!empty($_REQUEST['email']) ? $req->email = $_REQUEST['email'] : $req->email = '';
!empty($_REQUEST['phone']) ? $req->phone = $_REQUEST['phone'] : $req->phone = '';
!empty($_REQUEST['street1']) ? $req->street1 = $_REQUEST['street1'] : $req->street1 = '';
!empty($_REQUEST['street2']) ? $req->street2 = $_REQUEST['street2'] : $req->street2 = '';
!empty($_REQUEST['street3']) ? $req->street3 = $_REQUEST['street3'] : $req->street3 = '';
!empty($_REQUEST['zip']) ? $req->zip = $_REQUEST['zip'] : $req->zip = '';
!empty($_REQUEST['city']) ? $req->city = $_REQUEST['city'] : $req->city = '';
!empty($_REQUEST['country']) ? $req->country = $_REQUEST['country'] : $req->country = '';
!empty($_REQUEST['del-company']) ? $req->delCompany = $_REQUEST['del-company'] : $req->delCompany = '';
!empty($_REQUEST['del-co']) ? $req->delCo = $_REQUEST['del-co'] : $req->delCo = '';
!empty($_REQUEST['del-firstname']) ? $req->delFname = $_REQUEST['del-firstname'] : $req->delFname = '';
!empty($_REQUEST['del-lastname']) ? $req->delLname = $_REQUEST['del-lastname'] : $req->delLname = '';
!empty($_REQUEST['del-email']) ? $req->delEmail = $_REQUEST['del-email'] : $req->delEmail = '';
!empty($_REQUEST['del-phone']) ? $req->delPhone = $_REQUEST['del-phone'] : $req->delPhone = '';
!empty($_REQUEST['del-street1']) ? $req->delStreet1 = $_REQUEST['del-street1'] : $req->delStreet1 = '';
!empty($_REQUEST['del-street2']) ? $req->delStreet2 = $_REQUEST['del-street2'] : $req->delStreet2 = '';
!empty($_REQUEST['del-street3']) ? $req->delStreet3 = $_REQUEST['del-street3'] : $req->delStreet3 = '';
!empty($_REQUEST['del-zip']) ? $req->delZip = $_REQUEST['del-zip'] : $req->delZip = '';
!empty($_REQUEST['del-city']) ? $req->delCity = $_REQUEST['del-city'] : $req->delCity = '';
!empty($_REQUEST['del-country']) ? $req->delCountry = $_REQUEST['del-country'] : $req->delCountry = '';
!empty($_REQUEST['channel']) ? $req->channel = $_REQUEST['channel'] : $req->channel = '';
!empty($_REQUEST['paytype']) ? $req->paytype = $_REQUEST['paytype'] : $req->paytype = '';
!empty($_REQUEST['campcode']) ? $req->campcode = $_REQUEST['campcode'] : $req->campcode = '';
!empty($_REQUEST['veckor']) ? $req->veckor = $_REQUEST['veckor'] : $req->veckor = '';


//copy delivery data to buyer date
if ($req->street1 == '' && $req->city == '') { //consider buyerdata empty
  $req->company = $req->delCompany;
  $req->fname = $req->delFname;
  $req->lname = $req->delLname;
  $req->street1 = $req->delStreet1;
  $req->street2 = $req->delStreet2;
  $req->street3 = $req->delStreet3;
  $req->co = $req->delCo;
  $req->zip = $req->delZip;
  $req->city = $req->delCity;
  $req->email = $req->delEmail;
  $req->phone = $req->delPhone;
  $req->country = $req->delCountry;
}


$req->RE03 = (int) $req->RE03;
$req->RE04 = (int) $req->RE04;
$req->exmoms = (int) $req->exmoms;
$req->total = (int) $req->total;
$req->incmoms = round($req->incmoms, 2);

$req->startdatum = '';
if (!empty($_REQUEST["startdatumRadio"])) {
  if ($_REQUEST["startdatumRadio"] != 'egetdatum') {
    $req->startdatum = $_REQUEST["startdatumRadio"];
  } else {
    $req->startdatum = $_REQUEST["startdatum"];
  }
}

if (($req->RE03 == 0 && $req->RE04 == 0)) { //return to checkout
  $url = $SETTINGS["url"] . '/pages/skapaforetag.php?nbr=0';
  header('Location: ' . $url);
  exit;
} else {

  //do a price check to avoid javascript hacking
  $noFraud = Order::priceCheck($req->RE03, $req->RE04, $req->exmoms, $req->freight, $req->total, $req->incmoms, $req->discount);
  if ($noFraud) {  //javascript prices match to local calculation
    //everthing looks fine sofar, create the company 
    $kommun = Kommun::loadById(150);  //Use Ale - legacy
    $foretagLosen = Foretag::skapaLosen();  //a new is created in api/order if a purchase is made
    $isValid = 0;          
    $foretag = new Foretag($req->delCompany, $kommun, $foretagLosen, $req->startdatum, $req->channel, $req->campcode, $isValid, $req->weeks);  //param "Order::isValid" and is set to 0 - i.e. not a valid order yet
    $foretag->setTempLosenord($foretagLosen);  //a new is created in api/order if a purchase is made. Store this one!
    $foretag->setPayerCompanyName($req->company);
    $foretag->setPayerName($req->fname . ' ' . $req->lname);
    $foretag->setPayerFName($req->fname);
    $foretag->setPayerLName($req->lname);
    $req->street = $req->street1;
    !empty($req->street2) ? $req->street = $req->street . ' ;; ' . $req->street2 : null;
    !empty($req->street3) ? $req->street = $req->street . ' ;; ' . $req->street3 : null;
    $foretag->setPayerAddress($req->street);
    $foretag->setPayerCo($req->co);
    $foretag->setPayerZipCode($req->zip);
    $foretag->setPayerCity($req->city);
    $foretag->setPayerEmail($req->email);
    $foretag->setPayerPhone($req->phone);
    $foretag->setPayerMobile($req->phone);
    $foretag->setPayerCountry($req->country);
    $foretag->setReciverCompanyName($req->delCompany);
    $foretag->setReciverName($req->delFname . " " . $req->delLname);
    $req->delStreet = $req->delStreet1;
    !empty($req->delStreet2) ? $req->delStreet = $req->delStreet . ' ;; ' . $req->delStreet2 : null;
    !empty($req->delStreet3) ? $req->delStreet = $req->delStreet . ' ;; ' . $req->delStreet3 : null;
    $foretag->setReciverAddress($req->delStreet);
    $foretag->setReciverCo($req->delCo);
    $foretag->setReciverZipCode($req->delZip);
    $foretag->setReciverCity($req->delCity);
    $foretag->setReciverEmail($req->delEmail);
    $foretag->setReciverPhone($req->delPhone);
    $foretag->setReciverMobile($req->delPhone);
    $foretag->setReciverCountry($req->delCountry);
    $foretag->setCompanyName($req->company);
    $foretag->setCreatedDate();
    $foretag->setVeckor($req->veckor);    
    $foretag->commit();

    $token = null;
    if ($req->paytype == 'Direktbetalning') { //do a payson connection
      $nbrpers = $req->RE03 + $req->RE04;
      $paysonMsg = "Motiomera, $nbrpers deltagare, $req->RE03 stegräknare";
      if ($req->email == 'krillo@gmail.com' OR (strpos($req->email, '@erendi.se') > 0)) {
        $sumToPay = 1;   //for testing only pay 1 kr and allways kristian@erendi.se, don't forget to return the money in payson
        $req->email = 'kristian@erendi.se';
        $req->fname = 'kristian';
        $req->ename = 'erendi';
        $paysonMsg = $req->incmoms . ' ' . $paysonMsg;
      } else {
        $sumToPay = $req->total;
      }
      $data = Order::setupPaysonConnection($req->email, $req->fname, $req->lname, $sumToPay, $paysonMsg);
      $payResponse = $data['payResponse'];
      $api = $data['api'];
      if ($payResponse->getResponseEnvelope()->wasSuccessful()) {  // Step 3: verify that it suceeded
        //print_r($payResponse);
        $token = $payResponse->getToken();
        echo $token;
        //header("Location: " . $api->getForwardPayUrl($payResponse)); //do the redirection to payson
      } else {
        throw new UserException("Problem med Payson.se", "Det är något problem med betaltjänsten Payson.se. Prova igen senare eller välj faktura.");
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
    if ($req->RE03 > 0) {
      $orderRE03 = Order::__constructOrderWithSameRefId($ordertyp, $foretag, 'RE03', $req->RE03, $req->channel, $req->campcode, 0, false, $refId);
      $orderRE03->setForetag($foretag);
      $orderRE03->setCompanyName($req->delCompany);
      $priceRE03 = ((int) Order::$campaignCodes['RE03']['pris'] * $req->RE03);
      $orderRE03->setPrice($priceRE03);
      $orderRE03->setQuantity($req->RE03);
      $orderRE03->setAntal($req->RE03);
      $orderRE03->setItem(Order::$campaignCodes['RE03']['text']);
      $orderRE03->setSum($req->total);
      $orderRE03->setSumMoms($req->incmoms);
      $orderRE03->setPayment($paymenttype);
      $orderRE03->setDate();
      $orderRE03->setIpNr($ip);
      $orderRE03->setOrderRefCode($req->refcode);
      $orderRE03->commit();
      $orderId = $orderRE03->getId();
    }
    if ($req->RE04 > 0) {
      $orderRE04 = Order::__constructOrderWithSameRefId($ordertyp, $foretag, 'RE04', $req->RE04, $req->channel, $req->campcode, 0, false, $refId);
      $orderRE04->setForetag($foretag);
      $orderRE04->setCompanyName($req->delCompany);
      $priceRE04 = ((int) Order::$campaignCodes['RE04']['pris'] * $req->RE04);
      $orderRE04->setPrice($priceRE04);
      $orderRE04->setQuantity($req->RE04);
      $orderRE04->setAntal($req->RE04);
      $orderRE04->setItem(Order::$campaignCodes['RE04']['text']);
      $orderRE04->setSum($req->total);
      $orderRE04->setSumMoms($req->incmoms);
      $orderRE04->setPayment($paymenttype);
      $orderRE04->setDate();
      $orderRE04->setIpNr($ip);
      $orderRE04->setOrderRefCode($req->refcode);
      $orderRE04->commit();
      if ($orderId == '') {
        $orderId = $orderRE04->getId();
      }
    }
    if ($req->freight != 'FRAKT00') {
      $orderFR = Order::__constructOrderWithSameRefId($ordertyp, $foretag, $req->freight, 1, $req->channel, $req->campcode, 0, false, $refId);
      $orderFR->setForetag($foretag);
      $orderFR->setCompanyName($req->delCompany);
      $priceFR = (int) Order::$campaignCodes[$req->freight]['pris'];
      $orderFR->setPrice($priceFR);
      $orderFR->setQuantity(0);  //used to get nbr of step counters
      $orderFR->setAntal(1);
      $orderFR->setItem(Order::$campaignCodes[$req->freight]['text']);
      $orderFR->setSum($req->total);
      $orderFR->setSumMoms($req->incmoms);
      $orderFR->setPayment($paymenttype);
      $orderFR->setDate();
      $orderFR->setIpNr($ip);
      $orderFR->setOrderRefCode($req->refcode);
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