<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

Security::demand(ADMIN);

$smarty = new AdminSmarty;

if ( isset($_GET['created']) )
{
	$smarty->assign('created','true');
}
$fastaUtmaningar = Rutt::getAllFastaUtmaningar();
$smarty->assign("fastaUtmaningar", $fastaUtmaningar);

$smarty->display("fastautmaningar.tpl");
?>