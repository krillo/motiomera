<?php

include $_SERVER["DOCUMENT_ROOT"]  . "/php/init.php";



$smarty = new MMSmarty();
$smarty->assign("pagetitle", "Beställ nytt lösenord");



$smarty->display('glomtlosen.tpl');


?>