<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

Security::demand(SUPERADMIN);

$smarty = new AdminSmarty;

$admins = Admin::listAll();
$smarty->assign("admins", $admins);

$adminNiceNames = array("superadmin"=>"Superadministratör", "admin"=>"Admin", "redaktor"=>"Redaktör","kommun"=>"Kommun");
$smarty->assign("adminNiceNames",$adminNiceNames);


$smarty->display("listadmins.tpl");