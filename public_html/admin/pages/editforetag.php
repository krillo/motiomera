<?php

require $_SERVER["DOCUMENT_ROOT"] . "/php/init.php";

$smarty = new AdminSmarty();

$foretag = Foretag::loadById($_GET["fid"]);
$smarty->assign("foretag", $foretag);

$smarty->display('editforetag.tpl');

?>