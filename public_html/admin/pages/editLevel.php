<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

Security::demand(ADMIN);

$smarty = new AdminSmarty();

if(isset($_GET["id"])) {

	$level = Level::loadById($_GET["id"]);
	$smarty->assign("level",$level);
}

$smarty->display("editLevel.tpl");

?>