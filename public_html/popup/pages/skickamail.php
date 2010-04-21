<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

try{
	Misc::sendEmail($SETTINGS["kontakt"], $_POST["epost"], "Motiomera.se - Formulärsmail från " . $_POST["epost"], $_POST["meddelande"]);
}catch(MiscException $e){

	if($e->getCode() == -2){
	
		echo "Ogiltig e-postadress!";
		exit;
	
	}

}


$smarty = new PopSmarty();

$smarty->display('skickamail.tpl');

?>