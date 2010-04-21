<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

Security::demand(USER);

$smarty = new MMSmarty;
$smarty->assign("pagetitle", "Steg");


$minaSteg = new MinaSteg($USER);
$smarty->assign("minaSteg", $minaSteg);

$stegList = $minaSteg->listStegByDatum($_GET["datum"]);
$smarty->assign("stegList", $stegList);




$smarty->display('stegbydatum.tpl');


?>