<?php

//for reference see: /pages/foretag_kampanj_code.php
require_once($_SERVER["DOCUMENT_ROOT"] . "/php/init.php");

error_reporting(E_ALL);
ini_set('display_errors', '1');

$order = new stdClass;
!empty($_REQUEST['compcampcode']) ? $order->compcampcode = $_REQUEST['compcampcode'] : $order->compcampcode = '';
!empty($_REQUEST['type']) ? $order->type = $_REQUEST['type'] : $order->type = 'company_campaign';
!empty($_REQUEST['anamn']) ? $order->anamn = $_REQUEST['anamn'] : $order->anamn = '';
!empty($_REQUEST['sex']) ? $order->sex = $_REQUEST['sex'] : $order->sex = '';
!empty($_REQUEST['kid']) ? $order->kid = $_REQUEST['kid'] : $order->kid = '18';  //default to helsingborg :)
!empty($_REQUEST['mailone']) ? $order->email = $_REQUEST['mailone'] : $order->email = '';
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


$order->street = $order->street1;
!empty($order->street2) ? $order->street = $order->street . ' ' . $order->street2 : null;
!empty($order->street3) ? $order->street = $order->street . ' ' . $order->street3 : null;



if ($order->email == '' OR $order->fname == '' OR $order->lname == '') {
  throw new UserException('Du måste fylla i alla fält', 'Du måste fylla i alla fält. <a href="/pages/foretag_kampanj.php?email=' . $order->email . '&firstname=' . $order->fname . '&lastname=' . $order->lname . '" >Prova igen</a>');
}
$companyId = Foretag::getCompanyIdByCampaignMemberCode($order->compcampcode);
if (is_numeric($companyId) && $companyId > 0) {
  //everthing looks fine sofar, create the user
  try {
    $foretag = Foretag::loadById($companyId);
    $foretagsnyckel = $foretag->generateNycklar(1, true, $foretag->getOrderId());
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
    $medlem->setEpostBekraftad(1); //medlem valid
    $medlem->setLevelId(1);
    $medlem->setForetagsnyckel($foretagsnyckel[0]);
    $medlem->commit();
    $medlem->loggaIn($order->email, $order->pass, true);
  } catch (Exception $e) {
    $msg = $e->getMessage();
    throw new UserException($msg, null, $urlHandler->getUrl('Medlem', URL_CREATE), 'Tillbaka');
  }
} else {
  throw new UserException("Något gick fel", 'Försök igen: <a href="/pages/foretag_kampanj.php?email=' . $order->email . '&firstname=' . $order->fname . '&lastname=' . $order->lname . '" >Prova igen</a>');
}





header("Location: " . '/pages/minsida.php?mmForetagsnyckel=' . $order->nyckel);
?>