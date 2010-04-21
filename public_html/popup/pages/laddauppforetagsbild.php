<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

$smarty = new PopSmarty();

$smarty->assign("foretagsid", (isset($_POST["fid"])?$_POST["fid"]:''));
$smarty->display('laddauppforetagsbild.tpl');

?>