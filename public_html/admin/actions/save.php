<?php

include $_SERVER["DOCUMENT_ROOT"]  . "/php/init.php";


switch($_GET["table"]){
	
	case'fastautmaningar':
		$abroad = '';
		if(isset($_POST['abroad'])) {
			$abroad = 'true';
		}
		//print_r($_POST);
		//print_r($_FILES);
		//die('här');
		$id = Rutt::addFastRutt($_POST['regionName'], $_POST['routes'],$abroad);
		$path = $_SERVER['DOCUMENT_ROOT']. "/files/staticroute/".$id;
		if (isset($_FILES['some_name'])) {
			$bild = new Bild($_FILES['some_name'], $path);
			$bild->resize(330);
			$bild->approve($path);
		}
		$urlHandler->redirect("FastaUtmaningar", URL_ADMIN_SAVE);
		break;
		
	case "foretag":
		
		$foretag = Foretag::loadById($_POST["fid"]);
		$foretag->setNamn($_POST["namn"]);
		$foretag->setStartdatum($_POST["startdatum"]);
		$foretag->commit();
		$urlHandler->redirect("Foretag", URL_ADMIN_EDIT, $foretag->getId());
		break;

	case "admin":
		if(Security::checkLosenStrength($_POST['losenord']) == "ok"){
			if(empty($_GET["id"])){
				new Admin($_POST["anamn"], $_POST["losenord"], $_POST["typ"]);		
			}else{
				$admin = Admin::loadById($_GET["id"]);

				if(!empty($_POST["anamn"]))
					$admin->setANamn($_POST["anamn"]);

				if(!empty($_POST["typ"]))
					$admin->setTyp($_POST["typ"]);

				if(!empty($_POST["losenord"]))
					$admin->setLosenord($_POST["losenord"]);

				$admin->commit();
			}
			$urlHandler->redirect("Admin", URL_ADMIN_LIST);
			break;
		}else{
			$urlHandler->redirect("Admin", URL_ADMIN_CREATE, "false");
			break;
		}

	case'debug':
		$admin = Admin::loadById($_GET["id"]);
	
			if(empty($_POST['debug'])){
				$_POST['debug'] = "false";
			}else{
				$_POST['debug'] = "true";
			}

			$admin->setDebug($_POST['debug']);

		$admin->commit();
		$urlHandler->redirect("Admin", URL_VIEW);
		break;

	case "kommun":
	
		$kod = '';
		$framsidebildAuto = '';
		$googlename = false;
		
		if (isset($_POST['kod'])) {
			$kod = $_POST['kod'];			
		}
		
		if(isset($_POST["framsidebildAuto"])) {
			$framsidebildAuto = ($_POST["framsidebildAuto"] == "1") ? true : false;
		}
		
		if (isset($_POST['googlename'])) {
			$googlename = $_POST['googlename'];
		}
		
		if(isset($_POST['abroad'])) {
			$abroad = 'true';
		} else {
			$abroad = 'false';
		}

		if(empty($_GET["id"])){
		
			if(isset($_POST["sameort"])) {
			
				$_POST["ort"] = $_POST["namn"];
			}
				
			$kommun = new Kommun($_POST["namn"], $_POST["ort"], $_POST["areal"], $_POST["folkmangd"], $_POST["webb"], $_POST["info"], $framsidebildAuto,$abroad,$googlename);
			
			if(isset($_FILES["some_name"]) && $_FILES["some_name"]["tmp_name"]) {
				$bild = new Bild($_FILES["some_name"]);
				$bild->approve(ROOT."/staticroute/abroad_".$kommun->getId()."jpg");
			}
		}else{
			$kommun = Kommun::loadById($_GET["id"]);
			if($ADMIN->getTyp() != "kommun"){
				$kommun->setNamn($_POST["namn"]);
				$kommun->setOrt($_POST["ort"]);
				$kommun->setKod($kod);
				$kommun->setLan($_POST["lan"]);
			}
			$kommun->setAreal($_POST["areal"]);
			$kommun->setFolkmangd($_POST["folkmangd"]);
			$kommun->setWebb($_POST["webb"]);
			$kommun->setInfo($_POST["info"]);
			$kommun->setGoogleName($googlename);
			$kommun->setFramsidebildAuto($framsidebildAuto);
			$kommun->commit();
		}
		
		$urlHandler->redirect("Kommun", URL_EDIT, $kommun->getId());
	
		break;
	
	case "kommunavstand":
		$kommun = Kommun::loadById($_GET["id"]);
		$target = Kommun::loadById($_POST["target"]);
		$kommun->addAvstand($target, $_POST["km"]);
		break;
		
	case "kommunbild":
		if(empty($_GET["id"])){
			if (isset($_FILES['image'])){
				$bild = new Bild($_FILES["image"]);
				$kommun = Kommun::loadById($_POST["kid"]);
				$kommunbild = new Kommunbild($bild, $kommun, $_POST["namn"], $_POST["beskrivning"]);
			} else {
				throw new FilException('Bild', 'Det gick inte att hitta filen du försökta ladda upp');
			}
		}else{
			$kommunbild = Kommunbild::loadById($_GET["id"]);
			$kommun = $kommunbild->getKommun();
			$kommunbild->setNamn($_POST["namn"]);
			$kommunbild->setBeskrivning($_POST["beskrivning"]);
			$kommunbild->commit();
			if(isset($_FILES["image"]) && $_FILES["image"]["tmp_name"]){
				$bild = new Bild($_FILES["image"]);
				$kommunbild->setBild($bild);
			}
		}
		
		if(isset($_POST["miniatyr"])){
			if($kommun->getFramsidebildAuto()){
				$framsidebild = $kommunbild->skapaFramsidebild(null, null);
			}else{
				$framsidebild = $kommunbild->skapaFramsidebild($_POST["thumbsize"], $_POST["thumbHeight"]);
			}
		
		}else{
			if($kommunbild->getFramsidebild()){
				
				$kommunbild->getFramsidebild()->delete();
			
			}
		}
				
		$urlHandler->redirect("Kommun", URL_EDIT, $kommun->getId());
		break;
		
	case "mal":
		if(empty($_GET["id"])){
			$kommun = Kommun::loadById($_POST["kid"]);
			$mal = new Mal($_POST["namn"], $kommun, $_POST["avstand"]);
		}else{
			$mal = Mal::loadById($_GET["id"]);
			$kommun = Kommun::loadById($_POST["kid"]);
			$mal->setNamn($_POST["namn"]);
			$mal->setKommun($kommun);
			$mal->setAvstand($_POST["avstand"]);
			$mal->commit();
		}
		$urlHandler->redirect("Mal", URL_ADMIN_EDIT, $mal->getId());
		break;
		
	case "quizfraga":
		if(empty($_GET["id"])){
			$kommun = Kommun::loadById($_POST["kid"]);
			$quizFraga = new QuizFraga($kommun, $_POST["fraga"]);
		}else{
			$quizFraga = QuizFraga::loadById($_GET["id"]);
			$quizFraga->setFraga($_POST["fraga"]);
			$quizFraga->commit();
		}
		$urlHandler->redirect("QuizFraga", URL_ADMIN_EDIT, $quizFraga->getId());
		break;
	
	case "quizalternativ":
		$quizFraga = QuizFraga::loadById($_POST["fid"]);
		$rattSvar = (isset($_POST["rattSvar"])) ? true : false;
		new QuizAlternativ($quizFraga, $_POST["text"], $rattSvar);
		$urlHandler->getUrl("QuizFraga", URL_ADMIN_EDIT, $quizFraga->getId());
		break;
		
	case "minaquiz":
		if (empty($_GET["qid"])) {
			// Skapa ett nytt quiz
			$mittQuiz = new MinaQuiz($_POST, true);
		} else {
			// Ladda quiz från ID
			$mittQuiz = MinaQuiz::loadById($_GET["qid"]);

			// Uppdatera variabler
			$mittQuiz->setNamn(Security::escape($_POST["namn"]));
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
		$urlHandler->redirect("ProQuiz", URL_ADMIN_LIST);
		break;
	
	case "lagnamn":
		if(empty($_POST["id"])){
			$lagnamn = new LagNamn($_POST["namn"]);
			if(isset($_FILES["bild"]) && $_FILES["bild"]["tmp_name"]){
				$bild = new Bild($_FILES["bild"]);
				$lagnamn->setImg($bild);
				$lagnamn->commit();
			}
		}else{
			$lagnamn = LagNamn::loadById($_POST["id"]);
			$lagnamn->setNamn($_POST["namn"]);
			if(file_exists($_FILES["bild"]["tmp_name"])) {
				$bild = new Bild($_FILES["bild"]);
				$lagnamn->setImg($bild);
			}
			$lagnamn->commit();
		}
		$urlHandler->redirect("LagNamn", URL_ADMIN_LIST);
		break;
			
	
	case "profildata":
		if(empty($_GET["id"])){
			$profildata = new ProfilData($_POST["namn"], $_POST["beskrivning"]);
		}else{
			$profildata = ProfilData::loadById($_GET["id"]);
			if(!empty($_POST["namn"])) {
				$profildata->setNamn($_POST["namn"]);
			}
			if(isset($_POST["beskrivning"])) {
				$profildata->setBeskrivning($_POST["beskrivning"]);
			}
			$profildata->commit();
		}
		
		// Lägg in nya val:
		$_POST["profildatavals"] = trim($_POST["profildatavals"]);
		if (!empty($_POST["profildatavals"])) {
			$profildatavals = explode("\n", trim($_POST["profildatavals"]));
					if (count($profildatavals) > 0) {
				while(list($key,$value) = each($profildatavals)) {
					new ProfilDataVal(trim($value),$profildata->getId());
				}
			}
		}
		$urlHandler->redirect("ProfilData", URL_ADMIN_EDIT, $profildata->getId());
		break;
	
	case "texteditor":
		if(empty($_GET["id"])){
			$texteditor = new TextEditor($_POST["namn"], $_POST["tema"]);
		}else{
			$texteditor = TextEditor::loadById($_GET["id"]);
			if(!empty($_POST["namn"])) {
				$texteditor->setNamn($_POST["namn"]);
			}
			if(!empty($_POST["tema"])) {
				$texteditor->setTema($_POST["tema"]);
			}
			if(!empty($_POST["texten"])) {
				$texteditor->setTexten($_POST["texten"]);
			}
			$texteditor->commit();
		}
		$urlHandler->redirect("TextEditor", URL_ADMIN_EDIT, $texteditor->getId());
		break;
		
	
	case "help":
		if(empty($_GET["id"])){
			$help = new Help($_POST["namn"], $_POST["tema"], $_POST["sida"], $_POST["auto"], $_POST["sizeX"], $_POST["sizeY"]);
		}else{
			$help = Help::loadById($_GET["id"]);
			if(!empty($_POST["namn"])) {
				$help->setNamn($_POST["namn"]);
			}
			if(!empty($_POST["tema"])) {
				$help->setTema($_POST["tema"]);
			}
			if(!empty($_POST["texten"])) {
				$help->setTexten($_POST["texten"]);
			}
			if(!empty($_POST["sida"])) {
				$help->setPage($_POST["sida"]);
			}
			if(!empty($_POST["auto"])) {
				$help->setAuto($_POST["auto"]);
			}
			if(!empty($_POST["sizeX"])) {
				$help->setSizeX($_POST["sizeX"]);
			}
			if(!empty($_POST["sizeY"])) {
				$help->setSizeY($_POST["sizeY"]);
			}
			$help->commit();
		}
		$urlHandler->redirect("Help", URL_ADMIN_EDIT, $help->getId());
		break;
		
	case 'aktivitet_s':
		Security::demand(ADMIN);
		if (isset($_GET['id'])) {
				$aktivitet = Aktivitet::loadById($_GET['id']);
		
			if (isset($_GET['borttagen'])) {
				$aktivitet->setBorttagen('ja');			
			} else {
				$aktivitet->setBorttagen('nej');
			}
			$aktivitet->commit();
			$urlHandler->redirect('Aktivitet', URL_ADMIN_LIST);
		} else {
			throw new UserException("Inget id", "inget id angett i url");
		}
		break;
		
	case "aktivitet":
		if(empty($_POST["id"])){
			$aktivitet = new Aktivitet($_POST["namn"], $_POST["enhet"], $_POST["varde"], $_POST["beskrivning"]);		
			// print_r($aktivitet);
		}else{
			$aktivitet = Aktivitet::loadById($_POST["id"]);
			$aktivitet->setNamn($_POST["namn"]);
			$aktivitet->setEnhet($_POST["enhet"]);
			$aktivitet->setVarde($_POST["varde"]);
			$aktivitet->setBeskrivning($_POST["beskrivning"]);
			$aktivitet->setSvarighetsgrad($_POST['svarighetsgrad']);
			$aktivitet->commit();
		}
		$urlHandler->redirect("Aktivitet", URL_ADMIN_LIST);
		break;
	case "mergeorder":
		//added by krillo 090908	
	    $foretagIdFrom = $_POST['foretagid_from'];
	    $foretagIdTo = $_POST['foretagid_to'];
	    if($foretagIdFrom > 0 && $foretagIdTo > 0 ){
			$status = Foretag::mergeOrderNycklar($foretagIdFrom, $foretagIdTo);			
			$urlHandler->redirect("MergeOrder", "URL_ADMIN_MERGE", $status);		
		}else{
			$urlHandler->redirect("MergeOrder", "URL_ADMIN_MERGE", "missing_params");
		}
		break;				
	case "medlem":	
		$medlem = Medlem::loadById($_POST["medlem_id"]);
		if (!empty($_POST['sendPassword'])) {
			
			try{
				Medlem::nyttLosen($medlem->getEpost());
			}catch(MedlemException $e){
				if($e->getCode() == -17){
					throw new UserException("Felaktig e-postadress", "E-postadressen är inte knuten till något konto.");
				}
			}
			throw new UserException("Lösenord skickat", "Ett nytt lösenord har skapats och skickats");
		}
		if(isset($_POST["aktivera"])){
			$medlem->setEpostBekraftad(1);
			$medlem->commit();
		}
		else {
			$medlem->setPaidUntil($_POST["paidUntil"]);
			$medlem->setLevelId($_POST["levelId"]);
			$medlem->setEpost($_POST["epost"]);			
			$medlem->commit();
		}
		$urlHandler->redirect("Medlem", URL_ADMIN_EDIT, $medlem->getId());
		break;
		
	case "level":
		
		if(isset($_GET["action"]) && $_GET["action"] == "default") {
			
			$level = Level::loadById($_GET["id"]);
			$level->makeDefault();
			
		}
		else {
			if(empty($_GET["id"])) {
				$level = new Level($_POST["namn"]);
			}
			else {
				$level = Level::loadById($_GET["id"]);
				$level->setNamn($_POST["namn"]);
				$level->commit();
			}
		}
		$urlHandler->redirect("Level", URL_ADMIN_LIST);
		break;
	
	case "sajtdelar":
	
		list($action,$sajtdel,$levelid) = split(",",$_GET["args"]);
		
		
		$level = Level::loadById($levelid);
		
		if($action == "give") {
			SajtDelar::giveAccess($level,$sajtdel);
		}
		else {
			SajtDelar::removeAccess($level,$sajtdel);
		}
		
		$urlHandler->redirect("Level", URL_ADMIN_LIST);
		break;
	
	case "kommundialekt":
		try{
	
			if(isset($_POST["id"])){

				$dialekt = Kommundialekt::loadById($_POST["id"]);
				$dialekt->setKon($_POST["kon"]);
				$dialekt->setAlder($_POST["alder"]);
				$dialekt->setUrl($_POST["url"]);
				$dialekt->commit();	
			
			}else{
			
				$kommun = Kommun::loadById($_POST["kommun_id"]);
				$dialekt = new Kommundialekt($kommun, $_POST["kon"], $_POST["alder"], $_POST["url"]);
			
			}
		
		}catch (KommundialektException $e){
			
			$msg = $e->getMessage();
				
			if(isset($_POST["id"]))
				$url = $urlHandler->getUrl("Kommundialekt", URL_ADMIN_EDIT, $_POST["id"]);
			else
				$url = $urlHandler->getUrl("Kommundialekt", URL_ADMIN_CREATE, $_POST["kommun_id"]);
				
			throw new UserException("Inmatningsfel", $msg, $url, "Försök igen");
				
			
		}
		
		$urlHandler->redirect("Kommundialekt", URL_ADMIN_EDIT, $dialekt->getId());
	
		break;
		
	case "paminnelse_sql":
		Security::Demand(SUPERADMIN);
		if (empty($_GET['id'])) {
			$obj = new Paminnelse_sql($_POST);
		} else {
			if (!is_numeric($_GET['id'])) {
				throw new Exception("Ett felaktigt ID angavs", 1);
			} else {
				$sql = Paminnelse_sql::loadById($_GET['id']);
				$sql->setNamn($_POST['namn']);
				$sql->setDagarMellanUtskick($_POST['dagar_mellan_utskick']);
				$sql->setQuery($_POST['query']);
				$sql->setTitel($_POST['titel']);
				$sql->setInreMall($_POST['inre_mall']);
				$sql->setMeddelandeId(!empty($_POST['meddelande_id']) ? $_POST['meddelande_id'] : 0);
				$sql->commit();
			}
		}
		
		$urlHandler->redirect('Paminnelser', URL_ADMIN_LIST);
	
		break;
	
	case "paminnelse_meddelanden":
		if (empty($_GET['id'])) {
			$obj = new Paminnelse_meddelanden($_POST);
		} else {
			if (!is_numeric($_GET['id'])) {
				throw new Exception("Ett felaktigt ID angavs", 1);
			} else {
				$meddelande = Paminnelse_meddelanden::loadById($_GET['id']);
				$meddelande->setNamn($_POST['namn']);
				$meddelande->setMall($_POST['mall']);
				$meddelande->commit();
			}
		}
		
		$urlHandler->redirect('Paminnelser', URL_ADMIN_LIST);
		
		break;
		
}

		
		
if(empty($_GET["redirect"]))
	header("Location: " . $_SERVER["HTTP_REFERER"]);
else
	header("Location: " . $_GET["redirect"]);

?>
