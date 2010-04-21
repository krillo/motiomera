<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

Security::demand(USER, null, false);
$smarty = new PopSmarty();

$mid = Security::escape($_GET['id']);
$do = Security::escape($_GET['do']);
$medlem_to_send = Medlem::loadById($mid);
$smarty->assign("medlem_to_send", $medlem_to_send);
$smarty->assign("mid", $mid);

if($do == 'send'){
	$smarty->assign("is_replay", false);
	if(isset($_GET['re'])){
		$id = Security::escape($_GET['re']);
		$mail_to_read = MotiomeraMail::loadById($id);
		$smarty->assign("is_replay", true);
		$text_message_decoded = str_replace("<br>", "", $mail_to_read->getMsg());
		$text_message_decoded = str_replace("<br />", "", $mail_to_read->getMsg());
		$text_message = "\n\n********************\n";
		$text_message .= $text_message_decoded;
		$smarty->assign("text_message", $text_message);
		$smarty->assign("mail_to_read", $mail_to_read);
	}
	$action = "send";
}
else if($do == 'sent'){
	$action = "sent";
}
$smarty->assign("action", $action);
$smarty->display('send_mail.tpl');
?>