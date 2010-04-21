<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

Security::demand(USER, null, false);
$smarty = new PopSmarty();

$id = Security::escape($_GET['id']);
$is_inbox = isset($_GET['is_inbox']) ? Security::escape($_GET['is_inbox']) : false;

$mail_to_read = MotiomeraMail::loadById($id);

if (!isset($USER) || !($mail_to_read->getToId() == $USER->getId() or $mail_to_read->getSentFrom() == $USER->getId())) {
	throw new UserException('Ett fel har uppstått', 'Mailet du försöker läsa är inte skickat till dig.');
}

if(isset($is_inbox) && $is_inbox == '1'){
	$mail_to_read->setIsRead(1);
}

$smarty->assign("id", $id);
$smarty->assign("is_inbox", $is_inbox);
$smarty->assign("mail_to_read", $mail_to_read);
$smarty->assign("my_id", $USER->getId());


global $SETTINGS;
$fromMedlem = Medlem::loadById($mail_to_read->getSentFrom());
$smarty->assign("medlem",$fromMedlem);

$reserverade_anvandare = $SETTINGS["reserverade_anvandare"];
foreach($reserverade_anvandare as $k => $anv) {
	$reserverade_anvandare[$k] = strtolower($anv);
}
if (isset($SETTINGS["reserverade_anvandare"]))
	$replyable = (in_array(strtolower($fromMedlem->getANamn()),$reserverade_anvandare)) ? 0 : 1;
else
	$replyable = 1;

$smarty->assign("replyable", $replyable);

$from_id = $mail_to_read->getSentFrom();
$smarty->assign("blockerad", (int)MedlemsBlockering::verifyBlocked($USER->getId(),$from_id));

$myself = Medlem::loadById($USER->getId());
$my_contacts = $myself->getUsersThatHasMeAsContact($from_id);

$smarty->assign("my_contacts", $my_contacts);
$smarty->assign("from_id", $from_id);

// $mail_msg = str_replace('<br />',  '', $mail_to_read->getMsg());

// $smarty->assign("mail_msg",  $mail_msg);

$re_text = substr($mail_to_read->getSubject(), 0, 3) != 'RE:' ? 'RE: ' . $mail_to_read->getSubject() : $mail_to_read->getSubject();
$smarty->assign("re_text", $re_text);

$vb_text = substr($mail_to_read->getSubject(), 0, 3) != 'VB:' ? 'VB: ' . $mail_to_read->getSubject() : $mail_to_read->getSubject();
$smarty->assign("vb_text", $vb_text);

$nl = "\n\n-----------\n\n";
$smarty->assign("nl", $nl);

if($replyable)
	$mail_message = $mail_to_read->getMsg();

//html_decode due to Mobject htmlspecialchar encodes it every commit
$mail_to_read->setMsg(htmlspecialchars_decode($mail_to_read->getMsg()));
if(!$replyable || $mail_to_read->getAllowLinks() == 'true')
	$mail_message = $mail_to_read->getMsg();

$smarty->assign("mail_message", $mail_message);

$mail_to_read->commit();

$USER->recountOlastaMail();
$USER->commit();

//Note : After the commit
//decode html in mails from admins



$smarty->display('read_mail.tpl');
?>
