<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");
$smarty = new MMSmarty;
include('../php/messages.php');
if ( isset($_POST['email']) && isset($_POST['message']) )
{
	$key = $_POST['message'];
	Misc::sendEmail($_POST['email'], 'test@motiomera.se', $messages[$key]['title'], $messages[$key]['message']);
	$smarty->assign("notify", 'Du skickade iväg ett mail.');
}
$smarty->assign("messages", $messages);
$smarty->display('testmail.tpl');
?>