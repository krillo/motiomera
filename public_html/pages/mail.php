<?php

include $_SERVER["DOCUMENT_ROOT"]  . "/php/init.php";
Security::demand(USER);

$USER->recountOlastaMail();
$USER->commit();

$smarty = new MMSmarty();
$smarty->assign("pagetitle", "Mail");

$do = isset($_GET['do']) ? Security::escape($_GET['do']) : 'inbox'; 
$my_id = $USER->getId();
$smarty->assign("my_id", $my_id);
$motiomeraMail_Folders = new MotiomeraMail_Folders($my_id);
$folders = $motiomeraMail_Folders->getFolders();
$smarty->assign("folders", $folders);
$folder_id = "0";

$myself = Medlem::loadById($USER->getId());
$my_contacts = $myself->getUsersThatHasMeAsContact(0);
$smarty->assign("my_contacts", $my_contacts);

if($do == 'inbox'){
	$action = "inbox";
	if(isset($_GET['folder_id'])){
		$folder_id = Security::escape($_GET['folder_id']);
	}
	$box_mails = MotiomeraMail::listMailInbox($USER->getId(), $folder_id);
	$smarty->assign("box_mails", $box_mails);
	$smarty->assign("is_inbox", true);
	$smarty->assign("to_include", "mail_box.tpl");
}
else if($do == 'outbox'){
	$action = "outbox";
	$smarty->assign("is_inbox", false);
	$box_mails = MotiomeraMail::listMailOutbox($USER->getId());
	$smarty->assign("box_mails", $box_mails);
	$smarty->assign("to_include", "mail_box.tpl");
}
else if($do == 'manage_folders'){
	$action = "manage_folders";
	$smarty->assign("to_include", "mail_folders.tpl");
}

$smarty->assign("folder_id", $folder_id);
$smarty->assign("action", $action);
$smarty->display('mail.tpl');

?>