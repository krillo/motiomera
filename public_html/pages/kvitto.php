<?php
include $_SERVER["DOCUMENT_ROOT"]  . "/php/init.php";

$smarty = new MMSmarty();
$smarty->assign("pagetitle", "Kvitto");


try
{
	if(isset($_REQUEST["RefId"])){
		$refId = $_REQUEST["RefId"];
	}else{
		throw new OrderException("RefId saknas i anropet", -8);	
	}
	$i = 1; 
	$orderItemList = array();	
	$orderItemList = Order::listOrderDataByRefId($refId);	//can be more than one order row
	$order = Order::loadByRefId($_GET["RefId"]);
}catch(OrderException $e){
	if($e->getCode() == -5)
		throw new UserException("Kvittot har gått ut", "Det här kvittot har gått ut och kan därför inte längre visas.");
	else
		throw $e;
}


$orderTyp = $order->getTyp();
if($orderTyp != "medlem"){
    $foretag = $order->getForetag();        
    $orderList = array();
    $orderList["refId"] = $order->getRefId();
    $orderList["items"] = $order->getItems();
    $orderList["orderId"] = $order->getOrderId();
    $orderList["date"] = $order->getDate();
    $orderList["price"] = $order->getPrice();
    $orderList["quantity"] = $order->getQuantity();
    $orderList["item"] = $order->getItem();
    $orderList["magazineId"] = $order->getMagazineId();
    $orderList["payment"] = $order->getPayment();
    $orderList["sum"] = $order->getSum();
    $orderList["typ"] = $order->getTyp();
    $orderList["id"] = $order->getId();
}

switch (true)
{
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
		$pro_order = $order->getMedlem()->getSenastInloggad()=="0000-00-00 00:00:00"?false:true;
    $orderList["pro_order"] = $pro_order;		
		break;
} 	

$smarty->assign("orderList", $orderList);
$smarty->assign("orderItemList", $orderItemList);

$smarty->display('kvitto.tpl');

?>