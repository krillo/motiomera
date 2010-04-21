<?php
	
require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

Security::demand(USER, null, false);
	$smarty = new PopSmarty();
	
	if($_GET['gid']){
		$smarty->assign('gid', $_GET['gid']);
	}
	
	$smarty->display('ansok_klubb.tpl');
?>
