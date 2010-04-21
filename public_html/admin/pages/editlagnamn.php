<?php

require $_SERVER["DOCUMENT_ROOT"] . "/php/init.php";

$smarty = new AdminSmarty();
$smarty->assign("pagetitle", "Lag Namn");

if(isset($_GET["lid"])){
	
	$lagNamn = LagNamn::loadById($_GET["lid"]);
	$smarty->assign("lagnamn",$lagNamn);
}


$smarty->display('editlagnamn.tpl');

?>