<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

Security::demand(ADMIN);

$smarty = new AdminSmarty;

$teman = array(
		"Full Featured" => "Komplett",
		"Simple" => "Enkel"
	);
	
$smarty->assign("opt_teman",$teman);

$auto = array(
			0 => "Nej",
			1 => "Ja"
		);
		
$smarty->assign("opt_auto",$auto);
		


		
if(!isset($_GET["id"])){
	$helpId = null;
	$sel_typ = null;
	$sel_auto = 0;
}else{
	$help = Help::loadById($_GET["id"]);
	$helpId = $_GET["id"];
	$sel_typ = $help->getTema();
	$smarty->assign("help", $help);
	$sel_auto = $help->getAuto();
}
$smarty->assign("sel_typ", $sel_typ);
$smarty->assign("sel_auto", $sel_auto);
$smarty->assign("helpId", $helpId);

$smarty->display("edithelp.tpl");

?>