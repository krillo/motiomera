<?
	include $_SERVER["DOCUMENT_ROOT"]  . "/php/init.php";
	
	Security::demand(USER);
	
	$id_to_remove = Security::escape($_POST['id_to_remove']);
	MotiomeraMail::removeMail($id_to_remove);
	
	/*
	$send_to = Security::escape($_POST['mid']);
	$amne = isset($_POST['amne']) ? Security::escape($_POST['amne']) : "";
	$msg = isset($_POST['msg']) ? $_POST['msg'] : "";
	$sent_from = $USER->getId();
	$date = date("Y-m-d H:i:s");
	
	$mm_mail = new MotiomeraMail($amne, $msg, $sent_from, $send_to, $date, 0, 0);

	header("Location: /pages/mail.php?do=sent&mid=" . $send_to);
	*/
?>