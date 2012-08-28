<?php

//for reference see: /pages/foretag_kampanj_code.php
require_once($_SERVER["DOCUMENT_ROOT"] . "/php/init.php");

error_reporting(E_ALL);
ini_set('display_errors', '1');

$order = new stdClass;
!empty($_REQUEST['compcampcode']) ? $order->compcampcode = $_REQUEST['compcampcode'] : $order->compcampcode = '';
$order->compcampcode = mb_convert_case(urldecode($order->compcampcode), MB_CASE_LOWER, "UTF-8");
$order->compcampcode = trim($order->compcampcode);  //trim whitespaces
$order->compcampcode = trim($order->compcampcode, '"');  //trim "
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

$redirPage = $SETTINGS["url"] . "/pages/foretag_kampanj.php?anamn=" . $order->anamn . "&mailone=" . $order->email . '&firstname=' . $order->fname . '&lastname=' . $order->lname . '&co=' . $order->co . '&phone=' . $order->phone . '&street1=' . $order->street1 . '&street2=' . $order->street2 . '&street3=' . $order->street3 . '&zip=' . $order->zip . '&city=' . $order->city;


if ($order->compcampcode == '' OR $order->email == '' OR $order->fname == '' OR $order->lname == '') {
  Misc::logMotiomera("Error action/medlem_foretagskod.php  Fält saknas!  \n Params:\n" . print_r($order, true) . "\n ", 'ERROR');
  $redirPage .= "&msg=fields_missing";
  header('Location: ' . $redirPage);
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
    //header("Location: " . '/pages/minsida.php?mmForetagsnyckel=' . $foretagsnyckel[0]);
    header("Location: " . '/pages/minsida.php');
  } catch (Exception $e) {
    $msg = $e->getMessage();
    Misc::logMotiomera("Exception -  medlem_foretagskod.php  Params:\n" . print_r($order, true) . "\n CompanyId = $companyId \n Foretagsnyckel  \n " . print_r($foretagsnyckel, true) . "\n msg: " . $msg. "\n", 'ERROR');
    $redirPage .= "&msg=unknown_error";
    header('Location: ' . $redirPage);
  }
} else {
  Misc::logMotiomera("Error action/medlem_foretagskod.php  Fel Verifikationskod! \n Params:\n" . print_r($order, true) . "\n CompanyId = $companyId \n Foretagsnyckel  \n " . print_r($foretagsnyckel, true), 'ERROR');
  $redirPage .= "&msg=wrong_code";
  header('Location: ' . $redirPage);
}