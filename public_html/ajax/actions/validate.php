<?php

require $_SERVER["DOCUMENT_ROOT"] . "/php/init.php";



switch($_POST["typ"]){

	case "anamn":
		echo Medlem::ledigtAnvandarnamn(utf8_encode($_POST["varde"])) ? "1" : "0";
		break;
	
	case "epost":
		echo Medlem::ledigEpost($_POST["varde"]) ? "1" : "0";
		break;		
	
	case "foretagsnyckel":
		$result = Foretag::giltigForetagsnyckel($_POST["varde"]);
		if($result === true)
			echo "1";
		else if($result == Foretag::FN_OGILTIG)
			echo "OGILTIG";
		else if($result == Foretag::FN_UPPTAGEN)
			echo "UPPTAGEN";
		break;

  // added by krillo 11-01-18
	case "kampanjkod":
    $varde = mb_convert_case(utf8_encode(urldecode($_POST["varde"])), MB_CASE_LOWER, "UTF-8");
		$result = Order::giltigKampanjkod($varde);
		if($result === true){
			echo "1";
    }else{
			echo "OGILTIG";
    }
		break;

	case "gruppnamn":
		echo (Grupp::ledigtNamn($_POST["varde"])) ? "1" : "0";
		break;
		
	case "adminlosen":
		echo Security::checkLosenStrength($_POST['password']);
		break;
		
	case'emailcompare':
		if(Misc::matchString($_POST['mailone'],$_POST['mailtwo'])==true){
			echo "OK";
		}else{
			echo "Email adresserna matchar inte";
		}
		break;

	case 'stegtotal':

		$saveurl = '/actions/save.php?table=steg&ajax=true';
		foreach($_POST as $postArg=>$postValue) {
			$saveurl.='&'.$postArg.'='.$postValue;
		}
		
		$saveFlag = true;
		$veckoSumma = array();
		$dagSumma = array();
		global $USER;
		if(!empty($USER)) {
			for($i = 0; $i < $_POST["antalsteg"]; $i++){
				
				$tmpTime = strtotime($_POST["steg".$i."_datum"]);
				//$tmpWeek = strftime("%G%V",$tmpTime);			//not implemented in win32
				$tmpWeek = date('YW', $tmpTime);
				$tmpDate = $_POST["steg".$i."_datum"];

				$a = Aktivitet::loadById($_POST["steg".$i."_aid"]);
				if(!empty($a)) {

					if(!isset($veckoSumma[$tmpWeek])) {
						$dayOffset = date('N', $tmpTime);
						$tmpFirstDayOfWeek = $tmpTime - ($dayOffset - 1)*86400; 
						$firstWeekDay = date('Y-m-d',$tmpFirstDayOfWeek);
						$lastWeekDay = date('Y-m-d', $tmpFirstDayOfWeek + 6*86400);

						$veckoSumma[$tmpWeek] = Steg::getStegTotal($USER, $firstWeekDay, $lastWeekDay);

					}
					if(!isset($dagSumma[$tmpDate]))
						$dagSumma[$tmpDate] = Steg::getStegTotal($USER, $tmpDate, $tmpDate);


					$tmpSteg =  $_POST["steg".$i."_antal"]*$a->getVarde();
					if(!empty($tmpSteg))
						$dagSumma[$tmpDate]+=$tmpSteg;
					if(!empty($tmpWeek) && !empty($tmpSteg)) {

						if(empty($veckoSumma[$tmpWeek]))
							$veckoSumma[$tmpWeek] =0;

						$veckoSumma[$tmpWeek]+=$tmpSteg;
					}
				}
				else
					echo 'Felaktig aktivitet rapporterad. ';
				
			}
			foreach($dagSumma as $dkey => $dsumma) {
				if($dsumma > Steg::MAX_STEG_PER_DAG) {
					echo 'Du har rapporterat för många steg '.$dkey.' (motsvarande '.$dsumma. ' steg). Maximalt antal steg per dag är '.Steg::MAX_STEG_PER_DAG.'. ';
					$saveFlag = false;
				}

			}
			foreach($veckoSumma as $vkey => $vsumma) {
				if($vsumma > Steg::MAX_STEG_PER_VECKA) {
					echo 'Du har rapporterat för många steg under vecka '.substr($vkey,4,2).' (År  '.substr($vkey,0,4).'). Du har rapporterat motsvarande '.$vsumma.' steg. Maximalt antal steg per vecka är '.Steg::MAX_STEG_PER_VECKA.'. ';
					$saveFlag = false;
				}

			}

			if($saveFlag)
				header('Location: '.$saveurl);
		}
		else
			echo 'Ingen användare inloggad. ';
		break;
}


?>