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
!empty($_REQUEST['campcode']) ? $order->campcode = $_REQUEST['campcode'] : $order->campcode = 0;
!empty($_REQUEST['RE03']) ? $order->RE03 = $_REQUEST['RE03'] : $order->RE03 = 0;
!empty($_REQUEST['RE04']) ? $order->RE04 = $_REQUEST['RE04'] : $order->RE04 = 0;
!empty($_REQUEST['company']) ? $order->company = $_REQUEST['company'] : $order->company = 0;
!empty($_REQUEST['co']) ? $order->co = $_REQUEST['co'] : $order->co = 0;
!empty($_REQUEST['name']) ? $order->name = $_REQUEST['name'] : $order->name = 0;
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

$order->RE03 = (int) $order->RE03;
$order->RE04 = (int) $order->RE04;
$order->exmoms = (int) $order->exmoms;
$order->freight = (int) $order->freight;
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
  header('Location: '.$url);
  exit;
} else {
  //do a price check 
  echo '$re03: ' . $order->RE03 . ' $re04: ' . $order->RE04;


//print_r($order);
//

  $kampanjkod = "";
  $antal = "";
//
  $kommun = Kommun::loadById(150);  //Ale - legacy
  $isValid = 0;

  $foretag = new Foretag($order->company, $kommun, $foretagLosen, $order->startdatum, $order->channel, $order->campcode, $isValid);  //last param is "Order::isValid" and is set to 0 - i.e. not a valid order yet
  $foretag->setTempLosenord(Foretag::skapaLosen());  //a new is created in api/order if a purchase is made
  $foretag->setPayerName($order->name);
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




  $typ = "foretag";
  $refId = "first_iteration";
  if ($order->RE03 > 0) {
    echo 'inne i RE03 <br>';
    
    if ($refId == "first_iteration") {    //first order row
      $orderObj = new Order($typ, $foretag, 'RE03', $order->RE03, $order->channel, $order->campcode, 0);
      $refId = $orderObj->getRefId();    //use the same refId for all order rows
    } else {         //all other order rows
      $orderObj = Order::__constructOrderWithSameRefId($typ, $foretag, 'RE03', $order->RE03, $order->channel, $order->campcode, 0, false, $refId);
    }
    $orderObj->setForetag($foretag);
    $orderObj->setCompanyName($order->company);
    $orderObj->commit();
    $kampanjkod .= $arr["kampanjkod"]; //build request string
    $kampanjkod .= ";";
    $antal .= $arr["antal"];
    $antal .= ";";
  }
  if ($order->RE04 > 0) {
    echo 'inne i RE04 <br>';
    
    
    if ($refId == "first_iteration") {    //first order row
      $orderObj = new Order($typ, $foretag, 'RE04', $order->RE04, $order->channel, $order->campcode, 0);
      $refId = $orderObj->getRefId();    //use the same refId for all order rows
    } else {         //all other order rows
      $orderObj = Order::__constructOrderWithSameRefId($typ, $foretag, 'RE04', $order->RE04, $order->channel, $order->campcode, 0, false, $refId);
    }
    $orderObj->setForetag($foretag);
    $orderObj->setCompanyName($order->company);
    $orderObj->commit();
    $kampanjkod .= $arr["kampanjkod"]; //build request string
    $kampanjkod .= ";";
    $antal .= $arr["antal"];
    $antal .= ";";
  }
  if (strlen($kampanjkod) > 0) {   //remove last semicolon
    $kampanjkod = substr($kampanjkod, 0, strlen($kampanjkod) - 1);
  }
  if (strlen($antal) > 0) {
    $antal = substr($antal, 0, strlen($antal) - 1);
  }
}
?>