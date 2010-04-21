<?
	include $_SERVER["DOCUMENT_ROOT"]  . "/php/init.php";
	
	Security::demand(USER);
	$my_id = Security::escape($_POST['my_id']);
	$action = Security::escape($_POST['todo']);
	$folder_name = Security::escape($_POST['folder_name']);
	
	

	if($action == 'create'){

		$motiomeraMail_Folders = new MotiomeraMail_Folders($my_id);
		$folder_created = $motiomeraMail_Folders->createFolder(utf8_encode($folder_name));
		if($folder_created){
			echo '1';
			exit();
		}
		echo '0';
		exit();
	}
	
?>