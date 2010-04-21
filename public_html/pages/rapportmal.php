<?php

require_once($_SERVER["DOCUMENT_ROOT"]."/php/init.php");

Security::demand(USER);

$smarty = new MMSmarty();
$smarty->assign("pagetitle", "Rapport");

$malManager = new MalManager($USER);
$smarty->assign("malManager", $malManager);

$malList = $malManager->listMal();

$opt_mal = Misc::arrayKeyMerge(array(""=>"Välj..."), $malManager->listAvalibleNamn());
$smarty->assign("opt_mal", $opt_mal);

$currentMal = $malManager->getCurrentMal();
$smarty->assign("currentMal", $currentMal);

$smarty->display('rapportmal.tpl');


?>
