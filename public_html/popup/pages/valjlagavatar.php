<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

$smarty = new PopSmarty();

$avatarer = CustomLagbild::listAll();
$smarty->assign("avatarer", $avatarer);
$smarty->assign("lagid",$_GET['lagid']);

$smarty->display('valjlagavatar.tpl');

?>