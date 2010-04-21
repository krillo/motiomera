<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

Security::demand(EDITOR);

$smarty = new AdminSmarty;

$kommuner = Kommun::listAll();

$smarty->assign("kommuner", $kommuner);

$smarty->display('kommuner.tpl');

?>