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
!empty($_REQUEST['fid']) ? $order->fid = $_REQUEST['fid'] : $order->fid = 0;
!empty($_REQUEST['paytype']) ? $order->paytype = $_REQUEST['paytype'] : $order->paytype = '';
!empty($_REQUEST['channel']) ? $order->channel = $_REQUEST['channel'] : $order->channel = '';
!empty($_REQUEST['campcode']) ? $order->campcode = $_REQUEST['campcode'] : $order->campcode = '';
!empty($_REQUEST['refcode']) ? $order->refcode = $_REQUEST['refcode'] : $order->refcode = '';

$order->RE03 = (int) $order->RE03;
$order->RE04 = (int) $order->RE04;
$order->exmoms = (int) $order->exmoms;
$order->total = (int) $order->total;
$order->incmoms = round($order->incmoms, 2);

$foretag = Foretag::loadById($order->fid);
$order->email = $foretag->getPayerEmail();
$order->fname = $foretag->getPayerFName(); 
$order->lname = $foretag->getPayerLName();
$order->company = $foretag->getNamn();



if (($order->RE03 == 0 && $order->RE04 == 0)) { //return to checkout
  $url = $SETTINGS["url"] . '/pages/editforetag.php?fid='. $order->fid .'&tab='. $order->fid;
  header('Location: ' . $url);
  exit;
} else {

  //do a price check to avoid javascript hacking
  $noFraud = Order::priceCheck($order->RE03, $order->RE04, $order->exmoms, $order->freight, $order->total, $order->incmoms, $order->discount);
  if ($noFraud) {  //javascript prices match to local calculation

    $token = null;
    if ($order->paytype == 'Direktbetalning') { //do a payson connection
      $nbrpers = $order->RE03 + $order->RE04;
      $paysonMsg = "Motiomera tillägg , $nbrpers deltagare, $order->RE03 stegräknare";      
      if ($order->email == 'krillo@gmail.com' OR (strpos($order->email, '@erendi.se') > 0)) {
        $sumToPay = 1;   //for testing only pay 1 kr and allways kristian@erendi.se, don't forget to return the money in payson
        $order->email = 'kristian@erendi.se';
        $paysonMsg = $order->incmoms .' '. $paysonMsg;
      } else {
        $sumToPay = $order->total;
      }      
      $data = Order::setupPaysonConnection($order->email, $order->fname, $order->lname, $sumToPay, $paysonMsg);
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
    $ordertyp = "foretag_tillagg";
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