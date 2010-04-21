<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

$smarty = new PopSmarty();

$lagid = $_POST['lagid'];

$lag = Lag::loadById($lagid);
if (isset($lag)) {
	$smarty->assign("lag", $lag);
	$smarty->assign("lagnamn", $lag->getNamn());
	$smarty->display('laddaupplagbild.tpl');
}
else
	throw LagException("Lag invalid", -1000);

?>