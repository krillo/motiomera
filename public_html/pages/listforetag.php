<?php

include $_SERVER["DOCUMENT_ROOT"]  . "/php/init.php";

$smarty = new MMSmarty();
$smarty->assign("pagetitle", "Lista företag");

$foretagList = Foretag::listAll();

$smarty->assign("foretagList", $foretagList);


$smarty->display('listforetag.tpl');

?>