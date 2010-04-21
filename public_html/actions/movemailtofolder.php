<?
	include $_SERVER["DOCUMENT_ROOT"]  . "/php/init.php";
	
	Security::demand(USER);
	$folder_id = Security::escape($_GET['folder_id']);
	$move_to = Security::escape($_GET['move_to']);
	$nrofmails = Security::escape($_GET['nrofmails']);
	
	if($nrofmails > 0){
		for($i=0;$i<$nrofmails;$i++){
			$getvar = 'mail_id_' . $i;
			$mail_id = Security::escape($_GET[$getvar]);
			$motiomeraMail = MotiomeraMail::loadById($mail_id);
			$motiomeraMail->setToInFolder($move_to);
		}
	}
	header("Location: /pages/mail.php?do=inbox&folder_id=" . $folder_id);
?>