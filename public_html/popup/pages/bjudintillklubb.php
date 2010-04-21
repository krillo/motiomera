<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

Security::demand(USER);

$medlem = Medlem::loadById($_POST["medlem_id"]);

$grupper = Grupp::listInbjudningsbaraGrupper($medlem);
$opt_grupper = array();
$grupp = current($grupper);
foreach($grupper as $grupp){
	$opt_grupper[$grupp->getId()] = $grupp->getNamn();
}


$smarty = new PopSmarty();


if(count($opt_grupper) == 1)
	$smarty->assign("grupp", $grupp);
	
$smarty->assign("opt_grupper", $opt_grupper);

$smarty->display('bjudintillklubb.tpl');
?>