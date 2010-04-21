<?php
	require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");	  	
	
	//log order to file
	$file = LOG_DIR . "/order_api.log";    
	try { 	
			$fh = fopen($file, 'a');	
			$text = "\n\n" . date("Y-m-d H:i:s") . " CALL FROM: " . $_SERVER["REMOTE_ADDR"] . "\n";
			$text .= "==================================================\n";
			$text .= 'Post parameters: ' . print_r($_POST, true);		
			fwrite($fh, $text);
		} catch (Exception $e) {
			echo 'order.php - Kunde inte skriva till filen:' ,  $e->getMessage(), "\n";
		}		
		
	foreach($_POST as $key=>$value){
		$_POST[$key] = utf8_encode($value);
	}
	
	//find out what order type. It is not submited from prenshop 	
	$order = Order::loadByRefId($_POST["RefId"]);
	
	switch ($order->getTyp()){
	case ("medlem"):
		$order = Order::loadByRefId($_POST["RefId"]);	
		$order->setRefId($_POST["RefId"]); 
		$order->setItems($_POST["Items"]); 
		$order->setOrderId($_POST["OrderId"]); 
		$order->setDate($_POST["Date"]); 
		$order->setPayment($_POST["Payment"]); 
		$order->setPrice($_POST["Price0"]); 
		$order->setQuantity($_POST["Quantity0"]); 
		$order->setItem($_POST["Item0"]); 
		$order->setMagazineId($_POST["MagazineId0"]); 
		$order->setCampaignId($_POST["CampaignId0"]);
		$order->setOfferId($_POST["OfferId0"]); 
		$order->setSum($_POST["Sum"]);		 
		$order->setOrderStatus(Order::ORDERSTATUS_PAID); //Ordern har blivit betald i prenshopen - lyft orderstausen och sätt isValid
		$order->setIsValid(1);			
		$order->commit();
		
		$text2 = "\nOrder object - medlem:\n" . print_r($order, true);	
		fwrite($fh, $text2);	
		$order->getMedlem()->handleOrder($order);		
		$order->sendEmailReciept();
    echo "OK";
 		break;
	case ("foretag"):
		$orderItemList = array();    //id, item, price, quantity		        	
	  for($i = 0; isset($_POST["CampaignId$i"]); $i++){       
			$order = Order::loadByRefId($_POST["RefId"], $_POST["CampaignId$i"]);		
	    $order->setRefId($_POST["RefId"]); 
			$order->setItems($_POST["Items"]); 
			$order->setOrderId($_POST["OrderId"]); 
			$order->setDate($_POST["Date"]); 
			$order->setPayment($_POST["Payment"]); 
			$order->setPrice($_POST["Price$i"]); 
			$order->setQuantity($_POST["Quantity$i"]); 
			$order->setItem($_POST["Item$i"]); 
			$order->setMagazineId($_POST["MagazineId$i"]); 
			$order->setCampaignId($_POST["CampaignId$i"]);
			$order->setOfferId($_POST["OfferId$i"]); 
			$order->setSum($_POST["Sum"]);		 
			$order->setOrderStatus(Order::ORDERSTATUS_PAID); //Ordern har blivit betald i prenshopen - lyft orderstausen och sätt isValid
			$order->setIsValid(1);	
			$order->commit();						
      $foretag = $order->getForetag();
      $foretag->generateNycklar($_POST["Quantity$i"], true, $order->getId());
			//store some data for confirmation email
			$orderItemList[$i]["id"] = 0;   //not used
      $orderItemList[$i]["item"] = $_POST["Items"];
      $orderItemList[$i]["price"] = $_POST["Price$i"];
      $orderItemList[$i]["quantity"] = $_POST["Quantity$i"];               
	  }
	  $foretag = $order->getForetag();        	    
		$foretag->setCompanyName($_POST["PayerCompany"]); 
		$foretag->setPayerName($_POST["PayerName"]); 
		$foretag->setPayerAddress($_POST["PayerAddress"]); 
		$foretag->setPayerCo($_POST["PayerCo"]); 
		$foretag->setPayerZipCode($_POST["PayerZipCode"]); 
		$foretag->setPayerCity($_POST["PayerCity"]); 
		$foretag->setPayerEmail($_POST["PayerEmail"]);
		$foretag->setPayerPhone($_POST["PayerPhone"]); 
		$foretag->setPayerMobile($_POST["PayerMobile"]); 
		$foretag->setPayerCountry($_POST["PayerCountry"]); 
		$foretag->setReciverName($_POST["ReciverName0"]); 	
		$foretag->setReciverAddress($_POST["ReciverAddress0"]); 
		$foretag->setReciverCo($_POST["ReciverCo0"]); 
		$foretag->setReciverZipCode($_POST["ReciverZipCode0"]); 
		$foretag->setReciverCity($_POST["ReciverCity0"]); 
		$foretag->setReciverEmail($_POST["ReciverEmail0"]); 
		$foretag->setReciverPhone($_POST["ReciverPhone0"]);
		$foretag->setReciverMobile($_POST["ReciverMobile0"]); 
		$foretag->setReciverCountry($_POST["ReciverCountry0"]);						
		$foretag->setIsValid(1);  //foretag isValid
		//$foretagLosen = Foretag::skapaLosen();
		//$foretag->setLosenord($foretagLosen);
		//$foretag->setTempLosenord($foretagLosen);	
    $foretag->genereraLag();      //generate lag, only when typ = foretag  
		$foretag->commit();
		
    $text3 = "\nOBJECT - Foretagskop:\n";  
		$text3 .= print_r($order, true);	
    fwrite($fh, $text3);  		
		$foretag->sendEmailReciept($order->getTyp(), $_POST["RefId"]);
    echo "OK";
	 	break; 		
	case ("foretag_tillagg"):
	  for($i = 0; isset($_POST["CampaignId$i"]); $i++){       
			$order = Order::loadByRefId($_POST["RefId"], $_POST["CampaignId$i"]);		
	    $order->setRefId($_POST["RefId"]); 
			$order->setItems($_POST["Items"]); 
			$order->setOrderId($_POST["OrderId"]); 
			$order->setDate($_POST["Date"]); 
			$order->setPayment($_POST["Payment"]); 
			$order->setPrice($_POST["Price$i"]); 
			$order->setQuantity($_POST["Quantity$i"]); 
			$order->setItem($_POST["Item$i"]); 
			$order->setMagazineId($_POST["MagazineId$i"]); 
			$order->setCampaignId($_POST["CampaignId$i"]);
			$order->setOfferId($_POST["OfferId$i"]); 
			$order->setSum($_POST["Sum"]);		 
			$order->setOrderStatus(Order::ORDERSTATUS_PAID); //Ordern har blivit betald i prenshopen - lyft orderstausen och sätt isValid
			$order->setIsValid(1);  			
			$order->commit();						
      $foretag = $order->getForetag();     
      $foretag->generateNycklar($_POST["Quantity$i"], true, $order->getId());           			
	  }
	  $foretag = $order->getForetag();        	    
		$foretag->setCompanyName($_POST["PayerCompany"]); 
		$foretag->setPayerName($_POST["PayerName"]); 
		$foretag->setPayerAddress($_POST["PayerAddress"]); 
		$foretag->setPayerCo($_POST["PayerCo"]); 
		$foretag->setPayerZipCode($_POST["PayerZipCode"]); 
		$foretag->setPayerCity($_POST["PayerCity"]); 
		$foretag->setPayerEmail($_POST["PayerEmail"]);
		$foretag->setPayerPhone($_POST["PayerPhone"]); 
		$foretag->setPayerMobile($_POST["PayerMobile"]); 
		$foretag->setPayerCountry($_POST["PayerCountry"]); 
		$foretag->setReciverName($_POST["ReciverName0"]); 	
		$foretag->setReciverAddress($_POST["ReciverAddress0"]); 
		$foretag->setReciverCo($_POST["ReciverCo0"]); 
		$foretag->setReciverZipCode($_POST["ReciverZipCode0"]); 
		$foretag->setReciverCity($_POST["ReciverCity0"]); 
		$foretag->setReciverEmail($_POST["ReciverEmail0"]); 
		$foretag->setReciverPhone($_POST["ReciverPhone0"]);
		$foretag->setReciverMobile($_POST["ReciverMobile0"]); 
		$foretag->setReciverCountry($_POST["ReciverCountry0"]);						
		$foretag->setIsValid(1);  //foretag isValid
		//$foretagLosen = Foretag::skapaLosen();
		//$foretag->setLosenord($foretagLosen);
		//$foretag->setTempLosenord($foretagLosen);	  		
		$foretag->commit();
				
    $text3 = "\nOBJECT - Tillaggsorder:\n";  
    $text3 .= print_r($order, true);  
    fwrite($fh, $text3);      
		$foretag->sendEmailReciept($order->getTyp(), $_POST["RefId"]);
		echo "OK";
	 	break; 		
	case ("foretag_again"):
		$order->getForetag()->startNewContestSameAsLast();
		$order->setOrderStatus(Order::ORDERSTATUS_RENEWED); // orderstatus sätts till att ordern är förnyad och klar
		$order->commit();
    $text3 = "\nOBJECT - Omstart av foretagstavling:\n";  
    $text3 .= print_r($order, true);      
		fwrite($fh, $text3);
    $foretag->sendEmailReciept($order->getTyp(), $_POST["RefId"]);		
    echo "OK";				
 		break; 		
 	default :   
		throw new OrderException("Felaktigt ordertyp från prenshop: " . $order, -7);	
 		break;
} 	
	
?>