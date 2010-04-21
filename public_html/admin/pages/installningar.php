<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");
Security::demand(ADMIN);
	$smarty = new AdminSmarty();
	if(isset($ADMIN) && $ADMIN->getTyp() == "superadmin"){
		if($ADMIN->getDebug() == "true"){
			$smarty->assign("isdebug", " checked=\"checked\"");
		}
	}
	$smarty->display('installningar.tpl');
?>