<?php

include $_SERVER["DOCUMENT_ROOT"] . "/php/init.php";

error_reporting(E_ALL);
ini_set('display_errors', '1');

$refId = '';
try {
  if (isset($_REQUEST["TOKEN"])) {
    $refId = $_REQUEST["TOKEN"];
  } else {
    throw new OrderException("Token saknas i anropet", -8);
  }
} catch (OrderException $e) {
  if ($e->getCode() == -8) {
    throw new UserException("Kvittot har gått ut (1)", "Det här kvittots giltighetstid har gått ut och kan därför inte längre visas.");
  } else {
    throw $e;
  }
}


$msg = "\n\tToken/RefId: " . $refId;
$orderItemList = Order::listOrderDataByRefId($refId); //can be more than one order row
$order = Order::loadByRefId($refId); //find out what order type, load the first order row

if (!empty($order) && !empty($orderItemList)) {
  if ($order->getOrderStatus() < Order::ORDERSTATUS_CUST_NO) {  //check one of the rows that the order isn't allready handled 
    switch ($order->getTyp()) {
      case ("medlem"):
        $order = Order::loadByRefId($refId);
        $order->setOrderStatus(Order::ORDERSTATUS_CUST_NO); //The Order has been payed at payson - to status 30 (status does not aply any more)
        $order->setIsValid(1);
        $order->commit();
        $medlem = Medlem::loadById($order->getMedlemId());
        $msg .= "\nTyp: Medlem \nId: " . $order->getMedlemId() . "\nNamn: " . $medlem->getFNamn() . ' ' . $medlem->getENamn() . "\nEpost: " . $medlem->getEpost();
        //$msg .= "\nTelefon: " . $medlem->getPayerPhone() . 
        $msg .= "\nip: " . $order->getIp() . "\n" . print_r($orderItems, true);
        $order->getMedlem()->handleOrder($order);
        $order->sendEmailReciept();
        break;
      case ("foretag"):
        $orderItems = Order::listOrderDataByRefId($refId);
        $foretag = $order->getForetag();
        $msg .= "\n\tTyp: Foretag \n\tId: " . $foretag->getId() . "\n\tNamn: " . $foretag->getNamn() . "\n\tEpost: " . $foretag->getPayerEmail();
        $msg .= "\n\tTelefon: " . $foretag->getPayerPhone() . "\n\tip: " . $order->getIp() . "\n\t" . print_r($orderItems, true);
        Misc::logMotiomera($msg, 'INFO', 'order');
        foreach ($orderItems as $orderItem) {
          $order = Order::loadById($orderItem['id']);
          $order->setOrderStatus(Order::ORDERSTATUS_CUST_NO); //The Order has been payed at payson - to status 30 (status does not aply any more)
          $order->setIsValid(1);
          $order->commit();
          $foretag = $order->getForetag();
          $foretag->generateNycklar($order->getQuantity(), true, $order->getId());
          $paytype = $order->getPayment();
        }
        $foretag->setIsValid(1); //foretag isValid
        $foretag->genereraLag(); //generate lag, only when typ = foretag  
        $foretag->commit();
        $foretag->sendEmailReciept($order->getTyp(), $refId);
        if($paytype == 'faktura'){
          $foretag->createFakturaFile($refId);
          
        }
        break;
      case ("foretag_tillagg"):
        $orderItems = Order::listOrderDataByRefId($refId);
        $foretag = $order->getForetag();
        $msg .= "\nTyp: Foretag - tillaggsorder \nId: " . $foretag->getId() . "\nNamn: " . $foretag->getNamn() . "\nEpost: " . $foretag->getPayerEmail();
        $msg .= "\nTelefon: " . $foretag->getPayerPhone() . "\nip: " . $order->getIp() . "\n: " . print_r($orderItems, true);
        Misc::logMotiomera($msg, 'INFO', 'order');
        foreach ($orderItems as $orderItem) {
          $order = Order::loadById($orderItem['id']);
          $order->setOrderStatus(Order::ORDERSTATUS_CUST_NO); //The Order has been payed at payson - to status 30 (status does not aply any more)
          $order->setIsValid(1);
          $order->commit();
          $foretag = $order->getForetag();
          $foretag->generateNycklar($order->getQuantity(), true, $order->getId());
        }
        $foretag->commit();
        $foretag->sendEmailReciept($order->getTyp(), $refId);
        break;
      case ("foretag_again"):
        $order->getForetag()->startNewContestSameAsLast();
        $order->setOrderStatus(Order::ORDERSTATUS_RENEWED); // orderstatus sätts till att ordern är förnyad och klar
        $order->commit();
        $msg .= "\nTyp: Foretag - Omstart av foretagstavling \nId: " . $foretag->getId() . "\nNamn: " . $foretag->getNamn() . "\nEpost: " . $foretag->getPayerEmail();
        $msg .= "\nTelefon: " . $foretag->getPayerPhone() . "\nip: " . $order->getIp() . "\n: " . print_r($orderItems, true);
        Misc::logMotiomera($msg, 'INFO', 'order');
        $foretag->sendEmailReciept($order->getTyp(), $refId);
        break;
      default :
        throw new OrderException("Felaktigt ordertyp från prenshop: " . $order, -7);
        break;
    }







    /* -----------------------------------------
     * continue with the page printout data
     * ----------------------------------------- */
    $orderTyp = $order->getTyp();
    if ($orderTyp != "medlem") {
      $foretag = $order->getForetag();
      $orderList = array();
      $orderList["refId"] = $order->getRefId();
      $orderList["items"] = $order->getItems();
      $orderList["orderId"] = $order->getOrderId();
      $orderList["date"] = $order->getDate();
      $orderList["price"] = $order->getPrice();
      $orderList["quantity"] = $order->getQuantity();
      $orderList["item"] = $order->getItem();
      $orderList["payment"] = $order->getPayment();
      $orderList["sum"] = $order->getSum();
      $orderList["sumMoms"] = $order->getSumMoms();
      $orderList["orderRefCode"] = $order->getOrderRefcode();
      $orderList["typ"] = $order->getTyp();
      $orderList["id"] = $order->getId();
      $orderList["orderId"] = $foretag->getOrderId();
    }

    switch (true) {
      case ($orderTyp == "foretag"):
        $orderList["foretagLosen"] = $foretag->getTempLosenord();
      //continue
      case ($orderTyp == "foretag_again" || $orderTyp == "foretag_tillagg"):
        $orderList["companyName"] = $foretag->getCompanyName();
        $orderList["foretagANamn"] = $foretag->getANamn();
        $orderList["foretagsId"] = $foretag->getId();
        $orderList["startDatum"] = $foretag->getStartdatum();
        $orderList["pro_order"] = false;
        $orderList["payerName"] = $foretag->getPayerName();
        $orderList["payerAddress"] = $foretag->getPayerAddress();
        $orderList["payerCo"] = $foretag->getPayerCo();
        $orderList["payerZipCode"] = $foretag->getPayerZipCode();
        $orderList["payerCity"] = $foretag->getPayerCity();
        $orderList["payerEmail"] = $foretag->getPayerEmail();
        $orderList["payerPhone"] = $foretag->getPayerPhone();
        $orderList["payerMobile"] = $foretag->getPayerMobile();
        $orderList["payerCountry"] = $foretag->getPayerCountry();
        $orderList["reciverCompanyName"] = $foretag->getReciverCompanyName();
        $orderList["reciverName"] = $foretag->getReciverName();
        $orderList["reciverAddress"] = $foretag->getReciverAddress();
        $orderList["reciverCo"] = $foretag->getReciverCo();
        $orderList["reciverZipCode"] = $foretag->getReciverZipCode();
        $orderList["reciverCity"] = $foretag->getReciverCity();
        $orderList["reciverEmail"] = $foretag->getReciverEmail();
        $orderList["reciverPhone"] = $foretag->getReciverPhone();
        $orderList["reciverMobile"] = $foretag->getReciverMobile();
        $orderList["reciverCountry"] = $foretag->getReciverCountry();
        break;
      default :   //pro order   krillo 090604: typ.mm_order is not set when it is an pro order... (old Farm code)
        $pro_order = $order->getMedlem()->getSenastInloggad() == "0000-00-00 00:00:00" ? false : true;
        $orderList["pro_order"] = $pro_order;
        break;
    }



    $smarty = new MMSmarty();
    $smarty->assign("pagetitle", "Kvitto");
    $smarty->assign("orderList", $orderList);
    $smarty->assign("orderItemList", $orderItemList);
    $smarty->display('kvitto.tpl');
  } else {
    $msg .= "\n\tNågon försökte ladda kvittosidan igen - avbrutet!";
    Misc::logMotiomera($msg, 'WARN', 'order');
    throw new UserException("Kvittot har gått ut (2)", "Det här kvittots giltighetstid har gått ut och kan därför inte längre visas.");
  }
}
?>