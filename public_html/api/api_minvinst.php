<?php

/* ajax call 
 * krillo 2012-09-24
 */
require_once($_SERVER["DOCUMENT_ROOT"] . "/php/init.php");
error_reporting(E_ALL);
ini_set('display_errors', '1');

global $USER;
$order = new stdClass;
!empty($_REQUEST['id']) ? $order->id = $_REQUEST['id'] : $order->id = '';
!empty($_REQUEST['firstname']) ? $order->fname = $_REQUEST['firstname'] : $order->fname = '';
!empty($_REQUEST['lastname']) ? $order->lname = $_REQUEST['lastname'] : $order->lname = '';
!empty($_REQUEST['co']) ? $order->co = $_REQUEST['co'] : $order->co = '';
!empty($_REQUEST['phone']) ? $order->phone = $_REQUEST['phone'] : $order->phone = '';
!empty($_REQUEST['street1']) ? $order->street1 = $_REQUEST['street1'] : $order->street1 = '';
!empty($_REQUEST['zip']) ? $order->zip = $_REQUEST['zip'] : $order->zip = '';
!empty($_REQUEST['city']) ? $order->city = $_REQUEST['city'] : $order->city = '';
!empty($_REQUEST['magid']) ? $order->magid = $_REQUEST['magid'] : $order->magid = '';
!empty($_REQUEST['magname']) ? $order->magname = $_REQUEST['magname'] : $order->magname = '';

if ($USER->getId() == $order->id) {  //check for fraud
  //if ($USER->getVeckotavlingStatus == 1) {       // let the user choose as many times as she likes, the link to the page will disapear ones status is 2..
    $USER->setFNamn($order->fname);
    $USER->setENamn($order->lname);
    $USER->setCo($order->co);
    $USER->setAddress($order->street1);
    $USER->setZip($order->zip);
    $USER->setCity($order->city);
    $USER->setPhone($order->phone);
    $result = $USER->acceptVeckoVinst($order->magid, $order->magname);
    $USER->commit();
    echo $result;
  //} else {
  //  echo "redan valt";
  //}
} else {
  echo "fel användare";
}