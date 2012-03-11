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
!empty($_REQUEST['discount']) ? $order->discount = $_REQUEST['discount'] : $order->discount = 0;
!empty($_REQUEST['RE03']) ? $order->RE03 = $_REQUEST['RE03'] : $order->RE03 = 0;
!empty($_REQUEST['RE04']) ? $order->RE04 = $_REQUEST['RE04'] : $order->RE04 = 0;
!empty($_REQUEST['company']) ? $order->company = $_REQUEST['company'] : $order->company = 0;
!empty($_REQUEST['co']) ? $order->co = $_REQUEST['co'] : $order->co = '';
!empty($_REQUEST['firstname']) ? $order->fname = $_REQUEST['firstname'] : $order->fname = '';
!empty($_REQUEST['lastname']) ? $order->lname = $_REQUEST['lastname'] : $order->lname = '';
!empty($_REQUEST['refcode']) ? $order->refcode = $_REQUEST['refcode'] : $order->refcode = 0;
!empty($_REQUEST['email']) ? $order->email = $_REQUEST['email'] : $order->email = 0;
!empty($_REQUEST['phone']) ? $order->phone = $_REQUEST['phone'] : $order->phone = 0;
!empty($_REQUEST['street1']) ? $order->street1 = $_REQUEST['street1'] : $order->street1 = 0;
!empty($_REQUEST['street2']) ? $order->street2 = $_REQUEST['street2'] : $order->street2 = 0;
!empty($_REQUEST['street3']) ? $order->street3 = $_REQUEST['street3'] : $order->street3 = 0;
!empty($_REQUEST['zip']) ? $order->zip = $_REQUEST['zip'] : $order->zip = 0;
!empty($_REQUEST['city']) ? $order->city = $_REQUEST['city'] : $order->city = 0;
!empty($_REQUEST['country']) ? $order->country = $_REQUEST['country'] : $order->country = 0;
!empty($_REQUEST['del-company']) ? $order->delCompany = $_REQUEST['del-company'] : $order->delCompany = 0;
!empty($_REQUEST['del-co']) ? $order->delco = $_REQUEST['del-co'] : $order->delco = 0;
!empty($_REQUEST['del-name']) ? $order->delName = $_REQUEST['del-name'] : $order->delName = 0;
!empty($_REQUEST['del-email']) ? $order->delEmail = $_REQUEST['del-email'] : $order->delEmail = 0;
!empty($_REQUEST['del-phone']) ? $order->delPhone = $_REQUEST['del-phone'] : $order->delPhone = 0;
!empty($_REQUEST['del-street1']) ? $order->delStreet1 = $_REQUEST['del-street1'] : $order->delStreet1 = 0;
!empty($_REQUEST['del-street2']) ? $order->delStreet2 = $_REQUEST['del-street2'] : $order->delStreet2 = 0;
!empty($_REQUEST['del-street3']) ? $order->delStreet3 = $_REQUEST['del-street3'] : $order->delStreet3 = 0;
!empty($_REQUEST['del-zip']) ? $order->delZip = $_REQUEST['del-zip'] : $order->delZip = 0;
!empty($_REQUEST['del-city']) ? $order->delCity = $_REQUEST['del-city'] : $order->delCity = 0;
!empty($_REQUEST['del-country']) ? $order->delcountry = $_REQUEST['del-country'] : $order->delcountry = 0;
!empty($_REQUEST['channel']) ? $order->channel = $_REQUEST['channel'] : $order->channel = 0;
!empty($_REQUEST['paytype']) ? $order->paytype = $_REQUEST['paytype'] : $order->paytype = '';

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

  //http://motiomera.se/pages/kvitto.php?RefId=GIDAZJLVYX36QC20NRE4FXTFQKY6&OrderId=1531126
  //do a price check to avoid javascript hacking
  $noFraud = Order::priceCheck($order->RE03, $order->RE04, $order->exmoms, $order->freight, $order->total, $order->incmoms);
  if ($noFraud) {  //javascript prices match to local calculation
    //everthing looks fine sofar, create the company 
    $kommun = Kommun::loadById(150);  //Use Ale - legacy
    $isValid = 0;
    $foretag = new Foretag($order->company, $kommun, $foretagLosen, $order->startdatum, $order->channel, $order->campcode, $isValid);  //last param is "Order::isValid" and is set to 0 - i.e. not a valid order yet
    $foretag->setTempLosenord(Foretag::skapaLosen());  //a new is created in api/order if a purchase is made
    $foretag->setPayerName($order->fname . ' ' . $order->lname);
    $foretag->setPayerAddress($order->street1 . ' ;; ' . $order->street2 . ' ;; ' . $order->street3);
    $foretag->setPayerCo($order->co);
    $foretag->setPayerZipCode($order->zip);
    $foretag->setPayerCity($order->city);
    $foretag->setPayerEmail($order->email);
    $foretag->setPayerPhone($order->phone);
    $foretag->setPayerMobile($order->phone);
    $foretag->setPayerCountry($order->country);
    $foretag->setReciverCompanyName($order->delCompany);
    $foretag->setReciverName($order->delName);
    $foretag->setReciverAddress($order->delStreet1 . ' ;; ' . $order->delStreet2 . ' ;; ' . $order->delStreet3);
    $foretag->setReciverCo($order->delco);
    $foretag->setReciverZipCode($order->delZip);
    $foretag->setReciverCity($order->delCity);
    $foretag->setReciverEmail($order->delEmail);
    $foretag->setReciverPhone($order->delPhone);
    $foretag->setReciverMobile($order->delPhone);
    $foretag->setReciverCountry($order->delcountry);
    $foretag->commit();

    $token = null;
    if ($order->paytype == 'Direkt') { //do a payson connection
      $nbrpers = $order->RE03 + $order->RE04;
      $paysonMsg = "Motiomera, $nbrpers deltagare, $order->RE03 stegräknare";
      $payResponse = Order::setupPaysonConnection($order->email, $order->fname, $order->lname, $order->incmoms, $paysonMsg);
      if ($payResponse->getResponseEnvelope()->wasSuccessful()) {  // Step 3: verify that it suceeded
        //print_r($payResponse);
        $token = $payResponse->getToken();
        echo $token;
        //header("Location: " . $api->getForwardPayUrl($payResponse)); //do the redirection to payson
      } else {
        throw new UserException("Problem med Payson", "Det är något problem med betaltjänsten Payson. Prova igen senare eller välj faktura.");
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
    $ip=$_SERVER['REMOTE_ADDR'];
    $typ = "foretag";
    $foretagLosen = Foretag::skapaLosen();  //a new is created in api/order if a purchase is made
    $orderRE03 = null;
    $orderRE04 = null;
    $orderFR = null;
    $orderId = '';
    if ($order->RE03 > 0) {
      /*
        if ($refId == "first_iteration") {    //first order row
        $orderRE03 = new Order($typ, $foretag, 'RE03', $order->RE03, $order->channel, $order->campcode, 0);
        $refId = $orderRE03->getRefId();    //use the same refId for all order rows
        } else {         //all other order rows
       * 
       */      
      $orderRE03 = Order::__constructOrderWithSameRefId($typ, $foretag, 'RE03', $order->RE03, $order->channel, $order->campcode, 0, false, $refId);
      $orderRE03->setForetag($foretag);
      $orderRE03->setCompanyName($order->company);
      $priceRE03 = ((int) Order::$campaignCodes['RE03']['pris'] * $order->RE03);
      $priceRE03_moms = $priceRE03 * Order::$moms['percent'];
      $orderRE03->setPrice($priceRE03);
      $orderRE03->setSum($priceRE03_moms);
      $orderRE03->setQuantity($order->RE03);
      $orderRE03->setAntal($order->RE03);
      $orderRE03->setItem(Order::$campaignCodes['RE03']['text']);
      $orderRE03->setSum($order->incmoms);
      $orderRE03->setPayment($paymenttype);
      $orderRE03->setDate();
      $orderRE03->setIpNr($ip);
      $orderRE03->commit();
      $orderId = $orderRE03->getId();
    }
    if ($order->RE04 > 0) {
      /*
        if ($refId == "first_iteration") {    //first order row
        $orderRE04 = new Order($typ, $foretag, 'RE04', $order->RE04, $order->channel, $order->campcode, 0);
        $refId = $orderRE04->getRefId();    //use the same refId for all order rows
        } else {         //all other order rows
       * 
       */
      $orderRE04 = Order::__constructOrderWithSameRefId($typ, $foretag, 'RE04', $order->RE04, $order->channel, $order->campcode, 0, false, $refId);
      $orderRE04->setForetag($foretag);
      $orderRE04->setCompanyName($order->company);
      $priceRE04 = ((int) Order::$campaignCodes['RE04']['pris'] * $order->RE04);
      $priceRE04_moms = $priceRE04 * Order::$moms['percent'];
      $orderRE04->setPrice($priceRE04);
      $orderRE04->setSum($priceRE04_moms);
      $orderRE04->setQuantity($order->RE04);
      $orderRE04->setAntal($order->RE04);
      $orderRE04->setItem(Order::$campaignCodes['RE04']['text']);
      $orderRE04->setSum($order->incmoms);
      $orderRE04->setPayment($paymenttype);
      $orderRE04->setDate();
      $orderRE04->setIpNr($ip);      
      $orderRE04->commit();
      if ($orderId == '') {
        $orderId = $orderRE04->getId();
      }
    }
    if ($order->freight != 'FRAKT00') {
      $orderFR = Order::__constructOrderWithSameRefId($typ, $foretag, $order->freight, 1, $order->channel, $order->campcode, 0, false, $refId);
      $orderFR->setForetag($foretag);
      $orderFR->setCompanyName($order->company);
      $priceFR = (int) Order::$campaignCodes[$order->freight]['pris'];
      $priceFR_moms = $priceFR * Order::$moms['percent'];
      $orderFR->setPrice($priceFR);
      $orderFR->setSum($priceFR_moms);
      $orderFR->setQuantity(1);
      $orderFR->setAntal(1);
      $orderFR->setItem(Order::$campaignCodes[$order->freight]['text']);
      $orderFR->setSum($order->incmoms);
      $orderFR->setPayment($paymenttype);
      $orderFR->setDate();
      $orderFR->setIpNr($ip);      
      $orderFR->commit();
    }


    //do the redirect to payson or faktura kvittosida
    if (empty($token)) { 
      //header("Location: " . $api->getForwardPayUrl($payResponse)); //do the redirection to payson
    } else {
      $kvittoPage = $SETTINGS["paysonReturnUrl"] . '?RefId=' . $refId;
      //echo $kvittoPage; 
      //header("Location: " . $kvittoPage); //do the redirection to payson
    }
/*

    $message = "<br>......................................................<br>";
    $message .= "ORDERNUMMER: $orderId <br>";
    $message .= "$order->company <br>";
    $message .= "$order->name <br>";
    $message .= "$order->email <br>";
    $order->refcode != '' ? $message .= "Referenskod: $order->refcode <br>" : null;
    $message .= "<br>";

    if (isset($orderRE03)) {
      $text = Order::$campaignCodes['RE03']['text'];
      $price = Order::$campaignCodes['RE03']['pris'];
      $message .= "$order->RE03 x $text a $price Kr&nbsp; =  &nbsp;$priceRE03 Kr<br>";
    }
    if (isset($orderRE04)) {
      $text = Order::$campaignCodes['RE04']['text'];
      $price = Order::$campaignCodes['RE04']['pris'];
      $message .= "$order->RE04 x $text a $price Kr&nbsp;  =  &nbsp;$priceRE04 Kr<br>";
    }
    if (isset($orderFR)) {
      $text = Order::$campaignCodes[$order->freight]['text'];
      $price = Order::$campaignCodes[$order->freight]['pris'];
      $message .= "1 x $text a $price Kr&nbsp;  =  &nbsp;$priceFR Kr<br>";
    }
    $message .= ".....................................................<br>";
    $message .= "Summa: $order->total Kr<br>";
    $momssats = Order::$moms['text'];
    $message .= "Summa inkl $momssats moms: $order->incmoms Kr<br>";
    $message .= "<br><br>Pris att betala: $order->incmoms Kr<br>";

    //echo $message;
 * 
 * 
 */
  } else {
    echo 'priset stämmer inte...';
  }
}
?>