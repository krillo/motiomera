<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

Security::demand(EDITOR);

$smarty = new AdminSmarty;

if (!empty($_GET["kid"])) {
	$kommun = Kommun::loadById($_GET["kid"]);
}

$smarty->display('kommun.tpl');

?>
