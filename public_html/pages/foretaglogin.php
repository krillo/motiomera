<?php

include $_SERVER["DOCUMENT_ROOT"]  . "/php/init.php";



$smarty = new MMSmarty();
$smarty->assign("pagetitle", "Logga in");
if(isset($_REQUEST["u"])){$smarty->assign("u", $_REQUEST["u"]);}
if(isset($_REQUEST["p"])){$smarty->assign("p", $_REQUEST["p"]);}

if(isset($FORETAG)){
	$urlHandler->redirect("Foretag", URL_EDIT);
}

$smarty->display('foretaglogin.tpl');


?>