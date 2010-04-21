<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

Security::demand(ADMIN);

$smarty = new AdminSmarty;


if(!isset($_GET["id"])){
	$profilDataId = null;
}else{
	$profildata = ProfilData::loadById($_GET["id"]);
	$profilDataId = $_GET["id"];
	$smarty->assign("profildata", $profildata);
	
	$profilDataVals = ProfilDataVal::listByprofilData($profildata);
	
	$smarty->assign("profilDataVals", $profilDataVals);

}
$smarty->assign("profilDataId", $profilDataId);

$smarty->display("editprofildata.tpl");

?>