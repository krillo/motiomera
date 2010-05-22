<?php
	require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

	//these variables apply for every option
	$compAffCode = "";
	$kanal = "";
	if(isset($_POST["compaffcode"])){
		$compAffCode = $_POST["compaffcode"];
	}
	if(isset($_POST["kanal"])){
		$kanal = $_POST["kanal"];
	}

  switch (true){
    case (isset($_REQUEST["typ"]) && $_REQUEST["typ"] == "medlem"):
    $typ = "medlem";
    $kontotyp = $_REQUEST["kontotyp"];
      if(isset($_POST["medlem_id"])){
        $objekt = Medlem::loadById($_POST["medlem_id"]);
      } else {
        if(isset($USER)){
          $objekt = $USER;
        } else {
          throw new UserException("Ett fel har uppstått", "Ett fel uppstod när beställningen skulle skapas. Var god försök igen senare.");
        }
      }
      $antal = 1;
      $order = new Order($typ, $objekt, $kontotyp, $antal, $kanal, $compAffCode, 0);
      $order->setMedlem($objekt);
      $order->commit();
      $order->gorUppslag();
      break;
    case (isset($_REQUEST["typ"]) && $_REQUEST["typ"] == "foretag"):
			if(isset($_POST["startdatumRadio"])){
				if($_POST["startdatumRadio"] != 'egetdatum'){
					$startdatum = $_POST["startdatumRadio"];
				} else {
					$startdatum = $_POST["startdatum"];
				}
			}
			$typ = "foretag";
			$kampanjkod = "";
			$antal = "";
			$kommun = Kommun::loadById(150);  //Ale - legacy
			$foretagLosen = Foretag::skapaLosen();  //a new is created in api/order if a purchase is made
			$isValid = 0;
			$objekt = new Foretag($_REQUEST["namn"], $kommun, $foretagLosen, $startdatum, $kanal, $compAffCode, $isValid);  //last param is "Order::isValid" and is set to 0 - i.e. not a valid order yet
			$objekt->setTempLosenord($foretagLosen);
			$objekt->commit();

			if(isset($_REQUEST['$camparray'])){
				$camparray = $_REQUEST['$camparray'];
				$refId = "first_iteration";
				foreach($camparray as $ord => $arr){
					if($arr["antal"] > 0){
				    //$objekt->generateNycklar($arr["antal"], true);
						if($refId == "first_iteration"){    //first order row
							$order = new Order($typ, $objekt, $arr["kampanjkod"], $arr["antal"], $kanal, $compAffCode, 0);
							$refId = $order->getRefId();    //use the same refId for all order rows
						} else {         //all other order rows
							$order = Order::__constructOrderWithSameRefId($typ, $objekt, $arr["kampanjkod"], $arr["antal"], $kanal, $compAffCode, 0, false,  $refId);
						}
						$order->setForetag($objekt);
						$order->setCompanyName($objekt->getNamn());
						$order->commit();
						$kampanjkod .= $arr["kampanjkod"];	//build request string
						$kampanjkod .= ";";
						$antal .=  $arr["antal"];
						$antal .= ";";
					}
				}
				if(strlen($kampanjkod) > 0 ){   //remove last semicolon
					$kampanjkod = substr($kampanjkod, 0, strlen($kampanjkod)-1);
				}
				if(strlen($antal) > 0 ){
					$antal = substr($antal, 0, strlen($antal)-1);
				}
//Order::krilloLogToFile("refid: $refId, kampanjkod: $kampanjkod, antal: $antal");
				//$objekt->genereraLag();
				$order->gorUppslag($kampanjkod, $antal);		//send the order
			} else {
				throw new OrderException("" , -1);
			}
			break;
    case (isset($_REQUEST["typ"]) && $_REQUEST["typ"] == "foretag_tillagg"):
        	$typ = "foretag_tillagg";
          $objekt = Foretag::loadById($_REQUEST["fid"]);
          $kanal = $objekt->getKanal();
          $compAffCode = $objekt->getCompAffCode();
        	$kampanjkod = "";
          $antal = "";
          if(isset($_REQUEST['$camparray'])){
            $camparray = $_REQUEST['$camparray'];
				    $refId = "first_iteration";
				    foreach($camparray as $ord => $arr){
              if($arr["antal"] > 0){
                //$objekt->generateNycklar($arr["antal"], true);
						    if($refId == "first_iteration"){    //first order row
						      $order = new Order($typ, $objekt, $arr["kampanjkod"], $arr["antal"], $kanal, $compAffCode, 0);
						      $refId = $order->getRefId();    //use the same refId for all order rows
						    } else {         //all other order rows
							    $order = Order::__constructOrderWithSameRefId($typ, $objekt, $arr["kampanjkod"], $arr["antal"], $kanal, $compAffCode, 0, false,  $refId);
						    }
						    $order->setForetag($objekt);
						    $order->setCompanyName($objekt->getNamn());
						    $order->commit();
						    $kampanjkod .= $arr["kampanjkod"];  //build request string
						    $kampanjkod .= ";";
						    $antal .=  $arr["antal"];
						    $antal .= ";";
				      }
				    }
				    if(strlen($kampanjkod) > 0 ){    //remove last semicolon
					   $kampanjkod = substr($kampanjkod, 0, strlen($kampanjkod)-1);
				    }
				    if(strlen($antal) > 0 ){
				      $antal = substr($antal, 0, strlen($antal)-1);
				    }
//Order::krilloLogToFile("refid: $refId, kampanjkod: $kampanjkod, antal: $antal");
				    //$objekt->genereraLag();
				    $order->gorUppslag($kampanjkod, $antal);   //send the order
		      } else {
				    throw new OrderException("" , -1);
		      }
        break;
      case (isset($_REQUEST["typ"]) && $_REQUEST["typ"] == "foretag_again"):  // renewal of company contest        
        $typ = "foretag_again";
        $objekt = Foretag::loadById($_REQUEST["fid"]);
        $kontotyp = "RE04";
        $antal = $objekt->getAntalAnstallda();
        $order = new Order($typ, $objekt, $kontotyp, $antal, $kanal, $compAffCode, 0);
        $order->setForetag($objekt);
        $order->setCompanyName($objekt->getNamn());        
        $order->commit();
        $order->gorUppslag();
        break;
      default :
        $errMess = "Felaktig ordertyp ";
        if(isset($_REQUEST["typ"])){
          $errMess = $errMess . "  ->" . $_REQUEST["typ"] . "<-";
        }
        throw new OrderException($errMess , -1);
        break;
      }


	//last param is "Order::isValid" and is set to 0 - i.e. not a valid order until status >= 20
	//$order = new Order($typ, $objekt, $kontotyp, $antal, $kanal, $compAffCode, 0);

/*
	//update the Foretag object
	switch (true){
	  
    //case (isset($_REQUEST["typ"]) && $_REQUEST["typ"] == "medlem"):
    //  $order->setMedlem($objekt);
      //$order->commit();
    	//$order->gorUppslag();
    //  break;
      
    case (isset($_REQUEST["typ"]) && $_REQUEST["typ"] == "foretag"):
      //$order->setForetag($objekt);
      //$order->setCompanyName($objekt->getNamn());
    break;
    case (isset($_REQUEST["typ"]) && $_REQUEST["typ"] == "foretag_tillagg"):
      //$order->setForetag($objekt);
      //$order->setCompanyName($objekt->getNamn());
      break;
    case (isset($_REQUEST["typ"]) && $_REQUEST["typ"] == "foretag_again"):
      $order->setForetag($objekt);
      $order->setCompanyName($objekt->getNamn());
      break;
    default :
      $errMess = "Felaktig ordertyp ";
      if(isset($_REQUEST["typ"])){
        $errMess = $errMess . " ->" . $_REQUEST["typ"] . "<-";
      }
      throw new OrderException($errMess , -1);
      break;
	}
*/

	//$order->commit();
	//$order->gorUppslag();

?>