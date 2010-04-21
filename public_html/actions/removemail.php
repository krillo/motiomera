<?
	include $_SERVER["DOCUMENT_ROOT"]  . "/php/init.php";
	
	Security::demand(USER);
	
	$mail_id = Security::escape($_POST['mail_id']);
	$remover_id = Security::escape($_POST['remover_id']);
	$remover = Security::escape($_POST['remover']);
	
	if(isset($_POST['mails_to_remove'])){
		$mails_to_remove = $_POST['mails_to_remove'];
		for($i=0;$i<$mails_to_remove;$i++){
			$postvar = 'mail_id_' . $i;
			$mail_id = $_POST[$postvar];
			MotiomeraMail::removeMail($mail_id, $remover_id, $remover);
		}
	}
	else{
		MotiomeraMail::removeMail($mail_id, $remover_id, $remover);
	}

?>