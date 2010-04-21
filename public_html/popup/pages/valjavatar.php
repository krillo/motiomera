<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

$smarty = new PopSmarty();

$avatarer = Avatar::listAll();
$smarty->assign("avatarer", $avatarer);

$smarty->display('valjavatar.tpl');

?>