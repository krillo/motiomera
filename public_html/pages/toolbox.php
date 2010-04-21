<?php

include $_SERVER["DOCUMENT_ROOT"]  . "/php/init.php";
Security::demand(USER);

$smarty = new MMSmarty();

$smarty->display('adminlogin.tpl');


?>