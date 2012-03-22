<?php
require $_SERVER["DOCUMENT_ROOT"] . "/php/init.php";
// die('i save');
switch($_REQUEST["table"]){
  case "foretagsnyckel":
		if($USER->getForetagsnyckel(true) == "" && !empty($_POST["foretagsnyckel"])){			
      //echo 'krillo';
			$USER->setForetagsnyckel($_POST["foretagsnyckel"]);
			$USER->commit();
      /*
			if ($USER->getLag() == null)
				$urlHandler->redirect("Foretag", URL_VIEW, $USER->getForetag(true)->getId());
			else
				$urlHandler->redirect("Lag", URL_VIEW, $USER->getLag()->getId());
      }
       * 
       */  
    }
    break;
	case 'fastrutt':
		if(isset($USER)&&!empty($_GET['rid'])){		
			$USER->addStaticRouteToUser($_GET['rid']);			
		}elseif(isset($USER)&&!empty($_GET['mid'])){		
			$USER->removeStaticRouteForUser($_GET['mid']);		
		}
		break;
  case "device":
    try{
      $status = Medlem::loggaIn(trim($_REQUEST["mem"]),trim($_REQUEST["in"]));
      if(!isset($status) || !$status) {
        throw new UserException("Felaktig inloggning", $felInloggString);
      }
      $USER = Medlem::getInloggad();
      $USER->saveBrowserAndIp();

      $a = Aktivitet::loadById($_REQUEST["steg0_aid"]);
      new Steg($USER, $a, date($_REQUEST["steg0_datum"] . " H:i:s"), $_REQUEST["steg0_antal"]);
      header("Location: /pages/device_result.php", true, '301');
      exit;
    }catch(MedlemException $e){
      header("Location: /pages/device_result.php", true, '400');
      exit;
      //throw new UserException("Felaktig inloggning", $felInloggString);
    }





  break;
	case "grupp":
		if(!empty($_POST['publik'])){
			$publik = $_POST['publik'];
		}else{
			$publik = 0;
		}		
		if(isset($_POST["id"])){
			$grupp = Grupp::loadById($_POST["id"]);
			if(isset($_POST["starta"])){
				$grupp->setStart(date("Y-m-d"));
				$grupp->commit();
				$urlHandler->redirect("Grupp", URL_EDIT, $grupp->getId());
			}			
			if ( isset($_POST['startdatum']) && eregi('^[0-9]{4}-[0-9]{2}-[0-9]{2}$', $_POST["startdatum"]) )
			{
				$grupp->setStart($_POST["startdatum"]);
				$grupp->commit();
			}
			if(isset($_POST["tabort"])){
				$grupp->delete();
				$urlHandler->redirect("Grupp", URL_LIST);
			}else{
				$grupp->setPublik($publik);
				$grupp->commit();
			}
		}else{
			if (Grupp::ledigtNamn($_POST["namn"])==true) { //incase javascript disabled
				$grupp = new Grupp($_POST["namn"], $publik);
				$grupp->setStart($_POST["startdatum"]);
				$grupp->commit();
				if (isset($_POST['epost']) && !empty($_POST['epost'])) {
					$grupp->inviteByEmail($_POST['epost']);
				}
				if(isset($_POST['mid'])) {
					foreach($_POST['mid'] as $currMid) {
						$medlem = Medlem::loadById($currMid);
						$grupp->invite($medlem);
					}
				}
				$urlHandler->redirect("Grupp", URL_VIEW, $grupp->getId());
			}
		}
		break;
	case "steg":
		Security::demand(USER);
		$nykommun = false;
		$ajaxOutputDone = false;
		for($i = 0; $i < $_GET["antalsteg"]; $i++){
			$a = Aktivitet::loadById($_GET["steg".$i."_aid"]);
			new Steg($USER, $a, date($_GET["steg".$i."_datum"] . " H:i:s"), $_GET["steg".$i."_antal"],&$nykommun);
		}
		// echo Rutt::isLastOnStatic($USER->getCurrentKommun()->getId(), $USER->getFastRuttId());
		if($nykommun) {
			//echo $USER->getCurrentKommun()->getId()." | ". $USER->getFastRuttId();
			//echo Rutt::isLastOnStatic($USER->getCurrentKommun()->getId(), $USER->getFastRuttId());
			if((!empty($USER)) && ($USER->getUserOnStaticRoute() == true) && (Rutt::isLastOnStatic($USER->getCurrentKommun()->getId(), (int)$USER->getFastRuttId())==true)){
			//echo $USER->getCurrentKommun()->getId();
				$USER->setStaticRuttDone($USER->getFastRuttId());
				if(empty($_GET["ajax"])) {
					throw new UserException("Avklarat en fastutmaning", "Grattis, du har nu gått klart hela den fasta rutten! Som belöning har du fått en fin pokal i troféhyllan på Min sida. Glöm inte att gå till Välj din rutt-sidan för att skapa en ny rutt. Lycka till med ditt fortsatta MotioMerande!");/** ska vara någon trevlig sida här */
				}
				else if(!$ajaxOutputDone) {
					echo "ok_f";
					$ajaxOutputDone = true;
				}
			}
			//die();
			if(empty($_GET["ajax"])) {
				header("location:/pages/nykommun.php");
				exit;
			}
			else if(!$ajaxOutputDone) {
				echo "ok_nykommun";
				$ajaxOutputDone = true;
			}
		}
		if(empty($_GET["ajax"])) {
			$urlHandler->redirect("Medlem", URL_VIEW_OWN);
		}
		elseif(!$ajaxOutputDone) {
		
			echo "ok";
			$ajaxOutputDone = true;
		}
		exit;
		break;
	case "stracka":
		if(@$_POST["target"]) {
			$kommun = Kommun::loadById($_POST["target"]);
		}
		else {
			$kommun = Kommun::loadById($_GET["target"]);
		}
		new Stracka($kommun);
		exit;
		/*if(isset($_GET["target"]))
			$urlHandler->redirect("Medlem", URL_VIEW_OWN);
		else
			$urlHandler->redirect("Rutt", URL_VIEW);*/
		break;		
 	case "stracka_g":		
		$USER->approveTempStrackor();				
		$urlHandler->redirect("Rutt", URL_VIEW);
		break;		
 	case "stracka_r":		
		$USER->cleanTempStrackor();				
		$urlHandler->redirect("Rutt", URL_VIEW);
		break;		
	case "medlem":	
		if($_POST["kid"] != ""){
			$kommun = Kommun::loadById($_POST["kid"]);
		}else{
			$kommun = null;
		}		
		$USER->setFNamn($_POST["fnamn"]);
		$USER->setENamn($_POST["enamn"]);
		$USER->setEpost($_POST["epost"]);
		$USER->setAtkomst($_POST["atkomst"]);
		$USER->setBeskrivning($_POST["beskrivning"]);
		$USER->setRssUrl($_POST['rssUrl']);
		$USER->setKommun($kommun);
		$USER->setCustomerId((isset($_POST["customerId"])?$_POST["customerId"]:0));
		if($_POST["fodelsear"] != ""){
			$USER->setFodelsear($_POST["fodelsear"]);
		}
		if($_POST["andraLosen"] == "1"){
			$USER->setLosenord($_POST["losen"]);
		}
		/* block motiomeramails */
		if (isset($_POST['blockmail'])) {
			if($_POST['blockmail']=='false')
				$USER->setMotiomeraMailBlock('false');
			elseif($_POST['blockmail']=='true') {
				$USER->setMotiomeraMailBlock('true');
			}
		}
		$USER->commit();		
		if (isset($_POST['profilData'])):
			foreach($_POST["profilData"] as $id=>$profilDataVal):
				$USER->setProfilDataVal($id,$profilDataVal);
			endforeach;
		endif;		
		if (isset($_POST['profilDataFritext'])):
			foreach($_POST["profilDataFritext"] as $id=>$profilDataText):
				$USER->setProfilDataText($id,substr(trim($profilDataText),0,40));
				if (strlen(trim($profilDataText))):
					$USER->setProfilDataVal($id, false);
				endif;
			endforeach;
		endif;		
		$notifications = array();
		if (isset($_POST['notifications'])) {
			foreach ($_POST['notifications'] as $notification_id => $value) {
				if ($value === 'on') {
					$notifications[] = $notification_id;
				}
			}
		}	
		$data = Paminnelse_sql::getAktivaIDn($USER);
		Paminnelse_sql::uppdateraAktiva($USER, $notifications);
		if($USER->getForetagsnyckel(true) == "" && !empty($_POST["foretagsnyckel"])){			
			$USER->setForetagsnyckel($_POST["foretagsnyckel"]);
			$USER->commit();
			if ($USER->getLag() == null)
				$urlHandler->redirect("Foretag", URL_VIEW, $USER->getForetag(true)->getId());
			else
				$urlHandler->redirect("Lag", URL_VIEW, $USER->getLag()->getId());
		}
		if(isset($_POST["tab"]))
			$urlHandler->redirect("Medlem", URL_EDIT, $_POST["tab"]);
		else
			$urlHandler->redirect("Medlem", URL_EDIT);		
		break;
	case 'changeStartKommun':
		Security::demand(USER, $USER);
		if (isset($_GET['mid'])) {
			$medlem= Medlem::loadById($_GET['mid']);
			$medlem->setStartKommun($_GET['startkommun']);
			$urlHandler->redirect('Rutt', URL_EDIT);
		} else {
			throw new UserException('Ej ditt konto', 'Det konto du försöker ändra tillhör inte dig');
		}			
		break;
	case 'newcontest':
			//die("jag är här");
		if(!empty($_GET['fid'])) {
			$foretag = Foretag::loadById($_GET['fid']);
			Security::demand(FORETAG, $foretag);
			if(isset($ADMIN) && $ADMIN->isTyp(ADMIN)) { // admins can renew contests without paying
				$foretag->startNewContestSameAsLast();
				$urlHandler->redirect('Foretag', URL_EDIT, $foretag->getId());
			}
			else {
				header("location:/actions/sendorder.php?fid=" . $_GET["fid"] . "&typ=foretag_again");
				exit;
			}
		}
		break;
	case 'randomteams':
		if(!empty($_GET['fid'])){
			$foretag = Foretag::loadById($_GET['fid']);
			Security::demand(FORETAG, $foretag);
			$foretag->startNewContestNewTeams();
			$urlHandler->redirect('Foretag', URL_EDIT, $foretag->getId());			
		}
		break;
	case "foretag":
		if(empty($_GET["id"])){
			Security::demand(ADMIN);
			$kommun = Kommun::loadById($_POST["kid"]);
			$foretag = new Foretag($_POST["namn"], $kommun, $_POST["anamn"], $_POST["losenord"]);
			header("Location: " . $urlHandler->getUrl("Foretag", URL_VIEW, $foretag->getId()));
			exit;
		}else{
			$foretag = Foretag::loadById($_GET["id"]);
			Security::demand(FORETAG, $foretag);
			$kommun = Kommun::loadById(150);  //Ale - legacy
			$foretag->setKommun($kommun);
			if(!empty($_POST["losenord"])){
				$foretag->setLosenord($_POST["losenord"]);
			}
			if(isset($ADMIN) && $ADMIN->isTyp(ADMIN)){
				if(!empty($_POST["anamn"]))
					$foretag->setANamn($_POST["anamn"]);
				if(!empty($_POST["namn"]))
					$foretag->setNamn($_POST["namn"]);
				if(!empty($_POST["startdatum"]))
					$foretag->setStartdatum($_POST["startdatum"]);
			}
			$foretag->commit();
		}
		break;
	case'gaurlag':
		Security::demand(FORETAG);
		if(isset($FORETAG)){
			$foretag = $FORETAG;
		}else{
			$foretag = Foretag::loadById($_GET["fid"]);
		}
		$foretag->gaUr($_GET['id']);
		$urlHandler->redirect("Foretag", "URL_EDIT", array($foretag->getId(), 2));
		break;		
	case "lag":
		if(empty($_GET["id"])){
			Security::demand(FORETAG);
			if(!isset($FORETAG)){
				Security::demand(ADMIN);
				$FORETAG= Foretag::loadById($_GET['fid']);
			}
			$lagnamnList = LagNamn::listUnused($FORETAG);
			$lagnamn = $lagnamnList[array_rand($lagnamnList, 1)];
			$lag = new Lag($FORETAG, $_POST["namn"]);
		}else{
			if(!isset($lag)){
				$lag = Lag::loadById($_GET["id"]);
			}
			Security::demand(FORETAG, $lag->getForetag());
			if(isset($_POST["namn"])){
				$lag->setNamn($_POST["namn"]);
			}
			$lag->commit();		
		}
		$urlHandler->redirect("Lag", "URL_EDIT", $lag->getId());
		break;		
	case "malmanager":
		Security::demand(USER);
		$mal = Mal::loadById($_POST["mid"]);
		$malManager = new MalManager($USER);
		$malManager->addMal($mal);
		$urlHandler->redirect("MalManager", URL_VIEW);
		break;
	case "adressbok":
		Security::demand(USER);
		$adressbok = Adressbok::loadByMedlem($USER);
		$medlem = Medlem::loadById($_GET["mid"]);
		$adressbok->addKontakt($medlem);
		header("Location: " . $_SERVER["HTTP_REFERER"]);
		break;
	case "visningsbild":
		Security::demand(USER);
		$visningsbild = Visningsbild::loadByFilename($_GET["id"]);
		$USER->setVisningsbild($visningsbild);
		$urlHandler->redirect("Visningsbild", URL_LIST);
		break;
	case "minaquiz":
		Security::demand(USER);
		if (empty($_GET["qid"])) {
			// Skapa ett nytt quiz
			$mittQuiz = new MinaQuiz($_POST);
		} else {
			// Ladda quiz från ID
			$mittQuiz = MinaQuiz::loadById($_GET["qid"]);			
			// Ta bort rättigheter
			$db->nonquery("DELETE FROM mm_minaquizGruppAcl WHERE minaquiz_id = " . $mittQuiz->getId());			
			// Kolla om alla grupper har tillgång till quizet
			$tilltrade_alla_grupper = "nej";
			if (isset($_POST["tilltrade_grupper"])) {
				$count = count($_POST["tilltrade_grupper"]);
				for ($x=0;$x<$count;$x++) {
					if ($_POST["tilltrade_grupper"][$x] = "alla") {
						$tilltrade_alla_grupper = "ja";
						unset($_POST["tilltrade_grupper"][$x]);
					} else {
						$mittQuiz->addTilltradeGrupp($_POST["tilltrade_grupper"][$x]);
					}
				}
			}
			// Uppdatera variabler
			$mittQuiz->setNamn($_POST["namn"]);
			$mittQuiz->setTilltrade($_POST["tilltrade"]);
			$mittQuiz->setTilltradeAllaGrupper($tilltrade_alla_grupper);
			$mittQuiz->commit(); // Spara till databasen			
			// Uppdatera frågorna och lägg till nya
			foreach ($_POST['fraga'] as $key => $fraga) {
				$fraga = mysql_real_escape_string($fraga);
				$svar1 = mysql_real_escape_string(isset($_POST['svar_1'][$key]) ? $_POST['svar_1'][$key] : '');
				$svar2 = mysql_real_escape_string(isset($_POST['svar_2'][$key]) ? $_POST['svar_2'][$key] : '');
				$svar3 = mysql_real_escape_string(isset($_POST['svar_3'][$key]) ? $_POST['svar_3'][$key] : '');
				$ratt_svar = mysql_real_escape_string(isset($_POST['ratt_svar'][$key]) ? $_POST['ratt_svar'][$key] : '');
				if (substr_count($key,'new_')) { // Detta är en ny fråga
					switch ($ratt_svar) {
						case 1:
							$mittQuiz->addQuestion($fraga,$svar1,$svar2,$svar3);
							break;
						case 2:
							$mittQuiz->addQuestion($fraga,$svar2,$svar1,$svar3);
							break;
						case 3:
							$mittQuiz->addQuestion($fraga,$svar3,$svar2,$svar1);
							break;
					}
				} else {
					$db->query('UPDATE ' 
					         . MObject::TABLEPREFIX . MinaQuiz::QUIZ_QUESTIONS_TABLE
					         . ' SET fraga = "'.$fraga.'",'
					         . ' svar_1 =     "'.$svar1.'",'
					         . ' svar_2 =     "'.$svar2.'",'
					         . ' svar_3 =     "'.$svar3.'",'
					         . ' ratt_svar =  '.$ratt_svar
					         . ' WHERE id = ' . mysql_real_escape_string($key)
					);
				}
			}
		}
		$urlHandler->redirect("MinaQuiz", URL_LIST);
		break;
	case "fotoalbum":
		if(empty($_GET["fid"])) {
			// skapa nytt fotoalbum
			$fotoalbum = new Fotoalbum($_POST);
		} else {
			// ladda fotoalbum
			$fotoalbum = Fotoalbum::loadById($_GET["fid"]);
			// ta bort tidigare tillgång
			$db->nonquery("DELETE FROM mm_fotoalbumGruppAcl WHERE fotoalbum_id = " . $fotoalbum->getId());
			// kolla om alla grupper har tillgång till albumet
			$tilltrade_alla_grupper = "nej";
			if (isset($_POST["tilltrade_grupper"])) {
				$count = count($_POST["tilltrade_grupper"]);
				for($x=0;$x<$count;$x++) {
					if ($_POST["tilltrade_grupper"][$x] == "alla") {
						$tilltrade_alla_grupper = "ja";
						unset($_POST["tilltrade_grupper"][$x]);
					} else {
						$fotoalbum->addTilltradeGrupp($_POST["tilltrade_grupper"][$x]);
					}
				}
			}
			// uppdatera
			$fotoalbum->setNamn(Security::escape($_POST["namn"]));
			$fotoalbum->setBeskrivning(Security::escape($_POST["beskrivning"]));
			$fotoalbum->setTilltrade(Security::escape($_POST["tilltrade"]));
			$fotoalbum->setTilltradeAllaGrupper($tilltrade_alla_grupper);
			if (isset($_POST["tilltrade_foretag"])) {
				$fotoalbum->setTilltradeForetag(Security::escape($_POST["tilltrade_foretag"]));
			} else {
				$fotoalbum->setTilltradeForetag("nej");
			}
			$fotoalbum->commit();
		}
		$urlHandler->redirect("Fotoalbum", URL_VIEW, $fotoalbum->getId());
		break;
	case "fotoalbumbild":
		if (isset($_GET["id"])) {
			// uppdatera bilden
			$db->nonquery("	UPDATE
								mm_fotoalbumbild
							SET
								namn = '" . Security::escape($_POST["namn"]) . "',
								beskrivning = '" . Security::escape($_POST["beskrivning"]) . "',
								fotoalbum_id = '" . Security::escape($_POST["fotoalbum"]) . "'
							WHERE
								id = '" . $_GET["id"] . "'
			");
			if (!empty($_POST['kid']) || !empty($_GET['id'])) {
				$tag = new Tagg(array('objekt_table' => 'mm_fotoalbumbild', 'objekt_id' => $_GET["id"], 'objekt_namn' => $_POST['namn'], 'tag_table' => 'mm_kommun', 'tag_id' => $_POST['kid'], 'medlem_id' => $USER->getId()));
			}
			$urlHandler->redirect("Fotoalbum", URL_VIEW, $_GET["fid"]);
		} else {
			// uppdatera namn & beskrivningar på fotona
			foreach($_POST["namn"] as $id => $namn) {
				if (isset($_POST["fotoalbum"][$id])) {
					$album_sql = ", fotoalbum_id = '" . Security::escape($_POST["fotoalbum"][$id]) . "'";
				} else {
					$album_sql = "";
				}
				$db->nonquery("	UPDATE
									mm_fotoalbumbild
								SET
									namn = '" . Security::escape($_POST["namn"][$id]) . "',
									beskrivning = '" . Security::escape($_POST["beskrivning"][$id]) . "'
									$album_sql
								WHERE
									id = '" . $id . "'
				");
			}
			if (!empty($_POST['kid'])) {
				$tag = new Tagg(array('objekt_table' => 'mm_fotoalbumbild', 'objekt_id' => $id, 'objekt_namn' => $_POST['namn'], 'tag_table' => 'mm_kommun', 'tag_id' => $_POST['kid'], 'medlem_id' => $USER->getId()));
			}
			$urlHandler->redirect("Fotoalbum", URL_LIST);
		}
		break;
	case "anslagstavlarad":
	
		if(isset($_POST["aid"])) {
		
			$anslagstavla = Anslagstavla::loadById($_POST["aid"]);
			
			$anslagstavla->addRad($_POST["atext"]);
		}	
		break;
	case "newkeys":
		if (isset($_GET['foretagsid'])&& isset($_GET['orderid']) && isset($_GET['numkeys']) && (int)$_GET['numkeys']>0 && Security::authorized(ADMIN)) {
			$keys=(int)$_GET['numkeys'];
			$foretag=Foretag::loadById($_GET['foretagsid']);
			if(isset($foretag)) {
				$foretag->generateNycklar($keys,true, $_GET['orderid']);
			}
		}
		$urlHandler->redirect("Foretag", URL_EDIT,array((int)$_GET['foretagsid'],3));
		break;		
	case "kommundialekt":
		$kommun = Kommun::loadById($_POST["kid"]);		
		try{
			$dialekt = new Kommundialekt($kommun, $_POST["kon"], $_POST["alder"], $_POST["url"]);
		}catch(KommundialektException $e){			
			if($e->getCode() == -1){
				$msg = "Du måste ange kön.";
			}else if($e->getCode() == -2){
				$msg = "Du måste ange ålder.";
			}else if($e->getCode() == -3){
				$msg = "Sökvägen till ljudfilen är ogiltig.";
			}else if($e->getCode() == -4){
				$msg = "Felaktig filtyp.";
			}else if($e->getCode() == -5){
				$msg = "Filen är för stor";
			}else if($e->getCode() == -6){
				$msg = "Filen finns redan i vår databas.";
			}else{
				throw $e;
			}			
			new UserException("Ett fel påträffades", $msg, $urlHandler->getUrl("Kommundialekt", URL_CREATE, $kommun->getId()));
		}		
		new UserException("Ljudfil skickad!", "Tack för att du laddade upp en ljudfil!<br /> Ljudfilen kommer att synas på kommunsidan så fort den godkänts av en moderator.", $urlHandler->getUrl("Kommun", "URL_VIEW", $kommun->getNamn()), "Tillbaks till kommunsidan");
}
if(empty($_GET["redirect"])){
	header("Location: " . $_SERVER["HTTP_REFERER"]);
} else {
	header("Location: " . $_GET["redirect"]);
}
?>
