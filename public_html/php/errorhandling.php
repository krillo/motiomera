<?php

function exception_handler($e) {
	
	if ((defined('DEBUG_MAIL') && DEBUG_MAIL) or (defined('DEBUG_IM') && DEBUG_IM) && (!DEBUG) && !(!get_class($e) =="GruppException" && !get_class($e) == "MedlemException" && !get_class($e) == "SecurityException")) {
		$smarty = new MMSmarty;
		$smarty->assign('error', $e);
		$mess_err = $smarty->fetch('errormsg.tpl');
		// echo $mess_err;
		$host = $_SERVER['HTTP_HOST'];
		$browserPlain = Medlem::getCurrentBrowserVersion();
		$ipNr = Medlem::getCurrentIpNr();
		$mess = $host. " \n Browser used: " . $browserPlain;
		if (isset($USER)) {
			$mess .= "\n id: ". $USER->getId();
			$mess .= "\n Username: ". $USER->getANamn();
			$mess .= "\n Email: ". $USER->getEpost();
		}
		$mess .= "\n Referal page: ". $_SERVER['HTTP_REFERER'];
		$mess .= "\n Error page: ". $_SERVER['REQUEST_URI'];
		$mess .= "\n Get Aguments: ". print_r($_GET, true);
		$mess .= "\n Post Arguments: ". print_r($_POST, true); 
		$mess .= "\n DateTime: ". date('Y-m-d H-i-s');
		$mess .= " \n Ip: ". $ipNr.
		" \n\n ".$mess_err;
		unset($smarty);
	}
	
	if((defined('DEBUG_IM')) && (DEBUG_IM) && (!DEBUG) && !(!get_class($e) =="GruppException" && !get_class($e) == "MedlemException" && !get_class($e) == "SecurityException")) {
		global $IM, $SETTINGS;
		$IM->login(DEBUG_IM_MAIL, DEBUG_IM_PASS);
		foreach ($SETTINGS['im_recip'] as $mail) {
			
			$IM->createSession($mail);
			$IM->sendMessage($mess);
		}
	}
	
	if((defined('DEBUG_MAIL')) && (DEBUG_MAIL) && (!DEBUG) && !(!get_class($e) =="GruppException" && !get_class($e) == "MedlemException" && !get_class($e) == "SecurityException")) {
		foreach ($SETTINGS["debug_mail"] as $mail) {
			@Misc::sendEmail($mail, $SETTINGS["email"], "Debug meddelande från motiomera", $mess);
		}
	}
	if(get_class($e) == "UserException")
		return false;
		
	$smarty = new MMSmarty();

	if(DEBUG)
		$smarty->assign("error", $e);

	$smarty->display('error.tpl');
	
	exit;
}

function error_handler($errno, $errstr, $errfile, $errline){



}


if(defined('DEBUG') && DEBUG){
	error_reporting(E_ALL);
}else{
	set_error_handler('error_handler');
}

set_exception_handler('exception_handler');
?>
