<?php
include $_SERVER["DOCUMENT_ROOT"]  . "/php/init.php";
!empty($_REQUEST['email']) ? $email = $_REQUEST['email'] : $email = '';

$smarty = new MMSmarty();
$smarty->assign("pagetitle", "Beställ nytt lösenord");
$smarty->assign("email", $email);
$smarty->display('glomtlosen.tpl');
?>