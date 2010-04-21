<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

Security::demand(USER);

$smarty = new MMSmarty;


$medlemmar = Medlem::listAll();
$smarty->assign("medlemmar", $medlemmar);

$smarty->display('medlemmar.tpl');

?>