<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

Security::demand(ADMIN);

$smarty = new AdminSmarty;

$medlem = Medlem::loadById($_GET["id"]);
$smarty->assign("medlem", $medlem);

$levels = array();
$all_levels = Level::listAll();
foreach($all_levels as $level) {
	
	$levels[$level->getId()] = $level->getNamn();
}
$smarty->assign("opt_levels",$levels);

$medlem_level = $medlem->getLevelId();

if($medlem_level == 0) {
	// no level selected, load default
	$default_level = Level::getDefault();
	$medlem_level = $default_level->getId();
}

$smarty->assign("sel_level",$medlem_level);


$smarty->display('medlem.tpl');

?>