<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

Security::demand(SUPERADMIN);

$smarty = new AdminSmarty;


$opt_typ = array("superadmin"=>"Superadministratör","admin"=>"Administratör", "redaktor"=>"Redaktör", "moderator"=>"Moderator", "kommun"=>"Kommun");
$smarty->assign("opt_typ", $opt_typ);

if(!isset($_GET["id"])){
	$adminId = null;
	$sel_typ = null;
}else{
	$admin = Admin::loadById($_GET["id"]);
	$adminId = $_GET["id"];
	$sel_typ = $admin->getTyp();
	$smarty->assign("admin", $admin);
}
$smarty->assign("sel_typ", $sel_typ);
$smarty->assign("adminId", $adminId);
if(!empty($_GET['created'])){
	$smarty->assign("created", $_GET['created']);
}

$smarty->display("editadmin.tpl");

?>