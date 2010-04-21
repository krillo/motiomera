<?
if(!empty($_POST['mid'])) {
	include $_SERVER["DOCUMENT_ROOT"]  . "/php/init.php";
	
	$mid = $_POST['mid'];

	Security::demand(USER);
	
	if(!is_numeric($_POST['mid'])) {
		$mid = Medlem::verifyValidUsername($_POST['mid']);
		//if not valid -> do something
	}

	$send_to = Security::escape($mid);
	$send_to_Obj = Medlem::loadById($mid);
	
	$amne = isset($_POST['amne']) ? Security::escape(utf8_encode($_POST['amne'])) : "";
	$msg = isset($_POST['msg']) ? utf8_encode($_POST['msg']) : "";
	$allow_links = (isset($_POST['allow_links']) && $_POST['allow_links'] == 'true') ? 1 : 0;

	// $msg = nl2br($msg);
	$sent_from = $USER->getId();
	$date = date("Y-m-d H:i:s");

	if($USER->getId() == $mid) {
		echo 'mail_to_self';
		die;
	}

	if (MedlemsBlockering::verifyBlocked($USER->getId(),$mid)) {
		echo 'blockerad_user';
		die;
		//throw new MedlemsBlockeringException("Kan ej skicka mail till medlemmen, medlemmen har spärrat dig.", 6);
	}
	if(MedlemsBlockering::verifyBlocked($mid,$USER->getId())) {
		echo 'blockerad_target';
		die;
		//throw new MedlemsBlockeringException("Kan ej skicka mail till medlemmen, du har spärrat medlemmen.", 5);
	}

	
	if(($send_to_Obj->getMotiomeraMailBlock()=='true' && !$send_to_Obj->inAdressbok($USER))) { /** If user blocks mails from none friends */
		echo 'targetBlockMail';
		die;
	}
	if(!$send_to_Obj->synlig()) {
		echo 'blockedByProfile';
		die;
	}

	//åtkomst - ingen, foretag, adressbok (kom ihåg adminanvändare)

	new MotiomeraMail($amne, $msg, $sent_from, $send_to, $date, 0, 0, $allow_links);

	if(isset($_POST['rmid']) && !empty($_POST['rmid'])) {
		$reply_to = Security::escape($_POST['rmid']);
		$replyToMail = MotiomeraMail::loadById($reply_to);
		
		$replyToMail->setIsAnswered(1);
		$replyToMail->commit();
	}
	
	echo 'ok';
	//header("Location: /pages/mail.php?do=sent&mid=" . $send_to);
	//header("Location: /popup/pages/send_mail.php?do=sent&mid=" . $send_to);
}
?>