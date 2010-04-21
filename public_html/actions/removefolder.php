<?
	include $_SERVER["DOCUMENT_ROOT"]  . "/php/init.php";
	
	Security::demand(USER);
	
	
	$my_id = Security::escape($_POST['my_id']);
	$multiple = Security::escape($_POST['multiple']);
	
	if($multiple == 0){
		$folder_id = Security::escape($_POST['folder_id']);
		MotiomeraMail::removeMailFromFolder($folder_id, $my_id);
	}
	else{
		$nroffolders = Security::escape($_POST['nroffolders']);	
		for($i=0;$i<$nroffolders;$i++){
			$postvar = 'folder_id_' . $i;
			$folder_id = Security::escape($_POST[$postvar]);
			MotiomeraMail::removeMailFromFolder($folder_id, $my_id);
		}
	}
?>